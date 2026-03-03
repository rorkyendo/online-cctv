{{-- ============================================================ --}}
{{-- Live View Group — Grid semua CCTV dalam 1 Grup Lokasi --}}
{{-- ============================================================ --}}
@php
    $group    = $data['group'];
    $cctvList = $data['cctvList'];
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
.lv-cell.side        { opacity: .6; }
.lv-cell.hidden-cell { display: none; }

/* ── Video inside cell ───────────────────────────────────── */
.lv-cell video {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    background: #000;
}

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
    background: linear-gradient(transparent, rgba(0,0,0,.8));
    color: #fff;
    padding: 20px 10px 8px;
    font-size: .78rem;
    z-index: 6;
    pointer-events: none;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}
.lv-label .cam-name { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 70%; }
.lv-label .cam-loc  { font-size: .7rem; opacity: .75; }
.lv-status-dot      { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-online  { background: #50cd89; box-shadow: 0 0 6px #50cd89; }
.dot-offline { background: #f1416c; }
.dot-unknown { background: #888; }

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
}
.lv-toolbar .btn-icon:hover { background: rgba(255,255,255,.2); }
.lv-toolbar .btn-icon.active { background: #3e97ff; }

/* ── Layout picker ───────────────────────────────────────── */
.layout-picker { display: flex; gap: 4px; }
.layout-btn { background: rgba(255,255,255,.08); border: none; color: #aaa; border-radius: 6px; padding: 5px 9px; cursor: pointer; font-size: .75rem; transition: all .15s; }
.layout-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
.layout-btn.active { background: #3e97ff; color: #fff; }

/* ── Focus mode — 1 big + rest small ─────────────────────── */
.lv-grid.focus-mode { grid-template-columns: 1fr; }
.lv-grid.focus-mode .lv-cell { aspect-ratio: 16/9; }

/* ── Fullscreen dark wrapper ─────────────────────────────── */
.lv-wrapper.fullscreen-active {
    position: fixed;
    inset: 0;
    background: #000;
    z-index: 9999;
    padding: 8px;
    display: flex;
    flex-direction: column;
}
.lv-wrapper.fullscreen-active .lv-toolbar { border-radius: 6px; }
.lv-wrapper.fullscreen-active .lv-grid    { flex: 1; overflow: auto; }

/* ── Responsive tweaks ────────────────────────────────────── */
@media (max-width: 768px) {
    .lv-grid.cols-3, .lv-grid.cols-4 { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .lv-grid.cols-2, .lv-grid.cols-3, .lv-grid.cols-4 { grid-template-columns: 1fr; }
}
</style>

{{-- ── breadcrumb ── --}}
<div class="d-flex align-items-center gap-2 mb-4 fs-7 text-muted">
    <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="text-muted text-hover-primary">
        <i class="bi bi-grid me-1"></i>Grup Lokasi
    </a>
    <span>/</span>
    <a href="{{ url('/panel/grupLokasi/detailGrupLokasi/' . $group->id_group) }}" class="text-muted text-hover-primary">
        {{ $group->nama_group }}
    </a>
    <span>/</span>
    <span class="fw-bold text-dark">Live View</span>
</div>

<div class="lv-wrapper" id="lvWrapper">

    {{-- ── Top Toolbar ── --}}
    <div class="lv-toolbar">
        <div class="lv-title">
            <i class="bi bi-camera-video me-2 text-primary"></i>{{ $group->nama_group }}
            <small class="text-muted fw-normal ms-2">{{ $total }} kamera</small>
        </div>

        {{-- Layout picker --}}
        <div class="layout-picker" id="layoutPicker">
            <button class="layout-btn" data-cols="1" title="1 kolom">
                <i class="bi bi-square"></i>
            </button>
            <button class="layout-btn" data-cols="2" title="2×2">
                <i class="bi bi-grid-2x2"></i>
            </button>
            <button class="layout-btn" data-cols="3" title="3 kolom">
                <i class="bi bi-grid-3x3"></i>
            </button>
            <button class="layout-btn" data-cols="4" title="4 kolom">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M1 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 12a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/></svg>
            </button>
        </div>

        {{-- Protocol selector --}}
        <select id="globalProtocol" class="form-select form-select-sm w-auto" style="background:#2b2b3d;color:#fff;border-color:#3d3d5c;">
            <option value="hls">HLS</option>
            <option value="flv">FLV</option>
            <option value="rtmp">RTMP</option>
        </select>

        {{-- Actions --}}
        <button class="btn-icon" id="btnReloadAll" title="Reload semua stream">
            <i class="bi bi-arrow-repeat"></i> Reload Semua
        </button>
        <button class="btn-icon" id="btnMuteAll" title="Mute/unmute semua">
            <i class="bi bi-volume-mute" id="muteIcon"></i>
        </button>
        <button class="btn-icon" id="btnFullscreen" title="Fullscreen">
            <i class="bi bi-fullscreen"></i>
        </button>
        <a href="{{ url('/panel/grupLokasi/detailGrupLokasi/' . $group->id_group) }}" class="btn-icon" title="Kembali">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ── Camera Grid ── --}}
    @if($total === 0)
        <div class="card">
            <div class="card-body text-center py-12">
                <i class="bi bi-camera-video-off fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">Belum ada CCTV dalam grup ini</h5>
                <a href="{{ url('/panel/cctv/tambahCCTV') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-2"></i>Tambah CCTV
                </a>
            </div>
        </div>
    @else
    <div class="lv-grid cols-2" id="lvGrid">
        @foreach($cctvList as $cctv)
        <div class="lv-cell" id="cell-{{ $cctv->id_cctv }}"
             data-id="{{ $cctv->id_cctv }}"
             data-name="{{ $cctv->nama_cctv }}"
             data-lokasi="{{ $cctv->nama_lokasi ?? '' }}"
             data-status="{{ $cctv->status ?? 'unknown' }}"
             onclick="toggleFocus({{ $cctv->id_cctv }})">

            {{-- Video element --}}
            <video id="vid-{{ $cctv->id_cctv }}" muted autoplay playsinline
                   preload="none" style="display:none;"></video>

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

            {{-- Idle overlay (sebelum load) --}}
            <div class="lv-overlay d-none" id="ov-idle-{{ $cctv->id_cctv }}">
                <i class="bi bi-play-circle fs-2 opacity-50"></i>
                <span class="opacity-75">Klik untuk memuat</span>
            </div>

            {{-- Label bar --}}
            <div class="lv-label">
                <div>
                    <div class="cam-name">{{ $cctv->nama_cctv }}</div>
                    <div class="cam-loc">{{ $cctv->nama_lokasi ?? '' }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    {{-- focus indicator --}}
                    <i class="bi bi-arrows-fullscreen text-white opacity-50 focus-icon" id="ficon-{{ $cctv->id_cctv }}" style="font-size:.75rem; display:none;"></i>
                    <div class="lv-status-dot
                        @if(($cctv->status ?? '') === 'online') dot-online
                        @elseif(($cctv->status ?? '') === 'offline') dot-offline
                        @else dot-unknown @endif"
                        id="dot-{{ $cctv->id_cctv }}">
                    </div>
                </div>
            </div>

            {{-- Detail link (top right) --}}
            <a href="{{ url('/panel/cctv/detailCCTV/' . $cctv->id_cctv) }}"
               onclick="event.stopPropagation();"
               class="position-absolute top-0 end-0 m-2 btn btn-sm btn-icon btn-light-primary opacity-0 detail-btn"
               id="dbtn-{{ $cctv->id_cctv }}"
               title="Detail kamera"
               style="z-index:10; width:28px; height:28px; padding:0;">
                <i class="bi bi-box-arrow-up-right" style="font-size:.7rem;"></i>
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Focus Mode: single large view ── --}}
    <div id="focusBar" style="display:none;" class="mt-2">
        <div class="lv-toolbar" style="background:#0f3460; padding:8px 14px;">
            <span class="text-white fw-bold fs-7">
                <i class="bi bi-pin-angle-fill me-2 text-warning"></i>
                <span id="focusCamName">-</span>
                <small class="text-muted ms-2" id="focusCamLoc"></small>
            </span>
            <button class="btn-icon ms-auto" onclick="clearFocus()" title="Kembali ke grid">
                <i class="bi bi-grid-3x3 me-1"></i>Grid
            </button>
            <a href="#" id="focusDetailLink" class="btn-icon" title="Buka detail">
                <i class="bi bi-box-arrow-up-right me-1"></i>Detail
            </a>
        </div>
    </div>
</div>

{{-- ──────────────────────────────────────────────── --}}
<script>
const CSRF    = '{{ csrf_token() }}';
const hlsMap  = {};        // id_cctv → Hls instance
const stateMap = {};       // id_cctv → 'loading'|'ok'|'error'|'idle'
let focusedId  = null;
let globalMuted = true;    // start muted
let autoLoad    = true;    // load semua otomatis saat pertama

const CCTV_IDS = @json($cctvList->pluck('id_cctv'));

// ── Hover show detail btn ─────────────────────────
document.querySelectorAll('.lv-cell').forEach(cell => {
    const id = cell.dataset.id;
    const btn = document.getElementById('dbtn-' + id);
    cell.addEventListener('mouseenter', () => { if(btn) btn.style.opacity = '1'; });
    cell.addEventListener('mouseleave', () => { if(btn) btn.style.opacity = '0'; });
});

// ── Load single CCTV stream ───────────────────────
async function loadStream(id, protocol) {
    protocol = protocol || document.getElementById('globalProtocol').value;
    setOverlay(id, 'loading');

    try {
        const res = await fetch(`/panel/cctv/streamCCTV/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ protocol })
        });
        const data = await res.json();

        if (data.success && data.url) {
            playVideo(id, data.url, protocol);
        } else {
            setOverlay(id, 'error', data.message || 'Gagal mendapatkan URL stream');
        }
    } catch (e) {
        setOverlay(id, 'error', 'Koneksi error: ' + e.message);
    }
}

// ── Play via HLS.js ───────────────────────────────
function playVideo(id, url, protocol) {
    const video = document.getElementById('vid-' + id);
    video.style.display = 'block';
    video.muted = globalMuted;

    // Destroy old HLS instance if exists
    if (hlsMap[id]) { hlsMap[id].destroy(); delete hlsMap[id]; }

    if (protocol === 'hls' || url.includes('.m3u8')) {
        if (Hls.isSupported()) {
            const hls = new Hls({ enableWorker: true, lowLatencyMode: true });
            hls.loadSource(url);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                video.play().catch(() => {});
                setOverlay(id, 'ok');
            });
            hls.on(Hls.Events.ERROR, (event, d) => {
                if (d.fatal) setOverlay(id, 'error', 'HLS error: ' + d.type);
            });
            hlsMap[id] = hls;
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = url;
            video.play().catch(() => {});
            setOverlay(id, 'ok');
        } else {
            setOverlay(id, 'error', 'HLS tidak didukung browser ini');
        }
    } else {
        // flv/rtmp - gunakan native video tag
        video.src = url;
        video.play().catch(() => {});
        setOverlay(id, 'ok');
    }
}

// ── Overlay state manager ─────────────────────────
function setOverlay(id, state, msg) {
    stateMap[id] = state;
    const vid   = document.getElementById('vid-' + id);
    const oLoad = document.getElementById('ov-load-' + id);
    const oErr  = document.getElementById('ov-err-'  + id);
    const oIdle = document.getElementById('ov-idle-' + id);

    oLoad.classList.add('d-none');
    oErr.classList.add('d-none');
    oIdle.classList.add('d-none');

    if (state === 'loading') {
        oLoad.classList.remove('d-none');
    } else if (state === 'error') {
        vid.style.display = 'none';
        if (hlsMap[id]) { hlsMap[id].destroy(); delete hlsMap[id]; }
        if (msg) document.getElementById('ov-errmsg-' + id).textContent = msg;
        oErr.classList.remove('d-none');
    } else if (state === 'idle') {
        vid.style.display = 'none';
        oIdle.classList.remove('d-none');
    }
    // 'ok' = all overlays hidden, video visible
}

function reloadCell(id) {
    loadStream(id);
}

// ── Focus mode: klik satu kamera → perbesar ───────
function toggleFocus(id) {
    if (focusedId === id) { clearFocus(); return; }
    focusedId = id;

    const grid = document.getElementById('lvGrid');
    const bar  = document.getElementById('focusBar');
    const cell = document.getElementById('cell-' + id);

    // Simpan layout kolom saat ini
    grid.__prevCols = [...grid.classList].find(c => c.startsWith('cols-')) || 'cols-2';

    // Satu kolom, sembunyikan cell lain
    grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
    grid.classList.add('cols-1');

    document.querySelectorAll('.lv-cell').forEach(c => {
        if (c.dataset.id == id) {
            c.classList.add('focused');
            c.classList.remove('side','hidden-cell');
            document.getElementById('ficon-' + id).style.display = 'inline';
        } else {
            c.classList.add('hidden-cell');
            c.classList.remove('focused','side');
            document.getElementById('ficon-' + c.dataset.id).style.display = 'none';
        }
    });

    // Update focus bar
    bar.style.display = 'block';
    document.getElementById('focusCamName').textContent = cell.dataset.name;
    document.getElementById('focusCamLoc').textContent  = cell.dataset.lokasi;
    document.getElementById('focusDetailLink').href = `/panel/cctv/detailCCTV/${id}`;

    // Layout btns jadi inactive
    document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
}

function clearFocus() {
    focusedId = null;
    const grid = document.getElementById('lvGrid');
    const bar  = document.getElementById('focusBar');

    // Restore kolom
    const prev = grid.__prevCols || 'cols-2';
    grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
    grid.classList.add(prev);

    document.querySelectorAll('.lv-cell').forEach(c => {
        c.classList.remove('focused','side','hidden-cell');
        document.getElementById('ficon-' + c.dataset.id).style.display = 'none';
    });

    bar.style.display = 'none';

    // Re-activate layout btn
    const colsN = prev.replace('cols-','');
    document.querySelectorAll('.layout-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.cols === colsN);
    });
}

// ── Layout picker ─────────────────────────────────
document.getElementById('layoutPicker').querySelectorAll('.layout-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (focusedId) clearFocus();
        const n = btn.dataset.cols;
        const grid = document.getElementById('lvGrid');
        grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
        grid.classList.add('cols-' + n);
        document.querySelectorAll('.layout-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        // Simpan preference
        try { localStorage.setItem('lv_cols', n); } catch(e){}
    });
});

// Init default layout (2 kolom atau dari localStorage)
(function() {
    let savedCols = '2';
    try { savedCols = localStorage.getItem('lv_cols') || '2'; } catch(e) {}
    const grid = document.getElementById('lvGrid');
    if (grid) {
        grid.classList.remove('cols-1','cols-2','cols-3','cols-4');
        grid.classList.add('cols-' + savedCols);
    }
    document.querySelectorAll('.layout-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.cols === savedCols);
    });
})();

// ── Reload all ────────────────────────────────────
document.getElementById('btnReloadAll').addEventListener('click', () => {
    CCTV_IDS.forEach(id => loadStream(id));
});

// ── Mute toggle ───────────────────────────────────
document.getElementById('btnMuteAll').addEventListener('click', () => {
    globalMuted = !globalMuted;
    const icon = document.getElementById('muteIcon');
    icon.className = globalMuted ? 'bi bi-volume-mute' : 'bi bi-volume-up';
    document.querySelectorAll('.lv-cell video').forEach(v => { v.muted = globalMuted; });
});

// ── Protocol change → reload all ─────────────────
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
    const btn  = document.getElementById('btnFullscreen');
    const icon = btn.querySelector('i');
    if (document.fullscreenElement) {
        icon.className = 'bi bi-fullscreen-exit';
    } else {
        icon.className = 'bi bi-fullscreen';
    }
});

// ── Esc key → clear focus or exit fullscreen ──────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (focusedId) { clearFocus(); return; }
    }
});

// ── Auto-load semua stream saat halaman terbuka ───
// Delay antar request agar tidak overload
(function autoLoadAll() {
    if (!autoLoad) return;
    CCTV_IDS.forEach((id, i) => {
        setTimeout(() => loadStream(id), i * 600);
    });
})();
</script>
