{{-- ============================================================ --}}
{{-- Live View All Groups — Grid semua CCTV dari semua grup yg diizinkan --}}
{{-- ============================================================ --}}
@php
    $cctvList = $data['cctvList'];
    $groups   = $data['groups'];
    $total    = $cctvList->count();
@endphp

<style>
/* ── Grid container ─────────────────────────────────────── */
.lv-grid {
    display: grid;
    gap: 8px;
    transition: all .3s ease;
}
.lv-grid.cols-1 { grid-template-columns: 1fr; }
.lv-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
.lv-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
.lv-grid.cols-4 { grid-template-columns: repeat(4, 1fr); }

/* ── Camera cell ─────────────────────────────────────────── */
.lv-cell {
    position: relative;
    background: #0d0d1a;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 16/9;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color .2s, box-shadow .2s;
}
.lv-cell:hover       { border-color: #3e97ff; }
.lv-cell.focused     { border-color: #50cd89; box-shadow: 0 0 0 3px rgba(80,205,137,.3); }
.lv-cell.hidden-cell { display: none; }

/* ── Player container ─────────────────────────────────────── */
.lv-player-container {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    background: #000;
    overflow: hidden;
}
.lv-player-container > div { width: 100% !important; height: 100% !important; }
.lv-player-container canvas { width: 100% !important; height: 100% !important; }

/* ── Overlay states ──────────────────────────────────────── */
.lv-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.75);
    color: #fff;
    font-size: .82rem;
    gap: 8px;
    text-align: center;
    padding: 12px;
    z-index: 5;
}
.lv-overlay.d-none { display: none !important; }

/* ── Camera label bar ────────────────────────────────────── */
.lv-label {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,.85));
    color: #fff;
    padding: 20px 10px 8px;
    font-size: .78rem;
    z-index: 6;
    pointer-events: none;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}
