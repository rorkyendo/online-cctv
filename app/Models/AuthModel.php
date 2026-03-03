<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class AuthModel
{
    public function getPengguna($username, $password)
    {
        return DB::table('cv_pengguna')
            ->where('username', $username)
            ->where('password', $password)
            ->where('status', 'actived')
            ->first();
    }

    public function getHakAkses($namaHakAkses)
    {
        return DB::table('cv_hak_akses')
            ->where('nama_hak_akses', $namaHakAkses)
            ->first();
    }
}
