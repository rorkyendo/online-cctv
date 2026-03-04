#!/usr/bin/env bash
# ============================================================
# deploy.sh — Online CCTV Deployment Script
# Usage:
#   ./deploy.sh            → full deploy (build + up + migrate)
#   ./deploy.sh update     → pull, rebuild app, migrate, reload
#   ./deploy.sh rollback   → kembali ke image sebelumnya
#   ./deploy.sh status     → cek status semua container
#   ./deploy.sh logs       → tail logs semua service
#   ./deploy.sh logs app   → tail logs service tertentu
#   ./deploy.sh restart    → restart semua container
#   ./deploy.sh shell      → masuk bash ke container app
#   ./deploy.sh artisan <cmd> → jalankan artisan command
#   ./deploy.sh stop       → stop tapi tidak hapus container
#   ./deploy.sh down       → stop dan hapus container (volumes aman)
#   ./deploy.sh rebuild    → hapus container + image lama, build ulang dari nol
# ============================================================

set -euo pipefail

# ── Warna output ─────────────────────────────────────────────
RED='\033[0;31m'; YELLOW='\033[1;33m'; GREEN='\033[0;32m'
CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'

info()    { echo -e "${CYAN}[INFO]${NC}  $*"; }
success() { echo -e "${GREEN}[OK]${NC}    $*"; }
warn()    { echo -e "${YELLOW}[WARN]${NC}  $*"; }
error()   { echo -e "${RED}[ERROR]${NC} $*"; exit 1; }
header()  { echo -e "\n${BOLD}${CYAN}══ $* ══${NC}\n"; }

# ── Konstanta ─────────────────────────────────────────────────
COMPOSE="docker compose"
APP_CONTAINER="cctv-app"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Docker Compose 2.36+ menggunakan "docker buildx bake" secara default.
# Bake salah menafsirkan field `build.target` (Dockerfile stage) di docker-compose.yml
# sehingga stage "runtime" tidak ditemukan. Disable Bake agar pakai build klasik.
export COMPOSE_BAKE=false

# ── Pastikan dijalankan dari root project ─────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# ── Helper: fix CRLF → LF pada file kritis (Windows upload issue) ────
fix_crlf() {
    local files=(
        Dockerfile
        tools/Dockerfile
        docker-compose.yml
        deploy.sh
    )
    local fixed=0
    # Selalu convert (tidak deteksi dulu) — tr lebih reliable dari grep -P
    for f in "${files[@]}"; do
        if [[ -f "$f" ]]; then
            tr -d '\r' < "$f" > "${f}.crlf_tmp" && mv "${f}.crlf_tmp" "$f"
            fixed=$((fixed+1))
        fi
    done
    # docker/ subfolder
    while IFS= read -r -d '' f; do
        tr -d '\r' < "$f" > "${f}.crlf_tmp" && mv "${f}.crlf_tmp" "$f"
        fixed=$((fixed+1))
    done < <(find docker/ -type f -print0 2>/dev/null)

    info "CRLF→LF normalisasi selesai pada $fixed file."
}


check_env() {
    if [[ ! -f ".env" ]]; then
        warn ".env tidak ditemukan."
        if [[ -f ".env.production" ]]; then
            info "Menyalin .env.production → .env"
            cp .env.production .env
            warn "Pastikan isi .env sudah benar (APP_KEY, DB_HOST, DB_PASSWORD) sebelum melanjutkan."
            echo -e "${YELLOW}Tekan ENTER untuk lanjutkan, atau Ctrl+C untuk batal.${NC}"
            read -r
        else
            error ".env.production juga tidak ada. Buat .env terlebih dahulu."
        fi
    fi

    # Cek APP_KEY
    APP_KEY_VAL=$(grep -E "^APP_KEY=" .env | cut -d= -f2 | tr -d '"' | tr -d "'")
    if [[ -z "$APP_KEY_VAL" ]]; then
        warn "APP_KEY kosong. Mencoba generate..."
        if command -v php &>/dev/null; then
            APP_KEY_GEN=$(php artisan key:generate --show 2>/dev/null || true)
            if [[ -n "$APP_KEY_GEN" ]]; then
                sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY_GEN}|" .env
                success "APP_KEY di-generate: ${APP_KEY_GEN}"
            else
                warn "Tidak bisa auto-generate. Isi APP_KEY di .env secara manual."
            fi
        else
            warn "PHP tidak ditemukan di host. Isi APP_KEY di .env secara manual setelah build."
        fi
    fi
}

# ── Helper: jalankan artisan di dalam container ───────────────
run_artisan() {
    $COMPOSE exec -T app php artisan "$@"
}

