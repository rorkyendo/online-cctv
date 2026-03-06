<!-- Group Lokasi List - Card Grid Layout -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar Group Lokasi</h4>
        <span class="text-muted fs-7">Kelola group-group lokasi CCTV Anda</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/panel/cctv/liveViewAllGroups') }}" class="btn btn-success">
            <i class="bi bi-play-circle me-2"></i>Live Semua CCTV
        </a>
        <a href="{{ url('/panel/grupLokasi/tambahGrupLokasi') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Group
        </a>
    </div>
</div>

<div class="row g-5">
    @forelse($data['grupLokasi'] as $grup)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card cctv-card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column align-items-center text-center py-8 px-5">
                <div class="symbol symbol-70px mb-4">
                    <div class="symbol-label bg-light-primary">
                        <i class="bi bi-camera-video fs-1 text-primary"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $grup->nama_group }}</h5>
                <p class="text-muted fs-7 mb-2">{{ $grup->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                <div class="d-flex gap-1 mb-4">
                    <span class="badge badge-light-primary">
                        <i class="bi bi-camera-video me-1"></i>{{ $grup->total_cctv ?? 0 }} CCTV
                    </span>
                    <span class="badge badge-light-info">
                        <i class="bi bi-geo-alt me-1"></i>{{ $grup->total_lokasi ?? 0 }} Lokasi
                    </span>
                </div>
                <div class="d-flex gap-2 mt-auto w-100">
                    <a href="{{ url('/panel/grupLokasi/detailGrupLokasi/' . $grup->id_group) }}"
                       class="btn btn-sm btn-primary flex-grow-1">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>
                    <a href="{{ url('/panel/cctv/liveViewGroup/' . $grup->id_group) }}"
                       class="btn btn-sm btn-success flex-grow-1" title="Live View Group">
                        <i class="bi bi-play-circle me-1"></i>Live
                    </a>
                    <a href="{{ url('/panel/grupLokasi/updateGrupLokasi/' . $grup->id_group) }}"
                       class="btn btn-sm btn-light-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="{{ url('/panel/grupLokasi/hapusGrupLokasi/' . $grup->id_group) }}"
                       class="btn btn-sm btn-light-danger"
                       onclick="return confirm('Hapus group {{ $grup->nama_group }}?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="bi bi-folder2-open fs-1 text-muted d-block mb-4"></i>
                <h5 class="text-muted">Belum ada group lokasi</h5>
                <a href="{{ url('/panel/grupLokasi/tambahGrupLokasi') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Group Pertama
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
