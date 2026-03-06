{{-- Panduan Penggunaan Lengkap --}}
<style>
.guide-step-card  { border-left:4px solid #009ef7; border-radius:0 12px 12px 0; }
.guide-step-num   { width:36px;height:36px;border-radius:50%;font-size:1rem;font-weight:700;flex-shrink:0; }
.guide-tip        { background:#fff8e1;border-left:4px solid #ffc107;border-radius:0 8px 8px 0; }
.guide-warn       { background:#fff0f0;border-left:4px solid #f1416c;border-radius:0 8px 8px 0; }
.guide-info       { background:#f0f6ff;border-left:4px solid #009ef7;border-radius:0 8px 8px 0; }
.mock-btn         { display:inline-flex;align-items:center;gap:4px;background:#f1f5f9;border:1px solid #dee2e6;border-radius:6px;padding:2px 10px;font-size:.78rem;font-weight:600; }
.mock-btn.primary { background:#009ef7;border-color:#009ef7;color:#fff; }
.mock-btn.success { background:#50cd89;border-color:#50cd89;color:#fff; }
.mock-btn.danger  { background:#f1416c;border-color:#f1416c;color:#fff; }
.mock-btn.warning { background:#ffc700;border-color:#ffc700;color:#1e1e2d; }
.mock-btn.info    { background:#7239ea;border-color:#7239ea;color:#fff; }
.guide-nav .nav-link          { border-radius:8px;font-weight:600;font-size:.82rem;padding:7px 14px;color:#5e6278; }
.guide-nav .nav-link.active   { background:#009ef7;color:#fff; }
.guide-nav .nav-link:hover:not(.active) { background:#f1f5f9; }
.section-badge { display:inline-flex;align-items:center;gap:6px;background:#f0f6ff;border:1px solid #d6eaff;border-radius:20px;padding:3px 14px;font-size:.75rem;font-weight:700;color:#009ef7;margin-bottom:10px; }
.step-row { background:#f9fafb;border-radius:10px;padding:16px 20px;margin-bottom:12px; }
.step-row .step-num { width:28px;height:28px;min-width:28px;background:#009ef7;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700; }
.role-badge { padding:3px 10px;border-radius:20px;font-size:.73rem;font-weight:700; }
.role-su  { background:#ffd6e0;color:#c0143c; }
.role-adm { background:#d6eaff;color:#0056b3; }
.role-opr { background:#d6f5e6;color:#0a6640; }
.role-usr { background:#ede8ff;color:#5e3ec5; }
</style>

<div class="d-flex justify-content-between align-items-start mb-5">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-book-half me-2 text-primary"></i>Panduan Penggunaan Sistem</h4>
        <span class="text-muted fs-7">Panduan lengkap seluruh fitur aplikasi monitoring CCTV</span>
    </div>
    <a href="{{ url('/panel/dashboard') }}" class="btn btn-sm btn-light">
        <i class="bi bi-arrow-left me-2"></i>Dashboard
    </a>
</div>

<div class="card shadow-sm mb-5">
    <div class="card-body py-3 px-4">
        <ul class="nav guide-nav flex-wrap gap-1" id="guideTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-gruplokasi"><i class="bi bi-folder2 me-1"></i>Grup Lokasi</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-lokasi"><i class="bi bi-geo-alt me-1"></i>Lokasi</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-cctv"><i class="bi bi-camera-video me-1"></i>CCTV</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-liveview"><i class="bi bi-display me-1"></i>Live View</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-pengguna"><i class="bi bi-people me-1"></i>Pengguna</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-hakakses"><i class="bi bi-shield-check me-1"></i>Hak Akses</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-ezviz"><i class="bi bi-cloud me-1"></i>Akun EZVIZ</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-pengaturan"><i class="bi bi-gear me-1"></i>Pengaturan</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-log"><i class="bi bi-journal-text me-1"></i>Log Aktivitas</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-profil"><i class="bi bi-person-circle me-1"></i>Profil</a></li>
        </ul>
    </div>
</div>

<div class="tab-content">

{{-- DASHBOARD --}}
<div class="tab-pane fade show active" id="tab-dashboard">
    <div class="section-badge"><i class="bi bi-speedometer2"></i> DASHBOARD</div>
    <h5 class="fw-bold mb-4">Ringkasan & Overview Sistem</h5>
    <div class="guide-info p-4 mb-5"><strong>Dashboard</strong> adalah halaman utama setelah login, menampilkan ringkasan kondisi sistem secara real-time.</div>
    <div class="row g-4 mb-5">
        <div class="col-md-6"><div class="card border h-100"><div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-grid-1x2 me-2 text-primary"></i>Kartu Statistik</h6>
            <ul class="text-muted fs-7 mb-0">
                <li class="mb-2"><strong>Total Grup Lokasi</strong> — jumlah grup yang dapat diakses pengguna ini</li>
                <li class="mb-2"><strong>Total Lokasi</strong> — jumlah titik lokasi dalam grup yang diakses</li>
                <li class="mb-2"><strong>Total CCTV</strong> — jumlah kamera yang terdaftar</li>
                <li><strong>CCTV Online</strong> — kamera yang sedang terhubung ke cloud</li>
            </ul>
            <div class="guide-tip p-3 mt-3 fs-7">Klik tombol <span class="mock-btn primary" style="font-size:.7rem"><i class="bi bi-arrow-right"></i></span> pada tiap kartu untuk menuju halaman terkait.</div>
        </div></div></div>
        <div class="col-md-6"><div class="card border h-100"><div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-folder2 me-2 text-primary"></i>Daftar Grup Lokasi</h6>
            <ul class="text-muted fs-7 mb-0">
                <li class="mb-2">Menampilkan daftar grup yang bisa diakses pengguna</li>
                <li class="mb-2">Tiap item menunjukkan jumlah CCTV dan lokasi di dalam grup</li>
                <li>Klik item untuk menuju <strong>Detail Grup</strong></li>
            </ul>
        </div></div></div>
        <div class="col-md-6"><div class="card border h-100"><div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart me-2 text-primary"></i>Statistik Sistem</h6>
            <p class="text-muted fs-7 mb-0">Menampilkan jumlah pengguna aktif, akun cloud EZVIZ, dan jumlah hak akses (role) yang tersedia di sistem.</p>
        </div></div></div>
        <div class="col-md-6"><div class="card border h-100"><div class="card-body">
            <h6 class="fw-bold mb-3"><i class="bi bi-journal-text me-2 text-primary"></i>Log Aktivitas Terakhir</h6>
            <p class="text-muted fs-7 mb-0">Menampilkan 5 aktivitas terakhir pengguna. Klik <strong>"Lihat Semua"</strong> untuk menuju halaman Log Aktivitas lengkap.</p>
        </div></div></div>
    </div>
</div>

{{-- GRUP LOKASI --}}
<div class="tab-pane fade" id="tab-gruplokasi">
    <div class="section-badge"><i class="bi bi-folder2"></i> GRUP LOKASI</div>
    <h5 class="fw-bold mb-4">Mengelola Grup Lokasi</h5>
    <div class="guide-info p-4 mb-5"><strong>Grup Lokasi</strong> adalah pengelompokan area pemantauan (contoh: "Gedung A", "Area Parkir"). Setiap lokasi dan CCTV harus berada dalam satu grup.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-plus-circle me-2"></i>Menambah Grup Baru</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Grup Lokasi → Daftar Grup Lokasi</strong>, klik <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah Group</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi <strong>Nama Grup</strong> (wajib) dan <strong>Deskripsi</strong> (opsional), lalu klik <span class="mock-btn primary">Simpan</span>.</div></div>

    <h6 class="fw-bold text-primary mb-3 mt-4"><i class="bi bi-pencil me-2"></i>Edit & Hapus Grup</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn warning"><i class="bi bi-pencil"></i> Edit</span> untuk mengubah nama/deskripsi, atau <span class="mock-btn danger"><i class="bi bi-trash"></i> Hapus</span> untuk menghapus.</div></div>
    <div class="guide-warn p-3 mb-4"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Grup hanya bisa dihapus jika <strong>tidak memiliki lokasi</strong> di dalamnya.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-eye me-2"></i>Detail Grup</h6>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">1</div><div>Klik <span class="mock-btn"><i class="bi bi-eye"></i> Detail</span> untuk melihat daftar lokasi dan semua CCTV yang ada di dalam grup ini.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-display me-2"></i>Live View per Grup & Semua Grup</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn success"><i class="bi bi-display"></i> Live</span> pada kartu grup untuk menonton semua kamera dalam grup tersebut.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-3"><div class="step-num">2</div><div>Klik <span class="mock-btn success"><i class="bi bi-grid"></i> Live Semua CCTV</span> di header halaman untuk menonton <strong>semua grup sekaligus</strong> sesuai hak akses.</div></div>
    <div class="guide-tip p-3"><i class="bi bi-lightbulb me-2 text-warning"></i>Pengguna hanya melihat grup yang sesuai hak aksesnya. Grup yang tidak diizinkan tidak akan muncul.</div>
</div>

{{-- LOKASI --}}
<div class="tab-pane fade" id="tab-lokasi">
    <div class="section-badge"><i class="bi bi-geo-alt"></i> LOKASI</div>
    <h5 class="fw-bold mb-4">Mengelola Lokasi</h5>
    <div class="guide-info p-4 mb-5"><strong>Lokasi</strong> adalah titik area spesifik di dalam sebuah Grup (contoh: "Lobby", "Lantai 2", "Parkiran"). Setiap CCTV harus terpasang pada satu lokasi.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-plus-circle me-2"></i>Menambah Lokasi</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Lokasi → Daftar Lokasi</strong>, klik <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah Lokasi</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi <strong>Nama Lokasi</strong> (wajib), pilih <strong>Grup</strong> (wajib), dan <strong>Deskripsi</strong> (opsional).</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Simpan</span>.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-pencil me-2"></i>Edit & Hapus Lokasi</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn warning"><i class="bi bi-pencil"></i> Edit</span> untuk mengubah, atau <span class="mock-btn danger"><i class="bi bi-trash"></i> Hapus</span> untuk menghapus.</div></div>
    <div class="guide-warn p-3 mb-4"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Lokasi hanya bisa dihapus jika <strong>tidak ada CCTV</strong> yang terpasang di sana.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-display me-2"></i>Live View per Lokasi</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Dari <strong>Detail Lokasi</strong>, klik <span class="mock-btn success"><i class="bi bi-display"></i> Live View</span> untuk menonton semua kamera di lokasi tersebut.</div></div>
</div>

{{-- CCTV --}}
<div class="tab-pane fade" id="tab-cctv">
    <div class="section-badge"><i class="bi bi-camera-video"></i> CCTV</div>
    <h5 class="fw-bold mb-4">Mengelola Data CCTV</h5>
    <div class="guide-info p-4 mb-5"><strong>CCTV</strong> adalah data kamera yang sudah diimport ke sistem. Setiap kamera memiliki Serial Number, nama, lokasi, dan akun cloud yang mengelolanya.</div>

    <div class="guide-tip p-3 mb-4"><i class="bi bi-lightbulb me-2 text-warning"></i>Cara yang disarankan adalah melalui <strong>Import dari Akun EZVIZ</strong> (tab Akun EZVIZ) yang bisa import banyak sekaligus.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-plus-circle me-2"></i>Tambah CCTV (Manual)</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>CCTV → Daftar CCTV</strong>, klik <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah CCTV</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi: <strong>Nama Kamera</strong>, <strong>Serial Number</strong>, pilih <strong>Lokasi</strong>, pilih <strong>Akun EZVIZ</strong>, dan <strong>Channel</strong>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Simpan</span>.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-pencil me-2"></i>Edit & Hapus CCTV</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn warning"><i class="bi bi-pencil"></i> Edit</span> untuk mengubah nama, lokasi, atau akun cloud.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">2</div><div>Klik <span class="mock-btn danger"><i class="bi bi-trash"></i> Hapus</span> untuk menghapus data kamera dari sistem (tidak menghapus dari cloud).</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Detail CCTV & Capture</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn"><i class="bi bi-eye"></i> Detail</span> untuk melihat info lengkap kamera: SN, status online, akun cloud, lokasi, dan pratinjau live stream.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Di halaman detail, klik <span class="mock-btn success"><i class="bi bi-camera"></i> Capture</span> untuk mengambil screenshot dari stream kamera tersebut.</div></div>
</div>

{{-- LIVE VIEW --}}
<div class="tab-pane fade" id="tab-liveview">
    <div class="section-badge"><i class="bi bi-display"></i> LIVE VIEW</div>
    <h5 class="fw-bold mb-4">Menonton Siaran Langsung CCTV</h5>
    <div class="guide-info p-4 mb-5">Tersedia tiga level Live View: <strong>Semua Grup</strong>, <strong>per Grup</strong>, dan <strong>per Lokasi</strong>. Semua siaran real-time menggunakan protokol EZOPEN atau HLS.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-grid me-2"></i>Live View Semua Grup</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <strong>CCTV → Live Semua CCTV</strong>, atau dari Daftar Grup klik <span class="mock-btn success"><i class="bi bi-grid"></i> Live Semua CCTV</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">2</div><div>Gunakan tab filter grup (atas) dan kontrol: <strong>Layout</strong> kolom (1-4), <strong>Protokol</strong> (EZOPEN/HLS), <span class="mock-btn"><i class="bi bi-arrow-repeat"></i> Reload All</span>, <span class="mock-btn"><i class="bi bi-volume-mute"></i> Mute</span>. Klik tile kamera untuk mode fokus.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-folder2 me-2"></i>Live View per Grup</h6>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">1</div><div>Dari <strong>Daftar Grup Lokasi</strong>, klik <span class="mock-btn success"><i class="bi bi-display"></i> Live</span> pada kartu grup yang diinginkan.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Live View per Lokasi</h6>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">1</div><div>Buka <strong>Detail Lokasi</strong>, klik <span class="mock-btn success"><i class="bi bi-display"></i> Live View</span>.</div></div>

    <div class="guide-tip p-4">
        <h6 class="fw-bold text-warning mb-2"><i class="bi bi-lightbulb me-2"></i>Tips Live View</h6>
        <ul class="text-muted fs-7 mb-0">
            <li class="mb-1">Jika stream tidak muncul, klik ikon <i class="bi bi-arrow-repeat"></i> refresh pada tile kamera tersebut.</li>
            <li class="mb-1">Gunakan <strong>EZOPEN</strong> untuk koneksi lebih stabil. Gunakan <strong>HLS</strong> jika EZOPEN tidak bekerja.</li>
            <li class="mb-1">Pilihan kolom tersimpan otomatis di browser sehingga tidak perlu diatur ulang.</li>
            <li>Browser yang disarankan: <strong>Google Chrome</strong> atau <strong>Microsoft Edge</strong> versi terbaru.</li>
        </ul>
    </div>
</div>

{{-- PENGGUNA --}}
<div class="tab-pane fade" id="tab-pengguna">
    <div class="section-badge"><i class="bi bi-people"></i> MASTER DATA — PENGGUNA</div>
    <h5 class="fw-bold mb-4">Mengelola Data Pengguna</h5>
    <div class="guide-info p-4 mb-4">Pengguna adalah akun login yang bisa mengakses sistem. Setiap pengguna memiliki <strong>satu hak akses (role)</strong> yang menentukan modul dan grup yang bisa diakses.</div>
    <div class="d-flex flex-wrap gap-2 mb-5">
        <span class="role-badge role-su">superuser</span>
        <span class="role-badge role-adm">admin</span>
        <span class="role-badge role-opr">operator</span>
        <span class="role-badge role-usr">viewer_divisi_x</span>
        <span class="text-muted fs-7 align-self-center">← contoh peran yang tersedia</span>
    </div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-plus-circle me-2"></i>Menambah Pengguna Baru</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Master Data → Daftar Pengguna</strong>, klik <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah Pengguna</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi: <strong>Nama Lengkap</strong>, <strong>Username</strong> (unik), <strong>Email</strong>, <strong>Password</strong>, dan pilih <strong>Hak Akses</strong>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Simpan</span>. Pengguna langsung bisa login.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-pencil me-2"></i>Edit & Hapus Pengguna</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn warning"><i class="bi bi-pencil"></i> Edit</span>. Kosongkan field password jika tidak ingin mengubahnya.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-3"><div class="step-num">2</div><div>Klik <span class="mock-btn danger"><i class="bi bi-trash"></i> Hapus</span> untuk menghapus akun. Tindakan ini permanen.</div></div>
    <div class="guide-warn p-3"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Akun <strong>superuser</strong> tidak dapat dihapus untuk mencegah sistem terkunci.</div>
</div>

{{-- HAK AKSES --}}
<div class="tab-pane fade" id="tab-hakakses">
    <div class="section-badge"><i class="bi bi-shield-check"></i> MASTER DATA — HAK AKSES</div>
    <h5 class="fw-bold mb-4">Mengelola Hak Akses (Role)</h5>
    <div class="guide-info p-4 mb-5"><strong>Hak Akses</strong> menentukan: <strong>(1) modul apa yang bisa diakses</strong> dan <strong>(2) grup lokasi mana yang boleh dilihat</strong> oleh pengguna dengan role ini.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-plus-circle me-2"></i>Membuat Hak Akses Baru</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Master Data → Hak Akses</strong>, klik <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah Hak Akses</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi <strong>Nama Hak Akses</strong> (contoh: <code>viewer_divisi_b</code>).</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">3</div><div><strong>Centang Modul</strong> yang diizinkan. Centang parent akan mencentang semua sub-modul. Uncentang satu sub-modul tidak mempengaruhi yang lain.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">4</div><div><strong>Pilih Akses Grup</strong>: centang <em>"Semua Grup"</em> atau centang grup tertentu untuk membatasi visibilitas kamera.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">5</div><div>Klik <span class="mock-btn primary">Simpan</span>. Role ini langsung bisa dipilih saat membuat/mengedit pengguna.</div></div>

    <div class="guide-tip p-4 mb-4">
        <h6 class="fw-bold text-warning mb-2"><i class="bi bi-lightbulb me-2"></i>Skenario Privasi Antar Divisi</h6>
        <ul class="text-muted fs-7 mb-0">
            <li>Buat role <code>viewer_divisi_a</code> → centang hanya <strong>Grup Divisi A</strong></li>
            <li>Buat role <code>viewer_divisi_b</code> → centang hanya <strong>Grup Divisi B</strong></li>
            <li>Pengguna Divisi A tidak akan bisa melihat kamera Divisi B sama sekali di seluruh fitur (dashboard, daftar, live view).</li>
        </ul>
    </div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-pencil me-2"></i>Edit Hak Akses</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik <span class="mock-btn warning"><i class="bi bi-pencil"></i> Edit</span>, ubah modul atau grup yang diizinkan, lalu <span class="mock-btn primary">Simpan</span>. Perubahan langsung berlaku untuk semua pengguna dengan role tersebut.</div></div>
</div>

{{-- AKUN EZVIZ --}}
<div class="tab-pane fade" id="tab-ezviz">
    <div class="section-badge"><i class="bi bi-cloud"></i> MASTER DATA — AKUN EZVIZ & KAMERA</div>
    <h5 class="fw-bold mb-4">Mengelola Akun Cloud & Menambah Kamera</h5>
    <div class="guide-info p-4 mb-5">Akun EZVIZ adalah akun cloud yang digunakan untuk berkomunikasi dengan kamera. Alur penambahan kamera: <strong>Daftarkan ke Cloud → Sinkronisasi → Import ke Sistem</strong>.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-card-list me-2"></i>Persiapan — Info dari Stiker Kamera</h6>
    <div class="row g-3 mb-5">
        <div class="col-md-6"><div class="card border border-dashed border-primary h-100"><div class="card-body">
            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-upc-scan me-2"></i>Serial Number (SN)</h6>
            <p class="text-muted fs-7 mb-2">Kode unik identitas kamera di stiker badan kamera. Biasanya 9 karakter huruf besar + angka.</p>
            <div class="bg-dark text-success text-center rounded p-2 fw-bold" style="letter-spacing:3px;font-family:monospace">ABC123456</div>
        </div></div></div>
        <div class="col-md-6"><div class="card border border-dashed border-warning h-100"><div class="card-body">
            <h6 class="fw-bold text-warning mb-2"><i class="bi bi-key me-2"></i>Verification Code</h6>
            <p class="text-muted fs-7 mb-2">Kode keamanan 6 karakter di stiker kamera. Disebut juga <em>Valid Code</em>.</p>
            <div class="bg-dark text-warning text-center rounded p-2 fw-bold" style="letter-spacing:3px;font-family:monospace">ABCD12</div>
        </div></div></div>
    </div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-plus me-2"></i>Menambah Akun EZVIZ</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Master Data → Akun EZVIZ</strong>, klik <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah Akun</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">2</div><div>Isi <strong>Nama Akun</strong>, <strong>Email/Username EZVIZ</strong>, dan <strong>Password</strong>, lalu klik <span class="mock-btn primary">Simpan</span>.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-camera-video-fill me-2"></i>Menambah Kamera ke Akun Cloud</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Pada kartu akun, klik tombol <span class="mock-btn primary"><i class="bi bi-plus-circle"></i></span> (ikon plus biru).</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi <strong>Serial Number</strong> dan <strong>Verification Code</strong> dari stiker kamera fisik.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Tambahkan</span>. Kamera terdaftar di akun cloud EZVIZ.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-arrow-repeat me-2"></i>Sinkronisasi Kamera dari Cloud</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik tombol <span class="mock-btn info"><i class="bi bi-arrow-repeat"></i></span> (ikon sync ungu) pada kartu akun untuk menarik daftar kamera terbaru.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">2</div><div>Setelah sync, klik <span class="mock-btn success"><i class="bi bi-camera-video"></i></span> untuk membuka panel <strong>Import Device</strong>.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-download me-2"></i>Import Kamera ke Sistem</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Di panel Import Device, pilih kamera dari daftar hasil sync. Kamera yang belum diimport memiliki tombol <span class="mock-btn primary" style="font-size:.7rem"><i class="bi bi-plus"></i> Tambah</span>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi <strong>Nama Kamera</strong> dan pilih <strong>Lokasi</strong> yang sesuai.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Tambahkan ke Sistem</span>. Kamera muncul di <strong>CCTV → Daftar CCTV</strong>.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-key me-2"></i>Refresh Token</h6>
    <div class="step-row d-flex gap-3 align-items-start mb-3"><div class="step-num">1</div><div>Token EZVIZ berlaku <strong>7 hari</strong>. Jika live stream gagal, klik <span class="mock-btn warning"><i class="bi bi-arrow-clockwise"></i> Refresh</span> pada kartu akun.</div></div>
    <div class="guide-tip p-3"><i class="bi bi-lightbulb me-2 text-warning"></i>Admin dapat menjalankan perintah artisan <code>ezviz:refresh-tokens</code> untuk memperbarui token semua akun sekaligus.</div>
</div>

{{-- PENGATURAN --}}
<div class="tab-pane fade" id="tab-pengaturan">
    <div class="section-badge"><i class="bi bi-gear"></i> PENGATURAN SISTEM</div>
    <h5 class="fw-bold mb-4">Pengaturan Informasi Sistem</h5>
    <div class="guide-info p-4 mb-5">Halaman ini digunakan untuk mengatur identitas dan branding aplikasi yang tampil di seluruh halaman.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-pencil-square me-2"></i>Mengubah Pengaturan</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Pengaturan → Pengaturan Sistem</strong>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Ubah data yang diinginkan:
        <ul class="text-muted fs-7 mt-1 mb-0">
            <li><strong>Nama Aplikasi</strong> — tampil di judul browser</li>
            <li><strong>Nama Instansi, Alamat, Kota, Telepon, Email, Website</strong></li>
            <li><strong>Footer</strong> — teks di bagian bawah setiap halaman</li>
            <li><strong>Logo & Icon</strong> — upload file gambar (jpeg/png/webp, maks 2MB)</li>
        </ul>
    </div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Simpan</span>. Perubahan langsung berlaku di seluruh halaman.</div></div>
</div>

{{-- LOG AKTIVITAS --}}
<div class="tab-pane fade" id="tab-log">
    <div class="section-badge"><i class="bi bi-journal-text"></i> LOG AKTIVITAS</div>
    <h5 class="fw-bold mb-4">Memantau Aktivitas Pengguna</h5>
    <div class="guide-info p-4 mb-5">Log Aktivitas mencatat semua aksi penting: login, akses live view, perubahan data, dll. Berguna untuk audit dan tracing masalah.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-table me-2"></i>Membaca Log</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Buka <strong>Pengaturan → Log Aktivitas</strong>. Tabel menampilkan: Waktu, Username, Aksi, Modul, dan Detail.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">2</div><div>Gunakan kolom <strong>Cari</strong> di kanan atas untuk memfilter berdasarkan kata kunci.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-funnel me-2"></i>Filter per Pengguna <span class="role-badge role-su ms-2">superuser only</span></h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Superuser melihat log <strong>semua pengguna</strong>. Dropdown <strong>Filter Pengguna</strong> di kanan atas bisa diketik untuk mencari, lalu dipilih.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">2</div><div>Tabel otomatis difilter. Pilih <em>"Semua Pengguna"</em> untuk kembali ke tampilan penuh.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Melihat Info Pengguna dari Log</h6>
    <div class="step-row d-flex gap-3 align-items-start mb-3"><div class="step-num">1</div><div>Klik nama <strong>username</strong> (berwarna biru) pada baris manapun. Muncul modal berisi info lengkap: nama, role, status online, email, login terakhir.</div></div>
    <div class="guide-tip p-3"><i class="bi bi-lightbulb me-2 text-warning"></i>Non-superuser hanya bisa melihat log aktivitasnya <strong>sendiri</strong> — log pengguna lain tersembunyi otomatis.</div>
</div>

{{-- PROFIL --}}
<div class="tab-pane fade" id="tab-profil">
    <div class="section-badge"><i class="bi bi-person-circle"></i> PROFIL & KEAMANAN AKUN</div>
    <h5 class="fw-bold mb-4">Mengelola Profil & Password</h5>
    <div class="guide-info p-4 mb-5">Setiap pengguna dapat memperbarui data profil dan mengganti password kapan saja melalui halaman Profil.</div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-gear me-2"></i>Update Profil</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Klik avatar/nama di sidebar bawah kiri, pilih menu profil, atau akses langsung <strong>/panel/profile</strong>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Ubah <strong>Nama Lengkap</strong>, <strong>Email</strong>, dan/atau upload <strong>Foto Profil</strong> (jpeg/png/webp, maks 2MB).</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-4"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Simpan Perubahan</span>. Nama dan foto di sidebar langsung diperbarui.</div></div>

    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-shield-lock me-2"></i>Ganti Password</h6>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">1</div><div>Di halaman Profil, gulir ke bagian <strong>Ganti Password</strong>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start"><div class="step-num">2</div><div>Isi <strong>Password Lama</strong>, <strong>Password Baru</strong> (min. 6 karakter), dan <strong>Konfirmasi Password Baru</strong>.</div></div>
    <div class="step-row d-flex gap-3 align-items-start mb-3"><div class="step-num">3</div><div>Klik <span class="mock-btn primary">Ubah Password</span>. Gunakan password baru untuk login berikutnya.</div></div>
    <div class="guide-warn p-3"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Jika lupa password, minta <strong>superuser</strong> untuk mereset melalui menu <strong>Master Data → Pengguna → Edit</strong>.</div>
</div>

</div>{{-- end tab-content --}}