# ── Helper: cache Laravel production ─────────────────────────
cache_laravel() {
    info "Running Laravel production cache..."
    run_artisan config:cache   && success "config:cache"
    run_artisan route:cache    && success "route:cache"
    run_artisan view:cache     && success "view:cache"
    run_artisan event:cache    && success "event:cache"
}

# ── Helper: tunggu container healthy ─────────────────────────
wait_healthy() {
    local container=$1
    local max=30
    local i=0
    info "Menunggu container '${container}' siap..."
    until [[ "$(docker inspect --format='{{.State.Health.Status}}' "$container" 2>/dev/null)" == "healthy" ]]; do
        sleep 3
        i=$((i+1))
        if [[ $i -ge $max ]]; then
            warn "Timeout menunggu '${container}'. Melanjutkan..."
            return
        fi
        echo -n "."
    done
    echo ""
    success "'${container}' healthy."
}

# ══════════════════════════════════════════════════════════════
# COMMAND: deploy (default — first time setup)
# ══════════════════════════════════════════════════════════════
cmd_deploy() {
    header "FULL DEPLOY"

    fix_crlf
    check_env

    info "Pulling base images (php, nginx, python, composer)..."
    # Pull secara eksplisit agar error network terlihat jelas,
    # bukan dilaporkan sebagai 'stage not found' oleh BuildKit
    docker pull php:8.2-fpm-alpine  || warn "Gagal pull php:8.2-fpm-alpine — akan pakai cache lokal jika ada"
    docker pull composer:2.7        || warn "Gagal pull composer:2.7 — akan pakai cache lokal jika ada"
    docker pull nginx:1.27-alpine   || warn "Gagal pull nginx:1.27-alpine — akan pakai cache lokal jika ada"
    docker pull python:3.10-slim    || warn "Gagal pull python:3.10-slim — akan pakai cache lokal jika ada"

    info "Building image app..."
    $COMPOSE build --no-cache app

    info "Building image scraper..."
    $COMPOSE build --no-cache scraper

    info "Starting containers..."
    $COMPOSE up -d

    wait_healthy "$APP_CONTAINER"

    info "Setting storage permissions..."
    $COMPOSE exec -T app chmod -R 775 storage bootstrap/cache

    info "Running migrations..."
    run_artisan migrate --force

    cache_laravel

    info "Clearing stale cache..."
    run_artisan optimize:clear

    echo ""
    success "Deploy selesai!"
    cmd_status
}

# ══════════════════════════════════════════════════════════════
# COMMAND: update (zero-downtime rebuild)
# ══════════════════════════════════════════════════════════════
cmd_update() {
    header "UPDATE DEPLOYMENT"

    check_env

    # Backup image saat ini sebagai rollback target
    info "Membuat backup image (rollback point)..."
    CURRENT_IMAGE=$(docker inspect --format='{{.Image}}' "$APP_CONTAINER" 2>/dev/null || true)
    if [[ -n "$CURRENT_IMAGE" ]]; then
        docker tag "$CURRENT_IMAGE" "cctv-app-rollback:${TIMESTAMP}" 2>/dev/null && \
            success "Backup disimpan sebagai cctv-app-rollback:${TIMESTAMP}" || \
            warn "Gagal backup image lama."
    fi

    info "Pulling git changes (jika ada)..."
    if git rev-parse --is-inside-work-tree &>/dev/null; then
        git pull origin "$(git branch --show-current)" || warn "git pull gagal, melanjutkan dengan kode lokal..."
    fi

    info "Rebuilding app image..."
    $COMPOSE build --no-cache app

    info "Restarting app container..."
    $COMPOSE up -d --no-deps app

    wait_healthy "$APP_CONTAINER"

    info "Running migrations..."
    run_artisan migrate --force

    cache_laravel

    echo ""
    success "Update selesai!"
    cmd_status
}

# ══════════════════════════════════════════════════════════════
# COMMAND: rollback — kembali ke image sebelumnya
# ══════════════════════════════════════════════════════════════
cmd_rollback() {
    header "ROLLBACK"

    # Cari rollback image paling baru
    ROLLBACK_IMAGE=$(docker images --format "{{.Repository}}:{{.Tag}}" "cctv-app-rollback" \
        | sort -r | head -1)

    if [[ -z "$ROLLBACK_IMAGE" ]]; then
        error "Tidak ada rollback image tersedia. Jalankan update setidaknya sekali sebelum rollback."
    fi

    warn "Akan rollback ke: ${ROLLBACK_IMAGE}"
    echo -e "${YELLOW}Tekan ENTER untuk lanjutkan, atau Ctrl+C untuk batal.${NC}"
    read -r

    info "Mengganti image ke rollback..."
    docker tag "$ROLLBACK_IMAGE" "online-cctv-app:latest"
    $COMPOSE up -d --no-deps app

    wait_healthy "$APP_CONTAINER"
    success "Rollback ke ${ROLLBACK_IMAGE} berhasil."
    cmd_status
}

