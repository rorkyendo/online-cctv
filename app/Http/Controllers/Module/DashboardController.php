<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralModel;
use Illuminate\Support\Facades\DB;
use App\Helpers\AccessHelper;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getCommonData();
        $data['title']   = 'Dashboard';
        $data['content'] = 'module.dashboard.main';

        // Stats
        $allowedGroupIds = AccessHelper::getAllowedGroupIds();

        $groupQuery = DB::table('cv_lokasi_group')->where('status', 'aktif');
        if ($allowedGroupIds !== null) {
            $groupQuery->whereIn('id_group', $allowedGroupIds);
        }
        $data['totalGrupLokasi'] = $groupQuery->count();

        // Total lokasi from allowed groups
        $lokasiQuery = DB::table('cv_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group')
            ->where('cv_lokasi.status', 'aktif');
        if ($allowedGroupIds !== null) {
            $lokasiQuery->whereIn('cv_lokasi.id_group', $allowedGroupIds);
        }
        $data['totalLokasi'] = $lokasiQuery->count();

        // Total CCTV
        $cctvQuery = DB::table('cv_cctv')
            ->join('cv_lokasi', 'cv_cctv.id_lokasi', '=', 'cv_lokasi.id_lokasi')
            ->join('cv_lokasi_group', 'cv_lokasi.id_group', '=', 'cv_lokasi_group.id_group');
        if ($allowedGroupIds !== null) {
            $cctvQuery->whereIn('cv_lokasi_group.id_group', $allowedGroupIds);
        }
        $data['totalCCTV']        = $cctvQuery->count();
        $data['totalCCTVOnline']  = (clone $cctvQuery)->where('cv_cctv.status', 'online')->count();
        $data['totalCCTVOffline'] = (clone $cctvQuery)->where('cv_cctv.status', 'offline')->count();

        $data['totalPengguna'] = DB::table('cv_pengguna')->where('status', 'actived')->count();
        $data['totalEzvizAkun'] = DB::table('cv_ezviz_akun')->where('status', 'aktif')->count();

        // Recent activity
        $data['recentLog'] = DB::table('cv_log_aktivitas')
            ->orderBy('created_time', 'desc')
            ->limit(10)
            ->get();

        // Group lokasi cards for dashboard
        $grupQuery = DB::table('cv_lokasi_group')
            ->select('cv_lokasi_group.*',
                DB::raw('(SELECT COUNT(*) FROM cv_lokasi l JOIN cv_cctv c ON c.id_lokasi = l.id_lokasi WHERE l.id_group = cv_lokasi_group.id_group) as total_cctv')
            )
            ->where('cv_lokasi_group.status', 'aktif')
            ->orderBy('urutan');
        if ($allowedGroupIds !== null) {
            $grupQuery->whereIn('id_group', $allowedGroupIds);
        }
        $data['grupLokasi'] = $grupQuery->get();

        return view('module.content', ['data' => $data]);
    }
}
