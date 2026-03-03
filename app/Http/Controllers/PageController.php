<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function unauthorized()
    {
        $data = $this->getCommonData();
        $data['title'] = 'Akses Ditolak';
        return view('errors.401', ['data' => $data]);
    }

    public function notFound()
    {
        $data = $this->getCommonData();
        $data['title'] = 'Halaman Tidak Ditemukan';
        return view('errors.404', ['data' => $data]);
    }
}
