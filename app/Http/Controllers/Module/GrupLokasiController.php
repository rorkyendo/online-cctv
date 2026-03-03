<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Helpers\LogHelper;
use App\Helpers\AccessHelper;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GrupLokasiController extends Controller
{
    // -------------------------------------------------------
    // Daftar Grup Lokasi
    // -------------------------------------------------------
    public function daftarGrupLokasi(Request $request)
    {
        if ($request->ajax()) {
            $allowedGroupIds = AccessHelper::getAllowedGroupIds();

            $query = DB::table('cv_lokasi_group')
                ->select('cv_lokasi_group.*',
                    DB::raw('(SELECT COUNT(*) FROM cv_lokasi WHERE cv_lokasi.id_group = cv_lokasi_group.id_group) as total_lokasi'),
                    DB::raw('(SELECT COUNT(*) FROM cv_lokasi l JOIN cv_cctv c ON c.id_lokasi = l.id_lokasi WHERE l.id_group = cv_lokasi_group.id_group) as total_cctv')
                );

            if ($allowedGroupIds !== null) {
                $query->whereIn('id_group', $allowedGroupIds);
            }

            return DataTables::of($query)->make(true);
        }

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $grupQuery = DB::table('cv_lokasi_group')
            ->select('cv_lokasi_group.*',
                DB::raw('(SELECT COUNT(*) FROM cv_lokasi WHERE cv_lokasi.id_group = cv_lokasi_group.id_group) as total_lokasi'),
                DB::raw('(SELECT COUNT(*) FROM cv_lokasi l JOIN cv_cctv c ON c.id_lokasi = l.id_lokasi WHERE l.id_group = cv_lokasi_group.id_group) as total_cctv')
            )
            ->orderBy('urutan');
        if ($allowedGroupIds !== null) {
            $grupQuery->whereIn('id_group', $allowedGroupIds);
        }

        $data                = $this->getCommonData();
        $data['title']       = 'Grup Lokasi';
        $data['content']     = 'module.grupLokasi.data';
        $data['grupLokasi']  = $grupQuery->get();
        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Tambah Grup Lokasi
    // -------------------------------------------------------
    public function tambahGrupLokasi(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_group'  => 'required|max:150',
                'kode_group'  => 'nullable|max:20',
            ], [
                'nama_group.required' => 'Nama grup tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            GeneralModel::create('cv_lokasi_group', [
                'nama_group'  => $request->nama_group,
                'kode_group'  => $request->kode_group,
                'deskripsi'   => $request->deskripsi,
                'alamat'      => $request->alamat,
                'kota'        => $request->kota,
                'status'      => $request->status ?? 'aktif',
                'urutan'      => $request->urutan ?? 0,
                'created_time' => date('Y-m-d H:i:s'),
            ]);

            LogHelper::log('Tambah Grup Lokasi', 'GrupLokasi', 'Tambah grup: ' . $request->nama_group);
            session()->flash('success', 'Grup lokasi berhasil ditambahkan!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Tambah Grup Lokasi';
        $data['content'] = 'module.grupLokasi.create';
        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Update Grup Lokasi
    // -------------------------------------------------------
    public function updateGrupLokasi(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_group' => 'required|max:150',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            GeneralModel::updateById('cv_lokasi_group', [
                'nama_group'  => $request->nama_group,
                'kode_group'  => $request->kode_group,
                'deskripsi'   => $request->deskripsi,
                'alamat'      => $request->alamat,
                'kota'        => $request->kota,
                'status'      => $request->status ?? 'aktif',
                'urutan'      => $request->urutan ?? 0,
            ], 'id_group', $param1);

            LogHelper::log('Update Grup Lokasi', 'GrupLokasi', 'Update ID: ' . $param1);
            session()->flash('success', 'Grup lokasi berhasil diupdate!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        $data              = $this->getCommonData();
        $data['title']     = 'Update Grup Lokasi';
        $data['content']   = 'module.grupLokasi.update';
        $data['grupLokasi'] = GeneralModel::getByIdGeneral('cv_lokasi_group', 'first', 'id_group', $param1);

        if (!$data['grupLokasi']) {
            session()->flash('error', 'Data tidak ditemukan!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        return view('module.content', ['data' => $data]);
    }

    // -------------------------------------------------------
    // Hapus Grup Lokasi
    // -------------------------------------------------------
    public function hapusGrupLokasi(Request $request, $param1)
    {
        // Check if it has CCTV / lokasi
        $totalLokasi = DB::table('cv_lokasi')->where('id_group', $param1)->count();
        if ($totalLokasi > 0) {
            session()->flash('error', 'Grup lokasi tidak bisa dihapus karena masih memiliki data lokasi!');
            return redirect()->back();
        }

        GeneralModel::deleteById('cv_lokasi_group', 'id_group', $param1);
        LogHelper::log('Hapus Grup Lokasi', 'GrupLokasi', 'Hapus ID: ' . $param1);
        session()->flash('success', 'Grup lokasi berhasil dihapus!');
        return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
    }

    // -------------------------------------------------------
    // Detail Grup Lokasi (list lokasi within the group)
    // -------------------------------------------------------
    public function detailGrupLokasi(Request $request, $param1)
    {
        // Check CCTV group access permission
        if (!AccessHelper::cekCctvGroupAkses($param1)) {
            session()->flash('error', 'Anda tidak memiliki akses ke grup ini!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        $grupLokasi = GeneralModel::getByIdGeneral('cv_lokasi_group', 'first', 'id_group', $param1);
        if (!$grupLokasi) {
            session()->flash('error', 'Grup lokasi tidak ditemukan!');
            return redirect()->to('/panel/grupLokasi/daftarGrupLokasi');
        }

        if ($request->ajax()) {
            $query = DB::table('cv_lokasi')
                ->select('cv_lokasi.*',
                    DB::raw('(SELECT COUNT(*) FROM cv_cctv WHERE cv_cctv.id_lokasi = cv_lokasi.id_lokasi) as total_cctv')
                )
                ->where('id_group', $param1);
            return DataTables::of($query)->make(true);
        }

        $data              = $this->getCommonData();
        $data['title']     = 'Lokasi: ' . $grupLokasi->nama_group;
        $data['content']   = 'module.grupLokasi.detail';
        $data['grupLokasi'] = $grupLokasi;

        // Lokasi cards
        $data['lokasiList'] = DB::table('cv_lokasi')
            ->select('cv_lokasi.*',
                DB::raw('(SELECT COUNT(*) FROM cv_cctv WHERE cv_cctv.id_lokasi = cv_lokasi.id_lokasi) as total_cctv')
            )
            ->where('id_group', $param1)
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();

        return view('module.content', ['data' => $data]);
    }
}
