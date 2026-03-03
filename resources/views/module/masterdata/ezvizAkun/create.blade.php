<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Tambah Akun Ezviz</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-6">
            <i class="bi bi-info-circle me-2"></i>
            Dapatkan <strong>App Key</strong> dan <strong>App Secret</strong> dari
            <a href="https://open.ys7.com" target="_blank" class="fw-bold">Ezviz Developer Platform</a>.
            Setiap akun Gmail Ezviz memiliki App Key dan App Secret masing-masing.
        </div>
        <form method="POST" action="{{ url('/panel/masterData/tambahEzvizAkun/save') }}">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control"
                               placeholder="Contoh: Gmail Utama, Akun Gedung A"
                               value="{{ old('nama_akun') }}" required />
                        <div class="form-text text-muted">Nama untuk mengidentifikasi akun ini</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">App Key</label>
                        <input type="text" name="app_key" class="form-control font-monospace"
                               placeholder="App Key dari Ezviz Developer"
                               value="{{ old('app_key') }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">App Secret</label>
                        <input type="password" name="app_secret" class="form-control font-monospace"
                               placeholder="App Secret dari Ezviz Developer" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-semibold">API URL</label>
                        <input type="text" name="api_url" class="form-control"
                               placeholder="https://open.ys7.com"
                               value="{{ old('api_url', 'https://open.ys7.com') }}" />
                        <div class="form-text text-muted">Default: https://open.ys7.com</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="4"
                                  placeholder="Catatan tentang akun ini">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan & Test Koneksi
                </button>
            </div>
        </form>
    </div>
</div>
