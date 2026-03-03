<!-- Profile Page -->
<div class="row g-5 g-xl-10">
    <!-- Update Profile -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-5">
            <div class="card-header border-0 pt-6">
                <div class="card-title"><h3 class="fw-bold">Profil Saya</h3></div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('/panel/updateProfile') }}" enctype="multipart/form-data">
                    @csrf
                <div class="d-flex align-items-center mb-6">
                    {{-- Avatar / Foto --}}
                    <div class="me-5 position-relative" style="width:80px;height:80px;cursor:pointer"
                         onclick="document.getElementById('inputFotoProfil').click()" title="Klik untuk ganti foto">
                        @if(!empty($data['pengguna']->foto) && file_exists(public_path('assets/img/profil/' . $data['pengguna']->foto)))
                            <img id="previewFoto"
                                 src="{{ asset('assets/img/profil/' . $data['pengguna']->foto) }}"
                                 class="rounded-circle object-fit-cover border border-2 border-primary"
                                 style="width:80px;height:80px;" />
                        @else
                            <div id="previewFotoPlaceholder"
                                 class="rounded-circle bg-primary text-white fs-2 fw-bold d-flex align-items-center justify-content-center"
                                 style="width:80px;height:80px;">
                                {{ strtoupper(substr($data['pengguna']->nama_lengkap ?? 'U', 0, 1)) }}
                            </div>
                            <img id="previewFoto" src="" class="rounded-circle object-fit-cover border border-2 border-primary d-none"
                                 style="width:80px;height:80px;" />
                        @endif
                        {{-- Edit overlay --}}
                        <span class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow d-flex align-items-center justify-content-center"
                              style="width:24px;height:24px;border:1px solid #ddd">
                            <i class="bi bi-camera-fill text-primary" style="font-size:11px"></i>
                        </span>
                        <input type="file" id="inputFotoProfil" name="foto_profil"
                               accept="image/jpeg,image/png,image/webp"
                               class="d-none" onchange="previewFotoProfil(this)" />
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $data['pengguna']->nama_lengkap ?? '-' }}</h4>
                        <span class="badge badge-light-primary">{{ $data['pengguna']->hak_akses ?? '-' }}</span>
                        <div class="text-muted fs-8 mt-1">Klik foto untuk mengganti</div>
                    </div>
                </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control bg-light"
                               value="{{ $data['pengguna']->username }}" disabled />
                    </div>
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
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header border-0 pt-6">
                <div class="card-title"><h3 class="fw-bold">Ganti Password</h3></div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('/panel/updatePassword') }}">
                    @csrf
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control"
                               minlength="6" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control" required />
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-2"></i>Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewFotoProfil(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('previewFoto');
        const placeholder = document.getElementById('previewFotoPlaceholder');
        img.src = e.target.result;
        img.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
