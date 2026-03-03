<?php

namespace App\Http\Controllers;

use App\Models\GeneralModel;

abstract class Controller
{
    /**
     * Get common panel data: identitas + parentModul (for sidebar).
     */
    protected function getCommonData(): array
    {
        return [
            'identitas'   => GeneralModel::getByIdGeneral('cv_identitas', 'first', 'id_profile', '1'),
            'parentModul' => (new GeneralModel())->getGeneral(
                'cv_parent_modul',
                'all',
                [['status', '=', 'aktif']],
                'urutan ASC'
            ),
        ];
    }
}
