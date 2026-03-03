<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Edit Pengguna</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarPengguna') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/masterData/updatePengguna/' . $data['pengguna']->id_pengguna . '/save') }}" enctype="multipart/form-data">
            @csrf
            {{-- Foto Profil --}}
            @php $fotoAda = !empty($data['pengguna']->foto) && file_exists(public_path('assets/img/profil/' . $data['pengguna']->foto)); @endphp
            <div class="d-flex align-items-center mb-6">
                <div class="me-5 position-relative" style="width:80px;height:80px;cursor:pointer"
                     onclick="document.getElementById('inputFotoUpdate').click()" title="Klik untuk ganti foto">
                    @if($fotoAda)
                        <img id="previewFotoUpdate"
                             src="{{ asset('assets/img/profil/' . $data['pengguna']->foto) }}"
                             class="rounded-circle object-fit-cover border border-2 border-primary"
                             style="width:80px;height:80px;" />
                        <div id="previewFotoUpdatePh" class="rounded-circle bg-primary text-white fs-2 fw-bold d-flex align-items-center justify-content-center d-none"
                             style="width:80px;height:80px;">{{ strtoupper(substr($data['pengguna']->nama_lengkap, 0, 1)) }}</div>
                    @else
                        <div id="previewFotoUpdatePh"
                             class="rounded-circle bg-primary text-white fs-2 fw-bold d-flex align-items-center justify-content-center"
                             style="width:80px;height:80px;">{{ strtoupper(substr($data['pengguna']->nama_lengkap, 0, 1)) }}</div>
                        <img id="previewFotoUpdate" src="" class="rounded-circle object-fit-cover border border-2 border-primary d-none"
                             style="width:80px;height:80px;" />
                    @endif
                    <span class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow d-flex align-items-center justify-content-center"
                          style="width:24px;height:24px;border:1px solid #ddd">
                        <i class="bi bi-camera-fill text-primary" style="font-size:11px"></i>
                    </span>
                    <input type="file" id="inputFotoUpdate" name="foto_profil"
                           accept="image/jpeg,image/png,image/webp" class="d-none"
                           onchange="previewFotoPengguna(this,'previewFotoUpdate','previewFotoUpdatePh')" />
                </div>
                <div class="text-muted fs-7">Foto profil<br><small>Klik untuk {{ $fotoAda ? 'ganti' : 'upload' }} — JPG/PNG/WEBP, maks 2MB</small></div>
            </div>
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username', $data['pengguna']->username) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="password" class="form-control" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Hak Akses</label>
                        <select name="hak_akses" class="form-select" required>
                            @foreach($data['hakAksesList'] as $ha)
                                <option value="{{ $ha->nama_hak_akses }}"
                                        {{ $data['pengguna']->hak_akses == $ha->nama_hak_akses ? 'selected' : '' }}>
                                    {{ $ha->nama_hak_akses }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                               value="{{ old('nama_lengkap', $data['pengguna']->nama_lengkap) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $data['pengguna']->email) }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="actived" {{ $data['pengguna']->status == 'actived' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $data['pengguna']->status == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/masterData/daftarPengguna') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewFotoPengguna(input, imgId, placeholderId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(imgId);
        const ph  = document.getElementById(placeholderId);
        img.src = e.target.result;
        img.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
