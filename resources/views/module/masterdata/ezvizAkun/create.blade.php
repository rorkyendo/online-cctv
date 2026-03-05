<div class="card shadow-sm mb-5">
    <div class="card-header border-0 pt-6 bg-light-primary">
        <div class="card-title">
            <i class="bi bi-magic text-primary fs-3 me-3"></i>
            <h3 class="fw-bold text-primary">Ambil AppKey Otomatis dari Ezviz Console</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info d-flex align-items-start mb-5">
            <i class="bi bi-info-circle-fill fs-4 me-3 mt-1 text-info"></i>
            <div>
                <strong>Cara Cepat:</strong> Masukkan email dan password akun Ezviz Console Anda di bawah ini,
                lalu klik tombol <strong>"Ambil AppKey"</strong>. Sistem akan otomatis login ke
                <a href="https://isgpopen.ezviz.com" target="_blank">isgpopen.ezviz.com</a> dan mengisi
                <strong>AppKey</strong> &amp; <strong>Secret</strong> di form bawah secara otomatis.
                <br><small class="text-muted">Proses ini membutuhkan waktu ~30 detik. Pilih tipe login sesuai akun Anda.</small>
            </div>
        </div>

        {{-- Tipe Login --}}
        <div class="mb-4">
            <label class="form-label fw-semibold d-block">Tipe Login Akun</label>
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="scrape_login_type" id="loginTypeEzviz" value="ezviz" checked>
                <label class="btn btn-outline-primary" for="loginTypeEzviz">
                    <i class="bi bi-camera-video me-1"></i> EZVIZ
                </label>
                <input type="radio" class="btn-check" name="scrape_login_type" id="loginTypeHikconnect" value="hikconnect">
                <label class="btn btn-outline-info" for="loginTypeHikconnect">
                    <i class="bi bi-shield-lock me-1"></i> Hik-Connect
                </label>
            </div>
            <div class="form-text text-muted mt-1">
                Pilih <strong>Hik-Connect</strong> jika akun terdaftar melalui aplikasi Hik-Connect / Hikvision.
            </div>
        </div>

        <div class="row g-4 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email / Username Ezviz Console</label>
                <input type="email" id="scrape_email" class="form-control" placeholder="Contoh: user@gmail.com" />
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Password Ezviz Console</label>
                <div class="input-group">
                    <input type="password" id="scrape_password" class="form-control"
                        placeholder="Password akun Ezviz Console" />
                    <button type="button" class="btn btn-outline-secondary" id="toggleScrapePass">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <button type="button" id="btnScrape" class="btn btn-primary w-100">
                    <i class="bi bi-cloud-download me-2"></i>Ambil AppKey
                </button>
            </div>
        </div>

        {{-- Status scraping --}}
        <div id="scrapeStatus" class="mt-4 d-none">
            <div id="scrapeLoading" class="d-none">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm text-primary me-3" role="status"></div>
                    <span class="text-muted">Sedang login ke Ezviz Console dan mengambil AppKey... (±30 detik)</span>
                </div>
            </div>
            <div id="scrapeSuccess" class="alert alert-success d-none">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> AppKey dan Secret telah diisi otomatis di form di bawah.
                Silakan lengkapi field lain dan klik <strong>Simpan</strong>.
            </div>
            <div id="scrapeError" class="alert alert-danger d-none">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal:</strong> <span id="scrapeErrorMsg"></span>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- FORM MANUAL / FORM UTAMA --}}
{{-- ======================================================= --}}
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Data Akun Ezviz</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/masterData/tambahEzvizAkun/save') }}">
            @csrf
            <div class="row g-5">
                {{-- Kolom Kiri --}}
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama Akun</label>
                        <input type="text" name="nama_akun" class="form-control"
                            placeholder="Contoh: Akun Ezviz Gedung A" value="{{ old('nama_akun') }}" required />
                        <div class="form-text text-muted">Nama untuk mengidentifikasi akun ini di sistem</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Email Terdaftar (Ezviz Console)</label>
                        <input type="email" name="email_terdaftar" id="field_email_terdaftar" class="form-control"
                            placeholder="Email yang didaftarkan di Ezviz Console"
                            value="{{ old('email_terdaftar') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">
                            Password Ezviz Console
                            <span class="badge badge-light-warning ms-2 fs-8">Opsional — untuk scraping otomatis</span>
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_console" id="field_password_console"
                                class="form-control"
                                placeholder="Simpan untuk otomatisasi scraping di masa mendatang" />
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
                            <span class="badge badge-light-info ms-2 fs-8">Terisi otomatis saat scraping</span>
                        </label>
                        <input type="text" name="app_key" id="field_app_key" class="form-control font-monospace"
                            placeholder="Akan terisi otomatis setelah klik 'Ambil AppKey'"
                            value="{{ old('app_key') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">
                            Secret (App Secret)
                            <span class="badge badge-light-info ms-2 fs-8">Terisi otomatis saat scraping</span>
                        </label>
                        <div class="input-group">
                            <input type="password" name="app_secret" id="field_app_secret"
                                class="form-control font-monospace"
                                placeholder="Akan terisi otomatis setelah klik 'Ambil AppKey'" />
                            <button type="button" class="btn btn-outline-secondary" id="toggleSecret">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">API URL</label>
                        <input type="text" name="api_url" class="form-control" placeholder="https://isgpopen.ezvizlife.com"
                            value="{{ old('api_url', 'https://isgpopen.ezvizlife.com') }}" />
                        <div class="form-text text-muted">
                            Akun <strong>Internasional</strong>: https://isgpopen.ezvizlife.com &nbsp;|
                            Akun <strong>China</strong>: https://open.ys7.com
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Tipe Login</label>
                        <select name="login_type" id="field_login_type" class="form-select">
                            <option value="ezviz" {{ old('login_type') === 'hikconnect' ? '' : 'selected' }}>EZVIZ</option>
                            <option value="hikconnect" {{ old('login_type') === 'hikconnect' ? 'selected' : '' }}>Hik-Connect</option>
                        </select>
                        <div class="form-text text-muted">
                            Pilih <strong>Hik-Connect</strong> jika akun terdaftar melalui aplikasi Hik-Connect / Hikvision.
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                            placeholder="Catatan tentang akun ini">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="separator my-5"></div>

            {{-- AccessToken Info (readonly, akan terisi setelah scraping) --}}
            {{-- Hidden fields: token dikirim bersama form agar tidak perlu second API call --}}
            <input type="hidden" name="scraped_access_token" id="hidden_access_token" value="" />
            <input type="hidden" name="scraped_token_expiry" id="hidden_token_expiry" value="" />

            <div id="accessTokenSection" class="d-none mb-5">
                <h5 class="fw-semibold mb-3"><i class="bi bi-key me-2 text-success"></i>Access Token (dari Scraping)
                </h5>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label text-muted">AccessToken</label>
                        <input type="text" id="display_access_token" class="form-control font-monospace bg-light"
                            readonly placeholder="(akan terisi otomatis)" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Berlaku Hingga</label>
                        <input type="text" id="display_token_expiry" class="form-control bg-light" readonly />
                    </div>
                </div>
                <div id="tokenApiDebug" class="d-none mt-2">
                    <small class="text-muted">Debug API response: </small>
                    <pre id="tokenApiRaw" class="bg-light p-2 rounded small" style="max-height:120px;overflow:auto"></pre>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/masterData/daftarEzvizAkun') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan Akun
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

        // Toggle visibility helpers
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

        // ── Tombol Ambil AppKey ──────────────────────────────────
        // Sync radio button tipe login (scraping panel) → form select
        document.querySelectorAll('input[name="scrape_login_type"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const sel = document.getElementById('field_login_type');
                if (sel) sel.value = this.value;
            });
        });

        document.getElementById('btnScrape').addEventListener('click', async function () {
            const email = document.getElementById('scrape_email').value.trim();
            const password = document.getElementById('scrape_password').value.trim();
            const loginType = document.querySelector('input[name="scrape_login_type"]:checked')?.value || 'ezviz';

            if (!email || !password) {
                alert('Masukkan email dan password Ezviz Console terlebih dahulu.');
                return;
            }

            // Show loading
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
                    body: JSON.stringify({ email, password, login_type: loginType }),
                });

                const result = await response.json();

                if (result.success) {
                    // Isi field form secara otomatis
                    if (result.appKey) document.getElementById('field_app_key').value = result.appKey;
                    if (result.appSecret) document.getElementById('field_app_secret').value = result.appSecret;

                    // Isi email ke field email_terdaftar jika belum ada
                    const emailField = document.getElementById('field_email_terdaftar');
                    if (!emailField.value) emailField.value = email;

                    // Isi password_console
                    const passField = document.getElementById('field_password_console');
                    if (!passField.value) passField.value = password;

                    // Selalu tampilkan section token
                    document.getElementById('accessTokenSection').classList.remove('d-none');

                    if (result.accessToken) {
                        document.getElementById('display_access_token').value = result.accessToken;
                        document.getElementById('hidden_access_token').value  = result.accessToken;
                    } else {
                        document.getElementById('display_access_token').value = '(tidak tersedia — akan diambil saat simpan)';
                    }
                    if (result.tokenExpiry) {
                        document.getElementById('display_token_expiry').value = result.tokenExpiry;
                        document.getElementById('hidden_token_expiry').value  = result.tokenExpiry;
                    }

                    // Debug: tampilkan raw API response jika ada
                    if (result._tokenApiRaw) {
                        document.getElementById('tokenApiDebug').classList.remove('d-none');
                        document.getElementById('tokenApiRaw').textContent = JSON.stringify(result._tokenApiRaw, null, 2);
                    }

                    // Tampilkan peringatan jika token gagal diambil
                    if (result.tokenError) {
                        document.getElementById('display_access_token').value = '⚠ ' + result.tokenError;
                        document.getElementById('display_token_expiry').value = '-';
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
                this.innerHTML = '<i class="bi bi-cloud-download me-2"></i>Ambil AppKey';
            }
        });
    });
</script>
