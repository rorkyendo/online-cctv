<!-- Stats Row: compact horizontal cards -->
<div class="row g-4 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="background-color:#17c653">
            <div class="card-body d-flex align-items-center py-5 px-6">
                <div class="me-4">
                    <i class="bi bi-geo-alt fs-2x text-white opacity-75"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fs-2hx fw-bold text-white lh-1">{{ $data['totalGrupLokasi'] }}</div>
                    <div class="text-white opacity-75 fw-semibold fs-7 mt-1">Group Lokasi</div>
                </div>
                <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-sm btn-white btn-color-success ms-2">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="background-color:#009ef7">
            <div class="card-body d-flex align-items-center py-5 px-6">
                <div class="me-4">
                    <i class="bi bi-map fs-2x text-white opacity-75"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fs-2hx fw-bold text-white lh-1">{{ $data['totalLokasi'] }}</div>
                    <div class="text-white opacity-75 fw-semibold fs-7 mt-1">Lokasi</div>
                </div>
                <a href="{{ url('/panel/lokasi/daftarLokasi') }}" class="btn btn-sm btn-white btn-color-primary ms-2">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="background-color:#7239ea">
            <div class="card-body d-flex align-items-center py-5 px-6">
                <div class="me-4">
                    <i class="bi bi-camera-video fs-2x text-white opacity-75"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fs-2hx fw-bold text-white lh-1">{{ $data['totalCCTV'] }}</div>
                    <div class="text-white opacity-75 fw-semibold fs-7 mt-1">Total CCTV</div>
                </div>
                <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-sm btn-white ms-2" style="color:#7239ea">
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100" style="background-color:#f1416c">
            <div class="card-body d-flex align-items-center py-5 px-6">
                <div class="me-4">
                    <i class="bi bi-wifi fs-2x text-white opacity-75"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="fs-2hx fw-bold text-white lh-1">{{ $data['totalCCTVOnline'] }}</div>
                        <div class="text-white opacity-50 fs-6">/ {{ $data['totalCCTVOffline'] }} offline</div>
                    </div>
                    <div class="text-white opacity-75 fw-semibold fs-7 mt-1">CCTV Online / Offline</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-5 mb-5">
    <!-- Group Lokasi Cards -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-4 mb-1">Group Lokasi CCTV</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Klik group untuk melihat detail lokasi</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-grid me-2"></i>Semua Group
                    </a>
                </div>
            </div>
            <div class="card-body pt-3" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">
                <div class="row g-3">
                    @forelse($data['grupLokasi'] as $grup)
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ url('/panel/grupLokasi/detailGrupLokasi/' . $grup->id_group) }}"
                           class="d-flex align-items-center p-4 border rounded-2 text-decoration-none bg-hover-light-primary h-100" style="transition: background .15s">
                            <div class="me-3">
                                <div class="w-45px h-45px rounded-2 d-flex align-items-center justify-content-center" style="background:#eef3ff">
                                    <i class="bi bi-folder2 fs-4 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold text-dark text-truncate fs-6">{{ $grup->nama_group }}</div>
                                <div class="text-muted fs-8 mt-1">
                                    <i class="bi bi-camera-video me-1"></i>{{ $grup->total_cctv ?? 0 }} CCTV
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-muted fs-7 ms-2"></i>
                        </a>
                    </div>
                    @empty
                    <div class="col-12 text-center py-8 text-muted">
                        <i class="bi bi-folder2-open fs-1 d-block mb-3 opacity-40"></i>
                        <span class="fs-6">Belum ada group lokasi</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right sidebar -->
    <div class="col-xl-4 d-flex flex-column gap-5">
        <!-- Statistik -->
        <div class="card">
            <div class="card-header border-0 pt-5 pb-2">
                <h3 class="card-title fw-bold fs-5">Statistik Sistem</h3>
            </div>
            <div class="card-body pt-2 pb-4">
                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-40px h-40px rounded-2 d-flex align-items-center justify-content-center bg-light-success">
                            <i class="bi bi-people fs-5 text-success"></i>
                        </div>
                        <div>
                            <a href="{{ url('/panel/masterData/daftarPengguna') }}" class="fw-bold text-dark text-hover-primary fs-7">Pengguna Aktif</a>
                            <div class="text-muted fs-8">Total pengguna terdaftar</div>
                        </div>
                    </div>
                    <span class="badge badge-light-success fw-bold fs-7">{{ $data['totalPengguna'] }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-40px h-40px rounded-2 d-flex align-items-center justify-content-center bg-light-primary">
                            <i class="bi bi-cloud fs-5 text-primary"></i>
                        </div>
                        <div>
                            <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="fw-bold text-dark text-hover-primary fs-7">Akun Ezviz</a>
                            <div class="text-muted fs-8">Multi-akun terdaftar</div>
                        </div>
                    </div>
                    <span class="badge badge-light-primary fw-bold fs-7">{{ $data['totalEzvizAkun'] }}</span>
                </div>
            </div>
        </div>

        <!-- Log Aktifitas -->
        <div class="card flex-grow-1">
            <div class="card-header border-0 pt-5 pb-2">
                <h3 class="card-title fw-bold fs-5">Log Aktifitas</h3>
                <div class="card-toolbar">
                    <a href="{{ url('/panel/pengaturan/logAktivitas') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body pt-2" style="max-height: 280px; overflow-y: auto;">
                @forelse($data['recentLog'] as $log)
                <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="w-32px h-32px rounded-circle d-flex align-items-center justify-content-center bg-light-info flex-shrink-0 mt-1">
                        <i class="bi bi-activity text-info fs-8"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-dark fs-7 text-truncate">{{ $log->aksi }}</div>
                        <div class="text-muted fs-8">{{ $log->username }} &middot; {{ \Carbon\Carbon::parse($log->created_time)->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted fs-7">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
