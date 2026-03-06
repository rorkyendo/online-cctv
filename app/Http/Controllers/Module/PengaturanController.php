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
            $fields = [
                'apps_name'    => $request->apps_name,
                'apps_version' => $request->apps_version,
                'agency'       => $request->agency,
                'address'      => $request->address,
                'city'         => $request->city,
                'telephon'     => $request->telephon,
                'email'        => $request->email,
                'website'      => $request->website,
                'footer'       => $request->footer,
            ];

            $uploadDir = public_path('assets/media/logos');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            if ($request->hasFile('logo_file') && $request->file('logo_file')->isValid()) {
                $file = $request->file('logo_file');
                $ext  = $file->getClientOriginalExtension();
                $name = 'logo_' . time() . '.' . $ext;
                $file->move($uploadDir, $name);
                $fields['logo'] = 'assets/media/logos/' . $name;
            } elseif ($request->filled('logo')) {
                $fields['logo'] = $request->logo;
            }

            if ($request->hasFile('icon_file') && $request->file('icon_file')->isValid()) {
                $file = $request->file('icon_file');
                $ext  = $file->getClientOriginalExtension();
                $name = 'icon_' . time() . '.' . $ext;
                $file->move($uploadDir, $name);
                $fields['icon'] = 'assets/media/logos/' . $name;
            } elseif ($request->filled('icon')) {
                $fields['icon'] = $request->icon;
            }

            GeneralModel::updateById('cv_identitas', $fields, 'id_profile', 1);

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
        $data            = $this->getCommonData();
        $data['title']   = 'Log Aktivitas';
        $data['content'] = 'module.pengaturan.logAktivitas';
        return view('module.content', ['data' => $data]);
    }

    public function getLogAktivitas(Request $request)
    {
        return DataTables::of(DB::table('cv_log_aktivitas')->orderBy('created_time', 'desc'))
            ->addIndexColumn()
            ->make(true);
    }
}
