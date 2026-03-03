{{-- ======================================================= --}}
{{-- PANEL SCRAPING (UPDATE) --}}
{{-- ======================================================= --}}
<div class="card shadow-sm mb-5">
    <div class="card-header border-0 pt-6 bg-light-primary">
        <div class="card-title">
            <i class="bi bi-magic text-primary fs-3 me-3"></i>
            <h3 class="fw-bold text-primary">Sinkronisasi AppKey dari Ezviz Console</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-start mb-5">
            <i class="bi bi-info-circle-fill fs-4 me-3 mt-1 text-info"></i>
            <div>
                Masukkan email dan password akun Ezviz Console, lalu klik <strong>"Sync AppKey"</strong>
                untuk memperbarui <strong>AppKey</strong> &amp; <strong>Secret</strong> secara otomatis.
                <br><small class="text-muted">Proses membutuhkan waktu ±30 detik.</small>
            </div>
        </div>
        <div class="row g-4 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email Ezviz Console</label>
                <input type="email" id="scrape_email" class="form-control"
                    value="{{ $data['ezvizAkun']->email_terdaftar ?? '' }}" placeholder="user@gmail.com" />
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <input type="password" id="scrape_password" class="form-control"
                        placeholder="Password Ezviz Console" />
                    <button type="button" class="btn btn-outline-secondary" id="toggleScrapePass">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" id="btnScrape" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-repeat me-2"></i>Sync AppKey
                </button>
            </div>
        </div>
        <div id="scrapeStatus" class="mt-4 d-none">
            <div id="scrapeLoading" class="d-none">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-primary me-3" role="status"></div>
                    <span class="text-muted">Sedang mengambil AppKey dari Ezviz Console...</span>
                </div>
            </div>
            <div id="scrapeSuccess" class="alert alert-success d-none">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> AppKey dan Secret telah diperbarui di form di bawah.
            </div>
            <div id="scrapeError" class="alert alert-danger d-none">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal:</strong> <span id="scrapeErrorMsg"></span>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- FORM EDIT UTAMA --}}
{{-- ======================================================= --}}
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Edit Akun Ezviz: {{ $data['ezvizAkun']->nama_akun }}</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST"
            action="{{ url('/panel/masterData/updateEzvizAkun/' . $data['ezvizAkun']->id_ezviz_akun . '/save') }}">
            @csrf
            <div class="row g-5">
                {{-- Kolom Kiri --}}
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control"
                            value="{{ old('nama_akun', $data['ezvizAkun']->nama_akun) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Email Terdaftar (Ezviz Console)</label>
                        <input type="email" name="email_terdaftar" class="form-control"
                            value="{{ old('email_terdaftar', $data['ezvizAkun']->email_terdaftar) }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">
                            Password Ezviz Console
                            <small class="text-muted">(kosongkan jika tidak diubah)</small>
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_console" id="field_password_console"
                                class="form-control" placeholder="Kosongkan jika tidak diubah" />
                            <button type="button" class="btn btn-outline-secondary" id="toggleConsolePass">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted">Disimpan terenkripsi. Digunakan untuk refresh AppKey otomatis.
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-semibold">
                            AppKey
                            <span class="badge badge-light-info ms-2 fs-8">Terisi otomatis saat sync</span>
                        </label>
                        <input type="text" name="app_key" id="field_app_key" class="form-control font-monospace"
                            value="{{ old('app_key', $data['ezvizAkun']->app_key) }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">
                            Secret (App Secret)
                            <small class="text-muted">(kosongkan jika tidak diubah)</small>
                        </label>
                        <div class="input-group">
                            <input type="password" name="app_secret" id="field_app_secret"
                                class="form-control font-monospace" placeholder="Kosongkan jika tidak diubah" />
                            <button type="button" class="btn btn-outline-secondary" id="toggleSecret">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">API URL</label>
                        <input type="text" name="api_url" class="form-control"
                            value="{{ old('api_url', $data['ezvizAkun']->api_url ?? 'https://isgpopen.ezvizlife.com') }}" />
                        <div class="form-text text-muted">
                            Akun Internasional: https://isgpopen.ezvizlife.com &nbsp;|
                            Akun China: https://open.ys7.com
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Status Token</label>
                        <div class="form-control bg-light">
                            @if($data['ezvizAkun']->access_token && $data['ezvizAkun']->token_expiry && now()->lt($data['ezvizAkun']->token_expiry))
                                <span class="text-success fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>Valid hingga
                                    {{ \Carbon\Carbon::parse($data['ezvizAkun']->token_expiry)->format('d/m/Y H:i') }}
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
                        <textarea name="deskripsi" class="form-control"
                            rows="3">{{ old('deskripsi', $data['ezvizAkun']->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                {{-- Hidden fields untuk token hasil scraping --}}
                <input type="hidden" name="scraped_access_token" id="hidden_access_token" value="" />
                <input type="hidden" name="scraped_token_expiry" id="hidden_token_expiry" value="" />
                <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ======================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function togglePassword(btnId, inputId) {
            document.getElementById(btnId)?.addEventListener('click', function () {
                const inp = document.getElementById(inputId);
                const icon = this.querySelector('i');
                inp.type = inp.type === 'password' ? 'text' : 'password';
                icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
            });
        }
        togglePassword('toggleScrapePass', 'scrape_password');
        togglePassword('toggleConsolePass', 'field_password_console');
        togglePassword('toggleSecret', 'field_app_secret');

        document.getElementById('btnScrape').addEventListener('click', async function () {
            const email = document.getElementById('scrape_email').value.trim();
            const password = document.getElementById('scrape_password').value.trim();
            if (!email || !password) {
                alert('Masukkan email dan password Ezviz Console terlebih dahulu.');
                return;
            }

            const status = document.getElementById('scrapeStatus');
            status.classList.remove('d-none');
            document.getElementById('scrapeLoading').classList.remove('d-none');
            document.getElementById('scrapeSuccess').classList.add('d-none');
            document.getElementById('scrapeError').classList.add('d-none');
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengambil...';

            try {
                const response = await fetch('{{ url("/panel/masterData/scrapeEzvizAppKey") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ email, password }),
                });

                const result = await response.json();

                if (result.success) {
                    if (result.appKey)    document.getElementById('field_app_key').value    = result.appKey;
                    if (result.appSecret) document.getElementById('field_app_secret').value = result.appSecret;

                    // Simpan token ke hidden fields agar ikut di-POST saat Update
                    if (result.accessToken) {
                        document.getElementById('hidden_access_token').value = result.accessToken;
                        document.getElementById('hidden_token_expiry').value  = result.tokenExpiry || '';
                        document.getElementById('scrapeSuccess').innerHTML =
                            '<i class="bi bi-check-circle-fill me-2"></i>' +
                            '<strong>Berhasil!</strong> AppKey, Secret, dan Access Token telah diperbarui. ' +
                            '<span class="text-muted">Berlaku hingga: ' + (result.tokenExpiry || '-') + '</span>';
                    } else {
                        document.getElementById('hidden_access_token').value = '';
                        document.getElementById('hidden_token_expiry').value  = '';
                        const tokenWarn = result.tokenError ? ' <small class="text-warning">[Token: ' + result.tokenError + ']</small>' : '';
                        document.getElementById('scrapeSuccess').innerHTML =
                            '<i class="bi bi-check-circle-fill me-2"></i>' +
                            '<strong>Berhasil!</strong> AppKey & Secret diperbarui. Token akan diambil saat Simpan.' + tokenWarn;
                    }
                    document.getElementById('scrapeSuccess').classList.remove('d-none');
                } else {
                    document.getElementById('scrapeErrorMsg').textContent = result.message || 'Terjadi kesalahan.';
                    document.getElementById('scrapeError').classList.remove('d-none');
                }
            } catch (err) {
                document.getElementById('scrapeErrorMsg').textContent = 'Koneksi gagal: ' + err.message;
                document.getElementById('scrapeError').classList.remove('d-none');
            } finally {
                document.getElementById('scrapeLoading').classList.add('d-none');
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-arrow-repeat me-2"></i>Sync AppKey';
            }
        });
    });
</script>
