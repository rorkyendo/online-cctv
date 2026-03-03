<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Models\EzvizModel;
use App\Helpers\LogHelper;
use App\Helpers\AccessHelper;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CCTVController extends Controller
{
    protected EzvizModel $ezviz;

    public function __construct()
    {
        $this->ezviz = new EzvizModel();
    }

    // -------------------------------------------------------
    // Daftar CCTV
    // -------------------------------------------------------
    public function daftarCCTV(Request $request)
    {
        if ($request->ajax()) {
            $allowedGroupIds = AccessHelper::getAllowedGroupIds();

            $query = DB::table('cv_cctv')
                ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
                ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
                ->join('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
                ->select(
                    'cv_cctv.*',
                    'cv_lokasi.nama_lokasi',
                    'cv_lokasi_group.nama_group',
                    'cv_ezviz_akun.nama_akun as nama_ezviz'
                );

            if ($allowedGroupIds !== null) {
                $query->whereIn('cv_lokasi_group.id_group', $allowedGroupIds);
            }

            return DataTables::of($query)->make(true);
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Daftar CCTV';
        $data['content'] = 'module.cctv.data';

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $groupQuery = DB::table('cv_lokasi_group')->where('status', 'aktif');
        if ($allowedGroupIds !== null) $groupQuery->whereIn('id_group', $allowedGroupIds);
        $data['grupList'] = $groupQuery->orderBy('urutan')->get();

        // CCTV list
        $cctvQuery = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->leftJoin('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
            ->select(
                'cv_cctv.*',
                'cv_lokasi.nama_lokasi',
                'cv_lokasi_group.nama_group',
                'cv_ezviz_akun.nama_akun as nama_akun'
            )
            ->orderBy('cv_lokasi_group.urutan')
            ->orderBy('cv_cctv.nama_cctv');
        if ($allowedGroupIds !== null) {
            $cctvQuery->whereIn('cv_lokasi_group.id_group', $allowedGroupIds);
        }
        $data['cctvList'] = $cctvQuery->get();

        $data['ezvizAkunList'] = DB::table('cv_ezviz_akun')
            ->where('status', 'aktif')
            ->orderBy('nama_akun')
            ->get();

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Tambah CCTV
    // -------------------------------------------------------
    public function tambahCCTV(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'id_lokasi'      => 'required|integer',
                'id_ezviz_akun'  => 'required|integer',
                'nama_cctv'      => 'required|max:150',
                'device_serial'  => 'required|max:100',
                'channel_no'     => 'required|integer|min:1',
            ], [
                'id_lokasi.required'     => 'Lokasi wajib dipilih!',
                'id_ezviz_akun.required' => 'Akun Ezviz wajib dipilih!',
                'nama_cctv.required'     => 'Nama CCTV tidak boleh kosong!',
                'device_serial.required' => 'Device serial tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            // Validate location group access
            $lokasi = GeneralModel::getByIdGeneral('cv_lokasi', 'first', 'id_lokasi', $request->id_lokasi);
            if ($lokasi && !AccessHelper::cekCctvGroupAkses($lokasi->id_group)) {
                session()->flash('error', 'Anda tidak memiliki akses ke lokasi ini!');
                return redirect()->back()->withInput();
            }

            GeneralModel::create('cv_cctv', [
                'id_lokasi'      => $request->id_lokasi,
                'id_ezviz_akun'  => $request->id_ezviz_akun,
                'nama_cctv'      => $request->nama_cctv,
                'device_serial'  => $request->device_serial,
                'channel_no'     => $request->channel_no,
                'stream_type'    => $request->stream_type ?? 1,
                'validCode'      => $request->validCode,
                'deskripsi'      => $request->deskripsi,
                'posisi'         => $request->posisi,
                'status'         => 'offline',
                'created_time'   => date('Y-m-d H:i:s'),
            ]);

            LogHelper::log('Tambah CCTV', 'CCTV', 'Tambah CCTV: ' . $request->nama_cctv . ' [' . $request->device_serial . ']');
            session()->flash('success', 'CCTV berhasil ditambahkan!');
            return redirect()->to('/panel/cctv/daftarCCTV');
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Tambah CCTV';
        $data['content'] = 'module.cctv.create';

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $lokasiQuery = DB::table('cv_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_lokasi.*', 'cv_lokasi_group.nama_group')
            ->where('cv_lokasi.status', 'aktif');
        if ($allowedGroupIds !== null) $lokasiQuery->whereIn('cv_lokasi.id_group', $allowedGroupIds);
        $data['lokasiList'] = $lokasiQuery->orderBy('cv_lokasi_group.nama_group')->orderBy('cv_lokasi.nama_lokasi')->get();
        $data['ezvizList']  = DB::table('cv_ezviz_akun')->where('status', 'aktif')->get();

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Update CCTV
    // -------------------------------------------------------
    public function updateCCTV(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_cctv'     => 'required|max:150',
                'device_serial' => 'required|max:100',
                'channel_no'    => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            GeneralModel::updateById('cv_cctv', [
                'id_lokasi'     => $request->id_lokasi,
                'id_ezviz_akun' => $request->id_ezviz_akun,
                'nama_cctv'     => $request->nama_cctv,
                'device_serial' => $request->device_serial,
                'channel_no'    => $request->channel_no,
                'stream_type'   => $request->stream_type ?? 1,
                'validCode'     => $request->validCode,
                'deskripsi'     => $request->deskripsi,
                'posisi'        => $request->posisi,
            ], 'id_cctv', $param1);

            LogHelper::log('Update CCTV', 'CCTV', 'Update ID: ' . $param1);
            session()->flash('success', 'Data CCTV berhasil diupdate!');
            return redirect()->to('/panel/cctv/daftarCCTV');
        }

        $data           = $this->getCommonData();
        $data['title']  = 'Update CCTV';
        $data['content'] = 'module.cctv.update';
        $data['cctv']   = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->join('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
            ->select('cv_cctv.*', 'cv_lokasi.nama_lokasi', 'cv_lokasi_group.nama_group', 'cv_ezviz_akun.nama_akun as nama_ezviz')
            ->where('cv_cctv.id_cctv', $param1)
            ->first();

        if (!$data['cctv']) {
            session()->flash('error', 'Data CCTV tidak ditemukan!');
            return redirect()->to('/panel/cctv/daftarCCTV');
        }

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $lokasiQuery = DB::table('cv_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_lokasi.*', 'cv_lokasi_group.nama_group')
            ->where('cv_lokasi.status', 'aktif');
        if ($allowedGroupIds !== null) $lokasiQuery->whereIn('cv_lokasi.id_group', $allowedGroupIds);
        $data['lokasiList'] = $lokasiQuery->orderBy('cv_lokasi_group.nama_group')->orderBy('cv_lokasi.nama_lokasi')->get();
        $data['ezvizList']  = DB::table('cv_ezviz_akun')->where('status', 'aktif')->get();

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Hapus CCTV
    // -------------------------------------------------------
    public function hapusCCTV(Request $request, $param1)
    {
        $cctv = GeneralModel::getByIdGeneral('cv_cctv', 'first', 'id_cctv', $param1);
        if (!$cctv) {
            session()->flash('error', 'CCTV tidak ditemukan!');
            return redirect()->back();
        }

        GeneralModel::deleteById('cv_cctv', 'id_cctv', $param1);
        LogHelper::log('Hapus CCTV', 'CCTV', 'Hapus ID: ' . $param1 . ' [' . $cctv->device_serial . ']');
        session()->flash('success', 'CCTV berhasil dihapus!');
        return redirect()->to('/panel/cctv/daftarCCTV');
    }

    // -------------------------------------------------------
    // Detail CCTV - Show camera info + stream controls
    // -------------------------------------------------------
    public function detailCCTV(Request $request, $param1)
    {
        $cctv = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->join('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
            ->select(
                'cv_cctv.*',
                'cv_lokasi.nama_lokasi',
                'cv_lokasi.lantai',
                'cv_lokasi_group.nama_group',
                'cv_lokasi_group.id_group',
                'cv_ezviz_akun.nama_akun as nama_ezviz',
                'cv_ezviz_akun.email_terdaftar'
            )
            ->where('cv_cctv.id_cctv', $param1)
            ->first();

        if (!$cctv) {
            session()->flash('error', 'CCTV tidak ditemukan!');
            return redirect()->to('/panel/cctv/daftarCCTV');
        }

        // Check access to group
        if (!AccessHelper::cekCctvGroupAkses($cctv->id_group)) {
            session()->flash('error', 'Anda tidak memiliki akses ke CCTV ini!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        // Get device info from EZVIZ API (optional, might fail if offline)
        $deviceInfo = null;
        try {
            $result = $this->ezviz->getDeviceInfo($cctv->id_ezviz_akun, $cctv->device_serial);
            if ($result['success']) {
                $deviceInfo = $result['data'];

                // Update CCTV status based on device info
                $isOnline = isset($deviceInfo['status']) && $deviceInfo['status'] == 1;
                GeneralModel::updateById('cv_cctv', [
                    'status'      => $isOnline ? 'online' : 'offline',
                    'last_online' => $isOnline ? date('Y-m-d H:i:s') : $cctv->last_online,
                ], 'id_cctv', $cctv->id_cctv);
            }
        } catch (\Exception $e) {
            // Ignore API errors on detail page
        }

        LogHelper::log('Lihat Detail CCTV', 'CCTV', 'Detail CCTV: ' . $cctv->nama_cctv);

        $data               = $this->getCommonData();
        $data['title']      = 'Detail CCTV: ' . $cctv->nama_cctv;
        $data['content']    = 'module.cctv.detail';
        $data['cctv']       = $cctv;
        $data['deviceInfo'] = $deviceInfo;

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Live View Lokasi — grid semua CCTV dalam 1 lokasi
    // -------------------------------------------------------
    public function liveViewLokasi(Request $request, $param1)
    {
        $lokasi = DB::table('cv_lokasi')
            ->leftJoin('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_lokasi.*', 'cv_lokasi_group.nama_group', 'cv_lokasi_group.id_group')
            ->where('cv_lokasi.id_lokasi', $param1)
            ->first();

        if (!$lokasi) {
            session()->flash('error', 'Lokasi tidak ditemukan!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        if ($lokasi->id_group && !AccessHelper::cekCctvGroupAkses($lokasi->id_group)) {
            session()->flash('error', 'Anda tidak memiliki akses ke lokasi ini!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        $cctvList = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->join('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
            ->select(
                'cv_cctv.*',
                'cv_lokasi.nama_lokasi',
                'cv_lokasi.lantai',
                'cv_lokasi_group.nama_group',
                'cv_lokasi_group.id_group',
                'cv_ezviz_akun.nama_akun as nama_ezviz'
            )
            ->where('cv_cctv.id_lokasi', $param1)
            ->orderBy('cv_cctv.nama_cctv')
            ->get();

        LogHelper::log('Live View Lokasi', 'CCTV', 'Live view lokasi: ' . $lokasi->nama_lokasi . ' (' . $cctvList->count() . ' kamera)');

        // Reuse liveview-group blade — pass lokasi as 'group' for title/label compatibility
        $fakeGroup = (object) [
            'id_group'   => $lokasi->id_group,
            'nama_group' => $lokasi->nama_lokasi,
            'deskripsi'  => $lokasi->nama_group ? 'Lokasi: ' . $lokasi->nama_group : null,
        ];

        $data             = $this->getCommonData();
        $data['title']    = 'Live View: ' . $lokasi->nama_lokasi;
        $data['content']  = 'module.cctv.liveview-group';
        $data['group']    = $fakeGroup;
        $data['cctvList'] = $cctvList;

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Live View Group — grid semua CCTV dalam 1 grup
    // -------------------------------------------------------
    public function liveViewGroup(Request $request, $param1)
    {
        $group = DB::table('cv_lokasi_group')->where('id_group', $param1)->first();

        if (!$group) {
            session()->flash('error', 'Grup lokasi tidak ditemukan!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        if (!AccessHelper::cekCctvGroupAkses($param1)) {
            session()->flash('error', 'Anda tidak memiliki akses ke grup ini!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        $cctvList = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->join('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
            ->select(
                'cv_cctv.*',
                'cv_lokasi.nama_lokasi',
                'cv_lokasi.lantai',
                'cv_lokasi_group.nama_group',
                'cv_lokasi_group.id_group',
                'cv_ezviz_akun.nama_akun as nama_ezviz'
            )
            ->where('cv_lokasi_group.id_group', $param1)
            ->orderBy('cv_lokasi.nama_lokasi')
            ->orderBy('cv_cctv.nama_cctv')
            ->get();

        LogHelper::log('Live View Group', 'CCTV', 'Live view group: ' . $group->nama_group . ' (' . $cctvList->count() . ' kamera)');

        $data               = $this->getCommonData();
        $data['title']      = 'Live View: ' . $group->nama_group;
        $data['content']    = 'module.cctv.liveview-group';
        $data['group']      = $group;
        $data['cctvList']   = $cctvList;

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Stream CCTV (AJAX - returns stream URL)
    // -------------------------------------------------------
    public function streamCCTV(Request $request, $param1)
    {
        $cctv = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_cctv.*', 'cv_lokasi_group.id_group')
            ->where('cv_cctv.id_cctv', $param1)
            ->first();

        if (!$cctv) {
            return response()->json(['success' => false, 'message' => 'CCTV tidak ditemukan'], 404);
        }

        if (!AccessHelper::cekCctvGroupAkses($cctv->id_group)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $protocol = $request->input('protocol', 'hls');
        $result   = $this->ezviz->getLiveStreamUrl($cctv, $protocol);

        LogHelper::log('Stream CCTV', 'CCTV', 'Stream CCTV ID: ' . $param1 . ' protocol: ' . $protocol);

        return response()->json($result);
    }

    // -------------------------------------------------------
    // Capture image (AJAX)
    // -------------------------------------------------------
    public function captureCCTV(Request $request, $param1)
    {
        $cctv = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_cctv.*', 'cv_lokasi_group.id_group')
            ->where('cv_cctv.id_cctv', $param1)
            ->first();

        if (!$cctv || !AccessHelper::cekCctvGroupAkses($cctv->id_group)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $result = $this->ezviz->captureImage($cctv->id_ezviz_akun, $cctv->device_serial, $cctv->channel_no);
        return response()->json($result);
    }

    // -------------------------------------------------------
    // Sync device list from EZVIZ account (AJAX)
    // -------------------------------------------------------
    public function syncDevices(Request $request)
    {
        $idEzvizAkun = $request->input('id_ezviz_akun');
        if (!$idEzvizAkun) {
            return response()->json(['success' => false, 'message' => 'Akun Ezviz tidak ditemukan']);
        }

        $result = $this->ezviz->getDeviceList($idEzvizAkun);
        return response()->json($result);
    }

    // -------------------------------------------------------
    // Refresh EZVIZ token (AJAX)
    // -------------------------------------------------------
    public function refreshToken(Request $request, $param1 = null)
    {
        $idEzvizAkun = $param1 ?? $request->input('id_ezviz_akun');
        $akun = GeneralModel::getByIdGeneral('cv_ezviz_akun', 'first', 'id_ezviz_akun', $idEzvizAkun);

        if (!$akun) {
            return response()->json(['success' => false, 'message' => 'Akun tidak ditemukan']);
        }

        $result = $this->ezviz->getAccessToken($akun);
        return response()->json($result);
    }

    // -------------------------------------------------------
    // Import device dari EZVIZ portal ke sistem (AJAX)
    // -------------------------------------------------------
    public function importDevice(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'id_ezviz_akun' => 'required|integer',
            'id_lokasi'     => 'required|integer',
            'device_serial' => 'required|max:100',
            'nama_cctv'     => 'required|max:150',
            'channel_no'    => 'nullable|integer|min:1',
        ], [
            'id_ezviz_akun.required' => 'Akun EZVIZ wajib dipilih.',
            'id_lokasi.required'     => 'Lokasi wajib dipilih.',
            'device_serial.required' => 'Serial device wajib diisi.',
            'nama_cctv.required'     => 'Nama CCTV wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Cek lokasi group access
        $lokasi = GeneralModel::getByIdGeneral('cv_lokasi', 'first', 'id_lokasi', $request->id_lokasi);
        if (!$lokasi) {
            return response()->json(['success' => false, 'message' => 'Lokasi tidak ditemukan.'], 404);
        }
        if (!AccessHelper::cekCctvGroupAkses($lokasi->id_group)) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke lokasi ini.'], 403);
        }

        // Cek duplikat serial di akun yang sama
        $exists = DB::table('cv_cctv')
            ->where('id_ezviz_akun', $request->id_ezviz_akun)
            ->where('device_serial', $request->device_serial)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Device serial ' . $request->device_serial . ' sudah ada di sistem untuk akun ini.',
            ], 409);
        }

        $id = GeneralModel::create('cv_cctv', [
            'id_lokasi'     => $request->id_lokasi,
            'id_ezviz_akun' => $request->id_ezviz_akun,
            'nama_cctv'     => $request->nama_cctv,
            'device_serial' => strtoupper($request->device_serial),
            'channel_no'    => $request->channel_no ?? 1,
            'stream_type'   => 1,
            'validCode'     => $request->validCode ?: null,
            'deskripsi'     => $request->deskripsi ?: null,
            'posisi'        => $request->posisi ?: null,
            'status'        => $request->device_status ?? 'offline',
            'created_time'  => date('Y-m-d H:i:s'),
        ]);

        LogHelper::log('Import Device EZVIZ', 'CCTV',
            'Import device ' . $request->device_serial . ' sebagai "' . $request->nama_cctv . '"'
        );

        return response()->json([
            'success'  => true,
            'id_cctv'  => $id,
            'message'  => 'Device ' . $request->device_serial . ' berhasil ditambahkan ke sistem!',
        ]);
    }
}
