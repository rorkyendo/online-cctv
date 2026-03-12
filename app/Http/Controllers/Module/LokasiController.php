<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use App\Helpers\LogHelper;
use App\Helpers\AccessHelper;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LokasiController extends Controller
{
    public function daftarLokasi(Request $request)
    {
        if ($request->ajax()) {
            $allowedGroupIds = AccessHelper::getAllowedGroupIds();

            $query = DB::table('cv_lokasi')
                ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
                ->select(
                    'cv_lokasi.id_lokasi',
                    'cv_lokasi.nama_lokasi',
                    'cv_lokasi.deskripsi',
                    'cv_lokasi.id_group',
                    'cv_lokasi_group.nama_group',
                    DB::raw('(SELECT COUNT(*) FROM cv_cctv WHERE cv_cctv.id_lokasi = cv_lokasi.id_lokasi) as total_cctv')
                );

            if ($allowedGroupIds !== null) {
                $query->whereIn('cv_lokasi.id_group', $allowedGroupIds);
            }

            if ($request->filled('filter_group')) {
                $query->where('cv_lokasi_group.id_group', intval($request->filter_group));
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_lokasi_html', function ($row) {
                    return '<a href="' . url('/panel/lokasi/detailLokasi/' . $row->id_lokasi) . '" class="text-dark text-hover-primary fw-bold">'
                        . '<i class="bi bi-geo-alt me-2 text-primary"></i>' . e($row->nama_lokasi) . '</a>';
                })
                ->addColumn('nama_group_html', function ($row) {
                    return '<span class="badge badge-light-primary">' . e($row->nama_group ?? '-') . '</span>';
                })
                ->addColumn('total_cctv_html', function ($row) {
                    return '<span class="badge badge-light fw-semibold">' . intval($row->total_cctv) . '</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $detailUrl = url('/panel/lokasi/detailLokasi/' . $row->id_lokasi);
                    $editUrl   = url('/panel/lokasi/updateLokasi/' . $row->id_lokasi);
                    $hapusUrl  = url('/panel/lokasi/hapusLokasi/' . $row->id_lokasi);
                    $namaJs    = addslashes($row->nama_lokasi);
                    return '<div class="d-flex justify-content-end gap-1">'
                        . '<a href="' . $detailUrl . '" class="btn btn-sm btn-icon btn-light-primary" title="Detail"><i class="bi bi-eye"></i></a>'
                        . '<a href="' . $editUrl   . '" class="btn btn-sm btn-icon btn-light-warning" title="Edit"><i class="bi bi-pencil"></i></a>'
                        . '<a href="' . $hapusUrl  . '" class="btn btn-sm btn-icon btn-light-danger" onclick="return confirm(\"Hapus lokasi ' . $namaJs . '?\")" title="Hapus"><i class="bi bi-trash"></i></a>'
                        . '</div>';
                })
                ->rawColumns(['nama_lokasi_html', 'nama_group_html', 'total_cctv_html', 'aksi'])
                ->filterColumn('nama_lokasi', function ($q, $keyword) {
                    $q->where('cv_lokasi.nama_lokasi', 'like', "%{$keyword}%");
                })
                ->filterColumn('nama_group', function ($q, $keyword) {
                    $q->where('cv_lokasi_group.nama_group', 'like', "%{$keyword}%");
                })
                ->filterColumn('deskripsi', function ($q, $keyword) {
                    $q->where('cv_lokasi.deskripsi', 'like', "%{$keyword}%");
                })
                ->make(true);
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Daftar Lokasi';
        $data['content'] = 'module.lokasi.data';

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $groupQuery = DB::table('cv_lokasi_group')->where('status', 'aktif')->orderBy('urutan');
        if ($allowedGroupIds !== null) {
            $groupQuery->whereIn('id_group', $allowedGroupIds);
        }
        $data['grupList'] = $groupQuery->get();

        return view('module.content', ['data' => $data]);
    }

    public function tambahLokasi(Request $request, $param1 = '')
    {
        if ($param1 === 'save') {
            $validator = validator()->make($request->all(), [
                'id_group'    => 'required|integer',
                'nama_lokasi' => 'required|max:150',
            ], [
                'id_group.required'    => 'Grup lokasi wajib dipilih!',
                'nama_lokasi.required' => 'Nama lokasi tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            // Check access to this group
            if (!AccessHelper::cekCctvGroupAkses($request->id_group)) {
                session()->flash('error', 'Anda tidak memiliki akses ke grup ini!');
                return redirect()->back()->withInput();
            }

            GeneralModel::create('cv_lokasi', [
                'id_group'       => $request->id_group,
                'nama_lokasi'    => $request->nama_lokasi,
                'kode_lokasi'    => $request->kode_lokasi,
                'deskripsi'      => $request->deskripsi,
                'lantai'         => $request->lantai,
                'koordinat_lat'  => $request->koordinat_lat,
                'koordinat_lng'  => $request->koordinat_lng,
                'status'         => $request->status ?? 'aktif',
                'urutan'         => $request->urutan ?? 0,
                'created_time'   => date('Y-m-d H:i:s'),
            ]);

            LogHelper::log('Tambah Lokasi', 'Lokasi', 'Tambah lokasi: ' . $request->nama_lokasi);
            session()->flash('success', 'Lokasi berhasil ditambahkan!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        $data            = $this->getCommonData();
        $data['title']   = 'Tambah Lokasi';
        $data['content'] = 'module.lokasi.create';

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $groupQuery = DB::table('cv_lokasi_group')->where('status', 'aktif');
        if ($allowedGroupIds !== null) $groupQuery->whereIn('id_group', $allowedGroupIds);
        $data['grupList'] = $groupQuery->orderBy('urutan')->get();

        return view('module.content', ['data' => $data]);
    }

    public function updateLokasi(Request $request, $param1 = '', $param2 = '')
    {
        if ($param2 === 'save') {
            $validator = validator()->make($request->all(), [
                'nama_lokasi' => 'required|max:150',
            ]);

            if ($validator->fails()) {
                session()->flash('error', $validator->errors()->first());
                return redirect()->back()->withInput();
            }

            GeneralModel::updateById('cv_lokasi', [
                'nama_lokasi'   => $request->nama_lokasi,
                'kode_lokasi'   => $request->kode_lokasi,
                'deskripsi'     => $request->deskripsi,
                'lantai'        => $request->lantai,
                'koordinat_lat' => $request->koordinat_lat,
                'koordinat_lng' => $request->koordinat_lng,
                'status'        => $request->status ?? 'aktif',
                'urutan'        => $request->urutan ?? 0,
            ], 'id_lokasi', $param1);

            LogHelper::log('Update Lokasi', 'Lokasi', 'Update ID: ' . $param1);
            session()->flash('success', 'Lokasi berhasil diupdate!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        $data           = $this->getCommonData();
        $data['title']  = 'Update Lokasi';
        $data['content'] = 'module.lokasi.update';
        $data['lokasi'] = DB::table('cv_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_lokasi.*', 'cv_lokasi_group.nama_group')
            ->where('cv_lokasi.id_lokasi', $param1)
            ->first();

        if (!$data['lokasi']) {
            session()->flash('error', 'Data tidak ditemukan!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        $allowedGroupIds = AccessHelper::getAllowedGroupIds();
        $groupQuery = DB::table('cv_lokasi_group')->where('status', 'aktif');
        if ($allowedGroupIds !== null) $groupQuery->whereIn('id_group', $allowedGroupIds);
        $data['grupList'] = $groupQuery->orderBy('urutan')->get();

        return view('module.content', ['data' => $data]);
    }

    public function hapusLokasi(Request $request, $param1)
    {
        $totalCctv = DB::table('cv_cctv')->where('id_lokasi', $param1)->count();
        if ($totalCctv > 0) {
            session()->flash('error', 'Lokasi tidak bisa dihapus karena masih memiliki data CCTV!');
            return redirect()->back();
        }

        GeneralModel::deleteById('cv_lokasi', 'id_lokasi', $param1);
        LogHelper::log('Hapus Lokasi', 'Lokasi', 'Hapus ID: ' . $param1);
        session()->flash('success', 'Lokasi berhasil dihapus!');
        return redirect()->to('/panel/lokasi/daftarLokasi');
    }

    // Detail lokasi - list CCTV in this location
    public function detailLokasi(Request $request, $param1)
    {
        $lokasi = DB::table('cv_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->select('cv_lokasi.*', 'cv_lokasi_group.nama_group', 'cv_lokasi_group.id_group as group_id')
            ->where('cv_lokasi.id_lokasi', $param1)
            ->first();

        if (!$lokasi) {
            session()->flash('error', 'Lokasi tidak ditemukan!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        // Check CCTV group access
        if (!AccessHelper::cekCctvGroupAkses($lokasi->group_id)) {
            session()->flash('error', 'Anda tidak memiliki akses ke lokasi ini!');
            return redirect()->to('/panel/lokasi/daftarLokasi');
        }

        $data             = $this->getCommonData();
        $data['title']    = 'Lokasi: ' . $lokasi->nama_lokasi;
        $data['content']  = 'module.lokasi.detail';
        $data['lokasi']   = $lokasi;

        $data['cctvList'] = DB::table('cv_cctv')
            ->join('cv_ezviz_akun', 'cv_cctv.id_ezviz_akun', '=', 'cv_ezviz_akun.id_ezviz_akun')
            ->select('cv_cctv.*', 'cv_ezviz_akun.nama_akun as nama_ezviz')
            ->where('cv_cctv.id_lokasi', $param1)
            ->orderBy('cv_cctv.nama_cctv')
            ->get();

        return view('module.content', ['data' => $data]);
    }
}
