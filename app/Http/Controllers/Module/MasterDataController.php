<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Models\EzvizModel;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;

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

        $data = $this->getCommonData();
        $data['title'] = 'Daftar Pengguna';
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
                'username' => 'required|max:100|unique:cv_pengguna,username',
                'password' => 'required|min:6',
                'hak_akses' => 'required',
                'email' => 'nullable|email|unique:cv_pengguna,email',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            ], [
                'username.unique' => 'Username sudah digunakan!',
                'email.unique' => 'Email sudah digunakan!',
                'password.min' => 'Password minimal 6 karakter!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            $createData = [
                'nama_lengkap' => $request->nama_lengkap,
                'username'     => $request->username,
                'password'     => sha1($request->password),
                'email'        => $request->email,
                'no_telp'      => $request->no_telp,
                'hak_akses'    => $request->hak_akses,
                'status'       => $request->status ?? 'actived',
                'created_time' => date('Y-m-d H:i:s'),
            ];

            if ($request->hasFile('foto_profil') && $request->file('foto_profil')->isValid()) {
                $dir = public_path('assets/img/profil');
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $ext  = $request->file('foto_profil')->getClientOriginalExtension();
                $name = 'profil_' . preg_replace('/[^a-z0-9]/', '', strtolower($request->username)) . '_' . time() . '.' . $ext;
                $request->file('foto_profil')->move($dir, $name);
                $createData['foto'] = $name;
            }

            GeneralModel::create('cv_pengguna', $createData);

            LogHelper::log('Tambah Pengguna', 'MasterData', 'Tambah user: ' . $request->username);
            session()->flash('success', 'Pengguna berhasil ditambahkan!');
            return redirect()->to('/panel/masterData/daftarPengguna');
        }

        $data = $this->getCommonData();
        $data['title'] = 'Tambah Pengguna';
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

            if ($request->hasFile('foto_profil') && $request->file('foto_profil')->isValid()) {
                $dir = public_path('assets/img/profil');
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                // Delete old foto
                $oldFoto = DB::table('cv_pengguna')->where('id_pengguna', $param1)->value('foto');
                if ($oldFoto && file_exists($dir . '/' . $oldFoto)) @unlink($dir . '/' . $oldFoto);
                $ext  = $request->file('foto_profil')->getClientOriginalExtension();
                $name = 'profil_' . $param1 . '_' . time() . '.' . $ext;
                $request->file('foto_profil')->move($dir, $name);
                $updateData['foto'] = $name;
            }

            GeneralModel::updateById('cv_pengguna', $updateData, 'id_pengguna', $param1);
            LogHelper::log('Update Pengguna', 'MasterData', 'Update user ID: ' . $param1);
            session()->flash('success', 'Data pengguna berhasil diupdate!');
            return redirect()->to('/panel/masterData/daftarPengguna');
        }

        $data = $this->getCommonData();
        $data['title'] = 'Update Pengguna';
        $data['content'] = 'module.masterdata.pengguna.update';
        $data['pengguna'] = GeneralModel::getByIdGeneral('cv_pengguna', 'first', 'id_pengguna', $param1);
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

        $data = $this->getCommonData();
        $data['title'] = 'Daftar Hak Akses';
        $data['content'] = 'module.masterdata.hakAkses.data';
        $data['hakAksesList'] = DB::table('cv_hak_akses')->orderBy('nama_hak_akses')->get();
        $data['modulList'] = DB::table('cv_modul')->orderBy('class_parent_modul')->orderBy('urutan')->get();
        $data['parentModulList'] = DB::table('cv_parent_modul')->orderBy('urutan')->get();
        $data['grupList'] = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('nama_group')->get();
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
            $selectedModul = $request->input('modul', []);
            $modul_akses = json_encode(['modul' => $selectedModul]);

            // Build parent_modul_akses JSON
            $selectedParent = $request->input('parent_modul', []);
            $parent_akses = json_encode(['parent_modul' => $selectedParent]);

            // Build cctv_group_akses JSON
            $selectedGroups = $request->input('cctv_group', []);
            $cctv_group_akses = empty($selectedGroups) ? null : json_encode(array_map('intval', $selectedGroups));

            GeneralModel::create('cv_hak_akses', [
                'nama_hak_akses' => $request->nama_hak_akses,
                'deskripsi' => $request->deskripsi,
                'modul_akses' => $modul_akses,
                'parent_modul_akses' => $parent_akses,
                'cctv_group_akses' => $cctv_group_akses,
                'created_time' => date('Y-m-d H:i:s'),
            ]);

            LogHelper::log('Tambah Hak Akses', 'MasterData', 'Tambah hak akses: ' . $request->nama_hak_akses);
            session()->flash('success', 'Hak akses berhasil ditambahkan!');
            return redirect()->to('/panel/masterData/daftarHakAkses');
        }

        $data = $this->getCommonData();
        $data['title'] = 'Tambah Hak Akses';
        $data['content'] = 'module.masterdata.hakAkses.create';
        $data['modulList'] = DB::table('cv_modul')->orderBy('class_parent_modul')->orderBy('urutan')->get();
        $data['parentModulList'] = DB::table('cv_parent_modul')->orderBy('urutan')->get();
        $data['grupList'] = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('nama_group')->get();
        return view('module.content', ['data' => $data]);
    }

    public function updateHakAkses(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $selectedModul = $request->input('modul', []);
            $selectedParent = $request->input('parent_modul', []);
            $selectedGroups = $request->input('cctv_group', []);
            $cctv_group_akses = empty($selectedGroups) ? null : json_encode(array_map('intval', $selectedGroups));

            GeneralModel::updateById('cv_hak_akses', [
                'deskripsi' => $request->deskripsi,
                'modul_akses' => json_encode(['modul' => $selectedModul]),
                'parent_modul_akses' => json_encode(['parent_modul' => $selectedParent]),
                'cctv_group_akses' => $cctv_group_akses,
            ], 'id_hak_akses', $param1);

            LogHelper::log('Update Hak Akses', 'MasterData', 'Update hak akses ID: ' . $param1);
            session()->flash('success', 'Hak akses berhasil diupdate!');
            return redirect()->to('/panel/masterData/daftarHakAkses');
        }

        $data = $this->getCommonData();
        $data['title'] = 'Update Hak Akses';
        $data['content'] = 'module.masterdata.hakAkses.update';
        $data['hakAkses'] = GeneralModel::getByIdGeneral('cv_hak_akses', 'first', 'id_hak_akses', $param1);
        $data['modulList'] = DB::table('cv_modul')->orderBy('class_parent_modul')->orderBy('urutan')->get();
        $data['parentModulList'] = DB::table('cv_parent_modul')->orderBy('urutan')->get();
        $data['grupList'] = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('nama_group')->get();

        if (!$data['hakAkses']) {
            session()->flash('error', 'Hak akses tidak ditemukan!');
            return redirect()->to('/panel/masterData/daftarHakAkses');
        }
        return view('module.content', ['data' => $data]);
    }

    public function hapusHakAkses(Request $request, $param1)
    {
        $inUse = DB::table('cv_pengguna')->where(
            'hak_akses',
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
            return DataTables::of(
                DB::table('cv_ezviz_akun')
                    ->select('id_ezviz_akun', 'nama_akun', 'email_terdaftar', 'app_key', 'api_url', 'status', 'last_sync', 'token_expiry', 'created_time')
            )->make(true);
        }

        $data = $this->getCommonData();
        $data['title'] = 'Akun Ezviz';
        $data['content'] = 'module.masterdata.ezvizAkun.data';
        $data['ezvizAkunList'] = DB::table('cv_ezviz_akun')
            ->select('id_ezviz_akun', 'nama_akun', 'email_terdaftar', 'app_key', 'api_url', 'login_type', 'status', 'last_sync', 'token_expiry', 'access_token', 'created_time', 'password_console')
            ->orderBy('nama_akun')
            ->get();
        // Lokasi untuk form import device
        $data['lokasiList'] = DB::table('cv_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_lokasi.id_lokasi', 'cv_lokasi.nama_lokasi', 'cv_lokasi_group.nama_group')
            ->where('cv_lokasi.status', 'aktif')
            ->orderBy('cv_lokasi_group.nama_group')
            ->orderBy('cv_lokasi.nama_lokasi')
            ->get();
        return view('module.content', ['data' => $data]);
    }

    public function tambahEzvizAkun(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_akun' => 'required|max:100',
            ], [
                'nama_akun.required' => 'Nama akun tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            $id = GeneralModel::create('cv_ezviz_akun', [
                'nama_akun' => $request->nama_akun,
                'deskripsi' => $request->deskripsi,
                'email_terdaftar' => $request->email_terdaftar,
                'password_console' => $request->password_console ? encrypt($request->password_console) : null,
                'app_key' => $request->app_key ?: null,
                'app_secret' => $request->app_secret ?: null,
                'api_url' => $request->api_url ?? 'https://isgpopen.ezvizlife.com',
                'login_type' => in_array($request->login_type, ['ezviz', 'hikconnect']) ? $request->login_type : 'ezviz',
                'status' => $request->status ?? 'aktif',
                'created_time' => date('Y-m-d H:i:s'),
            ]);

            // Simpan token jika sudah tersedia dari scraping (dikirim via hidden field)
            $scrapedToken  = $request->scraped_access_token ?: null;
            $scrapedExpiry = $request->scraped_token_expiry ?: null;

            if ($scrapedToken && $scrapedExpiry) {
                // Token sudah ada dari scraping — langsung simpan
                DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $id)->update([
                    'access_token' => $scrapedToken,
                    'token_expiry' => $scrapedExpiry,
                    'last_sync'    => date('Y-m-d H:i:s'),
                ]);
                $msg = 'Akun Ezviz berhasil ditambahkan dan access token berhasil disimpan!';
            } elseif ($request->app_key && $request->app_secret) {
                // Fallback: ambil token via API EZVIZ
                $akun = GeneralModel::getByIdGeneral('cv_ezviz_akun', 'first', 'id_ezviz_akun', $id);
                $ezviz = new EzvizModel();
                $tokenResult = $ezviz->getAccessToken($akun);
                $msg = $tokenResult['success']
                    ? 'Akun Ezviz berhasil ditambahkan dan token berhasil diperoleh!'
                    : 'Akun Ezviz ditambahkan, namun gagal mendapatkan token.';
            } else {
                $msg = 'Akun Ezviz berhasil ditambahkan. Gunakan tombol "Ambil AppKey" untuk mengisi credentials otomatis.';
            }

            LogHelper::log('Tambah Akun Ezviz', 'MasterData', 'Tambah akun: ' . $request->nama_akun);
            session()->flash('success', $msg);
            return redirect()->to('/panel/masterData/daftarEzvizAkun');
        }

        $data = $this->getCommonData();
        $data['title'] = 'Tambah Akun Ezviz';
        $data['content'] = 'module.masterdata.ezvizAkun.create';
        return view('module.content', ['data' => $data]);
    }

    // ===========================================================
    // EZVIZ AKUN - SCRAPE APPKEY (via Flask scraper service)
    // ===========================================================
    public function scrapeEzvizAppKey(Request $request)
    {
        set_time_limit(0); // Scraping bisa makan waktu 60-90s, bebaskan limit PHP
        $email      = trim($request->input('email', ''));
        $password   = trim($request->input('password', ''));
        $loginType  = trim($request->input('login_type', 'ezviz'));

        if (!in_array($loginType, ['ezviz', 'hikconnect'])) {
            $loginType = 'ezviz';
        }

        if (!$email || !$password) {
            return response()->json(['success' => false, 'message' => 'Email dan password wajib diisi.'], 422);
        }

        $flaskUrl = config('services.ezviz_scraper_url', 'http://127.0.0.1:5055');

        try {
            $response = Http::timeout(180)->post($flaskUrl . '/scrape', [
                'email'      => $email,
                'password'   => $password,
                'login_type' => $loginType,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Flask scraper server error (HTTP ' . $response->status() . ').',
                ], 500);
            }

            $result = $response->json();

            if (!is_array($result)) {
                return response()->json(['success' => false, 'message' => 'Respons scraper tidak valid.'], 500);
            }

            if ($result['success'] ?? false) {
                LogHelper::log('Scrape AppKey Ezviz', 'MasterData', 'Scrape AppKey untuk email: ' . $email);

                // Jika scraper sudah berhasil mengambil token langsung dari portal, gunakan itu
                // Tidak perlu call API EZVIZ (menghindari masalah endpoint region/appKey tidak dikenal)
                if (!($result['accessToken'] ?? null)) {
                    // Token tidak berhasil di-scrape — coba via API sebagai fallback
                    $appKey    = $result['appKey'] ?? null;
                    $appSecret = $result['appSecret'] ?? null;

                    // Gunakan api_url dari akun yang ada di DB (jika ada), fallback ke default
                    $existingAkun = DB::table('cv_ezviz_akun')
                        ->where('email_terdaftar', $email)
                        ->first();
                    $apiUrl = $existingAkun?->api_url ?: 'https://isgpopen.ezvizlife.com';

                    if ($appKey && $appSecret) {
                        try {
                            $tokenResp = Http::timeout(15)->asForm()->post($apiUrl . '/api/lapp/token/get', [
                                'appKey'    => $appKey,
                                'appSecret' => $appSecret,
                            ]);
                            $tokenData = $tokenResp->json();
                            if (isset($tokenData['code']) && $tokenData['code'] == '200') {
                                $data = $tokenData['data'] ?? [];
                                $token = $data['accessToken'] ?? $data['access_token'] ?? $data['token'] ?? null;
                                $expireMs = $data['expireTime'] ?? $data['expire_time'] ?? 0;
                                $result['accessToken'] = $token;
                                $result['tokenExpiry']  = $expireMs ? date('Y-m-d H:i:s', intval($expireMs / 1000)) : null;
                            } else {
                                $result['tokenError'] = ($tokenData['msg'] ?? $tokenData['message'] ?? 'Gagal ambil token dari API EZVIZ')
                                    . ' [code=' . ($tokenData['code'] ?? '?') . ']';
                            }
                            $result['_tokenApiRaw'] = $tokenData;
                        } catch (\Exception $te) {
                            $result['tokenError'] = 'Error API token: ' . $te->getMessage();
                        }
                    }
                }
            }

            return response()->json($result);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke Flask scraper service (http://127.0.0.1:5055). '
                           . 'Pastikan service sudah berjalan.',
            ], 503);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateEzvizAkun(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $updateData = [
                'nama_akun' => $request->nama_akun,
                'deskripsi' => $request->deskripsi,
                'email_terdaftar' => $request->email_terdaftar,
                'app_key' => $request->app_key ?: null,
                'api_url' => $request->api_url ?? 'https://isgpopen.ezvizlife.com',
                'login_type' => in_array($request->login_type, ['ezviz', 'hikconnect']) ? $request->login_type : 'ezviz',
                'status' => $request->status ?? 'aktif',
            ];

            // Update password_console hanya jika diisi
            if (!empty($request->password_console)) {
                $updateData['password_console'] = encrypt($request->password_console);
            }

            if (!empty($request->app_secret)) {
                $updateData['app_secret'] = $request->app_secret;
            }

            // Jika ada token yang di-scrape langsung dari portal, simpan sekarang
            $scrapedToken  = trim($request->input('scraped_access_token', ''));
            $scrapedExpiry = trim($request->input('scraped_token_expiry', ''));
            if ($scrapedToken) {
                $updateData['access_token'] = $scrapedToken;
                $updateData['token_expiry']  = $scrapedExpiry ?: null;
            } elseif (!empty($request->app_secret)) {
                // Secret baru tapi tidak ada scraped token — invalidate token lama
                $updateData['access_token'] = null;
                $updateData['token_expiry']  = null;
            }

            GeneralModel::updateById('cv_ezviz_akun', $updateData, 'id_ezviz_akun', $param1);
            LogHelper::log('Update Akun Ezviz', 'MasterData', 'Update akun ID: ' . $param1);

            // Coba ambil token via API hanya jika belum ada token dari scraping
            if (!$scrapedToken) {
                $akun = GeneralModel::getByIdGeneral('cv_ezviz_akun', 'first', 'id_ezviz_akun', $param1);
                if ($akun && $akun->app_key && $akun->app_secret) {
                    $ezviz = new EzvizModel();
                    $tokenResult = $ezviz->getAccessToken($akun);
                    $msg = $tokenResult['success']
                        ? 'Akun Ezviz berhasil diupdate dan access token berhasil diperbarui!'
                        : 'Akun Ezviz diupdate. Token: ' . ($tokenResult['message'] ?? 'gagal diperbarui');
                } else {
                    $msg = 'Akun Ezviz berhasil diupdate!';
                }
            } else {
                $msg = 'Akun Ezviz berhasil diupdate dengan access token baru!';
            }

            session()->flash('success', $msg);
            return redirect()->to('/panel/masterData/daftarEzvizAkun');
        }

        $data = $this->getCommonData();
        $data['title'] = 'Update Akun Ezviz';
        $data['content'] = 'module.masterdata.ezvizAkun.update';
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

    // ===========================================================
    // EZVIZ AKUN - SCRAPE DEVICE LIST (via Flask scraper service)
    // ===========================================================
    public function scrapeEzvizDevices(Request $request)
    {
        set_time_limit(0); // Scraping bisa makan waktu 60-90s, bebaskan limit PHP

        $idAkun = $request->input('id_ezviz_akun');

        if (!$idAkun) {
            return response()->json(['success' => false, 'message' => 'id_ezviz_akun wajib diisi.'], 422);
        }

        $akun = DB::table('cv_ezviz_akun')->where('id_ezviz_akun', $idAkun)->first();
        if (!$akun) {
            return response()->json(['success' => false, 'message' => 'Akun EZVIZ tidak ditemukan.'], 404);
        }

        $email = $akun->email_terdaftar;
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email akun EZVIZ belum diisi.'], 422);
        }

        if (!$akun->password_console) {
            return response()->json(['success' => false, 'message' => 'Password console belum disimpan untuk akun ini.'], 422);
        }

        try {
            $password = decrypt($akun->password_console);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mendekripsi password. Coba simpan ulang akun.'], 500);
        }

        $loginType = $akun->login_type ?? 'ezviz';

        $flaskUrl = config('services.ezviz_scraper_url', 'http://127.0.0.1:5055');

        try {
            $response = Http::timeout(180)->post($flaskUrl . '/scrape-devices', [
                'email'      => $email,
                'password'   => $password,
                'login_type' => $loginType,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Flask scraper server error (HTTP ' . $response->status() . ').',
                ], 500);
            }

            $result = $response->json();

            // Tandai device yang sudah ada di sistem (cek serial + channel_no untuk NVR)
            if (!empty($result['devices'])) {
                $existingDevices = DB::table('cv_cctv')
                    ->where('id_ezviz_akun', $idAkun)
                    ->select('device_serial', 'channel_no')
                    ->get()
                    ->map(fn($d) => strtoupper($d->device_serial) . '-' . intval($d->channel_no))
                    ->toArray();

                $result['devices'] = array_map(function ($device) use ($existingDevices) {
                    $key = strtoupper($device['serial']) . '-' . intval($device['channel_no'] ?? 1);
                    $device['already_added'] = in_array($key, $existingDevices);
                    return $device;
                }, $result['devices']);
            }

            LogHelper::log('Scrape Device EZVIZ', 'MasterData', 'Scrape device list akun: ' . $akun->nama_akun);

            return response()->json($result);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke Flask scraper service. Pastikan service sudah berjalan.',
            ], 503);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ===========================================================
    // EZVIZ - Tambah device ke akun EZVIZ Open Platform
    // ===========================================================
    public function addDeviceToEzviz(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'id_ezviz_akun' => 'required|integer',
            'device_serial' => 'required|string|min:6|max:20',
            'device_code'   => 'required|string|min:1|max:20',
        ], [
            'id_ezviz_akun.required' => 'Akun EZVIZ wajib dipilih.',
            'device_serial.required' => 'Serial number wajib diisi.',
            'device_code.required'   => 'Verification code wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $result = $this->ezviz->addDeviceToAccount(
            $request->id_ezviz_akun,
            $request->device_serial,
            $request->device_code
        );

        if ($result['success']) {
            LogHelper::log('Add Device ke EZVIZ', 'MasterData',
                'Tambah device serial: ' . $request->device_serial . ' ke akun ID: ' . $request->id_ezviz_akun);
        }

        return response()->json($result);
    }
}
