<!-- Daftar Ezviz Akun -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar Akun Ezviz</h4>
        <span class="text-muted fs-7">Kelola akun API Ezviz (multi-akun)</span>
    </div>
    <a href="{{ url('/panel/masterData/tambahEzvizAkun') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Akun
    </a>
</div>

<div class="row g-5">
    @forelse($data['ezvizAkunList'] as $akun)
    <div class="col-md-6 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="symbol symbol-50px me-4">
                        <div class="symbol-label bg-light-primary">
                            <i class="bi bi-cloud fs-2 text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $akun->nama_akun }}</h5>
                        <span class="text-muted fs-7">{{ $akun->api_url ?? 'https://open.ys7.com' }}</span>
                    </div>
                </div>
                <div class="d-flex flex-stack mb-3">
                    <span class="text-muted">App Key</span>
                    <span class="fw-semibold font-monospace fs-7">{{ Str::limit($akun->app_key, 15) }}</span>
                </div>
                <div class="d-flex flex-stack mb-3">
                    <span class="text-muted">Token Status</span>
                    @if($akun->access_token && $akun->token_expiry && now()->lt($akun->token_expiry))
                        <span class="badge badge-success">
                            <i class="bi bi-check-circle me-1"></i>Valid
                        </span>
                    @else
                        <span class="badge badge-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>Expired
                        </span>
                    @endif
                </div>
                @if($akun->token_expiry)
                <div class="d-flex flex-stack mb-4">
                    <span class="text-muted">Expired</span>
                    <span class="fs-7 text-muted">{{ \Carbon\Carbon::parse($akun->token_expiry)->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ url('/panel/masterData/updateEzvizAkun/' . $akun->id_ezviz_akun) }}"
                       class="btn btn-sm btn-light-warning flex-grow-1">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <button class="btn btn-sm btn-light-info"
                            onclick="refreshToken({{ $akun->id_ezviz_akun }}, this)" title="Refresh Token">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                    <a href="{{ url('/panel/masterData/hapusEzvizAkun/' . $akun->id_ezviz_akun) }}"
                       class="btn btn-sm btn-light-danger"
                       onclick="return confirm('Hapus akun {{ $akun->nama_akun }}?')" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-10">
                <i class="bi bi-cloud-slash fs-1 text-muted d-block mb-4"></i>
                <h5 class="text-muted">Belum ada akun Ezviz terdaftar</h5>
                <p class="text-muted fs-7">Daftarkan akun Ezviz Anda untuk mengelola CCTV</p>
                <a href="{{ url('/panel/masterData/tambahEzvizAkun') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Akun Pertama
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<script>
function refreshToken(id, btn) {
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch(`/panel/cctv/refreshToken/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
        if (data.success) {
            alert('Token berhasil diperbarui!');
            location.reload();
        } else {
            alert('Gagal refresh token: ' + (data.message || 'Error'));
        }
    })
    .catch(e => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
        alert('Error: ' + e.message);
    });
}
</script>
