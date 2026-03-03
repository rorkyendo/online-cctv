<!-- Detail Lokasi - Shows CCTV list within the location -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1 fs-7 text-muted">
            <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="text-muted text-hover-primary">Group Lokasi</a>
            @if($data['lokasi']->id_group)
                <span>/</span>
                <a href="{{ url('/panel/grupLokasi/detailGrupLokasi/' . $data['lokasi']->id_group) }}"
                   class="text-muted text-hover-primary">{{ $data['lokasi']->nama_group ?? 'Group' }}</a>
            @endif
            <span>/</span>
            <span class="fw-bold text-dark">{{ $data['lokasi']->nama_lokasi }}</span>
        </div>
        @if($data['lokasi']->deskripsi)
            <span class="text-muted fs-7"><i class="bi bi-geo-alt me-1"></i>{{ $data['lokasi']->deskripsi }}</span>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/panel/cctv/tambahCCTV') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah CCTV
        </a>
    </div>
</div>

<!-- CCTV Grid -->
<div class="row g-5">
    @forelse($data['cctvList'] as $cctv)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card cctv-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column p-5">
                <!-- Camera preview area -->
                <div class="video-container mb-3" style="min-height:140px; background:#1a1a2e; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-camera-video text-white opacity-50 fs-1"></i>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0">{{ $cctv->nama_cctv }}</h6>
                    @if($cctv->status === 'online')
                        <span class="badge badge-success">Online</span>
                    @elseif($cctv->status === 'offline')
                        <span class="badge badge-danger">Offline</span>
                    @else
                        <span class="badge badge-secondary">Unknown</span>
                    @endif
                </div>
                <p class="text-muted fs-8 mb-3">Serial: {{ $cctv->device_serial ?? '-' }}</p>
                <a href="{{ url('/panel/cctv/detailCCTV/' . $cctv->id_cctv) }}"
                   class="btn btn-sm btn-primary w-100 mt-auto">
                    <i class="bi bi-camera-video me-2"></i>Live View
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="bi bi-camera-video fs-1 text-muted d-block mb-4"></i>
                <h5 class="text-muted">Belum ada CCTV di lokasi ini</h5>
                <a href="{{ url('/panel/cctv/tambahCCTV') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-2"></i>Tambah CCTV
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
