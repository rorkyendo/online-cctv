<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Module\AuthController;
use App\Http\Controllers\Module\DashboardController;
use App\Http\Controllers\Module\GrupLokasiController;
use App\Http\Controllers\Module\LokasiController;
use App\Http\Controllers\Module\CCTVController;
use App\Http\Controllers\Module\MasterDataController;
use App\Http\Controllers\Module\PengaturanController;
use App\Http\Controllers\Module\ProfileController;
use App\Http\Controllers\PageController;

// Auth Routes
Route::get('/', [AuthController::class, 'index']);
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/doLogin', [AuthController::class, 'doLogin'])->name('doLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Error Pages
Route::get('/unauthorized', [PageController::class, 'unauthorized'])->name('unauthorized');
Route::get('/notfound', [PageController::class, 'notFound'])->name('notfound');

// Panel Routes (authenticated)
Route::middleware(['auth'])->prefix('panel')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/updateProfile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');

    // Panduan
    Route::get('/panduan', [ProfileController::class, 'userGuide'])->name('panduan');

    // Protected routes (require module access)
    Route::middleware(['checkAccess'])->group(function () {

        // Grup Lokasi
        Route::prefix('grupLokasi')->group(function () {
            Route::get('/daftarGrupLokasi', [GrupLokasiController::class, 'daftarGrupLokasi'])->name('daftarGrupLokasi');
            Route::any('/tambahGrupLokasi/{param1?}', [GrupLokasiController::class, 'tambahGrupLokasi'])->name('tambahGrupLokasi');
            Route::any('/updateGrupLokasi/{param1?}/{param2?}', [GrupLokasiController::class, 'updateGrupLokasi'])->name('updateGrupLokasi');
            Route::get('/hapusGrupLokasi/{param1}', [GrupLokasiController::class, 'hapusGrupLokasi'])->name('hapusGrupLokasi');
            Route::get('/detailGrupLokasi/{param1}', [GrupLokasiController::class, 'detailGrupLokasi'])->name('detailGrupLokasi');
        });

        // Lokasi
        Route::prefix('lokasi')->group(function () {
            Route::get('/daftarLokasi', [LokasiController::class, 'daftarLokasi'])->name('daftarLokasi');
            Route::any('/tambahLokasi/{param1?}', [LokasiController::class, 'tambahLokasi'])->name('tambahLokasi');
            Route::any('/updateLokasi/{param1?}/{param2?}', [LokasiController::class, 'updateLokasi'])->name('updateLokasi');
            Route::get('/hapusLokasi/{param1}', [LokasiController::class, 'hapusLokasi'])->name('hapusLokasi');
            Route::get('/detailLokasi/{param1}', [LokasiController::class, 'detailLokasi'])->name('detailLokasi');
        });

        // CCTV
        Route::prefix('cctv')->group(function () {
            Route::get('/daftarCCTV', [CCTVController::class, 'daftarCCTV'])->name('daftarCCTV');
            Route::any('/tambahCCTV/{param1?}', [CCTVController::class, 'tambahCCTV'])->name('tambahCCTV');
            Route::any('/updateCCTV/{param1?}/{param2?}', [CCTVController::class, 'updateCCTV'])->name('updateCCTV');
            Route::get('/hapusCCTV/{param1}', [CCTVController::class, 'hapusCCTV'])->name('hapusCCTV');
            Route::get('/detailCCTV/{param1}', [CCTVController::class, 'detailCCTV'])->name('detailCCTV');
            Route::get('/liveViewAllGroups', [CCTVController::class, 'liveViewAllGroups'])->name('liveViewAllGroups');
            Route::get('/liveViewGroup/{param1}', [CCTVController::class, 'liveViewGroup'])->name('liveViewGroup');
            Route::get('/liveViewLokasi/{param1}', [CCTVController::class, 'liveViewLokasi'])->name('liveViewLokasi');
        });

        // Master Data - Pengguna
        Route::prefix('masterData')->group(function () {
            Route::get('/daftarPengguna', [MasterDataController::class, 'daftarPengguna'])->name('daftarPengguna');
            Route::any('/tambahPengguna/{param1?}', [MasterDataController::class, 'tambahPengguna'])->name('tambahPengguna');
            Route::any('/updatePengguna/{param1?}/{param2?}', [MasterDataController::class, 'updatePengguna'])->name('updatePengguna');
            Route::get('/hapusPengguna/{param1}', [MasterDataController::class, 'hapusPengguna'])->name('hapusPengguna');

            // Master Data - Hak Akses
            Route::get('/daftarHakAkses', [MasterDataController::class, 'daftarHakAkses'])->name('daftarHakAkses');
            Route::any('/tambahHakAkses/{param1?}', [MasterDataController::class, 'tambahHakAkses'])->name('tambahHakAkses');
            Route::any('/updateHakAkses/{param1?}/{param2?}', [MasterDataController::class, 'updateHakAkses'])->name('updateHakAkses');
            Route::get('/hapusHakAkses/{param1}', [MasterDataController::class, 'hapusHakAkses'])->name('hapusHakAkses');

            // Master Data - Ezviz Akun
            Route::get('/daftarEzvizAkun', [MasterDataController::class, 'daftarEzvizAkun'])->name('daftarEzvizAkun');
            Route::any('/tambahEzvizAkun/{param1?}', [MasterDataController::class, 'tambahEzvizAkun'])->name('tambahEzvizAkun');
            Route::any('/updateEzvizAkun/{param1?}/{param2?}', [MasterDataController::class, 'updateEzvizAkun'])->name('updateEzvizAkun');
            Route::get('/hapusEzvizAkun/{param1}', [MasterDataController::class, 'hapusEzvizAkun'])->name('hapusEzvizAkun');
        });

        // Pengaturan
        Route::prefix('pengaturan')->group(function () {
            Route::any('/pengaturanSistem/{param1?}', [PengaturanController::class, 'pengaturanSistem'])->name('pengaturanSistem');
            Route::get('/logAktivitas', [PengaturanController::class, 'logAktivitas'])->name('logAktivitas');
        });
    });

    // AJAX endpoints (auth only, no checkAccess — page access already verified by page load)
    Route::prefix('cctv')->group(function () {
        Route::get('/datatablesCCTV', [CCTVController::class, 'datatablesCCTV'])->name('datatablesCCTV');
        Route::post('/streamCCTV/{param1}', [CCTVController::class, 'streamCCTV'])->name('streamCCTV');
        Route::post('/captureCCTV/{param1}', [CCTVController::class, 'captureCCTV'])->name('captureCCTV');
        Route::post('/syncDevices/{param1?}', [CCTVController::class, 'syncDevices'])->name('syncDevices');
        Route::post('/refreshToken/{param1}', [CCTVController::class, 'refreshToken'])->name('refreshToken');
        Route::post('/importDevice', [CCTVController::class, 'importDevice'])->name('importDevice');
    });
    Route::prefix('masterData')->group(function () {
        Route::post('/scrapeEzvizAppKey', [MasterDataController::class, 'scrapeEzvizAppKey'])->name('scrapeEzvizAppKey');
        Route::post('/scrapeEzvizDevices', [MasterDataController::class, 'scrapeEzvizDevices'])->name('scrapeEzvizDevices');
        Route::post('/addDeviceToEzviz', [MasterDataController::class, 'addDeviceToEzviz'])->name('addDeviceToEzviz');
    });
    Route::prefix('pengaturan')->group(function () {
        Route::post('/getLogAktivitas', [PengaturanController::class, 'getLogAktivitas'])->name('getLogAktivitas');
        Route::get('/searchLogUsername', [PengaturanController::class, 'searchLogUsername'])->name('searchLogUsername');
        Route::get('/getUserInfo', [PengaturanController::class, 'getUserInfo'])->name('getUserInfo');
    });
});

// Fallback
Route::fallback([PageController::class, 'notFound']);
