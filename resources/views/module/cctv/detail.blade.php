<!-- CCTV Detail - Live Stream View -->
@php
    $cctv      = $data['cctv'];
    $deviceInfo = $data['deviceInfo'] ?? null;
@endphp

<div class="d-flex justify-content-between align-items-start mb-6 flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1 fs-7 text-muted">
            @if($cctv->id_lokasi)
                <a href="{{ url('/panel/lokasi/detailLokasi/' . $cctv->id_lokasi) }}" class="text-muted text-hover-primary">
                    {{ $cctv->nama_lokasi ?? 'Lokasi' }}
                </a>
                <span>/</span>
            @endif
            <span class="fw-bold text-dark">{{ $cctv->nama_cctv }}</span>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2">
            @if($cctv->status === 'online')
                <span class="badge badge-success px-3 py-2"><i class="bi bi-wifi me-1"></i>Online</span>
            @elseif($cctv->status === 'offline')
                <span class="badge badge-danger px-3 py-2"><i class="bi bi-wifi-off me-1"></i>Offline</span>
            @else
                <span class="badge badge-secondary px-3 py-2">Status Tidak Diketahui</span>
            @endif
            <span class="text-muted fs-7">
                <i class="bi bi-hdd me-1"></i>{{ $cctv->device_serial ?? '-' }}
            </span>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button id="btn-capture" class="btn btn-light-info" onclick="captureCCTV({{ $cctv->id_cctv }})">
            <i class="bi bi-camera me-2"></i>Capture
        </button>
        <button id="btn-stream" class="btn btn-primary" onclick="loadStream({{ $cctv->id_cctv }})">
            <i class="bi bi-play-circle me-2"></i>Live View
        </button>
        <a href="{{ url('/panel/cctv/updateCCTV/' . $cctv->id_cctv) }}" class="btn btn-light-warning">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
    </div>
</div>

