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
        <form method="POST" action="{{ url('/panel/masterData/updatePengguna/' . $data['pengguna']->id_pengguna . '/save') }}">
            @csrf
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
