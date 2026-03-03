<?php
// Fix missing detailGrupLokasi and detailLokasi in modul_akses for all roles

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$roles = DB::table('cv_hak_akses')->get();
foreach ($roles as $role) {
    $ma = json_decode($role->modul_akses, true);
    if ($ma && isset($ma['modul'])) {
        $moduls = $ma['modul'];
        $changed = false;
        if (in_array('daftarGrupLokasi', $moduls) && !in_array('detailGrupLokasi', $moduls)) {
            $moduls[] = 'detailGrupLokasi';
            $changed = true;
        }
        if (in_array('daftarLokasi', $moduls) && !in_array('detailLokasi', $moduls)) {
            $moduls[] = 'detailLokasi';
            $changed = true;
        }
        if ($changed) {
            DB::table('cv_hak_akses')->where('id_hak_akses', $role->id_hak_akses)
                ->update(['modul_akses' => json_encode(['modul' => $moduls])]);
            echo "Updated: {$role->nama_hak_akses}\n";
        }
    }
}
echo "Done\n";
