<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PengaturanController extends Controller
{
    public function pengaturanSistem(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            GeneralModel::updateById('cv_identitas', [
                'apps_name'  => $request->apps_name,
                'apps_version' => $request->apps_version,
                'agency'     => $request->agency,
                'address'    => $request->address,
                'city'       => $request->city,
                'telephon'   => $request->telephon,
                'email'      => $request->email,
                'website'    => $request->website,
                'footer'     => $request->footer,
            ], 'id_profile', 1);

            LogHelper::log('Update Pengaturan Sistem', 'Pengaturan');
            session()->flash('success', 'Pengaturan sistem berhasil disimpan!');
            return redirect()->to('/panel/pengaturan/pengaturanSistem');
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Pengaturan Sistem';
        $data['content'] = 'module.pengaturan.data';
        return view('module.content', ['data' => $data]);
    }

    public function logAktivitas(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::table('cv_log_aktivitas')->orderBy('created_time', 'desc'))->make(true);
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Log Aktivitas';
        $data['content'] = 'module.pengaturan.logAktivitas';
        return view('module.content', ['data' => $data]);
    }

    public function getLogAktivitas(Request $request)
    {
        return DataTables::of(DB::table('cv_log_aktivitas')->orderBy('created_time', 'desc'))->make(true);
    }
}
