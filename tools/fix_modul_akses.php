<?php
// Fix missing modules in modul_akses for all roles
// Run: php tools/fix_modul_akses.php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Aturan: jika role punya modul X, tambahkan modul Y secara otomatis
$addIfPresent = [
    // Scraping EZVIZ portal: ikut akses daftarEzvizAkun
    'daftarEzvizAkun'  => ['scrapeEzvizAppKey', 'scrapeEzvizDevices'],
    // Import device: ikut akses tambahCCTV
    'tambahCCTV'       => ['importDevice', 'syncDevices', 'captureCCTV', 'refreshToken'],
    // Detail pages
    'daftarGrupLokasi' => ['detailGrupLokasi'],
    'daftarLokasi'     => ['detailLokasi'],
];

$roles = DB::table('cv_hak_akses')->get();
foreach ($roles as $role) {
    $ma = json_decode($role->modul_akses, true);
    if (!$ma || !isset($ma['modul'])) continue;

    $moduls  = $ma['modul'];
    $changed = false;

    foreach ($addIfPresent as $trigger => $toAdd) {
        if (in_array($trigger, $moduls)) {
            foreach ($toAdd as $newModul) {
                if (!in_array($newModul, $moduls)) {
                    $moduls[] = $newModul;
                    $changed   = true;
                }
            }
        }
    }

    if ($changed) {
        DB::table('cv_hak_akses')->where('id_hak_akses', $role->id_hak_akses)
            ->update(['modul_akses' => json_encode(['modul' => $moduls])]);
        echo "Updated: {$role->nama_hak_akses}\n";
    } else {
        echo "No change: {$role->nama_hak_akses}\n";
    }
}
echo "Done.\n";

echo "Done\n";