.lv-label .cam-name  { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 65%; }
.lv-label .cam-group { font-size: .68rem; opacity: .65; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.lv-status-dot       { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-online  { background: #50cd89; box-shadow: 0 0 6px #50cd89; }
.dot-offline { background: #f1416c; }
.dot-unknown { background: #888; }

/* ── Group badge (top-left) ──────────────────────────────── */
.lv-group-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(62,151,255,.85);
    color: #fff;
    font-size: .68rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    z-index: 7;
    pointer-events: none;
    white-space: nowrap;
    max-width: 60%;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ── Top toolbar ─────────────────────────────────────────── */
.lv-toolbar {
    background: #1e1e2d;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.lv-toolbar .lv-title { color: #fff; font-weight: 700; font-size: 1rem; flex: 1; min-width: 120px; }
.lv-toolbar .btn-icon {
    background: rgba(255,255,255,.1);
    border: none;
    color: #fff;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: .8rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background .15s;
    text-decoration: none;
}
.lv-toolbar .btn-icon:hover { background: rgba(255,255,255,.2); color: #fff; }

/* ── Group filter tabs ───────────────────────────────────── */
.lv-group-filter {
    background: #14141f;
    border-radius: 8px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.grp-tab {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    color: #aaa;
    border-radius: 6px;
    padding: 4px 12px;
    font-size: .78rem;
    cursor: pointer;
    transition: all .15s;
    white-space: nowrap;
}
.grp-tab:hover  { background: rgba(255,255,255,.14); color: #fff; }
.grp-tab.active { background: #3e97ff; border-color: #3e97ff; color: #fff; }

/* ── Layout picker ───────────────────────────────────────── */
.layout-picker { display: flex; gap: 4px; }
.layout-btn { background: rgba(255,255,255,.08); border: none; color: #aaa; border-radius: 6px; padding: 5px 9px; cursor: pointer; font-size: .75rem; transition: all .15s; }
.layout-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
.layout-btn.active { background: #3e97ff; color: #fff; }

/* ── Fullscreen ──────────────────────────────────────────── */
.lv-wrapper.fullscreen-active {
    position: fixed;
    inset: 0;
    background: #000;
    z-index: 9999;
    padding: 8px;
    overflow-y: auto;
}
.lv-wrapper.fullscreen-active .lv-toolbar { border-radius: 6px; }

@media (max-width: 768px) {
    .lv-grid.cols-3, .lv-grid.cols-4 { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .lv-grid.cols-2, .lv-grid.cols-3, .lv-grid.cols-4 { grid-template-columns: 1fr; }
}
</style>

{{-- ── Breadcrumb ── --}}
<div class="d-flex align-items-center gap-2 mb-4 fs-7 text-muted">
    <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="text-muted text-hover-primary">
        <i class="bi bi-grid me-1"></i>Grup Lokasi
    </a>
    <span>/</span>
    <span class="fw-bold text-dark">Live View Semua CCTV</span>
</div>

<div class="lv-wrapper" id="lvWrapper">

    {{-- ── Top Toolbar ── --}}
    <div class="lv-toolbar">
        <div class="lv-title">
            <i class="bi bi-play-circle me-2 text-success"></i>Live View Semua CCTV
            <small class="text-muted fw-normal ms-2" id="lblTotal">{{ $total }} kamera</small>
        </div>

        {{-- Layout picker --}}
        <div class="layout-picker" id="layoutPicker">
            <button class="layout-btn" data-cols="1" title="1 kolom"><i class="bi bi-square"></i></button>
            <button class="layout-btn" data-cols="2" title="2 kolom"><i class="bi bi-grid-2x2"></i></button>
            <button class="layout-btn" data-cols="3" title="3 kolom"><i class="bi bi-grid-3x3"></i></button>
            <button class="layout-btn" data-cols="4" title="4 kolom">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M1 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 12a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/></svg>
            </button>
        </div>

        {{-- Protocol selector --}}
        <select id="globalProtocol" class="form-select form-select-sm w-auto" style="background:#2b2b3d;color:#fff;border-color:#3d3d5c;">
            <option value="ezopen" selected>EZOPEN</option>
            <option value="hls">HLS</option>
        </select>

        <button class="btn-icon" id="btnReloadAll" title="Reload semua stream">
            <i class="bi bi-arrow-repeat"></i> Reload Semua
        </button>
        <button class="btn-icon" id="btnMuteAll" title="Mute/unmute semua">
            <i class="bi bi-volume-mute" id="muteIcon"></i>
        </button>
        <button class="btn-icon" id="btnFullscreen" title="Fullscreen">
            <i class="bi bi-fullscreen"></i>
        </button>
        <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn-icon" title="Kembali">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ── Group Filter Tabs ── --}}
    @if($groups->count() > 1)
    <div class="lv-group-filter" id="groupFilter">
        <span class="text-muted fs-8 me-1"><i class="bi bi-funnel me-1"></i>Filter:</span>
        <button class="grp-tab active" data-group="all">Semua</button>
        @foreach($groups as $grp)
        <button class="grp-tab" data-group="{{ $grp->id_group }}">{{ $grp->nama_group }}</button>
        @endforeach
    </div>
    @endif

    {{-- ── Camera Grid ── --}}
    @if($total === 0)
    <div class="card">
        <div class="card-body text-center py-12">
            <i class="bi bi-camera-video-off fs-1 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Belum ada CCTV yang dapat diakses</h5>
            <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-primary mt-3">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Grup Lokasi
            </a>
        </div>
    </div>
    @else
    <div class="lv-grid cols-3" id="lvGrid">
        @foreach($cctvList as $cctv)
        <div class="lv-cell" id="cell-{{ $cctv->id_cctv }}"
             data-id="{{ $cctv->id_cctv }}"
             data-name="{{ $cctv->nama_cctv }}"
             data-lokasi="{{ $cctv->nama_lokasi ?? '' }}"
             data-group="{{ $cctv->id_group }}"
             data-status="{{ $cctv->status ?? 'unknown' }}"
             onclick="toggleFocus({{ $cctv->id_cctv }})">

            <div id="player-{{ $cctv->id_cctv }}" class="lv-player-container"></div>

            {{-- Group badge --}}
            <div class="lv-group-badge">{{ $cctv->nama_group }}</div>

            {{-- Loading overlay --}}
            <div class="lv-overlay" id="ov-load-{{ $cctv->id_cctv }}">
                <div class="spinner-border spinner-border-sm text-primary mb-1"></div>
                <span>Menghubungkan...</span>
            </div>

            {{-- Error overlay --}}
            <div class="lv-overlay d-none" id="ov-err-{{ $cctv->id_cctv }}">
                <i class="bi bi-exclamation-circle fs-3 text-danger"></i>
                <span id="ov-errmsg-{{ $cctv->id_cctv }}">Gagal memuat stream</span>
                <button class="btn btn-sm btn-danger mt-1"
                        onclick="event.stopPropagation(); reloadCell({{ $cctv->id_cctv }})">
                    <i class="bi bi-arrow-repeat me-1"></i>Coba Lagi
                </button>
            </div>

            {{-- Idle overlay --}}
            <div class="lv-overlay d-none" id="ov-idle-{{ $cctv->id_cctv }}">
                <i class="bi bi-play-circle fs-2 opacity-50"></i>
                <span class="opacity-75">Klik untuk memuat</span>
            </div>

            {{-- Label bar --}}
            <div class="lv-label">
                <div style="min-width:0">
                    <div class="cam-name">{{ $cctv->nama_cctv }}</div>
                    <div class="cam-group">{{ $cctv->nama_lokasi ?? '' }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-arrows-fullscreen text-white opacity-50 focus-icon" id="ficon-{{ $cctv->id_cctv }}" style="font-size:.75rem; display:none;"></i>
                    <div class="lv-status-dot
                        @if(($cctv->status ?? '') === 'online') dot-online
                        @elseif(($cctv->status ?? '') === 'offline') dot-offline
                        @else dot-unknown @endif"
                        id="dot-{{ $cctv->id_cctv }}">
                    </div>
                </div>
            </div>

            {{-- Detail link --}}
            <a href="{{ url('/panel/cctv/detailCCTV/' . $cctv->id_cctv) }}"
               onclick="event.stopPropagation();"
               class="position-absolute top-0 end-0 m-2 btn btn-sm btn-icon btn-light-primary opacity-0 detail-btn"
               id="dbtn-{{ $cctv->id_cctv }}"
               style="z-index:10; width:28px; height:28px; padding:0;">
                <i class="bi bi-box-arrow-up-right" style="font-size:.7rem;"></i>
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Focus bar --}}
    <div id="focusBar" style="display:none;" class="mt-2">
        <div class="lv-toolbar" style="background:#0f3460; padding:8px 14px;">
            <span class="text-white fw-bold fs-7">
                <i class="bi bi-pin-angle-fill me-2 text-warning"></i>
                <span id="focusCamName">-</span>
                <small class="text-muted ms-2" id="focusCamLoc"></small>
            </span>
            <button class="btn-icon ms-auto" onclick="clearFocus()">
                <i class="bi bi-grid-3x3 me-1"></i>Grid
            </button>
            <a href="#" id="focusDetailLink" class="btn-icon">
                <i class="bi bi-box-arrow-up-right me-1"></i>Detail
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest/dist/hls.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ezuikit-js@8.2.6/ezuikit.js"></script>

<script>
const CSRF        = '{{ csrf_token() }}';
const playerMap   = {};
const stateMap    = {};
const streamCache = {};
let focusedId   = null;
let globalMuted = true;
let activeGroup = 'all';

const CCTV_IDS = @json($cctvList->pluck('id_cctv'));

// ── Hover show detail btn ─────────────────────────
document.querySelectorAll('.lv-cell').forEach(cell => {
    const id  = cell.dataset.id;
    const btn = document.getElementById('dbtn-' + id);
    cell.addEventListener('mouseenter', () => { if(btn) btn.style.opacity = '1'; });
    cell.addEventListener('mouseleave', () => { if(btn) btn.style.opacity = '0'; });
});

// ── Group filter tabs ─────────────────────────────
document.querySelectorAll('.grp-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.grp-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        activeGroup = this.dataset.group;
        filterByGroup(activeGroup);
    });
});

function filterByGroup(groupId) {
    let visible = 0;
    document.querySelectorAll('.lv-cell').forEach(cell => {
        const show = groupId === 'all' || cell.dataset.group === groupId;
        cell.classList.toggle('hidden-cell', !show);
        if (show) visible++;
    });
    document.getElementById('lblTotal').textContent = visible + ' kamera';
}

// ── Destroy existing player ───────────────────────
function destroyPlayer(id) {
    const p = playerMap[id];
    if (!p) return;
    try {
        if (typeof p.stop === 'function')    p.stop();
        if (typeof p.destroy === 'function') p.destroy();
    } catch(e) {}
    delete playerMap[id];
    const c = document.getElementById('player-' + id);
    if (c) c.innerHTML = '';
}

// ── Load single CCTV stream ───────────────────────
async function loadStream(id, protocol) {
    protocol = protocol || document.getElementById('globalProtocol').value;
    setOverlay(id, 'loading');
    destroyPlayer(id);

    try {
        const res  = await fetch(`/panel/cctv/streamCCTV/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ protocol })
        });
        const data = await res.json();

        if (data.success && data.url) {
            if (data.url.startsWith('ezopen://') || protocol === 'ezopen') {
                streamCache[id] = { url: data.url, accessToken: data.access_token, apiUrl: data.api_url, validCode: data.validCode || '', protocol: 'ezopen' };
                playEzopen(id, data.url, data.access_token, data.api_url, data.validCode);
            } else {
                streamCache[id] = { url: data.url, protocol };
                playHls(id, data.url);
            }
        } else {
            setOverlay(id, 'error', data.message || 'Gagal mendapatkan URL stream');
        }
    } catch (e) {
        setOverlay(id, 'error', 'Koneksi error: ' + e.message);
    }
}

// ── Play EZUIKit ──────────────────────────────────
function playEzopen(id, url, accessToken, apiUrl, validCode) {
    const container = document.getElementById('player-' + id);
    if (!container) return;
    const cell = document.getElementById('cell-' + id);
    const rect = (cell || container).getBoundingClientRect();
    const w    = Math.round(rect.width)  || 640;
    const h    = Math.round(rect.height) || 360;

    try {
        const PlayerClass = (EZUIKit && EZUIKit.EZUIKitPlayer) ? EZUIKit.EZUIKitPlayer
                          : (typeof EZUIKit === 'function') ? EZUIKit : null;
        if (!PlayerClass) { setOverlay(id, 'error', 'EZUIKit SDK gagal dimuat'); return; }

        const player = new PlayerClass({
            id:          'player-' + id,
            accessToken: accessToken,
            url:         url,
            code:        validCode || undefined,
            width:       w,
            height:      h,
            scaleMode:   0,
            audio:       0,
            language:    'en',
            env: { domain: (apiUrl || 'https://isgpopen.ezvizlife.com').replace(/\/$/, '') },
            handleSuccess: () => setOverlay(id, 'ok'),
            handleError:   (e) => {
                const code = e && (e.retcode || e.nErrorCode || e.errorCode || '');
                setOverlay(id, 'error', code ? 'Player error [' + code + ']' : 'Gagal memutar stream');
            }
        });
        playerMap[id] = player;
        setTimeout(() => { if (stateMap[id] === 'loading') setOverlay(id, 'ok'); }, 20000);
    } catch(e) {
        setOverlay(id, 'error', 'EZUIKit error: ' + e.message);
    }
}

// ── Play HLS ─────────────────────────────────────
function playHls(id, url) {
    const container = document.getElementById('player-' + id);
    container.innerHTML = '<video style="width:100%;height:100%;object-fit:contain;background:#000;" autoplay playsinline></video>';
    const video = container.querySelector('video');
    video.muted = globalMuted;

    if (typeof Hls !== 'undefined' && Hls.isSupported()) {
        const hls = new Hls({ enableWorker: true, lowLatencyMode: true });
        hls.loadSource(url);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, () => { video.play().catch(() => {}); setOverlay(id, 'ok'); });
        hls.on(Hls.Events.ERROR, (ev, d) => { if (d.fatal) setOverlay(id, 'error', 'HLS error: ' + (d.details || d.type)); });
        playerMap[id] = hls;
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = url;
        video.play().catch(() => {});
        setOverlay(id, 'ok');
    } else {
        setOverlay(id, 'error', 'HLS tidak didukung');
    }
}

// ── Overlay manager ───────────────────────────────
function setOverlay(id, state, msg) {
    stateMap[id] = state;
    const oLoad = document.getElementById('ov-load-' + id);
    const oErr  = document.getElementById('ov-err-'  + id);
    const oIdle = document.getElementById('ov-idle-' + id);
    [oLoad, oErr, oIdle].forEach(el => el && el.classList.add('d-none'));

    if (state === 'loading') {
        oLoad && oLoad.classList.remove('d-none');
    } else if (state === 'error') {
        destroyPlayer(id);
        if (msg && document.getElementById('ov-errmsg-' + id))
            document.getElementById('ov-errmsg-' + id).textContent = msg;
        oErr && oErr.classList.remove('d-none');
    } else if (state === 'idle') {
        oIdle && oIdle.classList.remove('d-none');
    }
}

function reloadCell(id) { loadStream(id); }

function resizePlayer(id) {
    const cache = streamCache[id];
    if (!cache) return;
    const container = document.getElementById('player-' + id);
    if (!container) return;
    const rect = container.getBoundingClientRect();
    if (rect.width < 10 || rect.height < 10) return;
    destroyPlayer(id);
    if (cache.protocol === 'ezopen') {
        playEzopen(id, cache.url, cache.accessToken, cache.apiUrl, cache.validCode);
    } else {
        playHls(id, cache.url);
    }
}

function resizeAllPlayers() {
    setTimeout(() => CCTV_IDS.forEach(id => resizePlayer(id)), 350);
}

// ── Focus mode ────────────────────────────────────
function toggleFocus(id) {
    if (focusedId === id) { clearFocus(); return; }
    focusedId = id;
    const grid = document.getElementById('lvGrid');
    const cell = document.getElementById('cell-' + id);
    grid.__prevCols = [...grid.classList].find(c => c.startsWith('cols-')) || 'cols-3';

    grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
    grid.classList.add('cols-1');

    document.querySelectorAll('.lv-cell').forEach(c => {
        if (c.dataset.id == id) {
            c.classList.add('focused');
            c.classList.remove('hidden-cell');
            document.getElementById('ficon-' + id).style.display = 'inline';
        } else {
            c.classList.add('hidden-cell');
            c.classList.remove('focused');
            const fi = document.getElementById('ficon-' + c.dataset.id);
            if (fi) fi.style.display = 'none';
        }
    });

    const bar = document.getElementById('focusBar');
    bar.style.display = 'block';
    document.getElementById('focusCamName').textContent = cell.dataset.name;
    document.getElementById('focusCamLoc').textContent  = cell.dataset.lokasi;
    document.getElementById('focusDetailLink').href     = `/panel/cctv/detailCCTV/${id}`;

    document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
    setTimeout(() => resizePlayer(id), 350);
}

function clearFocus() {
    focusedId = null;
    const grid = document.getElementById('lvGrid');
    const prev = grid.__prevCols || 'cols-3';
    grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
    grid.classList.add(prev);

    // Restore visibility respecting group filter
    document.querySelectorAll('.lv-cell').forEach(c => {
        c.classList.remove('focused');
        const fi = document.getElementById('ficon-' + c.dataset.id);
        if (fi) fi.style.display = 'none';
    });
    filterByGroup(activeGroup);

    document.getElementById('focusBar').style.display = 'none';
    const n = prev.replace('cols-', '');
    document.querySelectorAll('.layout-btn').forEach(b => b.classList.toggle('active', b.dataset.cols === n));
    resizeAllPlayers();
}

// ── Layout picker ─────────────────────────────────
document.getElementById('layoutPicker').querySelectorAll('.layout-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (focusedId) clearFocus();
        const n    = btn.dataset.cols;
        const grid = document.getElementById('lvGrid');
        grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
        grid.classList.add('cols-' + n);
        document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        try { localStorage.setItem('lv_all_cols', n); } catch(e) {}
        resizeAllPlayers();
    });
});

// Init default layout
(function() {
    let saved = '3';
    try { saved = localStorage.getItem('lv_all_cols') || '3'; } catch(e) {}
    const grid = document.getElementById('lvGrid');
    if (grid) {
        grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
        grid.classList.add('cols-' + saved);
    }
    document.querySelectorAll('.layout-btn').forEach(b => b.classList.toggle('active', b.dataset.cols === saved));
})();

// ── Reload all ────────────────────────────────────
document.getElementById('btnReloadAll').addEventListener('click', () => {
    CCTV_IDS.forEach(id => loadStream(id));
});

// ── Mute toggle ───────────────────────────────────
document.getElementById('btnMuteAll').addEventListener('click', () => {
    globalMuted = !globalMuted;
    document.getElementById('muteIcon').className = globalMuted ? 'bi bi-volume-mute' : 'bi bi-volume-up';
    document.querySelectorAll('.lv-player-container video').forEach(v => v.muted = globalMuted);
});

// ── Protocol change ───────────────────────────────
document.getElementById('globalProtocol').addEventListener('change', () => {
    CCTV_IDS.forEach(id => {
        if (stateMap[id] === 'ok' || stateMap[id] === 'loading') loadStream(id);
    });
});

// ── Fullscreen ────────────────────────────────────
document.getElementById('btnFullscreen').addEventListener('click', () => {
    const wrapper = document.getElementById('lvWrapper');
    if (!document.fullscreenElement) {
        wrapper.requestFullscreen().catch(() => {});
    } else {
        document.exitFullscreen();
    }
});
document.addEventListener('fullscreenchange', () => {
    const wrapper = document.getElementById('lvWrapper');
    const icon = document.querySelector('#btnFullscreen i');
    if (document.fullscreenElement) {
        wrapper.classList.add('fullscreen-active');
        if (icon) icon.className = 'bi bi-fullscreen-exit';
    } else {
        wrapper.classList.remove('fullscreen-active');
        if (icon) icon.className = 'bi bi-fullscreen';
    }
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && focusedId) clearFocus();
});

window.addEventListener('resize', () => resizeAllPlayers());

// ── Auto-load semua stream ────────────────────────
(function autoLoadAll() {
    CCTV_IDS.forEach((id, i) => {
        setTimeout(() => loadStream(id), i * 600);
    });
})();
</script>
