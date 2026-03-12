# Online CCTV Monitoring

Aplikasi monitoring CCTV berbasis web yang terintegrasi dengan EZVIZ Open Platform API. Dibangun dengan Laravel 11 + Metronic Bootstrap theme.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11, PHP 8.2 |
| Frontend | Bootstrap 5 (Metronic), jQuery, DataTables, EZUIKit.js v8 |
| Database | MySQL |
| CCTV SDK | EZVIZ Open Platform API, EZUIKit.js (WebAssembly decoder) |
| Stream | EZOPEN (WebAssembly), HLS.js (fallback) |
| Container | Docker + Nginx |

---

## Fitur Aplikasi

### ✅ Sudah Diterapkan

#### Manajemen Data
| Fitur | URL | Keterangan |
|---|---|---|
| Daftar CCTV | `/panel/cctv/daftarCCTV` | DataTables server-side, filter: status / lokasi / group |
| Tambah CCTV | `/panel/cctv/tambahCCTV` | Form + validasi duplikat serial |
| Edit CCTV | `/panel/cctv/updateCCTV/{id}` | Update nama, lokasi, serial, dll |
| Hapus CCTV | `/panel/cctv/hapusCCTV/{id}` | Soft delete |
| Daftar Lokasi | `/panel/lokasi/daftarLokasi` | DataTables server-side, filter group |
| Tambah/Edit/Hapus Lokasi | `/panel/lokasi/*` | CRUD lengkap |
| Grup Lokasi | `/panel/grupLokasi/*` | CRUD + urutan tampil |
| Master Data Pengguna | `/panel/masterData/daftarPengguna` | CRUD + hak akses |
| Hak Akses | `/panel/masterData/daftarHakAkses` | Kelola modul & parent modul akses |
| Akun EZVIZ | `/panel/masterData/daftarEzvizAkun` | Kelola akun EZVIZ Open Platform |

#### Live View & Stream
| Fitur | URL | Keterangan |
|---|---|---|
| Detail CCTV + Live Stream | `/panel/cctv/detailCCTV/{id}` | EZUIKit.js / HLS fallback, capture screenshot |
| Live View per Grup | `/panel/cctv/liveViewGroup/{id_group}` | Grid multi-kamera, layout 1-4 kolom, focus mode |
| Live View semua Grup | `/panel/cctv/liveViewAllGroups` | Semua grup sekaligus |
| Live View per Lokasi | `/panel/cctv/liveViewLokasi/{id_lokasi}` | Grid semua CCTV dalam 1 lokasi |
| Protocol switch | — | Toggle EZOPEN ↔ HLS per stream |
| Fullscreen mode | — | Native browser fullscreen |

#### EZVIZ API Integration
| Fitur | Endpoint (internal) | Keterangan |
|---|---|---|
| Get stream URL | `POST /panel/cctv/streamCCTV/{id}` | Token auto-refresh |
| Capture screenshot | `POST /panel/cctv/captureCCTV/{id}` | Gambar dari server EZVIZ |
| **PTZ Control** *(baru)* | `POST /panel/cctv/ptzControl/{id}` | Start/stop: atas/bawah/kiri/kanan/zoom+/zoom− |
| Sync devices | `POST /panel/cctv/syncDevices` | Import perangkat dari akun EZVIZ |
| Refresh token | `POST /panel/cctv/refreshToken/{id}` | Manual refresh access token |
| Import device | `POST /panel/cctv/importDevice` | Tambah device dari scraper EZVIZ portal |
| Token management | — | Auto-refresh jika token < 5 menit expired |

#### Sistem
| Fitur | Keterangan |
|---|---|
| Auth + Session | Login/logout dengan proteksi session |
| Hak Akses per Modul | Middleware `CheckAccess` — per user, per parent modul |
| Akses per Grup Lokasi | `AccessHelper::getAllowedGroupIds()` — filter data berdasarkan akses grup |
| Log Aktivitas | Setiap aksi pengguna dicatat ke `cv_log_aktivitas` |
| Dashboard | Statistik kamera online/offline, grup, lokasi |
| Pengaturan Sistem | Nama aplikasi, logo, dll |