# ══════════════════════════════════════════════════════════════
# COMMAND: status
# ══════════════════════════════════════════════════════════════
cmd_status() {
    header "STATUS CONTAINER"
    $COMPOSE ps
    echo ""
    info "Disk usage:"
    docker system df --format "table {{.Type}}\t{{.TotalCount}}\t{{.Size}}\t{{.Reclaimable}}" 2>/dev/null || true
}

# ══════════════════════════════════════════════════════════════
# COMMAND: logs
# ══════════════════════════════════════════════════════════════
cmd_logs() {
    local service="${1:-}"
    if [[ -n "$service" ]]; then
        $COMPOSE logs -f --tail=100 "$service"
    else
        $COMPOSE logs -f --tail=50
    fi
}

# ══════════════════════════════════════════════════════════════
# COMMAND: restart
# ══════════════════════════════════════════════════════════════
cmd_restart() {
    local service="${1:-}"
    if [[ -n "$service" ]]; then
        info "Restarting service: ${service}..."
        $COMPOSE restart "$service"
    else
        info "Restarting semua service..."
        $COMPOSE restart
    fi
    success "Restart selesai."
    cmd_status
}

# ══════════════════════════════════════════════════════════════
# COMMAND: shell — masuk bash ke container app
# ══════════════════════════════════════════════════════════════
cmd_shell() {
    info "Membuka shell di container '${APP_CONTAINER}'..."
    docker exec -it "$APP_CONTAINER" sh
}

