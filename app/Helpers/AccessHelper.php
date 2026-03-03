<?php

namespace App\Helpers;

use App\Facades\GeneralModelFacade as GeneralModel;

class AccessHelper
{
    /**
     * Check if the current user's role has access to a parent module.
     *
     * @param string $parentModul Parent module class (e.g. 'MasterData', 'CCTV')
     * @return bool
     */
    public static function cekParentModulAkses($parentModul)
    {
        $user = session()->get('user');
        if (!$user) return false;

        $hakAkses = GeneralModel::getByIdGeneral('cv_hak_akses', '', 'nama_hak_akses', $user['hak_akses']);
        if (!$hakAkses || empty($hakAkses->parent_modul_akses)) return false;

        $decoded = json_decode($hakAkses->parent_modul_akses, true);
        if (!$decoded || !isset($decoded['parent_modul'])) return false;

        return in_array(
            strtoupper($parentModul),
            array_map('strtoupper', $decoded['parent_modul'])
        );
    }

    /**
     * Check if the current user's role has access to a specific module.
     *
     * @param string $controller Module controller name (e.g. 'daftarCCTV', 'streamCCTV')
     * @return bool
     */
    public static function cekModulAkses($controller)
    {
        $user = session()->get('user');
        if (!$user) return false;

        $hakAkses = GeneralModel::getByIdGeneral('cv_hak_akses', '', 'nama_hak_akses', $user['hak_akses']);
        if (!$hakAkses || empty($hakAkses->modul_akses)) return false;

        $decoded = json_decode($hakAkses->modul_akses, true);
        if (!$decoded || !isset($decoded['modul'])) return false;

        return in_array(
            strtoupper($controller),
            array_map('strtoupper', $decoded['modul'])
        );
    }

    /**
     * Check if the current user can access a specific CCTV location group.
     * Rules:
     *  - If cctv_group_akses is NULL or empty array → can access ALL groups
     *  - If cctv_group_akses contains group IDs → only those groups
     *
     * @param int $idGroup cv_lokasi_group.id_group
     * @return bool
     */
    public static function cekCctvGroupAkses($idGroup)
    {
        $user = session()->get('user');
        if (!$user) return false;

        $hakAkses = GeneralModel::getByIdGeneral('cv_hak_akses', '', 'nama_hak_akses', $user['hak_akses']);
        if (!$hakAkses) return false;

        // NULL or empty = access to all groups
        if (empty($hakAkses->cctv_group_akses)) {
            return true;
        }

        $allowedGroups = json_decode($hakAkses->cctv_group_akses, true);
        if (!is_array($allowedGroups) || empty($allowedGroups)) {
            return true; // empty array = all access
        }

        return in_array((int) $idGroup, array_map('intval', $allowedGroups));
    }

    /**
     * Get list of allowed group IDs for the current user.
     * Returns NULL if all groups are allowed.
     *
     * @return array|null NULL = all groups, array = specific group IDs
     */
    public static function getAllowedGroupIds()
    {
        $user = session()->get('user');
        if (!$user) return [];

        $hakAkses = GeneralModel::getByIdGeneral('cv_hak_akses', '', 'nama_hak_akses', $user['hak_akses']);
        if (!$hakAkses) return [];

        if (empty($hakAkses->cctv_group_akses)) {
            return null; // NULL means all groups
        }

        $allowedGroups = json_decode($hakAkses->cctv_group_akses, true);
        if (!is_array($allowedGroups) || empty($allowedGroups)) {
            return null;
        }

        return array_map('intval', $allowedGroups);
    }
}
