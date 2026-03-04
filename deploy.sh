#!/usr/bin/env bash
# ============================================================
# deploy.sh — Online CCTV Deployment Script
# ============================================================
set -euo pipefail

# ── Warna ─────────────────────────────────────────────────────
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
APP_IMAGE="cctv-app:latest"
SCRAPER_IMAGE="cctv-scraper:latest"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# ── Pastikan dijalankan dari root project ─────────────────────
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# ══════════════════════════════════════════════════════════════
# HELPERS
# ══════════════════════════════════════════════════════════════

fix_crlf() {
    local fixed=0
    local files=(Dockerfile tools/Dockerfile docker-compose.yml deploy.sh)
    for f in "${files[@]}"; do
        [[ -f "$f" ]] && { tr -d '\r' < "$f" > "${f}.tmp" && mv "${f}.tmp" "$f"; fixed=$((fixed+1)); }
    done
    while IFS= read -r -d '' f; do
        tr -d '\r' < "$f" > "${f}.tmp" && mv "${f}.tmp" "$f"
        fixed=$((fixed+1))
    done < <(find docker/ -type f -print0 2>/dev/null)
    info "CRLF fix: $fixed file."
}

check_env() {
    if [[ ! -f ".env" ]]; then
        if [[ -f ".env.production" ]]; then
            cp .env.production .env
            warn ".env dibuat dari .env.production — pastikan isinya benar."
            echo -e "${YELLOW}Tekan ENTER untuk lanjut, Ctrl+C untuk batal.${NC}"
            read -r
        else
            error ".env tidak ditemukan. Buat file .env dulu."
        fi
    fi
}

build_app() {
    info "Building app image: $APP_IMAGE"
    docker build -f Dockerfile -t "$APP_IMAGE" --no-cache .
    success "App image ready."
}

build_scraper() {
    info "Building scraper image: $SCRAPER_IMAGE"
    docker build -f tools/Dockerfile -t "$SCRAPER_IMAGE" --no-cache .
    success "Scraper image ready."
}

build_all() {
    info "Pulling base images..."
    docker pull php:8.2-fpm-alpine  || warn "pull php gagal"
    docker pull composer:2.7        || warn "pull composer gagal"
    docker pull nginx:1.27-alpine   || warn "pull nginx gagal"
    docker pull python:3.10-slim    || warn "pull python gagal"
    build_app
    build_scraper
}

run_artisan() {
    $COMPOSE exec -T app php artisan "$@"
}

cache_laravel() {
    info "Laravel cache..."
    run_artisan config:cache   && success "config:cache"
    run_artisan route:cache    && success "route:cache"
    run_artisan view:cache     && success "view:cache"
    run_artisan event:cache    && success "event:cache"
}

wait_healthy() {
    local container=$1 max=40 i=0
    info "Menunggu '$container' healthy..."
    until [[ "$(docker inspect --format='{{.State.Health.Status}}' "$container" 2>/dev/null)" == "healthy" ]]; do
        sleep 3; i=$((i+1))
        if [[ $i -ge $max ]]; then
            warn "Timeout. Log terakhir:"
            docker logs --tail 15 "$container" 2>&1 || true
            return
        fi
        echo -n "."
    done
    echo ""; success "'$container' healthy."
}

remove_old_images() {
    info "Hapus image lama project..."
    docker images --format '{{.Repository}}:{{.Tag}}' \
        | grep -Ei '(monitoring-cctv|online-cctv|cctv-app|cctv-scraper)' \
        | xargs -r docker rmi -f 2>/dev/null \
        && success "Image lama dihapus." \
        || info "Tidak ada image lama."
    docker image prune -f 2>/dev/null || true
}

# ══════════════════════════════════════════════════════════════
# COMMANDS
# ══════════════════════════════════════════════════════════════

cmd_deploy() {
    header "FULL DEPLOY"
    fix_crlf
    check_env
    build_all

    info "Starting containers..."
    $COMPOSE up -d
    wait_healthy "$APP_CONTAINER"

    info "Migrations..."
    run_artisan migrate --force
    cache_laravel

    success "Deploy selesai!"
    cmd_status
}

cmd_update() {
    header "UPDATE"
    check_env

    info "Backup image lama..."
    docker tag "$APP_IMAGE" "cctv-app-rollback:${TIMESTAMP}" 2>/dev/null \
        && success "Rollback: cctv-app-rollback:${TIMESTAMP}" \
        || warn "Tidak ada image lama untuk backup."

    if git rev-parse --is-inside-work-tree &>/dev/null; then
        git pull origin "$(git branch --show-current)" || warn "git pull gagal."
    fi

    fix_crlf
    build_app

    $COMPOSE up -d --no-deps app
    wait_healthy "$APP_CONTAINER"

    run_artisan migrate --force
    cache_laravel

    success "Update selesai!"
    cmd_status
}