# ══════════════════════════════════════════════════════════════
# COMMAND: artisan — jalankan artisan command
# ══════════════════════════════════════════════════════════════
cmd_artisan() {
    if [[ $# -eq 0 ]]; then
        error "Gunakan: ./deploy.sh artisan <command>   contoh: ./deploy.sh artisan migrate:status"
    fi
    info "php artisan $*"
    run_artisan "$@"
}

# ══════════════════════════════════════════════════════════════
# COMMAND: stop
# ══════════════════════════════════════════════════════════════
cmd_stop() {
    info "Stopping containers..."
    $COMPOSE stop
    success "Container distop (data/volume tetap aman)."
}

# ══════════════════════════════════════════════════════════════
# COMMAND: down
# ══════════════════════════════════════════════════════════════
cmd_down() {
    warn "Ini akan menghapus container (BUKAN volume). Data tetap aman."
    echo -e "${YELLOW}Tekan ENTER untuk lanjutkan, atau Ctrl+C untuk batal.${NC}"
    read -r
    $COMPOSE down
    success "Container dihapus. Jalankan './deploy.sh deploy' untuk deploy ulang."
}

# ══════════════════════════════════════════════════════════════
# COMMAND: rebuild — hapus container + image, build ulang dari nol
# ══════════════════════════════════════════════════════════════
cmd_rebuild() {
    header "REBUILD FROM SCRATCH"

    fix_crlf

    warn "Perintah ini akan:"
    warn "  1. Stop & hapus semua container (docker compose down)"
    warn "  2. Hapus image lama yang dibangun project ini"
    warn "  3. Build ulang semua image dari nol (--no-cache)"
    warn "  4. Jalankan ulang semua container"
    warn "  5. Jalankan migrate + cache Laravel"
    warn ""
    warn "CATATAN: Named volumes (data storage/foto) TIDAK akan dihapus."
    warn "Gunakan flag --with-volumes untuk hapus volumes juga (DATA HILANG!)."
    echo -e "${YELLOW}Tekan ENTER untuk lanjutkan, atau Ctrl+C untuk batal.${NC}"
    read -r

    local with_volumes="${1:-}"

    info "Stopping & removing containers..."
    if [[ "$with_volumes" == "--with-volumes" ]]; then
        warn "Menghapus volumes juga! Semua data storage/foto akan hilang."
        echo -e "${RED}Ketik 'HAPUS' untuk konfirmasi:${NC}"
        read -r confirm
        if [[ "$confirm" != "HAPUS" ]]; then
            error "Konfirmasi tidak cocok. Rebuild dibatalkan."
        fi
        $COMPOSE down --volumes
        success "Container + volumes dihapus."
    else
        $COMPOSE down
        success "Container dihapus. Volumes tetap aman."
    fi

    info "Menghapus image lama project ini (jika ada)..."
    docker images --format '{{.Repository}}:{{.Tag}}' \
        | grep -E '^(online-cctv|cctv-)' \
        | xargs -r docker rmi -f 2>/dev/null && success "Image lama dihapus." || info "Tidak ada image lama untuk dihapus."

    info "Membersihkan dangling images..."
    docker image prune -f 2>/dev/null || true

    check_env

    info "Pulling base images (php, nginx, python, composer)..."
    docker pull php:8.2-fpm-alpine  || warn "Gagal pull php:8.2-fpm-alpine — akan pakai cache lokal jika ada"
    docker pull composer:2.7        || warn "Gagal pull composer:2.7 — akan pakai cache lokal jika ada"
    docker pull nginx:1.27-alpine   || warn "Gagal pull nginx:1.27-alpine — akan pakai cache lokal jika ada"
    docker pull python:3.10-slim    || warn "Gagal pull python:3.10-slim — akan pakai cache lokal jika ada"

    info "Building image app (--no-cache)..."
    $COMPOSE build --no-cache app

    info "Building image scraper (--no-cache)..."
    $COMPOSE build --no-cache scraper

    info "Starting containers..."
    $COMPOSE up -d

    wait_healthy "$APP_CONTAINER"

    info "Setting storage permissions..."
    $COMPOSE exec -T app chmod -R 775 storage bootstrap/cache

    info "Running migrations..."
    run_artisan migrate --force

    cache_laravel

    echo ""
    success "Rebuild selesai!"
    cmd_status
}

# ══════════════════════════════════════════════════════════════
# COMMAND: help
# ══════════════════════════════════════════════════════════════
cmd_help() {
    echo -e "${BOLD}Online CCTV — Deploy Script${NC}"
    echo ""
    echo "Usage: ./deploy.sh [command] [options]"
    echo ""
    echo -e "${CYAN}Commands:${NC}"
    echo "  (none) / deploy     First-time deploy: build, up, migrate, cache"
    echo "  update              Pull, rebuild app, migrate, cache"
    echo "  rollback            Kembali ke image sebelum update terakhir"
    echo "  status              Tampilkan status container & disk usage"
    echo "  logs [service]      Tail logs (semua atau per service: app/nginx/scraper)"
    echo "  restart [service]   Restart semua atau per service"
    echo "  shell               Masuk shell ke container app"
    echo "  artisan <cmd>       Jalankan php artisan di container app"
    echo "  stop                Stop container (data aman)"
    echo "  down                Hapus container (data/volume tetap aman)"
    echo "  rebuild             Hapus container+image, build ulang dari nol"
    echo "  rebuild --with-volumes  Rebuild + hapus volumes (DATA HILANG!)"
    echo "  help                Tampilkan bantuan ini"
    echo ""
    echo -e "${CYAN}Contoh:${NC}"
    echo "  ./deploy.sh                          # deploy pertama kali"
    echo "  ./deploy.sh update                   # update setelah push kode baru"
    echo "  ./deploy.sh logs app                 # lihat log Laravel"
    echo "  ./deploy.sh artisan migrate:status   # cek status migrasi"
    echo "  ./deploy.sh artisan tinker           # buka tinker REPL"
    echo "  ./deploy.sh restart nginx            # reload nginx"
    echo "  ./deploy.sh rebuild                  # hapus semua, build ulang"
    echo "  ./deploy.sh rebuild --with-volumes   # rebuild + hapus data (hati-hati!)"
}

# ══════════════════════════════════════════════════════════════
# MAIN — Route ke command
# ══════════════════════════════════════════════════════════════

# Pastikan docker tersedia
command -v docker &>/dev/null || error "Docker tidak ditemukan. Install Docker terlebih dahulu."

COMMAND="${1:-deploy}"
shift || true   # shift argumen pertama, sisanya diteruskan

case "$COMMAND" in
    deploy)   cmd_deploy ;;
    update)   cmd_update ;;
    rollback) cmd_rollback ;;
    status)   cmd_status ;;
    logs)     cmd_logs "$@" ;;
    restart)  cmd_restart "$@" ;;
    shell)    cmd_shell ;;
    artisan)  cmd_artisan "$@" ;;
    stop)     cmd_stop ;;
    down)     cmd_down ;;
    rebuild)  cmd_rebuild "${1:-}" ;;
    help|--help|-h) cmd_help ;;
    *) error "Command tidak dikenal: '${COMMAND}'. Gunakan './deploy.sh help'." ;;
esac
