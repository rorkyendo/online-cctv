<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Edit Akun Ezviz: {{ $data['ezvizAkun']->nama_akun }}</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/masterData/updateEzvizAkun/' . $data['ezvizAkun']->id_ezviz_akun . '/save') }}">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control"
                               value="{{ old('nama_akun', $data['ezvizAkun']->nama_akun) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">App Key</label>
                        <input type="text" name="app_key" class="form-control font-monospace"
                               value="{{ old('app_key', $data['ezvizAkun']->app_key) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">App Secret <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="app_secret" class="form-control font-monospace" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-semibold">API URL</label>
                        <input type="text" name="api_url" class="form-control"
                               value="{{ old('api_url', $data['ezvizAkun']->api_url ?? 'https://open.ys7.com') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Status Token</label>
                        <div class="form-control bg-light">
                            @if($data['ezvizAkun']->access_token && $data['ezvizAkun']->token_expiry && now()->lt($data['ezvizAkun']->token_expiry))
                                <span class="text-success fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>Valid hingga {{ \Carbon\Carbon::parse($data['ezvizAkun']->token_expiry)->format('d/m/Y H:i') }}
                                </span>
                            @else
                                <span class="text-danger fw-bold">
                                    <i class="bi bi-exclamation-circle me-2"></i>Token expired / belum ada
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $data['ezvizAkun']->keterangan) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
