<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Models\EzvizModel;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MasterDataController extends Controller
{
    // ===========================================================
    // PENGGUNA
    // ===========================================================
    public function daftarPengguna(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::table('cv_pengguna')->select('id_pengguna', 'nama_lengkap', 'username', 'email', 'hak_akses', 'status', 'last_login', 'activity_status', 'created_time'))
                ->make(true);
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Daftar Pengguna';
        $data['content'] = 'module.masterdata.pengguna.data';
        $data['penggunaList'] = DB::table('cv_pengguna')
            ->select('id_pengguna', 'nama_lengkap', 'username', 'email', 'hak_akses', 'status', 'last_login', 'created_time')
            ->orderBy('nama_lengkap')
            ->get();
        return view('module.content', ['data' => $data]);
    }

    public function tambahPengguna(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_lengkap' => 'required|max:255',
                'username'     => 'required|max:100|unique:cv_pengguna,username',
                'password'     => 'required|min:6',
                'hak_akses'    => 'required',
                'email'        => 'nullable|email|unique:cv_pengguna,email',
            ], [
                'username.unique'  => 'Username sudah digunakan!',
                'email.unique'     => 'Email sudah digunakan!',
                'password.min'     => 'Password minimal 6 karakter!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            GeneralModel::create('cv_pengguna', [
                'nama_lengkap' => $request->nama_lengkap,
                'username'     => $request->username,
                'password'     => sha1($request->password),
                'email'        => $request->email,
                'no_telp'      => $request->no_telp,
                'hak_akses'    => $request->hak_akses,
                'status'       => $request->status ?? 'actived',
                'created_time' => date('Y-m-d H:i:s'),
            ]);

            LogHelper::log('Tambah Pengguna', 'MasterData', 'Tambah user: ' . $request->username);
            session()->flash('success', 'Pengguna berhasil ditambahkan!');
            return redirect()->to('/panel/masterData/daftarPengguna');
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Tambah Pengguna';
        $data['content'] = 'module.masterdata.pengguna.create';
        $data['hakAksesList'] = DB::table('cv_hak_akses')->orderBy('nama_hak_akses')->get();
        return view('module.content', ['data' => $data]);
    }

    public function updatePengguna(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'email'        => $request->email,
                'no_telp'      => $request->no_telp,
                'hak_akses'    => $request->hak_akses,
                'status'       => $request->status ?? 'actived',
            ];

            if (!empty($request->password)) {
                $updateData['password'] = sha1($request->password);
            }

            GeneralModel::updateById('cv_pengguna', $updateData, 'id_pengguna', $param1);
            LogHelper::log('Update Pengguna', 'MasterData', 'Update user ID: ' . $param1);
            session()->flash('success', 'Data pengguna berhasil diupdate!');
            return redirect()->to('/panel/masterData/daftarPengguna');
        }

        $data              = $this->getCommonData();
        $data['title']     = 'Update Pengguna';
        $data['content']   = 'module.masterdata.pengguna.update';
        $data['pengguna']  = GeneralModel::getByIdGeneral('cv_pengguna', 'first', 'id_pengguna', $param1);
        $data['hakAksesList'] = DB::table('cv_hak_akses')->orderBy('nama_hak_akses')->get();

        if (!$data['pengguna']) {
            session()->flash('error', 'Pengguna tidak ditemukan!');
            return redirect()->to('/panel/masterData/daftarPengguna');
        }
        return view('module.content', ['data' => $data]);
    }

    public function hapusPengguna(Request $request, $param1)
    {
        $currentUser = session()->get('user');
        if ($currentUser && ($currentUser['id_pengguna'] ?? null) == $param1) {
            session()->flash('error', 'Tidak bisa menghapus akun sendiri!');
            return redirect()->back();
        }

        GeneralModel::deleteById('cv_pengguna', 'id_pengguna', $param1);
        LogHelper::log('Hapus Pengguna', 'MasterData', 'Hapus user ID: ' . $param1);
        session()->flash('success', 'Pengguna berhasil dihapus!');
        return redirect()->to('/panel/masterData/daftarPengguna');
    }

    // ===========================================================
    // HAK AKSES
    // ===========================================================
    public function daftarHakAkses(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::table('cv_hak_akses'))->make(true);
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Daftar Hak Akses';
        $data['content'] = 'module.masterdata.hakAkses.data';
        $data['hakAksesList']   = DB::table('cv_hak_akses')->orderBy('nama_hak_akses')->get();
        $data['modulList']      = DB::table('cv_modul')->orderBy('class_parent_modul')->orderBy('urutan')->get();
        $data['parentModulList'] = DB::table('cv_parent_modul')->orderBy('urutan')->get();
        $data['grupList']       = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('nama_group')->get();
        return view('module.content', ['data' => $data]);
    }

    public function tambahHakAkses(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_hak_akses' => 'required|max:225|unique:cv_hak_akses,nama_hak_akses',
            ], ['nama_hak_akses.unique' => 'Nama hak akses sudah ada!']);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            // Build modul_akses JSON
            $selectedModul  = $request->input('modul', []);
            $modul_akses    = json_encode(['modul' => $selectedModul]);

            // Build parent_modul_akses JSON
            $selectedParent = $request->input('parent_modul', []);
            $parent_akses   = json_encode(['parent_modul' => $selectedParent]);

            // Build cctv_group_akses JSON
            $selectedGroups  = $request->input('cctv_group', []);
            $cctv_group_akses = empty($selectedGroups) ? null : json_encode(array_map('intval', $selectedGroups));

            GeneralModel::create('cv_hak_akses', [
                'nama_hak_akses'   => $request->nama_hak_akses,
                'deskripsi'        => $request->deskripsi,
                'modul_akses'      => $modul_akses,
                'parent_modul_akses' => $parent_akses,
                'cctv_group_akses' => $cctv_group_akses,
                'created_time'     => date('Y-m-d H:i:s'),
            ]);

            LogHelper::log('Tambah Hak Akses', 'MasterData', 'Tambah hak akses: ' . $request->nama_hak_akses);
            session()->flash('success', 'Hak akses berhasil ditambahkan!');
            return redirect()->to('/panel/masterData/daftarHakAkses');
        }

        $data                   = $this->getCommonData();
        $data['title']          = 'Tambah Hak Akses';
        $data['content']        = 'module.masterdata.hakAkses.create';
        $data['modulList']      = DB::table('cv_modul')->orderBy('class_parent_modul')->orderBy('urutan')->get();
        $data['parentModulList'] = DB::table('cv_parent_modul')->orderBy('urutan')->get();
        $data['grupList']       = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('nama_group')->get();
        return view('module.content', ['data' => $data]);
    }

    public function updateHakAkses(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $selectedModul    = $request->input('modul', []);
            $selectedParent   = $request->input('parent_modul', []);
            $selectedGroups   = $request->input('cctv_group', []);
            $cctv_group_akses = empty($selectedGroups) ? null : json_encode(array_map('intval', $selectedGroups));

            GeneralModel::updateById('cv_hak_akses', [
                'deskripsi'          => $request->deskripsi,
                'modul_akses'        => json_encode(['modul' => $selectedModul]),
                'parent_modul_akses' => json_encode(['parent_modul' => $selectedParent]),
                'cctv_group_akses'   => $cctv_group_akses,
            ], 'id_hak_akses', $param1);

            LogHelper::log('Update Hak Akses', 'MasterData', 'Update hak akses ID: ' . $param1);
            session()->flash('success', 'Hak akses berhasil diupdate!');
            return redirect()->to('/panel/masterData/daftarHakAkses');
        }

        $data                   = $this->getCommonData();
        $data['title']          = 'Update Hak Akses';
        $data['content']        = 'module.masterdata.hakAkses.update';
        $data['hakAkses']       = GeneralModel::getByIdGeneral('cv_hak_akses', 'first', 'id_hak_akses', $param1);
        $data['modulList']      = DB::table('cv_modul')->orderBy('class_parent_modul')->orderBy('urutan')->get();
        $data['parentModulList'] = DB::table('cv_parent_modul')->orderBy('urutan')->get();
        $data['grupList']       = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('nama_group')->get();

        if (!$data['hakAkses']) {
            session()->flash('error', 'Hak akses tidak ditemukan!');
            return redirect()->to('/panel/masterData/daftarHakAkses');
        }
        return view('module.content', ['data' => $data]);
    }

    public function hapusHakAkses(Request $request, $param1)
    {
        $inUse = DB::table('cv_pengguna')->where('hak_akses',
            DB::table('cv_hak_akses')->where('id_hak_akses', $param1)->value('nama_hak_akses')
        )->count();

        if ($inUse > 0) {
            session()->flash('error', 'Hak akses tidak bisa dihapus karena masih digunakan oleh pengguna!');
            return redirect()->back();
        }

        GeneralModel::deleteById('cv_hak_akses', 'id_hak_akses', $param1);
        LogHelper::log('Hapus Hak Akses', 'MasterData', 'Hapus hak akses ID: ' . $param1);
        session()->flash('success', 'Hak akses berhasil dihapus!');
        return redirect()->to('/panel/masterData/daftarHakAkses');
    }

    // ===========================================================
    // EZVIZ AKUN
    // ===========================================================
    public function daftarEzvizAkun(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(DB::table('cv_ezviz_akun')
                ->select('id_ezviz_akun', 'nama_akun', 'email_terdaftar', 'app_key', 'api_url', 'status', 'last_sync', 'token_expiry', 'created_time')
            )->make(true);
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Akun Ezviz';
        $data['content'] = 'module.masterdata.ezvizAkun.data';
        $data['ezvizAkunList'] = DB::table('cv_ezviz_akun')
            ->select('id_ezviz_akun', 'nama_akun', 'email_terdaftar', 'app_key', 'api_url', 'status', 'last_sync', 'token_expiry', 'access_token', 'created_time')
            ->orderBy('nama_akun')
            ->get();
        return view('module.content', ['data' => $data]);
    }

    public function tambahEzvizAkun(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_akun'  => 'required|max:100',
                'app_key'    => 'required|max:100',
                'app_secret' => 'required|max:255',
            ], [
                'nama_akun.required'  => 'Nama akun tidak boleh kosong!',
                'app_key.required'    => 'App Key tidak boleh kosong!',
                'app_secret.required' => 'App Secret tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            $id = GeneralModel::create('cv_ezviz_akun', [
                'nama_akun'       => $request->nama_akun,
                'deskripsi'       => $request->deskripsi,
                'email_terdaftar' => $request->email_terdaftar,
                'app_key'         => $request->app_key,
                'app_secret'      => $request->app_secret,
                'api_url'         => $request->api_url ?? 'https://open.ys7.com',
                'status'          => $request->status ?? 'aktif',
                'created_time'    => date('Y-m-d H:i:s'),
            ]);

            // Try to get token immediately
            $akun = GeneralModel::getByIdGeneral('cv_ezviz_akun', 'first', 'id_ezviz_akun', $id);
            $ezviz = new EzvizModel();
            $tokenResult = $ezviz->getAccessToken($akun);

            LogHelper::log('Tambah Akun Ezviz', 'MasterData', 'Tambah akun: ' . $request->nama_akun);

            if ($tokenResult['success']) {
                session()->flash('success', 'Akun Ezviz berhasil ditambahkan dan token berhasil diperoleh!');
            } else {
                session()->flash('warning', 'Akun Ezviz ditambahkan, namun gagal mendapatkan token. Periksa App Key & App Secret.');
            }
            return redirect()->to('/panel/masterData/daftarEzvizAkun');
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Tambah Akun Ezviz';
        $data['content'] = 'module.masterdata.ezvizAkun.create';
        return view('module.content', ['data' => $data]);
    }

    public function updateEzvizAkun(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $updateData = [
                'nama_akun'       => $request->nama_akun,
                'deskripsi'       => $request->deskripsi,
                'email_terdaftar' => $request->email_terdaftar,
                'app_key'         => $request->app_key,
                'api_url'         => $request->api_url ?? 'https://open.ys7.com',
                'status'          => $request->status ?? 'aktif',
            ];

            if (!empty($request->app_secret)) {
                $updateData['app_secret'] = $request->app_secret;
                // Invalidate token since secret changed
                $updateData['access_token'] = null;
                $updateData['token_expiry']  = null;
            }

            GeneralModel::updateById('cv_ezviz_akun', $updateData, 'id_ezviz_akun', $param1);
            LogHelper::log('Update Akun Ezviz', 'MasterData', 'Update akun ID: ' . $param1);
            session()->flash('success', 'Akun Ezviz berhasil diupdate!');
            return redirect()->to('/panel/masterData/daftarEzvizAkun');
        }

        $data             = $this->getCommonData();
        $data['title']    = 'Update Akun Ezviz';
        $data['content']  = 'module.masterdata.ezvizAkun.update';
        $data['ezvizAkun'] = GeneralModel::getByIdGeneral('cv_ezviz_akun', 'first', 'id_ezviz_akun', $param1);

        if (!$data['ezvizAkun']) {
            session()->flash('error', 'Akun Ezviz tidak ditemukan!');
            return redirect()->to('/panel/masterData/daftarEzvizAkun');
        }
        return view('module.content', ['data' => $data]);
    }

    public function hapusEzvizAkun(Request $request, $param1)
    {
        $inUse = DB::table('cv_cctv')->where('id_ezviz_akun', $param1)->count();
        if ($inUse > 0) {
            session()->flash('error', 'Akun Ezviz tidak bisa dihapus karena masih digunakan oleh CCTV!');
            return redirect()->back();
        }

        GeneralModel::deleteById('cv_ezviz_akun', 'id_ezviz_akun', $param1);
        LogHelper::log('Hapus Akun Ezviz', 'MasterData', 'Hapus akun ezviz ID: ' . $param1);
        session()->flash('success', 'Akun Ezviz berhasil dihapus!');
        return redirect()->to('/panel/masterData/daftarEzvizAkun');
    }
}