---

### 🔜 Belum Diterapkan (Tersedia via API)

| Fitur | Catatan |
|---|---|
| PTZ Preset (simpan & recall posisi) | Perlu tabel DB tambahan |
| Motion Detection on/off | `PUT /api/lapp/device/alarm/motionDetect` |
| Smart Detection (human/vehicle toggle) | Tergantung model kamera |
| Alarm / Event List | `POST /api/lapp/alarm/list/v2` |
| Playback rekaman (SD Card / Cloud) | Perlu EZUIKit SDK fitur berbayar |
| Two-way audio | Tersedia di EZOPEN stream, UI belum ada |

---

## Struktur Direktori Penting

```
app/
  Http/Controllers/Module/
    CCTVController.php      ← Live stream, capture, PTZ, sync
    LokasiController.php    ← CRUD lokasi + DataTables
    GrupLokasiController.php
    MasterDataController.php
    PengaturanController.php
  Models/
    EzvizModel.php          ← Semua call ke EZVIZ Open Platform API
    GeneralModel.php        ← Generic DB helper
  Helpers/
    AccessHelper.php        ← Cek akses grup lokasi
    LogHelper.php           ← Log aktivitas
resources/views/module/
  cctv/
    data.blade.php          ← Daftar CCTV (DataTables)
    detail.blade.php        ← Live stream + PTZ control
    liveview-group.blade.php
    liveview-all-groups.blade.php
  lokasi/
    data.blade.php          ← Daftar Lokasi (DataTables)
routes/
  web.php                   ← Semua route + pembagian checkAccess vs auth-only
tools/
  ezviz_flask_scraper.py   ← Python Flask scraper untuk EZVIZ portal
```

---

## EZVIZ API Reference

Base URL: `https://isgpopen.ezvizlife.com` (Singapore region)

| Method | Endpoint | Fungsi |
|---|---|---|
| POST | `/api/lapp/token/get` | Get access token |
| POST | `/api/lapp/device/list` | List devices dalam akun |
| POST | `/api/lapp/device/info` | Info + status device |
| POST | `/api/lapp/live/address/get` | Get HLS/RTMP stream URL |
| POST | `/api/lapp/device/capture` | Ambil screenshot |
| PUT | `/api/lapp/device/ptz/start` | Mulai gerak PTZ |
| PUT | `/api/lapp/device/ptz/stop` | Stop gerak PTZ |
| POST | `/api/lapp/device/add` | Tambah device ke akun |
| POST | `/api/lapp/alarm/list/v2` | List event/alarm |

**PTZ Direction codes:** `0`=atas `1`=bawah `2`=kiri `3`=kanan `4`=kiri-atas `5`=kanan-atas `6`=kiri-bawah `7`=kanan-bawah `8`=zoom-in `9`=zoom-out

---

## Setup Lokal

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy env
cp .env.example .env
php artisan key:generate

# 3. Migrasi database
php artisan migrate

# 4. Build frontend assets
npm install && npm run build

# 5. Jalankan server
php artisan serve
```

### Docker
```bash
docker-compose up -d
```

### Flask Scraper (untuk scraping EZVIZ portal)
```bash
python tools/ezviz_flask_scraper.py
```

---

## Database Schema (Tabel Utama)

| Tabel | Keterangan |
|---|---|
| `cv_lokasi_group` | Grup lokasi (dengan urutan tampil) |
| `cv_lokasi` | Lokasi fisik CCTV, relasi ke grup |
| `cv_cctv` | Data CCTV: serial, channel, status, dll |
| `cv_ezviz_akun` | Akun EZVIZ Open Platform + token |
| `cv_hak_akses` | Hak akses per role (JSON modul) |
| `cv_log_aktivitas` | Log semua aktivitas pengguna |
| `cv_identitas` | Setting aplikasi (nama, logo, dll) |

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
