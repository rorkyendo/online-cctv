<!-- Detail Group Lokasi - Shows Lokasi within the group -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <div class="d-flex align-items-center gap-3 mb-1">
            <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="text-muted text-hover-primary">
                <i class="bi bi-grid me-1"></i>Group Lokasi
            </a>
            <span class="text-muted">/</span>
            <span class="fw-bold text-dark">{{ $data['grupLokasi']->nama_group }}</span>
        </div>
        <span class="text-muted fs-7">{{ $data['grupLokasi']->deskripsi ?? 'Daftar lokasi dalam group ini' }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/panel/cctv/liveViewGroup/' . $data['grupLokasi']->id_group) }}" class="btn btn-success">
            <i class="bi bi-camera-video-fill me-2"></i>Live View Semua CCTV
        </a>
        <a href="{{ url('/panel/lokasi/tambahLokasi') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Lokasi
        </a>
        <a href="{{ url('/panel/grupLokasi/updateGrupLokasi/' . $data['grupLokasi']->id_group) }}" class="btn btn-light-warning">
            <i class="bi bi-pencil me-2"></i>Edit Group
        </a>
    </div>
</div>

<div class="row g-5">
    @forelse($data['lokasiList'] as $lokasi)
    <div class="col-sm-6 col-lg-4">
        <div class="card cctv-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center text-center py-8 px-5">
                <div class="symbol symbol-60px mb-4">
                    <div class="symbol-label bg-light-success">
                        <i class="bi bi-geo-alt fs-2 text-success"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $lokasi->nama_lokasi }}</h5>
                <p class="text-muted fs-7 mb-2">{{ $lokasi->deskripsi ?? '-' }}</p>
                <span class="badge badge-light-primary mb-4">
                    <i class="bi bi-camera-video me-1"></i>{{ $lokasi->total_cctv ?? 0 }} CCTV
                </span>
                <a href="{{ url('/panel/lokasi/detailLokasi/' . $lokasi->id_lokasi) }}"
                   class="btn btn-sm btn-primary w-100 mt-auto">
                    <i class="bi bi-eye me-2"></i>Lihat CCTV
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="bi bi-geo-alt fs-1 text-muted d-block mb-4"></i>
                <h5 class="text-muted">Belum ada lokasi dalam group ini</h5>
                <a href="{{ url('/panel/lokasi/tambahLokasi') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Lokasi
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
