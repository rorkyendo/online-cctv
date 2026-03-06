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
        $data                = $this->getCommonData();
        $data['title']       = 'Log Aktivitas';
        $data['content']     = 'module.pengaturan.logAktivitas';
        $user                = session()->get('user');
        $data['isSuperuser'] = isset($user['hak_akses']) && $user['hak_akses'] === 'superuser';
        return view('module.content', ['data' => $data]);
    }

    public function searchLogUsername(Request $request)
    {
        $user = session()->get('user');
        if (!isset($user['hak_akses']) || $user['hak_akses'] !== 'superuser') {
            return response()->json(['results' => []]);
        }

        $term  = $request->input('q', '');
        $page  = max(1, (int) $request->input('page', 1));
        $limit = 20;

        $baseQuery = DB::table('cv_log_aktivitas')
            ->when($term !== '', fn($q) => $q->where('username', 'like', '%' . $term . '%'));

        $total   = $baseQuery->distinct()->count('username');
        $results = (clone $baseQuery)
            ->select('username')
            ->distinct()
            ->orderBy('username')
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->pluck('username');

        return response()->json([
            'results'    => $results->map(fn($u) => ['id' => $u, 'text' => $u])->values(),
            'pagination' => ['more' => ($page * $limit) < $total],
        ]);
    }

    public function getUserInfo(Request $request)
    {
        $user = session()->get('user');
        if (!isset($user['hak_akses']) || $user['hak_akses'] !== 'superuser') {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $username = $request->input('username', '');
        $info = DB::table('cv_pengguna')
            ->where('username', $username)
            ->select('id_pengguna','nama_lengkap','username','email','no_telp','hak_akses','foto','status','last_login','last_logout','activity_status','created_time')
            ->first();

        if (!$info) {
            return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
        }

        return response()->json($info);
    }

    public function getLogAktivitas(Request $request)
    {
        $user        = session()->get('user');
        $isSuperuser = isset($user['hak_akses']) && $user['hak_akses'] === 'superuser';

        $query = DB::table('cv_log_aktivitas');

        if ($isSuperuser) {
            // Superuser: filter by username if provided
            if ($request->filled('filter_username')) {
                $query->where('username', $request->filter_username);
            }
        } else {
            // Non-superuser: only own logs
            $query->where('username', $user['username'] ?? '');
        }

        $query->orderBy('created_time', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
