<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class LogHelper
{
    /**
     * Log user activity to cv_log_aktivitas.
     *
     * @param string $aksi  Action description
     * @param string $modul Module name
     * @param string|null $detail Additional details
     */
    public static function log($aksi, $modul = '', $detail = null)
    {
        try {
            $user = session()->get('user');
            DB::table('cv_log_aktivitas')->insert([
                'id_pengguna' => $user ? ($user['id_pengguna'] ?? null) : null,
                'username'    => $user ? ($user['username'] ?? 'guest') : 'guest',
                'aksi'        => $aksi,
                'modul'       => $modul,
                'detail'      => $detail,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
                'created_time' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            // Silent fail - don't break app for logging errors
        }
    }
}
