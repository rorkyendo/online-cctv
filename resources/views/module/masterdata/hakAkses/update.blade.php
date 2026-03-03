@php
    use App\Facades\GeneralModelFacade as GeneralModel;
    $hakAkses         = $data['hakAkses'];
    $decoded          = $hakAkses->modul_akses ? json_decode($hakAkses->modul_akses, true) : [];
    $modulAkses       = is_array($decoded) ? ($decoded['modul'] ?? $decoded) : [];
    $decodedPM        = $hakAkses->parent_modul_akses ? json_decode($hakAkses->parent_modul_akses, true) : [];
    $parentModulAkses = is_array($decodedPM) ? ($decodedPM['parent_modul'] ?? $decodedPM) : [];
    $cctvGroupAkses   = $hakAkses->cctv_group_akses ? json_decode($hakAkses->cctv_group_akses, true) : [];
    if (!is_array($modulAkses))       $modulAkses = [];
    if (!is_array($parentModulAkses)) $parentModulAkses = [];
    if (!is_array($cctvGroupAkses))   $cctvGroupAkses = [];
    $cctvGroupAkses = array_map('strval', $cctvGroupAkses);
@endphp

<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Edit Hak Akses: {{ $hakAkses->nama_hak_akses }}</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarHakAkses') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/masterData/updateHakAkses/' . $hakAkses->id_hak_akses . '/save') }}">
            @csrf
            <div class="mb-6">
                <label class="form-label required fw-semibold">Nama Hak Akses</label>
                <input type="text" name="nama_hak_akses" class="form-control"
                       value="{{ old('nama_hak_akses', $hakAkses->nama_hak_akses) }}" required />
            </div>

            <!-- Module Access Permissions -->
            <div class="mb-6">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-shield-check me-2 text-primary"></i>Hak Akses Modul
                </h5>
                <div class="row g-4">
                    @foreach($data['parentModulList'] as $pm)
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light py-3">
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input pm-check" type="checkbox"
                                           name="parent_modul[]" value="{{ $pm->class }}"
                                           id="pm_{{ $pm->id_parent_modul }}"
                                           data-pm="{{ $pm->id_parent_modul }}"
                                           {{ in_array($pm->class, $parentModulAkses) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold ms-2" for="pm_{{ $pm->id_parent_modul }}">
                                        <i class="{!! $pm->icon !!} me-1"></i> {{ $pm->nama_parent_modul }}
                                    </label>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                @php
                                    $modul = GeneralModel::getByIdGeneral('cv_modul','all','class_parent_modul',$pm->class);
                                @endphp
                                @foreach($modul as $m)
                                <div class="form-check form-check-sm form-check-custom form-check-solid mb-2">
                                    <input class="form-check-input m-check" type="checkbox"
                                           name="modul[]" value="{{ $m->controller_modul }}"
                                           id="m_{{ $m->id_modul }}"
                                           data-pm="{{ $pm->id_parent_modul }}"
                                           {{ in_array($m->controller_modul, $modulAkses) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="m_{{ $m->id_modul }}">
                                        {{ $m->nama_modul }}
                                        <small class="text-muted">({{ $m->controller_modul }})</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- CCTV Group Access Permissions -->
            <div class="mb-6">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-camera-video me-2 text-success"></i>Pembatasan Akses Group CCTV
                </h5>
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Jika tidak ada group yang dipilih</strong>, pengguna dapat mengakses
                    <strong>semua group CCTV</strong>. Centang group tertentu untuk membatasi akses.
                    @if($hakAkses->cctv_group_akses === null)
                        <br><strong class="text-success">Saat ini: Akses ke semua group</strong>
                    @else
                        <br><strong class="text-warning">Saat ini: Terbatas ke group tertentu ({{ count($cctvGroupAkses) }} group)</strong>
                    @endif
                </div>
                <div class="row g-3">
                    @forelse($data['grupList'] as $grup)
                    <div class="col-md-4 col-lg-3">
                        <div class="form-check form-check-sm form-check-custom form-check-solid border rounded p-3">
                            <input class="form-check-input" type="checkbox"
                                   name="cctv_group[]" value="{{ $grup->id_group }}"
                                   id="cg_{{ $grup->id_group }}"
                                   {{ in_array($grup->id_group, $cctvGroupAkses) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 fw-semibold" for="cg_{{ $grup->id_group }}">
                                <i class="bi bi-folder me-1 text-primary"></i>
                                {{ $grup->nama_group }}
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-muted">Belum ada group lokasi</div>
                    @endforelse
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/masterData/daftarHakAkses') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.pm-check').forEach(pmChk => {
    pmChk.addEventListener('change', function() {
        const pmId = this.dataset.pm;
        document.querySelectorAll(`.m-check[data-pm="${pmId}"]`).forEach(mChk => {
            mChk.checked = this.checked;
        });
    });
});
document.querySelectorAll('.m-check').forEach(mChk => {
    mChk.addEventListener('change', function() {
        const pmId = this.dataset.pm;
        const allChecked = [...document.querySelectorAll(`.m-check[data-pm="${pmId}"]`)].every(c => c.checked);
        const pmChk = document.querySelector(`.pm-check[data-pm="${pmId}"]`);
        if (pmChk) pmChk.checked = allChecked;
    });
});
</script>
