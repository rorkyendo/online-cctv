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

# ── Pastikan dijalankan dari root project ─────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# ── Helper: cek .env ada ──────────────────────────────────────
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

    check_env

    info "Building images..."
    $COMPOSE build --pull --no-cache

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
    echo "  help                Tampilkan bantuan ini"
    echo ""
    echo -e "${CYAN}Contoh:${NC}"
    echo "  ./deploy.sh                          # deploy pertama kali"
    echo "  ./deploy.sh update                   # update setelah push kode baru"
    echo "  ./deploy.sh logs app                 # lihat log Laravel"
    echo "  ./deploy.sh artisan migrate:status   # cek status migrasi"
    echo "  ./deploy.sh artisan tinker           # buka tinker REPL"
    echo "  ./deploy.sh restart nginx            # reload nginx"
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
    help|--help|-h) cmd_help ;;
    *) error "Command tidak dikenal: '${COMMAND}'. Gunakan './deploy.sh help'." ;;
esac
