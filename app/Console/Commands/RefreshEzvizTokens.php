<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\EzvizModel;
use App\Helpers\LogHelper;

class RefreshEzvizTokens extends Command
{
    protected $signature   = 'ezviz:refresh-tokens
                                {--force : Refresh semua akun meskipun token masih valid}
                                {--hours=24 : Refresh jika token akan kadaluwarsa dalam X jam}';

    protected $description = 'Perbarui access token EZVIZ untuk semua akun aktif yang mendekati kadaluwarsa';

    public function handle(): int
    {
        $force = $this->option('force');
        $hours = (int) $this->option('hours');

        $this->info("=== EZVIZ Token Refresh ===");
        $this->info("Mode  : " . ($force ? 'Force (semua akun)' : "Akun yang expired atau < {$hours} jam lagi"));
        $this->newLine();

        $query = DB::table('cv_ezviz_akun')
            ->where('status', 'aktif')
            ->whereNotNull('app_key')
            ->whereNotNull('app_secret');

        // Filter yang perlu di-refresh (kecuali --force)
        if (!$force) {
            $threshold = now()->addHours($hours)->format('Y-m-d H:i:s');
            $query->where(function ($q) use ($threshold) {
                $q->whereNull('token_expiry')
                  ->orWhere('token_expiry', '<=', $threshold);
            });
        }

        $accounts = $query->get();

        if ($accounts->isEmpty()) {
            $this->info("✓ Tidak ada akun yang perlu diperbarui.");
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$accounts->count()} akun yang perlu diperbarui:");
        $this->newLine();

        $ezviz     = new EzvizModel();
        $success   = 0;
        $failed    = 0;
        $rows      = [];

        foreach ($accounts as $akun) {
            $this->line("  → [{$akun->nama_akun}] {$akun->email_terdaftar} ...");

            $result = $ezviz->getAccessToken($akun);

            if ($result['success']) {
                $success++;
                $rows[] = [
                    $akun->nama_akun,
                    '✓ Berhasil',
                    $result['expiry'] ?? '-',
                ];
                LogHelper::log(
                    'Auto Refresh Token Ezviz',
                    'Scheduler',
                    "Token diperbarui untuk akun: {$akun->nama_akun}"
                );
            } else {
                $failed++;
                $rows[] = [
                    $akun->nama_akun,
                    '✗ Gagal: ' . ($result['message'] ?? 'Error'),
                    '-',
                ];
                $this->warn("    Gagal: " . ($result['message'] ?? 'Error'));
            }
        }

        $this->newLine();
        $this->table(['Akun', 'Status', 'Token Berlaku Hingga'], $rows);
        $this->newLine();
        $this->info("Selesai: {$success} berhasil, {$failed} gagal.");

        return $failed === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
