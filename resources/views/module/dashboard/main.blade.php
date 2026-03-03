<div class="row g-5 g-xl-10 mb-5">
    <!-- Stats Row 1 -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-lg-100" style="background-color:#17c653">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $data['totalGrupLokasi'] }}</span>
                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Group Lokasi</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-sm btn-white btn-color-success">Lihat Semua</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-lg-100" style="background-color:#009ef7">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $data['totalLokasi'] }}</span>
                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Lokasi</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ url('/panel/lokasi/daftarLokasi') }}" class="btn btn-sm btn-white btn-color-primary">Lihat Semua</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush h-lg-100" style="background-color:#7239ea">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $data['totalCCTV'] }}</span>
                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total CCTV</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-sm btn-white btn-color-purple">Lihat Semua</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card card-flush h-lg-100" style="background-color:#f1416c">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $data['totalCCTVOnline'] }}</span>
                        <span class="text-white opacity-50 fs-4">/{{ $data['totalCCTVOffline'] }} offline</span>
                    </div>
                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">CCTV Online / Offline</span>
                </div>
            </div>
            <div class="card-body d-flex align-items-end pt-0">
                <span class="badge badge-white text-danger">
                    <i class="bi bi-wifi me-1"></i>{{ $data['totalCCTVOnline'] }} Online
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-5 g-xl-10 mb-5">
    <!-- Group Lokasi Cards -->
    <div class="col-xl-8">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Group Lokasi CCTV</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Klik group untuk melihat detail lokasi</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-grid me-2"></i>Semua Group
                    </a>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="row g-4">
                    @forelse($data['grupLokasi'] as $grup)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ url('/panel/grupLokasi/detailGrupLokasi/' . $grup->id_group) }}"
                           class="card cctv-card border-0 shadow-sm text-decoration-none">
                            <div class="card-body text-center py-6">
                                <div class="mb-3">
                                    <span class="badge badge-light-primary fs-7 px-3 py-2">
                                        <i class="bi bi-camera-video me-1"></i>
                                        {{ $grup->total_cctv ?? 0 }} CCTV
                                    </span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $grup->nama_group }}</h5>
                                <p class="text-muted fs-7 mb-0">{{ $grup->deskripsi ?? 'Klik untuk detail' }}</p>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
                        Belum ada group lokasi
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Stats sidebar -->
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-5">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold fs-3">Statistik Sistem</h3>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex flex-stack mb-5">
                    <div class="d-flex align-items-center me-2">
                        <div class="symbol symbol-50px me-3">
                            <div class="symbol-label bg-light-success">
                                <i class="bi bi-people fs-2 text-success"></i>
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('/panel/masterData/daftarPengguna') }}" class="fs-6 text-gray-800 text-hover-primary fw-bold">Pengguna Aktif</a>
                            <div class="fs-7 text-muted fw-semibold mt-1">Total pengguna terdaftar</div>
                        </div>
                    </div>
                    <div class="badge badge-light fw-semibold">{{ $data['totalPengguna'] }}</div>
                </div>
                <div class="d-flex flex-stack mb-5">
                    <div class="d-flex align-items-center me-2">
                        <div class="symbol symbol-50px me-3">
                            <div class="symbol-label bg-light-primary">
                                <i class="bi bi-cloud fs-2 text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="fs-6 text-gray-800 text-hover-primary fw-bold">Akun Ezviz</a>
                            <div class="fs-7 text-muted fw-semibold mt-1">Multi-akun terdaftar</div>
                        </div>
                    </div>
                    <div class="badge badge-light fw-semibold">{{ $data['totalEzvizAkun'] }}</div>
                </div>
            </div>
        </div>

        <!-- Recent Log -->
        <div class="card card-xl-stretch">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title fw-bold fs-3">Log Aktifitas</h3>
                <div class="card-toolbar">
                    <a href="{{ url('/panel/pengaturan/logAktivitas') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body pt-0">
                @forelse($data['recentLog'] as $log)
                <div class="d-flex align-items-center mb-4">
                    <div class="symbol symbol-35px me-3">
                        <div class="symbol-label bg-light-info">
                            <i class="bi bi-activity text-info fs-6"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <span class="fs-7 fw-bold text-dark">{{ $log->aksi }}</span>
                        <div class="fs-8 text-muted">{{ $log->username }} &middot; {{ \Carbon\Carbon::parse($log->created_time)->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-3 text-muted fs-7">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
