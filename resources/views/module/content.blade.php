@php
    use App\Facades\GeneralModelFacade as GeneralModel;
    $identitas   = GeneralModel::getByIdGeneral('cv_identitas','first','id_profile','1');
    $parentModul = GeneralModel::getGeneral('cv_parent_modul', 'all');
    $data += [
        'identitas'   => $identitas,
        'parentModul' => $parentModul,
    ];
@endphp

@include('module.layout.header', ['data' => $data])
@include('module.main', ['data' => $data])