cmd_rollback() {
    header "ROLLBACK"
    local img
    img=$(docker images --format "{{.Repository}}:{{.Tag}}" "cctv-app-rollback" | sort -r | head -1)
    [[ -z "$img" ]] && error "Tidak ada rollback image."
    warn "Rollback ke: $img"
    echo -e "${YELLOW}ENTER untuk lanjut, Ctrl+C batal.${NC}"; read -r
    docker tag "$img" "$APP_IMAGE"
    $COMPOSE up -d --no-deps app
    wait_healthy "$APP_CONTAINER"
    success "Rollback OK."
    cmd_status
}

cmd_rebuild() {
    header "REBUILD FROM SCRATCH"
    local with_vol="${1:-}"

    warn "Stop container → hapus image → build ulang → start."
    [[ "$with_vol" == "--with-volumes" ]] && warn "DATA STORAGE AKAN HILANG!"
    echo -e "${YELLOW}ENTER untuk lanjut, Ctrl+C batal.${NC}"; read -r

    if [[ "$with_vol" == "--with-volumes" ]]; then
        echo -e "${RED}Ketik 'HAPUS' untuk konfirmasi:${NC}"; read -r confirm
        [[ "$confirm" != "HAPUS" ]] && error "Dibatalkan."
        $COMPOSE down --volumes
    else
        $COMPOSE down
    fi

    remove_old_images
    docker builder prune -f 2>/dev/null || true

    fix_crlf
    check_env
    build_all

    info "Starting containers..."
    $COMPOSE up -d
    wait_healthy "$APP_CONTAINER"

    run_artisan migrate --force
    cache_laravel

    success "Rebuild selesai!"
    cmd_status
}

cmd_status() {
    header "STATUS"
    $COMPOSE ps
    echo ""
    docker images | grep -E "(cctv|REPO)" || true
}

cmd_logs() {
    local svc="${1:-}"
    [[ -n "$svc" ]] && $COMPOSE logs -f --tail=100 "$svc" || $COMPOSE logs -f --tail=50
}

cmd_restart() {
    local svc="${1:-}"
    [[ -n "$svc" ]] && $COMPOSE restart "$svc" || $COMPOSE restart
    success "Restart OK."
}

cmd_shell()   { docker exec -it "$APP_CONTAINER" sh; }
cmd_stop()    { $COMPOSE stop; success "Stopped."; }
cmd_down()    { warn "Hapus container (volume aman)."; echo -e "${YELLOW}ENTER/Ctrl+C${NC}"; read -r; $COMPOSE down; success "Done."; }

cmd_artisan() {
    [[ $# -eq 0 ]] && error "Usage: ./deploy.sh artisan <command>"
    info "php artisan $*"
    run_artisan "$@"
}

cmd_help() {
    echo -e "${BOLD}Online CCTV — Deploy Script${NC}"
    echo ""
    echo "Usage: ./deploy.sh [command] [options]"
    echo ""
    echo -e "${CYAN}Commands:${NC}"
    echo "  deploy (default)    Build + start + migrate + cache"
    echo "  update              Pull git, rebuild app, migrate"
    echo "  rollback            Kembali ke image sebelum update"
    echo "  rebuild             Hapus semua, build ulang dari nol"
    echo "  rebuild --with-volumes  Rebuild + hapus data (HATI-HATI!)"
    echo "  status              Status container"
    echo "  logs [service]      Tail logs (app/nginx/scraper)"
    echo "  restart [service]   Restart container"
    echo "  shell               Shell ke container app"
    echo "  artisan <cmd>       Jalankan php artisan"
    echo "  stop                Stop container"
    echo "  down                Hapus container (volume aman)"
    echo "  help                Bantuan ini"
}

# ══════════════════════════════════════════════════════════════
# MAIN
# ══════════════════════════════════════════════════════════════
command -v docker &>/dev/null || error "Docker tidak ditemukan."

COMMAND="${1:-deploy}"; shift || true

case "$COMMAND" in
    deploy)     cmd_deploy ;;
    update)     cmd_update ;;
    rollback)   cmd_rollback ;;
    rebuild)    cmd_rebuild "${1:-}" ;;
    status)     cmd_status ;;
    logs)       cmd_logs "$@" ;;
    restart)    cmd_restart "$@" ;;
    shell)      cmd_shell ;;
    artisan)    cmd_artisan "$@" ;;
    stop)       cmd_stop ;;
    down)       cmd_down ;;
    help|--help|-h) cmd_help ;;
    *)          error "Unknown: '$COMMAND'. Try './deploy.sh help'." ;;
esac
