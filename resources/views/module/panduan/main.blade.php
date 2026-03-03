{{-- User Guide / Panduan Penggunaan --}}
<style>
.guide-step-card { border-left: 4px solid #009ef7; border-radius: 0 12px 12px 0; }
.guide-step-num  { width:40px; height:40px; border-radius:50%; font-size:1.1rem; font-weight:700; flex-shrink:0; }
.guide-tip       { background:#fff8e1; border-left:4px solid #ffc107; border-radius:0 8px 8px 0; }
.guide-warn      { background:#fff3cd; border-left:4px solid #f1416c; border-radius:0 8px 8px 0; }
.guide-badge-step{ background:#009ef7; color:#fff; padding:3px 12px; border-radius:20px; font-size:.75rem; font-weight:600; }
.device-sticker  { background:#1e1e2d; color:#a2a3b7; border-radius:12px; font-family:monospace; }
.mock-btn        { display:inline-flex; align-items:center; gap:4px; background:#f1f5f9; border:1px solid #dee2e6; border-radius:6px; padding:2px 10px; font-size:.8rem; font-weight:600; }
.mock-btn.primary  { background:#009ef7; border-color:#009ef7; color:#fff; }
.mock-btn.success  { background:#50cd89; border-color:#50cd89; color:#fff; }
.mock-btn.warning  { background:#ffc700; border-color:#ffc700; color:#1e1e2d; }
.mock-btn.info     { background:#7239ea; border-color:#7239ea; color:#fff; }
.step-connector    { width:2px; background:#e4e6ef; margin:0 auto; }
</style>

<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-book-half me-2 text-primary"></i>Panduan Penambahan Kamera</h4>
        <span class="text-muted fs-7">Panduan lengkap cara mendaftarkan dan mengelola kamera CCTV di sistem ini</span>
    </div>
    <a href="{{ url('/panel/dashboard') }}" class="btn btn-sm btn-light">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>
</div>

{{-- Quick Nav --}}
<div class="card shadow-sm mb-6">
    <div class="card-body py-4">
        <div class="d-flex flex-wrap gap-3 align-items-center">
            <span class="fw-semibold text-muted fs-7 me-2">Loncat ke:</span>
            <a href="#step1" class="btn btn-sm btn-light-primary">1 · Persiapan</a>
            <a href="#step2" class="btn btn-sm btn-light-primary">2 · Daftarkan Kamera</a>
            <a href="#step3" class="btn btn-sm btn-light-primary">3 · Sinkronisasi</a>
            <a href="#step4" class="btn btn-sm btn-light-primary">4 · Import & Atur</a>
            <a href="#step5" class="btn btn-sm btn-light-primary">5 · Live View</a>
            <a href="#faq"   class="btn btn-sm btn-light-warning">FAQ</a>
        </div>
    </div>
</div>

{{-- ─────────────────────────── STEP 1 ─────────────────────────── --}}
<div id="step1" class="card shadow-sm guide-step-card mb-4">
    <div class="card-body p-6">
        <div class="d-flex align-items-center gap-4 mb-5">
            <div class="guide-step-num bg-primary text-white d-flex align-items-center justify-content-center">1</div>
            <div>
                <span class="guide-badge-step mb-1 d-inline-block">LANGKAH PERTAMA</span>
                <h5 class="fw-bold mb-0">Persiapan — Informasi yang Dibutuhkan</h5>
            </div>
        </div>

        <p class="text-muted mb-4">Sebelum menambahkan kamera, siapkan dua informasi penting yang tertera pada <strong>stiker di badan kamera</strong>:</p>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card border border-dashed border-primary h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-upc-scan me-2"></i>Serial Number (SN)</h6>
                        <p class="text-muted fs-7 mb-3">Kode unik identitas perangkat kamera. Biasanya terdiri dari 9 karakter huruf besar dan angka.</p>
                        <div class="device-sticker p-3 text-center">
                            <div class="text-muted" style="font-size:.65rem;letter-spacing:1px">SERIAL NUMBER</div>
                            <div style="font-size:1.3rem;letter-spacing:3px;color:#50cd89">ABC123456</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border border-dashed border-warning h-100">
                    <div class="card-body">
                        <h6 class="fw-bold text-warning mb-2"><i class="bi bi-key me-2"></i>Verification Code</h6>
                        <p class="text-muted fs-7 mb-3">Kode keamanan 6 karakter. Biasanya disebut <em>Verification Code</em> atau <em>Valid Code</em> pada stiker.</p>
                        <div class="device-sticker p-3 text-center">
                            <div class="text-muted" style="font-size:.65rem;letter-spacing:1px">VERIFICATION CODE</div>
                            <div style="font-size:1.3rem;letter-spacing:3px;color:#ffc700">ABCD12</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="guide-tip p-4">
            <h6 class="fw-bold text-warning mb-1"><i class="bi bi-lightbulb me-2"></i>Tips</h6>
            <ul class="mb-0 text-muted fs-7">
                <li>Stiker biasanya berada di bagian <strong>bawah</strong> atau <strong>belakang</strong> kamera.</li>
                <li>Pastikan kamera sudah <strong>terpasang</strong>, <strong>menyala</strong>, dan <strong>terhubung ke internet/jaringan lokal</strong> sebelum melanjutkan.</li>
                <li>Serial Number dan Verification Code juga bisa ditemukan di <strong>kemasan dus</strong> kamera.</li>
            </ul>
        </div>
    </div>
</div>

<div class="step-connector" style="height:32px"></div>

{{-- ─────────────────────────── STEP 2 ─────────────────────────── --}}
<div id="step2" class="card shadow-sm guide-step-card mb-4">
    <div class="card-body p-6">
        <div class="d-flex align-items-center gap-4 mb-5">
            <div class="guide-step-num bg-success text-white d-flex align-items-center justify-content-center">2</div>
            <div>
                <span class="guide-badge-step mb-1 d-inline-block" style="background:#50cd89">LANGKAH KEDUA</span>
                <h5 class="fw-bold mb-0">Daftarkan Kamera ke Platform Cloud</h5>
            </div>
        </div>

        <p class="text-muted mb-5">Sistem ini menggunakan <strong>Platform Cloud</strong> untuk mengelola koneksi ke kamera secara aman. Anda perlu mendaftarkan kamera terlebih dahulu agar sistem dapat menemukannya.</p>

        <div class="row g-4 mb-5">
            {{-- Sub-step 2a --}}
            <div class="col-12">
                <div class="d-flex gap-3 align-items-start p-4 bg-light rounded">
                    <div class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:.8rem">a</div>
                    <div>
                        <p class="fw-semibold mb-1">Buka menu <strong>Master Data</strong> di sidebar kiri, lalu pilih sub-menu yang sesuai.</p>
                        <p class="text-muted fs-7 mb-0">Anda akan melihat daftar kartu akun cloud yang terdaftar di sistem.</p>
                    </div>
                </div>
            </div>
            {{-- Sub-step 2b --}}
            <div class="col-12">
                <div class="d-flex gap-3 align-items-start p-4 bg-light rounded">
                    <div class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:.8rem">b</div>
                    <div>
                        <p class="fw-semibold mb-2">Pada kartu akun yang ingin digunakan, klik tombol <span class="mock-btn primary"><i class="bi bi-plus-circle"></i> Tambah Device</span></p>
                        <div class="card border border-dashed mb-0" style="max-width:420px">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px">
                                        <i class="bi bi-cloud fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">Nama Akun Cloud</div>
                                        <div class="text-muted fs-7">platform.cloudsystem.com</div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="mock-btn warning flex-grow-1 justify-content-center"><i class="bi bi-pencil"></i> Edit</span>
                                    <span class="mock-btn success"><i class="bi bi-camera-video"></i></span>
                                    <span class="mock-btn primary"><i class="bi bi-plus-circle"></i></span>
                                    <span class="mock-btn info"><i class="bi bi-arrow-repeat"></i></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted fs-7 mt-2 mb-0">Tombol <span class="mock-btn primary" style="font-size:.7rem"><i class="bi bi-plus-circle"></i></span> adalah tombol tambah device baru.</p>
                    </div>
                </div>
            </div>
            {{-- Sub-step 2c --}}
            <div class="col-12">
                <div class="d-flex gap-3 align-items-start p-4 bg-light rounded">
                    <div class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:.8rem">c</div>
                    <div class="flex-grow-1">
                        <p class="fw-semibold mb-2">Isi modal yang muncul dengan data dari stiker kamera:</p>
                        <div class="card border border-dashed" style="max-width:400px">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3">Tambah Device ke Cloud</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold fs-7 required">Serial Number</label>
                                    <div class="form-control bg-light text-muted fs-7">Contoh: ABC123456</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold fs-7 required">Verification Code</label>
                                    <div class="form-control bg-light text-muted fs-7">Contoh: ABCD12</div>
                                </div>
                                <span class="mock-btn primary w-100 justify-content-center py-2"><i class="bi bi-plus-circle me-2"></i>Tambahkan</span>
                            </div>
                        </div>
                        <p class="text-muted fs-7 mt-2 mb-0">Klik <strong>Tambahkan</strong>. Jika berhasil, muncul notifikasi hijau — kamera kini terdaftar di platform cloud.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="guide-warn p-4">
            <h6 class="fw-bold text-danger mb-1"><i class="bi bi-exclamation-triangle me-2"></i>Perhatian</h6>
            <ul class="mb-0 text-muted fs-7">
                <li>Pastikan kamera sudah <strong>online dan terhubung ke internet</strong> saat mendaftarkan.</li>
                <li>Verification Code bersifat <strong>case-sensitive</strong> — pastikan penulisan sesuai stiker.</li>
                <li>Jika muncul pesan <em>"Device sudah terdaftar di akun lain"</em>, kamera tersebut perlu dilepas dari akun lama terlebih dahulu.</li>
            </ul>
        </div>
    </div>
</div>

<div class="step-connector" style="height:32px"></div>

{{-- ─────────────────────────── STEP 3 ─────────────────────────── --}}
<div id="step3" class="card shadow-sm guide-step-card mb-4">
    <div class="card-body p-6">
        <div class="d-flex align-items-center gap-4 mb-5">
            <div class="guide-step-num bg-info text-white d-flex align-items-center justify-content-center">3</div>
            <div>
                <span class="guide-badge-step mb-1 d-inline-block" style="background:#7239ea">LANGKAH KETIGA</span>
                <h5 class="fw-bold mb-0">Sinkronisasi — Tarik Data Kamera ke Sistem</h5>
            </div>
        </div>

        <p class="text-muted mb-5">Setelah kamera berhasil didaftarkan di platform cloud, langkah berikutnya adalah menarik (sinkronisasi) data kamera tersebut ke dalam aplikasi ini.</p>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-camera-video me-2 text-primary"></i>Cara A — Melalui Menu CCTV</h6>
                        <ol class="text-muted fs-7 ps-3 mb-0">
                            <li class="mb-2">Buka menu <strong>CCTV</strong> → <strong>Daftar CCTV</strong> di sidebar.</li>
                            <li class="mb-2">Klik tombol <span class="mock-btn" style="font-size:.7rem"><i class="bi bi-arrow-repeat me-1"></i>Sinkronisasi</span> di bagian atas halaman.</li>
                            <li class="mb-2">Pilih akun cloud yang digunakan, lalu klik <strong>Sinkronisasi</strong>.</li>
                            <li>Daftar kamera dari platform cloud akan ditarik untuk proses import.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-cloud-download me-2 text-success"></i>Cara B — Langsung dari Master Data</h6>
                        <ol class="text-muted fs-7 ps-3 mb-0">
                            <li class="mb-2">Masih di halaman <strong>Master Data</strong>, klik tombol <span class="mock-btn success" style="font-size:.7rem"><i class="bi bi-camera-video"></i></span> pada kartu akun.</li>
                            <li class="mb-2">Sistem akan menampilkan daftar semua kamera yang terdaftar di akun cloud tersebut.</li>
                            <li>Kamera yang baru didaftarkan di Langkah 2 akan muncul di sini.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="guide-tip p-4">
            <strong><i class="bi bi-info-circle me-2 text-primary"></i>Info:</strong>
            <span class="text-muted fs-7"> Sinkronisasi hanya <em>menarik data</em> dari platform cloud ke antarmuka import — belum menyimpan kamera ke sistem. Penyimpanan dilakukan di Langkah 4.</span>
        </div>
    </div>
</div>

<div class="step-connector" style="height:32px"></div>

{{-- ─────────────────────────── STEP 4 ─────────────────────────── --}}
<div id="step4" class="card shadow-sm guide-step-card mb-4">
    <div class="card-body p-6">
        <div class="d-flex align-items-center gap-4 mb-5">
            <div class="guide-step-num bg-warning text-dark d-flex align-items-center justify-content-center">4</div>
            <div>
                <span class="guide-badge-step mb-1 d-inline-block" style="background:#ffc700;color:#1e1e2d">LANGKAH KEEMPAT</span>
                <h5 class="fw-bold mb-0">Import & Atur Detail Kamera</h5>
            </div>
        </div>

        <p class="text-muted mb-5">Setelah daftar kamera muncul, pilih kamera yang ingin ditambahkan, isi detailnya, lalu simpan ke sistem.</p>

        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="d-flex gap-3 align-items-start p-4 bg-light rounded">
                    <div class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:.8rem">a</div>
                    <div>
                        <p class="fw-semibold mb-1">Dari daftar kamera yang muncul setelah sinkronisasi, temukan kamera yang ingin ditambahkan.</p>
                        <p class="text-muted fs-7 mb-0">Kamera yang sudah pernah diimport akan ditandai <span class="badge badge-light-success fs-8">Sudah ditambahkan</span>. Kamera baru memiliki tombol <span class="mock-btn primary" style="font-size:.7rem"><i class="bi bi-plus me-1"></i>Tambah</span>.</p>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-3 align-items-start p-4 bg-light rounded">
                    <div class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:.8rem">b</div>
                    <div class="flex-grow-1">
                        <p class="fw-semibold mb-2">Klik tombol <span class="mock-btn primary" style="font-size:.7rem"><i class="bi bi-plus me-1"></i>Tambah</span> — form import akan muncul. Isi data berikut:</p>
                        <div class="table-responsive" style="max-width:520px">
                            <table class="table table-sm table-bordered fs-7">
                                <thead class="table-light">
                                    <tr><th>Field</th><th>Keterangan</th><th>Wajib?</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><strong>Nama CCTV</strong></td><td>Nama deskriptif, mis. "Pintu Depan Lobby"</td><td><span class="text-danger">Ya</span></td></tr>
                                    <tr><td><strong>Lokasi</strong></td><td>Lokasi fisik kamera (pilih dari daftar)</td><td><span class="text-danger">Ya</span></td></tr>
                                    <tr><td><strong>Posisi</strong></td><td>Keterangan posisi spesifik, mis. "Sudut kiri atas"</td><td>Opsional</td></tr>
                                    <tr><td><strong>Verification Code</strong></td><td>Kode dari stiker kamera (untuk enkripsi stream)</td><td>Opsional</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-3 align-items-start p-4 bg-light rounded">
                    <div class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:.8rem">c</div>
                    <div>
                        <p class="fw-semibold mb-1">Klik <strong>Tambahkan ke Sistem</strong>. Kamera kini tersimpan dan bisa dikelola dari menu <strong>CCTV</strong>.</p>
                        <p class="text-muted fs-7 mb-0">Ulangi proses ini untuk setiap kamera yang ingin ditambahkan dari daftar yang sama.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="guide-tip p-4">
            <h6 class="fw-bold text-warning mb-1"><i class="bi bi-lightbulb me-2"></i>Pastikan Lokasi Sudah Terdaftar</h6>
            <p class="text-muted fs-7 mb-0">Sebelum mengimport kamera, pastikan <strong>Lokasi</strong> tujuan kamera sudah dibuat terlebih dahulu melalui menu <strong>Lokasi → Daftar Lokasi → Tambah Lokasi</strong>. Lokasi juga bisa dikelompokkan ke dalam <strong>Grup Lokasi</strong> untuk kemudahan pengelolaan.</p>
        </div>
    </div>
</div>

<div class="step-connector" style="height:32px"></div>

{{-- ─────────────────────────── STEP 5 ─────────────────────────── --}}
<div id="step5" class="card shadow-sm guide-step-card mb-6">
    <div class="card-body p-6">
        <div class="d-flex align-items-center gap-4 mb-5">
            <div class="guide-step-num bg-danger text-white d-flex align-items-center justify-content-center">5</div>
            <div>
                <span class="guide-badge-step mb-1 d-inline-block" style="background:#f1416c">LANGKAH KELIMA</span>
                <h5 class="fw-bold mb-0">Live View — Menonton Siaran Langsung</h5>
            </div>
        </div>

        <p class="text-muted mb-5">Setelah kamera berhasil diimport, Anda dapat langsung memantau siaran langsung kamera.</p>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-light-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px">
                            <i class="bi bi-camera-video fs-2 text-primary"></i>
                        </div>
                        <h6 class="fw-bold">Per Kamera</h6>
                        <p class="text-muted fs-7 mb-0">Buka <strong>CCTV → Daftar CCTV</strong>, klik kartu kamera → klik tombol <span class="mock-btn primary" style="font-size:.7rem"><i class="bi bi-play-circle"></i></span> atau buka halaman detail kamera.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-light-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px">
                            <i class="bi bi-grid fs-2 text-success"></i>
                        </div>
                        <h6 class="fw-bold">Per Grup / Lokasi</h6>
                        <p class="text-muted fs-7 mb-0">Buka <strong>Grup Lokasi</strong> atau <strong>Lokasi</strong>, klik <strong>"Live View Semua"</strong> untuk menampilkan semua kamera di lokasi tersebut dalam grid.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-light-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px">
                            <i class="bi bi-camera fs-2 text-warning"></i>
                        </div>
                        <h6 class="fw-bold">Capture Gambar</h6>
                        <p class="text-muted fs-7 mb-0">Di halaman detail kamera, klik tombol <strong>Capture</strong> untuk mengambil tangkapan layar dari stream yang sedang berjalan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="guide-tip p-4">
            <strong><i class="bi bi-info-circle me-2 text-primary"></i>Catatan Kompatibilitas:</strong>
            <span class="text-muted fs-7"> Stream kamera menggunakan protokol <strong>EZOPEN</strong> yang memerlukan browser berbasis Chromium (Google Chrome, Microsoft Edge). Gunakan browser terbaru untuk pengalaman terbaik.</span>
        </div>
    </div>
</div>

{{-- ─────────────────────────── RINGKASAN ─────────────────────────── --}}
<div class="card shadow-sm mb-6" style="background:linear-gradient(135deg,#1e1e2d,#2b2b40);border:none">
    <div class="card-body p-6">
        <h5 class="fw-bold text-white mb-4"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Ringkasan Alur</h5>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="badge badge-light-primary fs-7 px-3 py-2">1 · Siapkan SN & Kode</span>
            <i class="bi bi-arrow-right text-muted"></i>
            <span class="badge badge-light-success fs-7 px-3 py-2">2 · Daftarkan ke Cloud</span>
            <i class="bi bi-arrow-right text-muted"></i>
            <span class="badge badge-light-info fs-7 px-3 py-2">3 · Sinkronisasi</span>
            <i class="bi bi-arrow-right text-muted"></i>
            <span class="badge badge-light-warning fs-7 px-3 py-2">4 · Import & Atur</span>
            <i class="bi bi-arrow-right text-muted"></i>
            <span class="badge badge-light-danger fs-7 px-3 py-2">5 · Live View</span>
        </div>
    </div>
</div>

{{-- ─────────────────────────── FAQ ─────────────────────────── --}}
<div id="faq" class="card shadow-sm mb-6">
    <div class="card-header border-0 pt-6">
        <h5 class="fw-bold"><i class="bi bi-question-circle me-2 text-warning"></i>Pertanyaan Umum (FAQ)</h5>
    </div>
    <div class="card-body px-6 pb-6">
        <div class="accordion" id="accordionFaq">

            <div class="accordion-item border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Kamera sudah terdaftar tapi tidak muncul saat sinkronisasi?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-muted fs-7">
                        Pastikan kamera <strong>online</strong> dan terhubung ke internet. Coba klik tombol <strong>Refresh Token</strong> <i class="bi bi-arrow-repeat"></i> pada kartu akun di Master Data, lalu ulangi sinkronisasi.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Muncul pesan "Device sudah terdaftar di akun lain"?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-muted fs-7">
                        Kamera tersebut masih terikat ke akun cloud lain. Hubungi administrator untuk melepaskan kamera dari akun sebelumnya, atau lakukan <em>factory reset</em> pada kamera (tekan tombol reset di badan kamera selama 10 detik).
                    </div>
                </div>
            </div>

            <div class="accordion-item border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Live View tidak muncul / layar hitam?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-muted fs-7">
                        <ul class="mb-0">
                            <li>Pastikan browser yang digunakan adalah <strong>Chrome</strong> atau <strong>Edge</strong> versi terbaru.</li>
                            <li>Pastikan kamera sedang <strong>online</strong>.</li>
                            <li>Coba klik kembali tombol <strong>Play / EZOPEN</strong> di halaman detail kamera.</li>
                            <li>Refresh token akun akses dari menu Master Data jika sudah lama tidak digunakan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="accordion-item border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        Bagaimana cara menghapus kamera dari sistem?
                    </button>
                </h2>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-muted fs-7">
                        Buka <strong>CCTV → Daftar CCTV</strong>, temukan kamera, lalu klik tombol <span class="mock-btn" style="font-size:.7rem;background:#f1416c;color:#fff;border-color:#f1416c"><i class="bi bi-trash"></i> Hapus</span>. Ini hanya menghapus data dari sistem lokal — perangkat fisik tetap terdaftar di platform cloud.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                        Berapa banyak kamera yang bisa ditambahkan?
                    </button>
                </h2>
                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-muted fs-7">
                        Tidak ada batas maksimal dari sisi aplikasi ini. Batas berlaku sesuai dengan kapasitas akun platform cloud yang dikonfigurasi oleh administrator sistem.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                        Hak akses apa yang diperlukan untuk menambah kamera?
                    </button>
                </h2>
                <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                    <div class="accordion-body text-muted fs-7">
                        Untuk menambah kamera diperlukan akses ke modul <strong>Master Data</strong> (registrasi ke cloud dan import device) serta modul <strong>CCTV</strong> (sinkronisasi dan manajemen). Hubungi administrator sistem untuk pengaturan hak akses.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
