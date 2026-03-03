<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Tambah Pengguna</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarPengguna') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/masterData/tambahPengguna/save') }}">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username') }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Hak Akses</label>
                        <select name="hak_akses" class="form-select" required>
                            <option value="">-- Pilih Hak Akses --</option>
                            @foreach($data['hakAksesList'] as $ha)
                                <option value="{{ $ha->nama_hak_akses }}"
                                        {{ old('hak_akses') == $ha->nama_hak_akses ? 'selected' : '' }}>
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
                               value="{{ old('nama_lengkap') }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="actived" {{ old('status') == 'actived' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/masterData/daftarPengguna') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
