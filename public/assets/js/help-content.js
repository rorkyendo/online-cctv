/**
 * ============================================
 * HELP CONTENT DATA - Koperasi Application
 * Konten panduan lengkap untuk semua fitur
 * Termasuk panduan CRUD per-field
 * ============================================
 */
const HelpContent = {

    // ==========================================
    // DASHBOARD
    // ==========================================
    'dashboard': {
        title: 'Dashboard',
        description: 'Halaman utama yang menampilkan ringkasan data koperasi secara real-time.',
        icon: 'fas fa-tachometer-alt',
        sections: [
            {
                title: 'Kartu Statistik',
                icon: 'fas fa-chart-bar',
                content: '<p>Terdapat 4 kartu statistik: <strong>Total Anggota</strong>, <strong>Total Simpanan</strong>, <strong>Pinjaman Aktif</strong>, dan <strong>Saldo Kas</strong>.</p>'
            },
            {
                title: 'Grafik & Aksi Cepat',
                icon: 'fas fa-chart-area',
                content: '<p>Grafik area menampilkan trend keuangan 6 bulan terakhir. Panel aksi cepat menyediakan pintasan ke fitur yang sering digunakan.</p>'
            }
        ],
        tour: [
            { selector: '.col-xxl-3:first-child .card', title: 'Kartu Statistik Anggota', desc: 'Menampilkan jumlah total anggota koperasi beserta persentase anggota yang aktif.' },
            { selector: '#chart_dashboard', title: 'Grafik Trend Keuangan', desc: 'Grafik area interaktif yang menampilkan trend simpanan, pinjaman, dan kas masuk selama 6 bulan terakhir.' },
            { selector: '.col-xl-4 .card', title: 'Aktivitas Terkini', desc: 'Daftar transaksi dan aktivitas terbaru di sistem koperasi.' }
        ]
    },

    // ==========================================
    // MASTER DATA - DAFTAR PENGGUNA
    // ==========================================
    'daftarPengguna': {
        title: 'Daftar Pengguna',
        description: 'Mengelola data pengguna sistem koperasi. Setiap pengguna memiliki hak akses yang menentukan fitur apa saja yang bisa diakses.',
        icon: 'fas fa-users-cog',
        sections: [
            {
                title: 'Cara Menambah Pengguna',
                icon: 'fas fa-user-plus',
                steps: [
                    'Klik tombol <strong>"Tambah Data"</strong> berwarna hijau di pojok kanan atas tabel.',
                    'Anda akan diarahkan ke halaman form tambah pengguna.',
                    'Isi semua field yang wajib, lalu klik <strong>"SIMPAN DATA"</strong>.'
                ]
            },
            {
                title: 'Cara Mengedit Pengguna',
                icon: 'fas fa-user-edit',
                steps: [
                    'Pada tabel, cari pengguna yang ingin diedit.',
                    'Klik ikon <strong>pensil berwarna kuning</strong> <i class="fa fa-pencil text-warning"></i> pada kolom Aksi.',
                    'Ubah data yang diperlukan pada form yang muncul.',
                    'Klik <strong>"SIMPAN DATA"</strong> untuk menyimpan perubahan.'
                ]
            },
            {
                title: 'Cara Menghapus Pengguna',
                icon: 'fas fa-user-minus',
                steps: [
                    'Pada tabel, cari pengguna yang ingin dihapus.',
                    'Klik ikon <strong>X berwarna merah</strong> <i class="fa fa-times text-danger"></i> pada kolom Aksi.',
                    'Konfirmasi dialog akan muncul — klik <strong>"OK"</strong> untuk menghapus.'
                ]
            },
            {
                title: 'Ikon & Tombol pada Tabel',
                icon: 'fas fa-icons',
                content: '<ul class="help-icon-list"><li class="help-icon-item"><div class="icon-preview bg-success"><i class="fas fa-plus"></i></div><div class="icon-info"><div class="icon-name">Tambah Data</div><div class="icon-desc">Membuka form untuk menambah pengguna baru</div></div></li><li class="help-icon-item"><div class="icon-preview bg-warning"><i class="fa fa-pencil"></i></div><div class="icon-info"><div class="icon-name">Edit</div><div class="icon-desc">Membuka form edit data pengguna</div></div></li><li class="help-icon-item"><div class="icon-preview bg-danger"><i class="fa fa-times"></i></div><div class="icon-info"><div class="icon-name">Hapus</div><div class="icon-desc">Menghapus pengguna dari sistem (dengan konfirmasi)</div></div></li></ul>'
            }
        ],
        tour: [
            { selector: '.btn-success', title: 'Tombol Tambah Data', desc: 'Klik tombol hijau ini untuk menambah pengguna baru ke sistem.' },
            { selector: '#filter_hak_akses', title: 'Filter Hak Akses', desc: 'Gunakan dropdown ini untuk memfilter tampilan berdasarkan role/hak akses pengguna.' },
            { selector: '#table', title: 'Tabel Data Pengguna', desc: 'Tabel ini menampilkan semua pengguna. Gunakan kolom "Aksi" untuk edit (ikon pensil kuning) atau hapus (ikon X merah).' },
            { selector: '.dt-buttons', title: 'Tombol Export', desc: 'Gunakan tombol-tombol ini untuk mengexport data ke format Copy, CSV, Excel, PDF, atau Print.' }
        ]
    },

    // ==========================================
    // TAMBAH PENGGUNA (CREATE)
    // ==========================================
    'tambahPengguna': {
        title: 'Form Tambah Pengguna',
        description: 'Formulir untuk mendaftarkan pengguna baru ke sistem. Isi semua field yang bertanda bintang (*) wajib diisi.',
        icon: 'fas fa-user-plus',
        sections: [
            {
                title: 'Panduan Mengisi Form',
                icon: 'fas fa-edit',
                steps: [
                    'Upload <strong>Foto Pengguna</strong> (opsional) — klik area foto lalu pilih file gambar (PNG/JPG).',
                    'Isi <strong>Username</strong> — ini digunakan untuk login ke sistem. Harus unik.',
                    'Pilih <strong>Hak Akses</strong> — menentukan level akses: Superuser, Admin, Petugas, atau Anggota.',
                    'Masukkan <strong>Password</strong> — gunakan kombinasi huruf dan angka yang kuat.',
                    'Isi <strong>Nama Lengkap</strong> — nama yang ditampilkan di sistem.',
                    'Isi <strong>No Telp</strong> (opsional) — nomor telepon pengguna.',
                    'Isi <strong>Email</strong> (opsional) — alamat email pengguna.',
                    'Klik tombol <strong>"SIMPAN DATA"</strong> berwarna biru di bawah form.'
                ]
            },
            {
                title: 'Tips Penting',
                icon: 'fas fa-lightbulb',
                content: '<div class="help-tip-box"><div class="tip-title"><i class="fas fa-lightbulb"></i> Tips</div><p class="tip-text">Username tidak bisa diubah setelah disimpan. Pastikan username sudah benar sebelum menyimpan.</p></div><div class="help-tip-box info"><div class="tip-title"><i class="fas fa-info-circle"></i> Info</div><p class="tip-text">Tombol <strong>"KEMBALI"</strong> berwarna merah untuk kembali ke daftar pengguna tanpa menyimpan.</p></div>'
            }
        ],
        tour: [
            { selector: '.image-input', title: 'Foto Pengguna', desc: 'Klik di sini untuk mengupload foto profil pengguna. Format yang didukung: PNG, JPG. Ini bersifat opsional.' },
            { selector: 'input[name="username"]', title: 'Username', desc: 'Masukkan username unik untuk login. Username tidak bisa diubah setelah disimpan.' },
            { selector: '#hak_akses', title: 'Hak Akses', desc: 'Pilih level akses pengguna: Superuser (akses penuh), Admin, Petugas, atau Anggota.' },
            { selector: 'input[name="password"]', title: 'Password', desc: 'Masukkan password untuk login. Gunakan kombinasi huruf, angka, dan simbol agar aman.' },
            { selector: 'input[name="nama_lengkap"]', title: 'Nama Lengkap', desc: 'Masukkan nama lengkap pengguna yang akan ditampilkan di sistem.' },
            { selector: 'input[name="no_telp"]', title: 'No Telepon', desc: 'Masukkan nomor telepon pengguna (opsional).' },
            { selector: 'input[name="email"]', title: 'Email', desc: 'Masukkan alamat email pengguna (opsional).' },
            { selector: '#kt_account_profile_details_submit', title: 'Tombol Simpan', desc: 'Klik tombol ini untuk menyimpan data pengguna baru. Pastikan semua field wajib sudah terisi.' }
        ]
    },

    // ==========================================
    // UPDATE PENGGUNA
    // ==========================================
    'updatePengguna': {
        title: 'Form Edit Pengguna',
        description: 'Formulir untuk mengubah data pengguna yang sudah terdaftar. Username tidak bisa diubah.',
        icon: 'fas fa-user-edit',
        sections: [
            {
                title: 'Panduan Edit Data',
                icon: 'fas fa-edit',
                steps: [
                    'Ubah <strong>Foto Pengguna</strong> — klik area foto dan pilih file baru.',
                    'Ubah <strong>Nama Lengkap</strong> jika diperlukan.',
                    'Ubah <strong>No Telp</strong> atau <strong>Email</strong> jika ada perubahan.',
                    'Klik <strong>"SIMPAN DATA"</strong> untuk menyimpan perubahan.'
                ]
            },
            {
                title: 'Tips Penting',
                icon: 'fas fa-lightbulb',
                content: '<div class="help-tip-box info"><div class="tip-title"><i class="fas fa-info-circle"></i> Info</div><p class="tip-text">Username dan Hak Akses tidak bisa diubah melalui halaman ini. Hubungi Superuser untuk mengubahnya.</p></div>'
            }
        ],
        tour: [
            { selector: '.image-input', title: 'Foto Pengguna', desc: 'Klik untuk mengubah foto profil pengguna.' },
            { selector: 'input[name="nama_lengkap"]', title: 'Nama Lengkap', desc: 'Ubah nama lengkap pengguna di sini.' },
            { selector: 'input[name="no_telp"]', title: 'No Telepon', desc: 'Perbarui nomor telepon pengguna.' },
            { selector: 'input[name="email"]', title: 'Email', desc: 'Perbarui alamat email pengguna.' },
            { selector: '#kt_account_profile_details_submit', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan perubahan data pengguna.' }
        ]
    },

    // ==========================================
    // MASTER DATA - DAFTAR INSTANSI
    // ==========================================
    'daftarInstansi': {
        title: 'Daftar Instansi',
        description: 'Mengelola data instansi/unit kerja yang terhubung dengan koperasi.',
        icon: 'fas fa-building',
        sections: [
            {
                title: 'Cara Mengelola Instansi',
                icon: 'fas fa-cogs',
                steps: [
                    'Klik tombol <strong>"Tambah Data"</strong> berwarna hijau untuk menambah instansi.',
                    'Klik ikon <strong>pensil kuning</strong> untuk mengedit instansi.',
                    'Klik ikon <strong>X merah</strong> untuk menghapus instansi.'
                ]
            },
            {
                title: 'Ikon & Tombol',
                icon: 'fas fa-icons',
                content: '<ul class="help-icon-list"><li class="help-icon-item"><div class="icon-preview bg-success"><i class="fas fa-plus"></i></div><div class="icon-info"><div class="icon-name">Tambah Data</div><div class="icon-desc">Menambah instansi baru</div></div></li><li class="help-icon-item"><div class="icon-preview bg-warning"><i class="fa fa-pencil"></i></div><div class="icon-info"><div class="icon-name">Edit</div><div class="icon-desc">Mengubah data instansi</div></div></li><li class="help-icon-item"><div class="icon-preview bg-danger"><i class="fa fa-times"></i></div><div class="icon-info"><div class="icon-name">Hapus</div><div class="icon-desc">Menghapus instansi</div></div></li></ul>'
            }
        ]
    },

    // ==========================================
    // TAMBAH INSTANSI
    // ==========================================
    'tambahInstansi': {
        title: 'Form Tambah Instansi',
        description: 'Formulir untuk menambahkan instansi/unit kerja baru ke sistem.',
        icon: 'fas fa-building',
        sections: [
            {
                title: 'Panduan Mengisi Form',
                icon: 'fas fa-edit',
                steps: [
                    'Pilih <strong>Nama Wilayah</strong> dari dropdown — ini akan memfilter sub wilayah yang tersedia.',
                    'Pilih <strong>Sub Wilayah</strong> (opsional) — akan muncul otomatis setelah memilih wilayah.',
                    'Isi <strong>Nama Instansi</strong> — nama lengkap instansi/unit kerja.',
                    'Klik tombol <strong>"SIMPAN DATA"</strong> untuk menyimpan.'
                ]
            }
        ],
        tour: [
            { selector: '#wilayah', title: 'Nama Wilayah', desc: 'Pilih wilayah dari dropdown. Sub wilayah akan otomatis dimuat sesuai pilihan wilayah.' },
            { selector: '#subwilayah', title: 'Sub Wilayah', desc: 'Pilih sub wilayah (opsional). Opsi akan muncul setelah memilih wilayah di atas.' },
            { selector: 'input[name="instansi"]', title: 'Nama Instansi', desc: 'Masukkan nama lengkap instansi atau unit kerja.' },
            { selector: '#kt_account_profile_details_submit', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan data instansi baru.' }
        ]
    },

    // ==========================================
    // UPDATE INSTANSI
    // ==========================================
    'updateInstansi': {
        title: 'Form Edit Instansi',
        description: 'Formulir untuk mengubah data instansi yang sudah terdaftar.',
        icon: 'fas fa-building',
        sections: [
            {
                title: 'Panduan Edit Data',
                icon: 'fas fa-edit',
                steps: [
                    'Ubah <strong>Wilayah</strong> atau <strong>Sub Wilayah</strong> jika diperlukan.',
                    'Ubah <strong>Nama Instansi</strong> jika ada perubahan.',
                    'Klik <strong>"SIMPAN DATA"</strong> untuk menyimpan perubahan.'
                ]
            }
        ],
        tour: [
            { selector: '#wilayah', title: 'Nama Wilayah', desc: 'Ubah wilayah jika diperlukan.' },
            { selector: '#subwilayah', title: 'Sub Wilayah', desc: 'Ubah sub wilayah sesuai kebutuhan.' },
            { selector: 'input[name="instansi"]', title: 'Nama Instansi', desc: 'Ubah nama instansi di sini.' },
            { selector: '#kt_account_profile_details_submit', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan perubahan data instansi.' }
        ]
    },

    // ==========================================
    // MASTER DATA - HAK AKSES
    // ==========================================
    'daftarHakAkses': {
        title: 'Daftar Hak Akses',
        description: 'Mengelola role/hak akses pengguna. Setiap hak akses menentukan modul dan fitur yang dapat diakses.',
        icon: 'fas fa-user-shield',
        sections: [
            {
                title: 'Jenis Hak Akses',
                icon: 'fas fa-layer-group',
                content: '<table class="help-table-info"><tr><th>Hak Akses</th><th>Deskripsi</th></tr><tr><td><strong>Superuser</strong></td><td>Akses penuh ke semua fitur</td></tr><tr><td><strong>Admin Koperasi</strong></td><td>Mengelola anggota, simpanan, pinjaman, kas, laporan, SHU</td></tr><tr><td><strong>Petugas Koperasi</strong></td><td>Mengelola transaksi harian</td></tr><tr><td><strong>Anggota Koperasi</strong></td><td>Melihat data pribadi</td></tr></table>'
            },
            {
                title: 'Cara Mengelola Hak Akses',
                icon: 'fas fa-cogs',
                steps: [
                    'Klik <strong>"Tambah Data"</strong> untuk membuat hak akses baru.',
                    'Klik ikon <strong>pensil kuning</strong> untuk mengedit.',
                    'Klik ikon <strong>X merah</strong> untuk menghapus.'
                ]
            }
        ]
    },

    // ==========================================
    // TAMBAH HAK AKSES
    // ==========================================
    'tambahHakAkses': {
        title: 'Form Tambah Hak Akses',
        description: 'Formulir untuk membuat hak akses/role baru. Centang modul-modul yang boleh diakses oleh role ini.',
        icon: 'fas fa-user-shield',
        sections: [
            {
                title: 'Panduan Mengisi Form',
                icon: 'fas fa-edit',
                steps: [
                    'Masukkan <strong>Nama Hak Akses</strong> — contoh: admin_cabang, petugas_loket.',
                    'Centang <strong>Parent Modul</strong> (menu grup) yang akan ditampilkan di sidebar.',
                    'Centang <strong>Modul</strong> (sub menu) yang boleh diakses oleh role ini.',
                    'Klik <strong>"SIMPAN DATA"</strong> untuk menyimpan.'
                ]
            },
            {
                title: 'Tips Penting',
                icon: 'fas fa-lightbulb',
                content: '<div class="help-tip-box info"><div class="tip-title"><i class="fas fa-info-circle"></i> Info</div><p class="tip-text">Centang parent modul terlebih dahulu agar menu grup muncul di sidebar, kemudian centang modul-modul di bawahnya.</p></div>'
            }
        ],
        tour: [
            { selector: 'input[name="nama_hak_akses"]', title: 'Nama Hak Akses', desc: 'Masukkan nama unik untuk role/hak akses baru. Contoh: admin_cabang, petugas_loket.' },
            { selector: '.table-striped', title: 'Daftar Modul', desc: 'Centang modul-modul yang boleh diakses oleh role ini. Centang parent modul (menu grup) agar tampil di sidebar, lalu centang modul-modul di bawahnya.' },
            { selector: '#kt_account_profile_details_submit', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan hak akses baru beserta konfigurasi modulnya.' }
        ]
    },

    // ==========================================
    // UPDATE HAK AKSES
    // ==========================================
    'updateHakAkses': {
        title: 'Form Edit Hak Akses',
        description: 'Formulir untuk mengubah konfigurasi hak akses/role yang sudah ada.',
        icon: 'fas fa-user-shield',
        sections: [
            {
                title: 'Panduan Edit Hak Akses',
                icon: 'fas fa-edit',
                steps: [
                    'Ubah <strong>Nama Hak Akses</strong> jika diperlukan.',
                    'Tambah/hapus centang pada <strong>modul</strong> yang perlu diubah aksesnya.',
                    'Klik <strong>"SIMPAN DATA"</strong> untuk menyimpan perubahan.'
                ]
            },
            {
                title: 'Peringatan',
                icon: 'fas fa-exclamation-triangle',
                content: '<div class="help-tip-box danger"><div class="tip-title"><i class="fas fa-exclamation-triangle"></i> Peringatan</div><p class="tip-text">Mengubah hak akses akan langsung mempengaruhi semua pengguna yang memiliki role ini. Berhati-hatilah saat menghapus centang modul.</p></div>'
            }
        ],
        tour: [
            { selector: 'input[name="nama_hak_akses"]', title: 'Nama Hak Akses', desc: 'Nama role/hak akses. Ubah jika diperlukan.' },
            { selector: '.table-striped', title: 'Daftar Modul', desc: 'Modul yang sudah dicentang adalah yang saat ini bisa diakses. Tambah/hapus centang sesuai kebutuhan.' },
            { selector: '#kt_account_profile_details_submit', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan perubahan konfigurasi hak akses.' }
        ]
    },

    // ==========================================
    // DAFTAR ANGGOTA
    // ==========================================
    'daftarAnggota': {
        title: 'Manajemen Anggota',
        description: 'Mengelola data anggota koperasi termasuk pendaftaran, pembaruan data, dan melihat detail informasi anggota.',
        icon: 'fas fa-id-card',
        sections: [
            {
                title: 'Cara Mengelola Anggota',
                icon: 'fas fa-user-plus',
                steps: [
                    'Klik <strong>"Tambah Data"</strong> untuk mendaftarkan anggota baru.',
                    'Klik ikon <strong>mata biru</strong> <i class="fa fa-eye text-primary"></i> untuk melihat detail.',
                    'Klik ikon <strong>pensil kuning</strong> <i class="fa fa-pencil text-warning"></i> untuk mengedit.',
                    'Klik ikon <strong>X merah</strong> <i class="fa fa-times text-danger"></i> untuk menghapus.'
                ]
            },
            {
                title: 'Status Anggota',
                icon: 'fas fa-info-circle',
                content: '<table class="help-table-info"><tr><th>Badge</th><th>Status</th><th>Keterangan</th></tr><tr><td><span class="badge badge-success">Aktif</span></td><td>Aktif</td><td>Anggota aktif yang bisa melakukan transaksi</td></tr><tr><td><span class="badge badge-danger">Non Aktif</span></td><td>Nonaktif</td><td>Anggota yang sudah tidak aktif</td></tr><tr><td><span class="badge badge-warning">Pending</span></td><td>Pending</td><td>Menunggu persetujuan/aktivasi</td></tr></table>'
            }
        ],
        tour: [
            { selector: '.btn-success', title: 'Tambah Anggota', desc: 'Klik untuk mendaftarkan anggota baru ke koperasi.' },
            { selector: '#table', title: 'Tabel Anggota', desc: 'Daftar semua anggota koperasi. Kolom aksi berisi tombol Detail (biru), Edit (kuning), dan Hapus (merah).' }
        ]
    },

    // ==========================================
    // TAMBAH ANGGOTA (CREATE)
    // ==========================================
    'tambahAnggota': {
        title: 'Form Tambah Anggota',
        description: 'Formulir untuk mendaftarkan anggota baru koperasi. Field bertanda bintang (*) wajib diisi.',
        icon: 'fas fa-user-plus',
        sections: [
            {
                title: 'Data Pribadi',
                icon: 'fas fa-user',
                steps: [
                    'Isi <strong>Nama Lengkap</strong> * — nama lengkap sesuai KTP.',
                    'Isi <strong>NIK</strong> * — Nomor Induk Kependudukan (16 digit). Hanya angka yang bisa dimasukkan.',
                    'Pilih <strong>Jenis Kelamin</strong> * — Laki-laki atau Perempuan.',
                    'Pilih <strong>Tanggal Lahir</strong> * — tanggal lahir anggota.',
                    'Isi <strong>Tempat Lahir</strong> (opsional) — kota kelahiran.',
                    'Isi <strong>Alamat</strong> * — alamat lengkap tempat tinggal.',
                    'Isi <strong>Kota</strong> dan <strong>Kode Pos</strong> (opsional).'
                ]
            },
            {
                title: 'Data Kontak & Keanggotaan',
                icon: 'fas fa-address-card',
                steps: [
                    'Isi <strong>No Telepon</strong> * — nomor HP yang bisa dihubungi.',
                    'Isi <strong>Email</strong> (opsional) — alamat email anggota.',
                    'Pilih <strong>Tanggal Bergabung</strong> * — otomatis terisi tanggal hari ini.',
                    'Isi <strong>Simpanan Pokok</strong> * — nominal simpanan pokok (default Rp 50.000).',
                    'Isi <strong>Simpanan Wajib</strong> (opsional) — simpanan wajib awal.'
                ]
            },
            {
                title: 'Akun Login (Opsional)',
                icon: 'fas fa-key',
                steps: [
                    'Isi <strong>Username</strong> — hanya jika anggota perlu akses login ke sistem.',
                    'Isi <strong>Password</strong> — wajib jika username diisi, minimal 6 karakter.',
                    'Upload <strong>Foto Anggota</strong> (opsional) — format JPG/PNG, maksimal 2MB.'
                ]
            },
            {
                title: 'Menyimpan Data',
                icon: 'fas fa-save',
                steps: [
                    'Periksa kembali semua data yang sudah diisi.',
                    'Klik tombol <strong>"Simpan Data"</strong> berwarna biru di bagian bawah.',
                    'Jika ada error, perbaiki field yang ditandai merah lalu simpan kembali.'
                ]
            }
        ],
        tour: [
            { selector: '#nama_lengkap', title: 'Nama Lengkap', desc: 'Masukkan nama lengkap anggota sesuai KTP. Field ini wajib diisi.' },
            { selector: '#nik', title: 'NIK', desc: 'Masukkan 16 digit Nomor Induk Kependudukan. Hanya angka yang diterima, otomatis dibatasi 16 karakter.' },
            { selector: '#jenis_kelamin', title: 'Jenis Kelamin', desc: 'Pilih jenis kelamin: Laki-laki atau Perempuan.' },
            { selector: '#tanggal_lahir', title: 'Tanggal Lahir', desc: 'Pilih tanggal lahir anggota dari date picker.' },
            { selector: '#alamat', title: 'Alamat', desc: 'Masukkan alamat lengkap tempat tinggal anggota. Field ini wajib diisi.' },
            { selector: '#no_telp', title: 'No Telepon', desc: 'Masukkan nomor HP anggota yang bisa dihubungi. Hanya angka dan tanda + yang diterima.' },
            { selector: '#tanggal_bergabung', title: 'Tanggal Bergabung', desc: 'Tanggal bergabung menjadi anggota koperasi. Otomatis terisi hari ini.' },
            { selector: '#simpanan_pokok', title: 'Simpanan Pokok', desc: 'Nominal simpanan pokok yang dibayarkan saat mendaftar. Default Rp 50.000.' },
            { selector: '#username', title: 'Username (Opsional)', desc: 'Isi hanya jika anggota perlu akses login ke sistem. Kosongkan jika tidak perlu.' },
            { selector: '#password', title: 'Password', desc: 'Wajib diisi jika username diisi. Minimal 6 karakter.' },
            { selector: '#foto_pengguna', title: 'Foto Anggota', desc: 'Upload foto anggota (opsional). Format: JPG/PNG, maksimal 2MB.' },
            { selector: 'button[type="submit"]', title: 'Tombol Simpan', desc: 'Klik tombol ini untuk menyimpan data anggota baru. Pastikan semua field wajib (*) sudah terisi.' }
        ]
    },

    // ==========================================
    // UPDATE ANGGOTA
    // ==========================================
    'updateAnggota': {
        title: 'Form Edit Anggota',
        description: 'Formulir untuk mengubah data anggota koperasi yang sudah terdaftar.',
        icon: 'fas fa-user-edit',
        sections: [
            {
                title: 'Data yang Bisa Diubah',
                icon: 'fas fa-edit',
                steps: [
                    '<strong>No Anggota</strong> — bersifat readonly, tidak bisa diubah.',
                    'Ubah <strong>Data Pribadi</strong>: Nama, NIK, Jenis Kelamin, Tanggal Lahir, Alamat, dll.',
                    'Ubah <strong>Data Kontak</strong>: No Telepon, Email.',
                    'Tambahkan <strong>Pekerjaan</strong>, <strong>Pendidikan</strong>, <strong>Nama Ibu Kandung</strong>.',
                    'Ubah <strong>Status Anggota</strong>: Pending, Aktif, atau Non Aktif.'
                ]
            },
            {
                title: 'Field Readonly',
                icon: 'fas fa-lock',
                content: '<div class="help-tip-box info"><div class="tip-title"><i class="fas fa-info-circle"></i> Info</div><p class="tip-text"><strong>Simpanan Pokok, Wajib, dan Sukarela</strong> bersifat readonly. Kelola melalui menu Transaksi Simpanan.</p></div>'
            },
            {
                title: 'Update Password',
                icon: 'fas fa-key',
                content: '<div class="help-tip-box"><div class="tip-title"><i class="fas fa-lightbulb"></i> Tips</div><p class="tip-text">Kosongkan field <strong>Password Baru</strong> jika tidak ingin mengubah password. Isi hanya jika ingin mereset password anggota.</p></div>'
            }
        ],
        tour: [
            { selector: '#no_anggota', title: 'No Anggota', desc: 'Nomor anggota bersifat readonly dan tidak bisa diubah.' },
            { selector: '#nama_lengkap', title: 'Nama Lengkap', desc: 'Ubah nama lengkap anggota jika diperlukan.' },
            { selector: '#nik', title: 'NIK', desc: 'Ubah NIK anggota. Harus 16 digit angka.' },
            { selector: '#jenis_kelamin', title: 'Jenis Kelamin', desc: 'Ubah jenis kelamin jika ada kesalahan data.' },
            { selector: '#no_telp', title: 'No Telepon', desc: 'Perbarui nomor telepon anggota.' },
            { selector: '#status_anggota', title: 'Status Anggota', desc: 'Ubah status: Pending (menunggu aktivasi), Aktif (bisa transaksi), Non Aktif (dinonaktifkan).' },
            { selector: '#simpanan_pokok', title: 'Simpanan Pokok (Readonly)', desc: 'Tidak bisa diubah di sini. Kelola melalui menu Transaksi Simpanan.' },
            { selector: '#password', title: 'Password Baru', desc: 'Kosongkan jika tidak ingin mengubah. Isi hanya untuk mereset password.' },
            { selector: 'button[type="submit"]', title: 'Tombol Update', desc: 'Klik untuk menyimpan perubahan data anggota.' }
        ]
    },

    // ==========================================
    // DETAIL ANGGOTA
    // ==========================================
    'detailAnggota': {
        title: 'Detail Anggota',
        description: 'Halaman detail lengkap data anggota termasuk informasi pribadi, kontak, keanggotaan, dan saldo simpanan.',
        icon: 'fas fa-id-card',
        sections: [
            {
                title: 'Informasi yang Ditampilkan',
                icon: 'fas fa-info-circle',
                content: '<table class="help-table-info"><tr><th>Bagian</th><th>Isi</th></tr><tr><td>Header</td><td>Foto, Nama, No Anggota, Badge Status</td></tr><tr><td>Data Pribadi</td><td>NIK, Jenis Kelamin, TTL, Alamat, Kota, Kode Pos, Pekerjaan, Pendidikan</td></tr><tr><td>Data Kontak</td><td>No Telepon, Email</td></tr><tr><td>Data Keanggotaan</td><td>Tanggal Bergabung, Status, Username</td></tr><tr><td>Saldo Simpanan</td><td>Simpanan Pokok, Wajib, Sukarela, Total</td></tr></table>'
            },
            {
                title: 'Tombol Aksi',
                icon: 'fas fa-mouse-pointer',
                content: '<ul class="help-icon-list"><li class="help-icon-item"><div class="icon-preview bg-warning"><i class="fas fa-pencil-alt"></i></div><div class="icon-info"><div class="icon-name">Edit</div><div class="icon-desc">Membuka form edit data anggota</div></div></li><li class="help-icon-item"><div class="icon-preview bg-secondary"><i class="fas fa-arrow-left"></i></div><div class="icon-info"><div class="icon-name">Kembali</div><div class="icon-desc">Kembali ke daftar anggota</div></div></li></ul>'
            }
        ],
        tour: [
            { selector: '.img-thumbnail', title: 'Foto Anggota', desc: 'Foto profil anggota yang sudah diupload.' },
            { selector: '.badge', title: 'Status Anggota', desc: 'Badge warna menunjukkan status: Hijau = Aktif, Merah = Non Aktif, Kuning = Pending.' },
            { selector: '.btn-warning', title: 'Tombol Edit', desc: 'Klik untuk membuka form edit data anggota ini.' }
        ]
    },

    // ==========================================
    // SIMPANAN
    // ==========================================
    'daftarSimpanan': {
        title: 'Daftar Simpanan',
        description: 'Melihat seluruh riwayat transaksi simpanan anggota koperasi.',
        icon: 'fas fa-piggy-bank',
        sections: [
            {
                title: 'Memahami Data Simpanan',
                icon: 'fas fa-info-circle',
                content: '<table class="help-table-info"><tr><th>Kolom</th><th>Keterangan</th></tr><tr><td>No Transaksi</td><td>Nomor unik setiap transaksi</td></tr><tr><td>Jenis Simpanan</td><td>Pokok, Wajib, Sukarela, dll</td></tr><tr><td>Jenis Transaksi</td><td><span class="badge badge-success">Setor</span> atau <span class="badge badge-warning">Tarik</span></td></tr><tr><td>Jumlah</td><td>Nominal transaksi</td></tr><tr><td>Status</td><td>Pending/Berhasil/Gagal</td></tr></table>'
            }
        ]
    },

    'transaksiSimpanan': {
        title: 'Transaksi Simpanan',
        description: 'Form untuk melakukan transaksi setoran atau penarikan simpanan anggota.',
        icon: 'fas fa-exchange-alt',
        sections: [
            {
                title: 'Cara Melakukan Transaksi',
                icon: 'fas fa-money-bill-wave',
                steps: [
                    'Pilih <strong>Anggota</strong> dari dropdown — bisa dicari berdasarkan nama atau no anggota.',
                    'Pilih <strong>Jenis Simpanan</strong> (Pokok, Wajib, Sukarela, dll).',
                    'Pilih <strong>Jenis Transaksi</strong>: <strong>Setor</strong> atau <strong>Tarik</strong>.',
                    'Masukkan <strong>Jumlah (Rp)</strong> — minimal Rp 1.000.',
                    'Isi <strong>Keterangan</strong> (opsional).',
                    'Upload <strong>Bukti Setor/Penarikan</strong> (opsional) — format PDF, JPG, PNG.',
                    'Klik <strong>"Simpan Transaksi"</strong>.'
                ]
            },
            {
                title: 'Tips Penting',
                icon: 'fas fa-lightbulb',
                content: '<div class="help-tip-box"><div class="tip-title"><i class="fas fa-lightbulb"></i> Tips</div><p class="tip-text">Untuk penarikan lebih dari Rp 10.000.000, sistem akan menampilkan konfirmasi tambahan.</p></div><div class="help-tip-box info"><div class="tip-title"><i class="fas fa-info-circle"></i> Info</div><p class="tip-text">Riwayat transaksi hari ini tampil di bagian bawah halaman.</p></div>'
            }
        ],
        tour: [
            { selector: 'select[name="id_anggota"]', title: 'Pilih Anggota', desc: 'Pilih anggota yang akan melakukan transaksi. Ketik nama atau nomor untuk mencari.' },
            { selector: 'select[name="id_jenis_simpanan"]', title: 'Jenis Simpanan', desc: 'Pilih jenis simpanan: Pokok, Wajib, Sukarela, dll.' },
            { selector: 'select[name="jenis_transaksi"]', title: 'Jenis Transaksi', desc: 'Pilih Setor (menyetorkan uang) atau Tarik (menarik uang).' },
            { selector: 'input[name="jumlah"]', title: 'Jumlah (Rp)', desc: 'Masukkan nominal transaksi. Minimal Rp 1.000, kelipatan Rp 1.000.' },
            { selector: 'textarea[name="keterangan"]', title: 'Keterangan', desc: 'Isi catatan transaksi (opsional). Contoh: "Simpanan bulan Februari".' },
            { selector: 'input[name="bukti_setor"]', title: 'Bukti Transaksi', desc: 'Upload bukti setor/penarikan (opsional). Format: PDF, JPG, PNG.' },
            { selector: 'button[type="submit"]', title: 'Simpan Transaksi', desc: 'Klik untuk memproses transaksi simpanan.' },
            { selector: '#table-riwayat', title: 'Riwayat Transaksi', desc: 'Tabel ini menampilkan riwayat transaksi simpanan hari ini.' }
        ]
    },

    'jenisSimpanan': {
        title: 'Jenis Simpanan',
        description: 'Mengelola jenis-jenis simpanan yang tersedia di koperasi.',
        icon: 'fas fa-tags',
        sections: [
            { title: 'Cara Mengelola', icon: 'fas fa-cogs', steps: ['Klik <strong>"Tambah Data"</strong> untuk menambah jenis simpanan baru.', 'Klik ikon <strong>pensil</strong> untuk mengedit.', 'Klik ikon <strong>X</strong> untuk menghapus.'] }
        ]
    },

    // ==========================================
    // TAMBAH JENIS SIMPANAN
    // ==========================================
    'tambahJenisSimpanan': {
        title: 'Form Tambah Jenis Simpanan',
        description: 'Formulir untuk menambahkan jenis simpanan baru ke sistem koperasi.',
        icon: 'fas fa-plus-circle',
        sections: [
            {
                title: 'Panduan Mengisi Form',
                icon: 'fas fa-edit',
                steps: [
                    'Isi <strong>Kode Jenis</strong> — kode unik singkat (maks 10 karakter). Contoh: SMP, SMW, SMS.',
                    'Isi <strong>Nama Jenis</strong> — nama lengkap jenis simpanan. Contoh: Simpanan Pokok.',
                    'Isi <strong>Deskripsi</strong> (opsional) — penjelasan tentang jenis simpanan ini.',
                    'Isi <strong>Minimal Setor</strong> — jumlah minimal per setoran.',
                    'Isi <strong>Maksimal Setor</strong> (opsional) — jumlah maksimal per setoran. Kosongkan jika tidak ada batas.',
                    'Isi <strong>Bunga (%)</strong> — persentase bunga per tahun (0-100).',
                    'Pilih <strong>Dapat Ditarik</strong> — Ya atau Tidak.',
                    'Pilih <strong>Status</strong> — Aktif atau Nonaktif.',
                    'Klik <strong>"Simpan"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#kode_jenis', title: 'Kode Jenis', desc: 'Masukkan kode unik singkat, maksimal 10 karakter. Contoh: SMP, SMW, SMS.' },
            { selector: '#nama_jenis', title: 'Nama Jenis', desc: 'Masukkan nama lengkap jenis simpanan. Contoh: Simpanan Pokok, Simpanan Sukarela.' },
            { selector: '#deskripsi', title: 'Deskripsi', desc: 'Penjelasan tentang jenis simpanan ini (opsional).' },
            { selector: '#minimal_setor', title: 'Minimal Setor', desc: 'Jumlah minimal per transaksi setoran.' },
            { selector: '#maksimal_setor', title: 'Maksimal Setor', desc: 'Jumlah maksimal per setoran. Kosongkan jika tidak ada batas.' },
            { selector: '#bunga_persen', title: 'Bunga (%)', desc: 'Persentase bunga per tahun. Masukkan 0 jika tidak ada bunga.' },
            { selector: '#dapat_ditarik', title: 'Dapat Ditarik', desc: 'Pilih Ya jika simpanan bisa ditarik, Tidak jika tidak bisa (seperti simpanan pokok).' },
            { selector: '#status', title: 'Status', desc: 'Aktif = jenis simpanan bisa digunakan, Nonaktif = tidak tampil di pilihan transaksi.' },
            { selector: 'button[type="submit"]', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan jenis simpanan baru.' }
        ]
    },

    // ==========================================
    // UPDATE JENIS SIMPANAN
    // ==========================================
    'updateJenisSimpanan': {
        title: 'Form Edit Jenis Simpanan',
        description: 'Formulir untuk mengubah data jenis simpanan yang sudah ada.',
        icon: 'fas fa-edit',
        sections: [
            {
                title: 'Panduan Edit',
                icon: 'fas fa-edit',
                steps: [
                    'Ubah field yang perlu diperbarui.',
                    'Klik <strong>"Update"</strong> untuk menyimpan perubahan.'
                ]
            }
        ],
        tour: [
            { selector: '#kode_jenis', title: 'Kode Jenis', desc: 'Kode jenis simpanan. Ubah jika diperlukan.' },
            { selector: '#nama_jenis', title: 'Nama Jenis', desc: 'Nama jenis simpanan. Ubah jika diperlukan.' },
            { selector: '#bunga_persen', title: 'Bunga (%)', desc: 'Persentase bunga. Perubahan tidak berlaku retroaktif.' },
            { selector: '#status', title: 'Status', desc: 'Ubah ke Nonaktif untuk menonaktifkan jenis simpanan ini.' },
            { selector: 'button[type="submit"]', title: 'Tombol Update', desc: 'Klik untuk menyimpan perubahan.' }
        ]
    },

    'approvalSimpanan': {
        title: 'Approval Simpanan',
        description: 'Menyetujui atau menolak transaksi simpanan yang masih berstatus pending.',
        icon: 'fas fa-check-double',
        sections: [
            { title: 'Cara Approval', icon: 'fas fa-check-circle', steps: ['Lihat daftar transaksi berstatus <strong>"Pending"</strong>.', 'Klik tombol <strong>"Approval"</strong> pada transaksi yang ingin diproses.', 'Pilih <strong>Status</strong>: Disetujui atau Ditolak.', 'Isi <strong>Keterangan</strong> (opsional).', 'Klik <strong>"Simpan"</strong>.'] }
        ],
        tour: [
            { selector: '#table', title: 'Tabel Transaksi', desc: 'Daftar transaksi simpanan yang menunggu approval.' },
            { selector: '.dt-buttons', title: 'Tombol Export', desc: 'Export data ke berbagai format.' }
        ]
    },

    // ==========================================
    // PINJAMAN
    // ==========================================
    'daftarPinjaman': {
        title: 'Daftar Pinjaman',
        description: 'Melihat dan mengelola seluruh data pinjaman anggota koperasi.',
        icon: 'fas fa-hand-holding-usd',
        sections: [
            {
                title: 'Status Pinjaman',
                icon: 'fas fa-info-circle',
                content: '<table class="help-table-info"><tr><th>Status</th><th>Keterangan</th></tr><tr><td><span class="badge badge-warning">Pengajuan</span></td><td>Menunggu approval</td></tr><tr><td><span class="badge badge-info">Disetujui</span></td><td>Menunggu pencairan</td></tr><tr><td><span class="badge badge-success">Dicairkan</span></td><td>Dana sudah dicairkan</td></tr><tr><td><span class="badge badge-primary">Lunas</span></td><td>Pinjaman selesai</td></tr><tr><td><span class="badge badge-danger">Ditolak</span></td><td>Pengajuan ditolak</td></tr></table>'
            }
        ]
    },

    'pengajuanPinjaman': {
        title: 'Pengajuan Pinjaman',
        description: 'Form untuk mengajukan pinjaman baru bagi anggota koperasi.',
        icon: 'fas fa-file-invoice-dollar',
        sections: [
            {
                title: 'Cara Mengajukan Pinjaman',
                icon: 'fas fa-hand-holding-usd',
                steps: [
                    'Pilih <strong>Anggota</strong> yang mengajukan pinjaman.',
                    'Pilih <strong>Jenis Pinjaman</strong> — setiap jenis memiliki batas dan bunga berbeda.',
                    'Masukkan <strong>Jumlah Pinjaman</strong> — perhatikan batas minimal dan maksimal.',
                    'Masukkan <strong>Tenor (Bulan)</strong> — perhatikan batas tenor.',
                    'Isi <strong>Tujuan Pinjaman</strong> — wajib diisi.',
                    'Isi <strong>Agunan</strong> dan <strong>Nilai Agunan</strong> (opsional).',
                    'Upload <strong>File Agunan</strong> (opsional).',
                    'Periksa <strong>Simulasi Angsuran</strong> yang muncul otomatis.',
                    'Klik <strong>"Ajukan Pinjaman"</strong>.'
                ]
            },
            {
                title: 'Simulasi Angsuran',
                icon: 'fas fa-calculator',
                content: '<div class="help-tip-box info"><div class="tip-title"><i class="fas fa-calculator"></i> Simulasi Otomatis</div><p class="tip-text">Setelah memilih jenis pinjaman dan mengisi jumlah + tenor, simulasi angsuran muncul otomatis: jumlah, tenor, bunga, angsuran/bulan, dan total pengembalian.</p></div>'
            }
        ],
        tour: [
            { selector: 'select[name="id_anggota"]', title: 'Pilih Anggota', desc: 'Pilih anggota yang mengajukan pinjaman.' },
            { selector: '#id_jenis_pinjaman', title: 'Jenis Pinjaman', desc: 'Pilih jenis pinjaman. Info batas pinjaman dan bunga akan muncul otomatis.' },
            { selector: '#jumlah_pinjaman', title: 'Jumlah Pinjaman', desc: 'Masukkan nominal pinjaman. Perhatikan batas min/max yang tampil.' },
            { selector: '#tenor', title: 'Tenor (Bulan)', desc: 'Masukkan jangka waktu pinjaman dalam bulan.' },
            { selector: 'textarea[name="tujuan_pinjaman"]', title: 'Tujuan Pinjaman', desc: 'Jelaskan tujuan penggunaan dana pinjaman. Field ini wajib diisi.' },
            { selector: 'input[name="agunan"]', title: 'Agunan', desc: 'Masukkan jenis agunan/jaminan (opsional). Contoh: BPKB Motor, Sertifikat Tanah.' },
            { selector: 'input[name="nilai_agunan"]', title: 'Nilai Agunan', desc: 'Masukkan estimasi nilai agunan dalam Rupiah (opsional).' },
            { selector: 'input[name="file_agunan"]', title: 'File Agunan', desc: 'Upload foto/scan dokumen agunan (opsional). Format: PDF, JPG, PNG.' },
            { selector: '#simulasi_angsuran', title: 'Simulasi Angsuran', desc: 'Simulasi otomatis menampilkan rincian: angsuran per bulan, total bunga, dan total pengembalian.' },
            { selector: 'button[type="submit"]', title: 'Ajukan Pinjaman', desc: 'Klik untuk mengirim pengajuan pinjaman. Pengajuan akan masuk ke proses approval.' }
        ]
    },

    'approvalPinjaman': {
        title: 'Approval Pinjaman',
        description: 'Menyetujui atau menolak pengajuan pinjaman anggota.',
        icon: 'fas fa-clipboard-check',
        sections: [
            { title: 'Proses Approval', icon: 'fas fa-check-double', steps: ['Lihat daftar pengajuan pinjaman yang masih <strong>Pending</strong>.', 'Klik tombol <strong>"Approval"</strong>.', 'Pilih <strong>Status</strong>: Disetujui atau Ditolak.', 'Isi <strong>Keterangan</strong> (opsional).', 'Klik <strong>"Simpan"</strong>.'] }
        ]
    },

    'persetujuanPinjaman': {
        title: 'Persetujuan Pinjaman',
        description: 'Persetujuan akhir pinjaman sebelum proses pencairan dana.',
        icon: 'fas fa-stamp',
        sections: [
            { title: 'Proses Persetujuan', icon: 'fas fa-gavel', steps: ['Review pinjaman yang sudah diapprove.', 'Klik tombol <strong>"Detail"</strong> untuk melihat rincian.', 'Klik <strong>"Setujui"</strong> untuk menyetujui atau <strong>"Tolak"</strong> untuk menolak.'] }
        ]
    },

    'pencairanPinjaman': {
        title: 'Pencairan Pinjaman',
        description: 'Proses pencairan dana pinjaman yang sudah disetujui.',
        icon: 'fas fa-money-check-alt',
        sections: [
            { title: 'Cara Mencairkan', icon: 'fas fa-money-bill-wave', steps: ['Pilih pinjaman berstatus <strong>"Disetujui"</strong>.', 'Klik tombol <strong>"Cairkan"</strong>.', 'Pilih <strong>Tanggal Pencairan</strong> (default hari ini).', 'Jumlah pencairan otomatis terisi.', 'Isi <strong>Keterangan</strong> (opsional).', 'Klik <strong>"Cairkan"</strong> — jadwal angsuran dibuat otomatis.'] }
        ],
        tour: [
            { selector: '#table', title: 'Tabel Pinjaman', desc: 'Daftar pinjaman yang sudah disetujui dan siap dicairkan.' },
            { selector: '.dt-buttons', title: 'Tombol Export', desc: 'Export data ke berbagai format.' }
        ]
    },

    'angsuranPinjaman': {
        title: 'Angsuran Pinjaman',
        description: 'Melihat jadwal dan riwayat angsuran pinjaman anggota.',
        icon: 'fas fa-calendar-check',
        sections: [
            {
                title: 'Memahami Tabel Angsuran',
                icon: 'fas fa-table',
                content: '<table class="help-table-info"><tr><th>Kolom</th><th>Keterangan</th></tr><tr><td>Angsuran Ke</td><td>Nomor urut angsuran</td></tr><tr><td>Jatuh Tempo</td><td>Batas waktu pembayaran</td></tr><tr><td>Total Angsuran</td><td>Pokok + Bunga</td></tr><tr><td>Status</td><td>Belum Bayar / Sudah Bayar / Telat</td></tr></table>'
            }
        ]
    },

    'bayarAngsuran': {
        title: 'Bayar Angsuran',
        description: 'Melakukan pembayaran angsuran pinjaman anggota.',
        icon: 'fas fa-coins',
        sections: [
            {
                title: 'Cara Bayar Angsuran',
                icon: 'fas fa-money-bill-wave',
                steps: [
                    'Periksa <strong>Informasi Pinjaman</strong> di bagian atas (No Pinjaman, Nama, Angsuran Ke).',
                    'Periksa <strong>Detail Angsuran</strong> (Jatuh Tempo, Pokok, Bunga, Total).',
                    'Pilih <strong>Tanggal Pembayaran</strong> (default hari ini).',
                    'Jumlah pembayaran sudah otomatis terisi (readonly).',
                    'Pilih <strong>Metode Pembayaran</strong>: Tunai, Transfer, atau Debet Langsung.',
                    'Upload <strong>Bukti Pembayaran</strong> (opsional).',
                    'Isi <strong>Keterangan</strong> (opsional).',
                    'Klik <strong>"Bayar Angsuran"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#tanggal_bayar', title: 'Tanggal Pembayaran', desc: 'Pilih tanggal pembayaran. Default terisi hari ini.' },
            { selector: '#jumlah_bayar', title: 'Jumlah Pembayaran', desc: 'Nominal yang harus dibayar (otomatis, tidak bisa diubah).' },
            { selector: '#metode_bayar', title: 'Metode Pembayaran', desc: 'Pilih metode: Tunai, Transfer Bank, atau Debet Langsung dari simpanan.' },
            { selector: '#bukti_bayar', title: 'Bukti Pembayaran', desc: 'Upload bukti pembayaran (opsional). Format: PDF, JPG, PNG.' },
            { selector: '#keterangan', title: 'Keterangan', desc: 'Tambahkan catatan pembayaran jika perlu (opsional).' },
            { selector: 'button[type="submit"]', title: 'Bayar Angsuran', desc: 'Klik untuk memproses pembayaran angsuran ini.' }
        ]
    },

    'jenisPinjaman': {
        title: 'Jenis Pinjaman',
        description: 'Mengelola jenis-jenis pinjaman yang tersedia di koperasi.',
        icon: 'fas fa-tags',
        sections: [
            { title: 'Cara Mengelola', icon: 'fas fa-cogs', steps: ['Klik <strong>"Tambah Data"</strong> untuk menambah jenis pinjaman baru.', 'Klik ikon <strong>pensil</strong> untuk mengedit.', 'Klik ikon <strong>X</strong> untuk menghapus.'] }
        ]
    },

    // ==========================================
    // TAMBAH JENIS PINJAMAN
    // ==========================================
    'tambahJenisPinjaman': {
        title: 'Form Tambah Jenis Pinjaman',
        description: 'Formulir untuk menambahkan jenis pinjaman baru ke sistem koperasi.',
        icon: 'fas fa-plus-circle',
        sections: [
            {
                title: 'Panduan Mengisi Form',
                icon: 'fas fa-edit',
                steps: [
                    'Isi <strong>Kode Jenis</strong> — kode unik singkat (maks 10 karakter).',
                    'Isi <strong>Nama Jenis</strong> — nama lengkap jenis pinjaman.',
                    'Isi <strong>Deskripsi</strong> (opsional).',
                    'Tentukan <strong>Minimal</strong> dan <strong>Maksimal Pinjam</strong>.',
                    'Isi <strong>Bunga (%)</strong> per tahun.',
                    'Pilih <strong>Jenis Bunga</strong>: Flat, Efektif, atau Anuitas.',
                    'Isi <strong>Denda (%)</strong> untuk keterlambatan (opsional).',
                    'Tentukan <strong>Tenor Min</strong> dan <strong>Max</strong> (dalam bulan).',
                    'Pilih <strong>Status</strong>: Aktif atau Nonaktif.',
                    'Isi <strong>Syarat & Ketentuan</strong> (opsional).',
                    'Klik <strong>"Simpan"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#kode_jenis', title: 'Kode Jenis', desc: 'Kode unik singkat untuk jenis pinjaman, maks 10 karakter.' },
            { selector: '#nama_jenis', title: 'Nama Jenis', desc: 'Nama lengkap jenis pinjaman. Contoh: Pinjaman Konsumtif.' },
            { selector: '#minimal_pinjam', title: 'Minimal Pinjam', desc: 'Jumlah minimal yang bisa dipinjam.' },
            { selector: '#maksimal_pinjam', title: 'Maksimal Pinjam', desc: 'Jumlah maksimal yang bisa dipinjam.' },
            { selector: '#bunga_persen', title: 'Bunga (%)', desc: 'Persentase bunga per tahun (0-100%).' },
            { selector: '#jenis_bunga', title: 'Jenis Bunga', desc: 'Flat = bunga tetap, Efektif = bunga menurun, Anuitas = angsuran tetap.' },
            { selector: '#denda_persen', title: 'Denda (%)', desc: 'Persentase denda keterlambatan per hari/bulan (opsional).' },
            { selector: '#tenor_min', title: 'Tenor Minimal', desc: 'Jangka waktu pinjaman minimal dalam bulan.' },
            { selector: '#tenor_max', title: 'Tenor Maksimal', desc: 'Jangka waktu pinjaman maksimal dalam bulan.' },
            { selector: '#status', title: 'Status', desc: 'Aktif = bisa digunakan untuk pengajuan, Nonaktif = tidak tampil.' },
            { selector: 'button[type="submit"]', title: 'Tombol Simpan', desc: 'Klik untuk menyimpan jenis pinjaman baru.' }
        ]
    },

    // ==========================================
    // UPDATE JENIS PINJAMAN
    // ==========================================
    'updateJenisPinjaman': {
        title: 'Form Edit Jenis Pinjaman',
        description: 'Formulir untuk mengubah data jenis pinjaman yang sudah ada.',
        icon: 'fas fa-edit',
        sections: [
            { title: 'Panduan Edit', icon: 'fas fa-edit', steps: ['Ubah field yang perlu diperbarui.', 'Klik <strong>"Update"</strong> untuk menyimpan perubahan.'] }
        ],
        tour: [
            { selector: '#kode_jenis', title: 'Kode Jenis', desc: 'Ubah kode jenis pinjaman jika diperlukan.' },
            { selector: '#nama_jenis', title: 'Nama Jenis', desc: 'Ubah nama jenis pinjaman.' },
            { selector: '#bunga_persen', title: 'Bunga (%)', desc: 'Ubah persentase bunga. Tidak berlaku retroaktif.' },
            { selector: '#status', title: 'Status', desc: 'Ubah ke Nonaktif untuk menonaktifkan jenis pinjaman ini.' },
            { selector: 'button[type="submit"]', title: 'Tombol Update', desc: 'Klik untuk menyimpan perubahan.' }
        ]
    },

    // ==========================================
    // KAS KOPERASI
    // ==========================================
    'kasKoperasi': {
        title: 'Kas Koperasi',
        description: 'Ringkasan dan riwayat transaksi kas koperasi.',
        icon: 'fas fa-cash-register',
        sections: [
            {
                title: 'Kartu Ringkasan',
                icon: 'fas fa-chart-bar',
                content: '<ul class="help-icon-list"><li class="help-icon-item"><div class="icon-preview bg-primary"><i class="fas fa-wallet"></i></div><div class="icon-info"><div class="icon-name">Saldo Kas</div><div class="icon-desc">Total saldo kas saat ini</div></div></li><li class="help-icon-item"><div class="icon-preview bg-success"><i class="fas fa-arrow-down"></i></div><div class="icon-info"><div class="icon-name">Kas Masuk</div><div class="icon-desc">Total pemasukan hari ini</div></div></li><li class="help-icon-item"><div class="icon-preview bg-danger"><i class="fas fa-arrow-up"></i></div><div class="icon-info"><div class="icon-name">Kas Keluar</div><div class="icon-desc">Total pengeluaran hari ini</div></div></li></ul>'
            }
        ]
    },

    'transaksiKas': {
        title: 'Transaksi Kas',
        description: 'Form untuk membuat transaksi kas masuk atau kas keluar.',
        icon: 'fas fa-exchange-alt',
        sections: [
            {
                title: 'Cara Membuat Transaksi',
                icon: 'fas fa-cash-register',
                steps: [
                    'Pilih <strong>Jenis Transaksi</strong>: Masuk (pemasukan) atau Keluar (pengeluaran).',
                    'Pilih <strong>Kategori</strong>: operasional, administrasi, pendapatan lain, dll.',
                    'Masukkan <strong>Jumlah (Rp)</strong>.',
                    'Isi <strong>Deskripsi</strong> untuk dokumentasi.',
                    'Upload <strong>Bukti Transaksi</strong> (opsional).',
                    'Periksa <strong>Preview Transaksi</strong> yang muncul otomatis.',
                    'Klik <strong>"Simpan Transaksi"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: 'select[name="jenis_transaksi"]', title: 'Jenis Transaksi', desc: 'Pilih Masuk (pemasukan) atau Keluar (pengeluaran).' },
            { selector: 'select[name="kategori"]', title: 'Kategori', desc: 'Pilih kategori: operasional, administrasi, biaya umum, pendapatan lain, dll.' },
            { selector: 'input[name="jumlah"]', title: 'Jumlah (Rp)', desc: 'Masukkan nominal transaksi.' },
            { selector: 'textarea[name="deskripsi"]', title: 'Deskripsi', desc: 'Jelaskan detail transaksi untuk dokumentasi.' },
            { selector: 'input[name="bukti_transaksi"]', title: 'Bukti Transaksi', desc: 'Upload bukti transaksi (opsional). Format: PDF, JPG, PNG.' },
            { selector: '#preview_transaksi', title: 'Preview Transaksi', desc: 'Preview otomatis menampilkan ringkasan transaksi sebelum disimpan.' },
            { selector: 'button[type="submit"]', title: 'Simpan Transaksi', desc: 'Klik untuk menyimpan transaksi kas.' }
        ]
    },

    'mutasiKas': {
        title: 'Mutasi Kas',
        description: 'Laporan mutasi kas koperasi berdasarkan periode.',
        icon: 'fas fa-list-alt',
        sections: [
            { title: 'Cara Menggunakan', icon: 'fas fa-search', steps: ['Pilih <strong>Tanggal Mulai</strong> dan <strong>Tanggal Selesai</strong>.', 'Klik <strong>"Filter"</strong>.', 'Data mutasi kas akan tampil di tabel.', 'Gunakan tombol export untuk mengunduh laporan.'] }
        ],
        tour: [
            { selector: '#start_date', title: 'Tanggal Mulai', desc: 'Pilih tanggal awal periode yang ingin dilihat.' },
            { selector: '#end_date', title: 'Tanggal Selesai', desc: 'Pilih tanggal akhir periode.' },
            { selector: '#table', title: 'Tabel Mutasi', desc: 'Menampilkan semua mutasi kas dalam periode yang dipilih.' }
        ]
    },

    // ==========================================
    // LAPORAN
    // ==========================================
    'laporanSimpanan': { title: 'Laporan Simpanan', description: 'Laporan lengkap transaksi simpanan berdasarkan periode.', icon: 'fas fa-file-alt', sections: [{ title: 'Cara Melihat', icon: 'fas fa-search', steps: ['Pilih <strong>periode tanggal</strong>.', 'Filter berdasarkan <strong>jenis simpanan/anggota</strong> (opsional).', 'Klik <strong>"Filter"</strong>.', 'Export ke Excel/PDF/Print.'] }] },
    'laporanPinjaman': { title: 'Laporan Pinjaman', description: 'Laporan lengkap pinjaman berdasarkan status dan periode.', icon: 'fas fa-file-invoice', sections: [{ title: 'Cara Melihat', icon: 'fas fa-search', steps: ['Pilih <strong>periode</strong>.', 'Filter berdasarkan <strong>status pinjaman</strong>.', 'Klik <strong>"Filter"</strong>.', 'Export laporan.'] }] },
    'laporanKas': { title: 'Laporan Kas', description: 'Laporan arus kas koperasi.', icon: 'fas fa-file-invoice-dollar', sections: [{ title: 'Cara Melihat', icon: 'fas fa-search', steps: ['Pilih <strong>periode</strong>.', 'Klik <strong>"Filter"</strong>.', 'Export ke Excel/PDF.'] }] },
    'laporanAnggota': { title: 'Laporan Anggota', description: 'Laporan data anggota koperasi.', icon: 'fas fa-file-contract', sections: [{ title: 'Cara Melihat', icon: 'fas fa-search', steps: ['Filter berdasarkan <strong>status/periode bergabung</strong>.', 'Klik <strong>"Filter"</strong>.', 'Export data.'] }] },
    'laporanKeuangan': { title: 'Laporan Keuangan', description: 'Laporan keuangan lengkap koperasi.', icon: 'fas fa-chart-pie', sections: [{ title: 'Cara Melihat', icon: 'fas fa-search', steps: ['Pilih <strong>periode</strong>.', 'Laporan menampilkan ringkasan: simpanan, pinjaman, kas, SHU.', 'Export laporan.'] }] },

    // ==========================================
    // SHU
    // ==========================================
    'dataSHU': {
        title: 'Data SHU',
        description: 'Data Sisa Hasil Usaha (SHU) anggota koperasi per tahun.',
        icon: 'fas fa-gift',
        sections: [
            {
                title: 'Memahami Data SHU',
                icon: 'fas fa-info-circle',
                content: '<table class="help-table-info"><tr><th>Kolom</th><th>Keterangan</th></tr><tr><td>Total Simpanan</td><td>Dasar perhitungan dari simpanan</td></tr><tr><td>Total Pinjaman</td><td>Dasar perhitungan dari pinjaman</td></tr><tr><td>Jasa Simpanan</td><td>SHU dari kontribusi simpanan</td></tr><tr><td>Jasa Pinjaman</td><td>SHU dari kontribusi pinjaman</td></tr><tr><td>Total SHU</td><td>Total yang diterima anggota</td></tr><tr><td>Status</td><td>Sudah Bayar / Belum Bayar</td></tr></table>'
            }
        ]
    },

    'perhitunganSHU': {
        title: 'Perhitungan SHU',
        description: 'Detail perhitungan SHU koperasi.',
        icon: 'fas fa-calculator',
        sections: [
            { title: 'Info', icon: 'fas fa-info-circle', content: '<p>Menampilkan detail perhitungan SHU termasuk total pendapatan, biaya operasional, dan pembagian berdasarkan kontribusi anggota.</p>' }
        ]
    },

    'hitungSHU': {
        title: 'Hitung SHU',
        description: 'Melakukan perhitungan SHU untuk periode tertentu.',
        icon: 'fas fa-calculator',
        sections: [
            {
                title: 'Cara Menghitung SHU',
                icon: 'fas fa-play',
                steps: [
                    'Pilih <strong>Tahun</strong> perhitungan.',
                    'Masukkan <strong>Total SHU (Rp)</strong> — total laba bersih koperasi.',
                    'Isi <strong>Persentase Jasa Simpanan</strong> (default 60%).',
                    'Isi <strong>Persentase Jasa Pinjaman</strong> (default 40%).',
                    'Total persentase tidak boleh lebih dari 100%.',
                    'Klik <strong>"Hitung SHU"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#tahun', title: 'Tahun', desc: 'Pilih tahun perhitungan SHU.' },
            { selector: '#total_shu', title: 'Total SHU (Rp)', desc: 'Masukkan total laba bersih koperasi yang akan dibagikan sebagai SHU.' },
            { selector: '#persentase_jasa_simpanan', title: 'Jasa Simpanan (%)', desc: 'Persentase SHU untuk jasa simpanan (default 60%). Dihitung berdasarkan kontribusi simpanan anggota.' },
            { selector: '#persentase_jasa_pinjaman', title: 'Jasa Pinjaman (%)', desc: 'Persentase SHU untuk jasa pinjaman (default 40%). Dihitung berdasarkan kontribusi pinjaman anggota.' },
            { selector: 'button[type="submit"]', title: 'Hitung SHU', desc: 'Klik untuk menghitung SHU berdasarkan parameter yang sudah diisi.' }
        ]
    },

    'distribusiSHU': {
        title: 'Distribusi SHU',
        description: 'Mendistribusikan SHU yang sudah dihitung ke anggota.',
        icon: 'fas fa-share-alt',
        sections: [
            {
                title: 'Cara Distribusi',
                icon: 'fas fa-play',
                steps: [
                    'Pilih <strong>Tahun</strong> SHU yang akan didistribusikan.',
                    'Klik <strong>"Tampilkan Data"</strong> untuk memuat data.',
                    'Centang anggota yang akan menerima distribusi, atau klik <strong>"Pilih Semua"</strong>.',
                    'Klik <strong>"Distribusikan SHU"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#tahun', title: 'Pilih Tahun', desc: 'Pilih tahun SHU yang akan didistribusikan.' },
            { selector: '#checkAll', title: 'Pilih Semua', desc: 'Centang untuk memilih semua anggota sekaligus.' },
            { selector: '#btnDistribusi', title: 'Distribusikan SHU', desc: 'Klik untuk mendistribusikan SHU ke anggota yang dipilih.' }
        ]
    },

    'bayarSHU': {
        title: 'Bayar SHU',
        description: 'Memproses pembayaran SHU ke anggota.',
        icon: 'fas fa-money-bill-wave',
        sections: [
            {
                title: 'Cara Bayar SHU',
                icon: 'fas fa-money-check-alt',
                steps: [
                    'Filter berdasarkan <strong>Tahun</strong> dan <strong>Status</strong> (Belum Bayar/Sudah Bayar).',
                    'Klik <strong>"Filter"</strong> untuk menampilkan data.',
                    'Klik tombol <strong>"Bayar"</strong> pada anggota yang ingin dibayar.',
                    'Pilih <strong>Tanggal Pembayaran</strong>.',
                    'Pilih <strong>Metode Pembayaran</strong>: Tunai, Transfer, atau Debet Simpanan.',
                    'Isi <strong>Keterangan</strong> (opsional).',
                    'Klik <strong>"Bayar SHU"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: 'select[name="tahun"]', title: 'Filter Tahun', desc: 'Pilih tahun SHU yang ingin ditampilkan.' },
            { selector: 'select[name="status"]', title: 'Filter Status', desc: 'Filter berdasarkan status pembayaran.' },
            { selector: '#table', title: 'Tabel Data SHU', desc: 'Daftar anggota beserta nominal SHU dan status pembayaran.' }
        ]
    },

    'laporanSHU': { title: 'Laporan SHU', description: 'Laporan lengkap SHU koperasi per tahun.', icon: 'fas fa-file-alt', sections: [{ title: 'Cara Melihat', icon: 'fas fa-search', steps: ['Pilih <strong>tahun</strong>.', 'Laporan menampilkan distribusi per anggota dan status pembayaran.', 'Export ke Excel/PDF.'] }] },

    // ==========================================
    // NOTIFIKASI
    // ==========================================
    'daftarNotifikasi': {
        title: 'Daftar Notifikasi',
        description: 'Melihat semua notifikasi yang masuk.',
        icon: 'fas fa-bell',
        sections: [
            {
                title: 'Jenis Notifikasi',
                icon: 'fas fa-info-circle',
                content: '<table class="help-table-info"><tr><th>Jenis</th><th>Keterangan</th></tr><tr><td><span class="badge badge-warning">Angsuran Jatuh Tempo</span></td><td>Pengingat angsuran</td></tr><tr><td><span class="badge badge-danger">Angsuran Telat</span></td><td>Angsuran lewat jatuh tempo</td></tr><tr><td><span class="badge badge-success">SHU Tersedia</span></td><td>SHU siap diambil</td></tr><tr><td><span class="badge badge-info">Sistem</span></td><td>Notifikasi sistem</td></tr></table>'
            }
        ]
    },

    'kirimNotifikasi': {
        title: 'Kirim Notifikasi',
        description: 'Mengirim notifikasi ke anggota koperasi.',
        icon: 'fas fa-paper-plane',
        sections: [
            {
                title: 'Cara Kirim Notifikasi',
                icon: 'fas fa-paper-plane',
                steps: [
                    'Isi <strong>Judul Notifikasi</strong> — judul singkat dan jelas.',
                    'Tulis <strong>Pesan Notifikasi</strong> — isi pesan lengkap.',
                    'Upload <strong>Lampiran</strong> (opsional) — maks 5MB, format JPG/PNG/PDF.',
                    'Pilih <strong>Tipe</strong>: Info, Warning, Success, atau Danger.',
                    'Pilih <strong>Target Penerima</strong>: Semua, Per Hak Akses, atau Pengguna Tertentu.',
                    'Jika per hak akses, pilih <strong>Hak Akses Target</strong>.',
                    'Jika pengguna tertentu, pilih <strong>Pengguna Target</strong>.',
                    'Klik <strong>"Kirim Notifikasi"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#judul', title: 'Judul Notifikasi', desc: 'Masukkan judul singkat dan jelas untuk notifikasi.' },
            { selector: '#pesan', title: 'Pesan Notifikasi', desc: 'Tulis isi pesan notifikasi secara lengkap.' },
            { selector: '#file_attachment', title: 'Lampiran File', desc: 'Upload lampiran (opsional). Maks 5MB, format: JPG, PNG, PDF.' },
            { selector: '#tipe', title: 'Tipe Notifikasi', desc: 'Pilih tipe: Info (biru), Warning (kuning), Success (hijau), Danger (merah).' },
            { selector: '#target_type', title: 'Target Penerima', desc: 'Semua = kirim ke semua, Per Hak Akses = kirim ke role tertentu, Pengguna Tertentu = pilih manual.' },
            { selector: '#preview_content', title: 'Preview', desc: 'Preview otomatis menampilkan bagaimana notifikasi akan terlihat oleh penerima.' },
            { selector: 'button[type="submit"]', title: 'Kirim Notifikasi', desc: 'Klik untuk mengirim notifikasi ke penerima yang dipilih.' }
        ]
    },

    'pengaturanNotifikasi': {
        title: 'Pengaturan Notifikasi',
        description: 'Mengatur konfigurasi notifikasi termasuk integrasi WhatsApp.',
        icon: 'fas fa-cog',
        sections: [
            {
                title: 'Integrasi WhatsApp',
                icon: 'fab fa-whatsapp',
                steps: [
                    'Klik <strong>"Hubungkan WhatsApp"</strong> untuk memulai koneksi.',
                    'Scan <strong>QR Code</strong> yang muncul menggunakan WhatsApp di HP.',
                    'Setelah terhubung, status berubah menjadi <strong>"Connected"</strong>.',
                    'Notifikasi akan dikirim otomatis via WhatsApp.'
                ]
            },
            {
                title: 'Template & Auto Notifikasi',
                icon: 'fas fa-file-alt',
                steps: [
                    'Atur <strong>template pesan</strong> untuk simpanan, pinjaman, dan angsuran.',
                    'Aktifkan/nonaktifkan <strong>auto notifikasi</strong> per jenis transaksi.',
                    'Atur <strong>interval notifikasi angsuran</strong> (1-30 hari sebelum jatuh tempo).',
                    'Klik <strong>"Simpan Pengaturan"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '#btn-connect-wa', title: 'Hubungkan WhatsApp', desc: 'Klik untuk memulai koneksi WhatsApp. QR Code akan muncul untuk di-scan.' },
            { selector: '#service-status', title: 'Status Service', desc: 'Menampilkan status service WhatsApp (Running/Stopped).' },
            { selector: '#connection-status', title: 'Status Koneksi', desc: 'Menampilkan status koneksi WhatsApp (Connected/Disconnected).' },
            { selector: 'select[name="whatsapp_enabled"]', title: 'Aktifkan WhatsApp', desc: 'Aktifkan atau nonaktifkan fitur notifikasi via WhatsApp.' },
            { selector: 'input[name="interval_notif_angsuran"]', title: 'Interval Notifikasi', desc: 'Berapa hari sebelum jatuh tempo notifikasi angsuran dikirim (1-30 hari).' },
            { selector: 'button[type="submit"]', title: 'Simpan Pengaturan', desc: 'Klik untuk menyimpan semua pengaturan notifikasi.' }
        ]
    },

    // ==========================================
    // PENGATURAN
    // ==========================================
    'pengaturanSistem': {
        title: 'Pengaturan Sistem',
        description: 'Mengelola identitas dan konfigurasi aplikasi koperasi.',
        icon: 'fas fa-cogs',
        sections: [
            {
                title: 'Cara Update Pengaturan',
                icon: 'fas fa-save',
                steps: [
                    'Upload <strong>Icon Aplikasi</strong> — ikon yang tampil di tab browser (favicon).',
                    'Upload <strong>Logo Aplikasi</strong> — logo di sidebar dan header.',
                    'Ubah <strong>Nama Aplikasi</strong>, <strong>Kode</strong>, dan <strong>Versi</strong>.',
                    'Ubah <strong>Footer</strong> — teks di bawah halaman.',
                    'Ubah <strong>No Telp</strong> dan <strong>Email</strong> koperasi.',
                    'Klik <strong>"SIMPAN DATA"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: 'input[name="apps_name"]', title: 'Nama Aplikasi', desc: 'Nama koperasi yang ditampilkan di seluruh halaman.' },
            { selector: 'input[name="apps_code"]', title: 'Kode Aplikasi', desc: 'Kode unik aplikasi.' },
            { selector: 'input[name="apps_version"]', title: 'Versi Aplikasi', desc: 'Versi aplikasi saat ini.' },
            { selector: 'textarea[name="footer"]', title: 'Footer', desc: 'Teks yang tampil di bagian bawah setiap halaman.' },
            { selector: 'input[name="telephon"]', title: 'No Telepon', desc: 'Nomor telepon koperasi.' },
            { selector: 'input[name="email"]', title: 'Email', desc: 'Alamat email koperasi.' },
            { selector: '#kt_account_profile_details_submit', title: 'Simpan Data', desc: 'Klik untuk menyimpan semua perubahan pengaturan.' }
        ]
    },

    'logAktivitas': {
        title: 'Log Aktivitas',
        description: 'Catatan seluruh aktivitas yang dilakukan di sistem.',
        icon: 'fas fa-history',
        sections: [
            {
                title: 'Informasi Log',
                icon: 'fas fa-info-circle',
                content: '<p>Log mencatat: Login/Logout, Tambah/Edit/Hapus data, Transaksi, Perubahan pengaturan.</p><div class="help-tip-box info"><div class="tip-title"><i class="fas fa-shield-alt"></i> Keamanan</div><p class="tip-text">Hanya superuser yang dapat mengakses log aktivitas.</p></div>'
            }
        ]
    },

    // ==========================================
    // PROFILE
    // ==========================================
    'profilePengguna': {
        title: 'Profil Pengguna',
        description: 'Melihat dan mengedit data profil akun Anda.',
        icon: 'fas fa-user-circle',
        sections: [
            {
                title: 'Cara Update Profil',
                icon: 'fas fa-user-edit',
                steps: [
                    'Upload <strong>Foto Profil</strong> — klik area foto dan pilih file baru.',
                    'Ubah <strong>Nama Lengkap</strong>, <strong>No Telp</strong>, <strong>Email</strong>.',
                    'Untuk mengubah <strong>Password</strong>, isi field Password Baru.',
                    'Kosongkan field password jika tidak ingin mengubah.',
                    'Klik <strong>"SIMPAN DATA"</strong>.'
                ]
            }
        ],
        tour: [
            { selector: '.image-input', title: 'Foto Profil', desc: 'Klik untuk mengubah foto profil Anda.' },
            { selector: 'input[name="nama_lengkap"]', title: 'Nama Lengkap', desc: 'Ubah nama lengkap Anda.' },
            { selector: 'input[name="no_telp"]', title: 'No Telepon', desc: 'Perbarui nomor telepon Anda.' },
            { selector: 'input[name="email"]', title: 'Email', desc: 'Perbarui alamat email Anda.' },
            { selector: 'input[name="password"]', title: 'Password Baru', desc: 'Isi untuk mengubah password. Kosongkan jika tidak ingin mengubah.' },
            { selector: '#kt_account_profile_details_submit', title: 'Simpan Data', desc: 'Klik untuk menyimpan perubahan profil.' }
        ]
    },

    // ==========================================
    // PANDUAN TABEL DATA (GENERIC)
    // ==========================================
    '_datatables': {
        title: 'Panduan Tabel Data',
        description: 'Panduan umum penggunaan tabel data.',
        icon: 'fas fa-table',
        sections: [
            {
                title: 'Tombol Export',
                icon: 'fas fa-download',
                content: '<ul class="help-icon-list"><li class="help-icon-item"><div class="icon-preview bg-secondary"><i class="fas fa-copy"></i></div><div class="icon-info"><div class="icon-name">Copy</div><div class="icon-desc">Menyalin data ke clipboard</div></div></li><li class="help-icon-item"><div class="icon-preview bg-success"><i class="fas fa-file-csv"></i></div><div class="icon-info"><div class="icon-name">CSV</div><div class="icon-desc">Download format CSV</div></div></li><li class="help-icon-item"><div class="icon-preview bg-success"><i class="fas fa-file-excel"></i></div><div class="icon-info"><div class="icon-name">Excel</div><div class="icon-desc">Download format Excel</div></div></li><li class="help-icon-item"><div class="icon-preview bg-danger"><i class="fas fa-file-pdf"></i></div><div class="icon-info"><div class="icon-name">PDF</div><div class="icon-desc">Download format PDF</div></div></li><li class="help-icon-item"><div class="icon-preview bg-primary"><i class="fas fa-print"></i></div><div class="icon-info"><div class="icon-name">Print</div><div class="icon-desc">Cetak data langsung</div></div></li></ul>'
            },
            {
                title: 'Navigasi Tabel',
                icon: 'fas fa-arrows-alt',
                content: '<ul class="help-icon-list"><li class="help-icon-item"><div class="icon-preview bg-primary"><i class="fas fa-search"></i></div><div class="icon-info"><div class="icon-name">Pencarian</div><div class="icon-desc">Ketik di kotak "Cari:" untuk mencari data</div></div></li><li class="help-icon-item"><div class="icon-preview bg-primary"><i class="fas fa-sort"></i></div><div class="icon-info"><div class="icon-name">Sorting</div><div class="icon-desc">Klik header kolom untuk mengurutkan</div></div></li><li class="help-icon-item"><div class="icon-preview bg-primary"><i class="fas fa-chevron-right"></i></div><div class="icon-info"><div class="icon-name">Pagination</div><div class="icon-desc">Navigasi halaman di bawah tabel</div></div></li></ul>'
            }
        ]
    }
};

// Make available globally
window.HelpContent = HelpContent;
