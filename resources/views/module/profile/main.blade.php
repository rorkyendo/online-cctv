<!-- Profile Page -->
<div class="row g-5 g-xl-10">
    <!-- Update Profile -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-5">
            <div class="card-header border-0 pt-6">
                <div class="card-title"><h3 class="fw-bold">Profil Saya</h3></div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-6">
                    <div class="symbol symbol-80px me-5">
                        <div class="symbol-label bg-primary text-white fs-2 fw-bold">
                            {{ strtoupper(substr($data['pengguna']->nama_lengkap ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $data['pengguna']->nama_lengkap ?? '-' }}</h4>
                        <span class="badge badge-light-primary">{{ $data['pengguna']->hak_akses ?? '-' }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ url('/panel/updateProfile') }}">
                    @csrf
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