<div class="row g-5">
    <!-- Main Stream Area -->
    <div class="col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold">
                    <i class="bi bi-camera-video me-2 text-primary"></i>Live Stream
                </h3>
                <div class="card-toolbar">
                    <div class="d-flex gap-2">
                        <select id="stream-protocol" class="form-select form-select-sm w-auto">
                            <option value="hls">HLS</option>
                            <option value="ezopen">EZOpen</option>
                            <option value="rtmp">RTMP</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="stream-container" class="video-container" style="min-height: 400px; background: #1a1a2e; border-radius: 0 0 12px 12px;">
                    <div id="stream-placeholder" class="video-overlay flex-column gap-3">
                        <i class="bi bi-camera-video fs-1 opacity-50"></i>
                        <span class="opacity-75 fs-6">Klik "Live View" untuk memulai streaming</span>
                        <button class="btn btn-sm btn-primary" onclick="loadStream({{ $cctv->id_cctv }})">
                            <i class="bi bi-play-fill me-2"></i>Mulai Live View
                        </button>
                    </div>
                    <video id="cctv-video" controls autoplay muted playsinline style="display:none; width:100%; max-height:500px;"></video>
                    <!-- For EZOpen protocol - iframe -->
                    <iframe id="cctv-iframe" style="display:none; width:100%; min-height:400px; border:none;"></iframe>
                    <div id="stream-loading" style="display:none;" class="video-overlay">
                        <div class="spinner-border text-white" role="status"></div>
                        <span class="ms-3 text-white">Menghubungkan ke kamera...</span>
                    </div>
                    <div id="stream-error" style="display:none;" class="video-overlay flex-column gap-3">
                        <i class="bi bi-exclamation-circle fs-1 text-danger"></i>
                        <span id="stream-error-msg" class="text-white fs-6">Gagal memuat stream</span>
                        <button class="btn btn-sm btn-danger" onclick="loadStream({{ $cctv->id_cctv }})">
                            <i class="bi bi-arrow-repeat me-2"></i>Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Capture Result -->
        <div id="capture-result" class="card shadow-sm mt-5" style="display:none;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold"><i class="bi bi-camera me-2"></i>Hasil Capture</h3>
            </div>
            <div class="card-body">
                <img id="capture-img" src="" alt="Capture" class="img-fluid rounded" style="max-height:300px;" />
                <div class="mt-3">
                    <a id="capture-download" href="#" download="capture.jpg" class="btn btn-sm btn-success">
                        <i class="bi bi-download me-2"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Info Panel -->
    <div class="col-xl-4">
        <div class="card shadow-sm mb-5">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold">Info Perangkat</h3>
            </div>
            <div class="card-body">
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Nama</span>
                    <span class="fw-bold text-dark">{{ $cctv->nama_cctv }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Serial</span>
                    <span class="fw-bold text-dark font-monospace">{{ $cctv->device_serial ?? '-' }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Channel</span>
                    <span class="fw-bold text-dark">{{ $cctv->channel_no ?? 1 }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Stream Type</span>
                    <span class="badge badge-light-primary">{{ strtoupper($cctv->stream_type ?? 'HD') }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Akun Ezviz</span>
                    <span class="fw-bold text-dark">{{ $cctv->nama_akun ?? '-' }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Lokasi</span>
                    <span class="fw-bold text-dark">{{ $cctv->nama_lokasi ?? '-' }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Group</span>
                    <span class="badge badge-light-success">{{ $cctv->nama_group ?? '-' }}</span>
                </div>
                @if($deviceInfo)
                <div class="separator my-4"></div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Firmware</span>
                    <span class="fw-bold text-dark fs-7">{{ $deviceInfo['data']['deviceSoftwareVersion'] ?? '-' }}</span>
                </div>
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted fw-semibold">Tipe Perangkat</span>
                    <span class="fw-bold text-dark fs-7">{{ $deviceInfo['data']['deviceType'] ?? '-' }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold">Aksi Cepat</h3>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <button class="btn btn-light-primary text-start" onclick="loadStream({{ $cctv->id_cctv }})">
                    <i class="bi bi-play-circle me-3"></i>Start Live View
                </button>
                <button class="btn btn-light-info text-start" onclick="captureCCTV({{ $cctv->id_cctv }})">
                    <i class="bi bi-camera me-3"></i>Ambil Screenshot
                </button>
                <a href="{{ url('/panel/cctv/updateCCTV/' . $cctv->id_cctv) }}"
                   class="btn btn-light-warning text-start">
                    <i class="bi bi-gear me-3"></i>Pengaturan CCTV
                </a>
                <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-light text-start">
                    <i class="bi bi-arrow-left me-3"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function showStreamLoading() {
        document.getElementById('stream-placeholder').style.display = 'none';
        document.getElementById('cctv-video').style.display = 'none';
        document.getElementById('cctv-iframe').style.display = 'none';
        document.getElementById('stream-error').style.display = 'none';
        document.getElementById('stream-loading').style.display = 'flex';
    }

    function showStreamError(msg) {
        document.getElementById('stream-loading').style.display = 'none';
        document.getElementById('stream-error-msg').innerText = msg || 'Gagal memuat stream';
        document.getElementById('stream-error').style.display = 'flex';
    }

    function loadStream(cctvId) {
        const protocol = document.getElementById('stream-protocol').value;
        showStreamLoading();

        fetch(`/panel/cctv/streamCCTV/${cctvId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ protocol: protocol })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('stream-loading').style.display = 'none';
            if (data.success && data.url) {
                if (protocol === 'hls') {
                    playHLS(data.url);
                } else if (protocol === 'ezopen') {
                    playEZOpen(data.url);
                } else {
                    playWithVideo(data.url);
                }
            } else {
                showStreamError(data.message || 'Gagal mendapatkan URL stream');
            }
        })
        .catch(err => {
            showStreamError('Koneksi error: ' + err.message);
        });
    }

    function playHLS(url) {
        const video = document.getElementById('cctv-video');
        video.style.display = 'block';
        document.getElementById('cctv-iframe').style.display = 'none';

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(url);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                video.play();
            });
            hls.on(Hls.Events.ERROR, (event, data) => {
                if (data.fatal) showStreamError('HLS stream error');
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = url;
            video.play();
        } else {
            showStreamError('HLS tidak didukung browser ini');
        }
    }

    function playEZOpen(url) {
        document.getElementById('cctv-video').style.display = 'none';
        const iframe = document.getElementById('cctv-iframe');
        iframe.src = url;
        iframe.style.display = 'block';
    }

    function playWithVideo(url) {
        const video = document.getElementById('cctv-video');
        video.src = url;
        video.style.display = 'block';
        document.getElementById('cctv-iframe').style.display = 'none';
        video.play().catch(e => showStreamError('Gagal memutar video: ' + e.message));
    }

    function captureCCTV(cctvId) {
        const btn = document.getElementById('btn-capture');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Capturing...';

        fetch(`/panel/cctv/captureCCTV/${cctvId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-camera me-2"></i>Capture';
            if (data.success && data.imageUrl) {
                document.getElementById('capture-img').src = data.imageUrl;
                document.getElementById('capture-download').href = data.imageUrl;
                document.getElementById('capture-result').style.display = 'block';
                document.getElementById('capture-result').scrollIntoView({ behavior: 'smooth' });
            } else {
                alert('Gagal capture: ' + (data.message || 'Error tidak diketahui'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-camera me-2"></i>Capture';
            alert('Error: ' + err.message);
        });
    }
</script>
