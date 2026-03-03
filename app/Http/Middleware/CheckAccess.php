<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Facades\GeneralModelFacade as GeneralModel;

class CheckAccess
{
    /**
     * URL segment → parent module class mapping
     */
    protected array $parentModuleMapping = [
        'dashboard'   => 'Dashboard',
        'gruplokasi'  => 'GrupLokasi',
        'lokasi'      => 'Lokasi',
        'cctv'        => 'CCTV',
        'masterdata'  => 'MasterData',
        'pengaturan'  => 'Pengaturan',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $currentUrl = $request->path(); // e.g. panel/cctv/daftarCCTV
        $segments    = explode('/', $currentUrl);

        $parentSegment = isset($segments[1]) ? strtolower($segments[1]) : null;
        $moduleSegment = isset($segments[2]) ? $segments[2] : null;

        $actualParentModule = $this->parentModuleMapping[$parentSegment] ?? $parentSegment;

        $sessionUser = session()->get('user');
        if (!$sessionUser) {
            return redirect()->to('/login');
        }

        $hakAkses = $sessionUser['hak_akses'];
        $user = GeneralModel::getByIdGeneral('cv_hak_akses', '', 'nama_hak_akses', $hakAkses);

        // If no hak_akses record found, deny
        if (!$user || empty($user->modul_akses) || empty($user->parent_modul_akses)) {
            return redirect()->to('/unauthorized');
        }

        $modulAccess  = json_decode($user->modul_akses, true);
        $parentAccess = json_decode($user->parent_modul_akses, true);

        if (!$modulAccess || !isset($modulAccess['modul']) || !$parentAccess || !isset($parentAccess['parent_modul'])) {
            return redirect()->to('/unauthorized');
        }

        $modulesUpper  = array_map('strtoupper', $modulAccess['modul']);
        $parentsUpper  = array_map('strtoupper', $parentAccess['parent_modul']);

        $moduleAllowed = $moduleSegment
            ? in_array(strtoupper($moduleSegment), $modulesUpper)
            : true;

        $parentAllowed = $actualParentModule
            ? in_array(strtoupper($actualParentModule), $parentsUpper)
            : true;

        if ($moduleAllowed && $parentAllowed) {
            return $next($request);
        }

        return redirect()->to('/unauthorized');
    }
}
