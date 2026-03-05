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
                        @if(($akun->login_type ?? 'ezviz') === 'hikconnect')
                            <span class="badge badge-light-info ms-2 fs-9">
                                <i class="bi bi-shield-lock me-1"></i>Hik-Connect
                            </span>
                        @endif
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
                    <button class="btn btn-sm btn-light-success"
                            data-akun-id="{{ $akun->id_ezviz_akun }}"
                            data-akun-nama="{{ $akun->nama_akun }}"
                            data-has-password="{{ $akun->password_console ? '1' : '0' }}"
                            onclick="lihatDevice(this)"
                            title="Lihat &amp; Import Device dari Portal">
                        <i class="bi bi-camera-video"></i>
                    </button>
                    <button class="btn btn-sm btn-light-primary"
                            onclick="openAddDeviceModal({{ $akun->id_ezviz_akun }}, '{{ $akun->nama_akun }}')"
                            title="Tambah Device Baru ke Akun EZVIZ ini">
                        <i class="bi bi-plus-circle"></i>
                    </button>
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

{{-- =========================================================== --}}
{{-- MODAL: Daftar Device EZVIZ                                --}}
{{-- =========================================================== --}}
<div class="modal fade" id="modalDeviceList" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-camera-video me-2 text-success"></i>
                    Daftar Device — <span id="modalAkunNama" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Loading --}}
                <div id="deviceLoadingState" class="text-center py-10 d-none">
                    <div class="spinner-border text-primary mb-4" style="width:2.5rem;height:2.5rem"></div>
                    <p class="text-muted">Sedang login ke EZVIZ portal dan mengambil daftar device...<br>
                        <small>Proses ini membutuhkan ~30 detik.</small></p>
                </div>
                {{-- Error --}}
                <div id="deviceErrorState" class="d-none">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span id="deviceErrorMsg"></span>
                    </div>
                </div>
                {{-- No password --}}
                <div id="deviceNoPasswordState" class="d-none">
                    <div class="alert alert-warning">
                        <i class="bi bi-key me-2"></i>
                        <strong>Password console belum tersimpan</strong> untuk akun ini.<br>
                        Silakan <a id="linkEditAkun" href="#" class="alert-link">edit akun</a> dan simpan
                        <em>Password Ezviz Console</em> agar device dapat diambil otomatis.
                    </div>
                </div>
                {{-- Device table --}}
                <div id="deviceTableState" class="d-none">
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <span id="deviceTotalMsg"></span>
                        <span class="ms-2 text-muted fs-7">— Klik <strong>Tambah</strong> untuk memasukkan device ke sistem Anda.</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Serial Number</th>
                                    <th>Nama (Portal)</th>
                                    <th>Status</th>
                                    <th>Ch.</th>
                                    <th>Tgl. Tambah</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="deviceTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================== --}}
{{-- MODAL: Import Device ke Sistem                             --}}
{{-- =========================================================== --}}
<div class="modal fade" id="modalImportDevice" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-success">
                <h5 class="modal-title text-success">
                    <i class="bi bi-plus-square me-2"></i>Tambah Device ke Sistem
                </h5>
                <button type="button" class="btn-close" onclick="backToDeviceList()"></button>
            </div>
            <div class="modal-body">
                <div class="p-4 bg-light rounded mb-6 d-flex align-items-center gap-4">
                    <i class="bi bi-camera-video fs-1 text-success"></i>
                    <div>
                        <div class="fw-bold fs-5 font-monospace" id="importSerialDisplay"></div>
                        <div class="text-muted fs-7" id="importNameDisplay"></div>
                    </div>
                </div>

                <input type="hidden" id="importAkunId">
                <input type="hidden" id="importSerial">
                <input type="hidden" id="importChannelNo">
                <input type="hidden" id="importDeviceStatus">

                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label class="form-label required fw-semibold">Nama CCTV di Sistem</label>
                            <input type="text" id="importNamaCctv" class="form-control"
                                placeholder="Contoh: Kamera Pintu Depan">
                            <div class="form-text text-muted">Nama yang muncul di dashboard sistem kita</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required fw-semibold">Lokasi</label>
                            <select id="importLokasi" class="form-select">
                                <option value="">— Pilih Lokasi —</option>
                                @php $lastGroup = '' @endphp
                                @foreach($data['lokasiList'] as $lok)
                                    @if($lok->nama_group !== $lastGroup)
                                        @if($lastGroup !== '') </optgroup> @endif
                                        <optgroup label="{{ $lok->nama_group }}">
                                        @php $lastGroup = $lok->nama_group @endphp
                                    @endif
                                    <option value="{{ $lok->id_lokasi }}">{{ $lok->nama_lokasi }}</option>
                                @endforeach
                                @if($lastGroup !== '') </optgroup> @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-5">
                            <label class="form-label fw-semibold">Validation Code</label>
                            <input type="text" id="importValidCode" class="form-control font-monospace"
                                placeholder="6 karakter, dari stiker device" maxlength="10">
                            <div class="form-text text-muted">Tertera di stiker belakang/bawah kamera</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-semibold">Posisi / Label</label>
                            <input type="text" id="importPosisi" class="form-control"
                                placeholder="Contoh: Pojok NE, Pintu utama">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="backToDeviceList()">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </button>
                <button type="button" class="btn btn-success" id="btnDoImport" onclick="doImport()">
                    <i class="bi bi-plus-circle me-2"></i>Tambahkan ke Sistem
                </button>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================== --}}
{{-- MODAL: Tambah Device ke Akun EZVIZ                        --}}
{{-- =========================================================== --}}
<div class="modal fade" id="modalAddDevice" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>
                    Tambah Device ke Akun: <span id="addDevAkunNama" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="addDevAkunId">

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Masukkan <strong>Serial Number</strong> dan <strong>Verification Code</strong> yang tertera
                    pada <strong>stiker di bagian bawah / belakang kamera</strong>.
                </div>

                <div class="mb-4">
                    <label class="form-label required fw-semibold">Device Serial Number</label>
                    <input type="text" id="addDevSerial" class="form-control font-monospace text-uppercase"
                           placeholder="Contoh: BG8997978" maxlength="20"
                           oninput="this.value = this.value.toUpperCase()">
                    <div class="form-text text-muted">9 karakter, contoh: BG8997978 atau K89195808</div>
                </div>

                <div class="mb-2">
                    <label class="form-label required fw-semibold">Device Verification Code</label>
                    <input type="text" id="addDevCode" class="form-control font-monospace"
                           placeholder="Contoh: ABCD12" maxlength="20">
                    <div class="form-text text-muted">
                        Biasanya 6 karakter, tertera di stiker kamera dengan label
                        <em>"Verification Code"</em> atau <em>"Code"</em>.
                    </div>
                </div>

                <div id="addDevResult" class="mt-4 d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnDoAddDevice" onclick="doAddDevice()">
                    <i class="bi bi-plus-circle me-2"></i>Tambahkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let activeAkunId = null;

// ── Refresh Token ────────────────────────────────────────────
function refreshToken(id, btn) {
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    fetch(`/panel/cctv/refreshToken/${id}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
        if (data.success) { alert('Token berhasil diperbarui!'); location.reload(); }
        else { alert('Gagal refresh token: ' + (data.message || 'Error')); }
    })
    .catch(e => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i>';
        alert('Error: ' + e.message);
    });
}

// ── Lihat Device ─────────────────────────────────────────────
function lihatDevice(btn) {
    const akunId   = btn.dataset.akunId;
    const akunNama = btn.dataset.akunNama;
    const hasPass  = btn.dataset.hasPassword === '1';

    activeAkunId = akunId;
    document.getElementById('modalAkunNama').textContent = akunNama;

    ['deviceLoadingState','deviceErrorState','deviceTableState','deviceNoPasswordState']
        .forEach(id => document.getElementById(id).classList.add('d-none'));

    const modal = new bootstrap.Modal(document.getElementById('modalDeviceList'));
    modal.show();

    if (!hasPass) {
        document.getElementById('linkEditAkun').href = '/panel/masterData/updateEzvizAkun/' + akunId;
        document.getElementById('deviceNoPasswordState').classList.remove('d-none');
        return;
    }

    document.getElementById('deviceLoadingState').classList.remove('d-none');

    fetch('{{ url("/panel/masterData/scrapeEzvizDevices") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({id_ezviz_akun: akunId})
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('deviceLoadingState').classList.add('d-none');
        if (!data.success) {
            document.getElementById('deviceErrorMsg').textContent = data.message || 'Terjadi kesalahan.';
            document.getElementById('deviceErrorState').classList.remove('d-none');
            return;
        }
        renderDeviceTable(data.devices, data.total);
    })
    .catch(e => {
        document.getElementById('deviceLoadingState').classList.add('d-none');
        document.getElementById('deviceErrorMsg').textContent = 'Koneksi gagal: ' + e.message;
        document.getElementById('deviceErrorState').classList.remove('d-none');
    });
}

// ── Render tabel device ──────────────────────────────────────
function renderDeviceTable(devices, total) {
    document.getElementById('deviceTotalMsg').textContent = total + ' device ditemukan';
    const tbody = document.getElementById('deviceTableBody');
    tbody.innerHTML = '';

    if (!devices || devices.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-6">Tidak ada device di akun ini.</td></tr>';
    } else {
        devices.forEach(dev => {
            const badge = dev.status === 'online'
                ? '<span class="badge badge-light-success"><i class="bi bi-circle-fill me-1 fs-9"></i>Online</span>'
                : '<span class="badge badge-light-secondary"><i class="bi bi-circle me-1 fs-9"></i>Offline</span>';

            // Unique key: serial + channel (karena NVR punya banyak channel dengan serial yang sama)
            const rowKey = dev.serial + '-ch' + dev.channel_no;

            const action = dev.already_added
                ? '<span class="badge badge-light-success"><i class="bi bi-check-circle me-1"></i>Sudah ditambahkan</span>'
                : `<button class="btn btn-sm btn-success" onclick="openImportForm('${dev.serial}','${(dev.name||'').replace(/'/g,"\\'")}',${dev.channel_no},'${dev.status}')"><i class="bi bi-plus me-1"></i>Tambah</button>`;

            // Badge tipe device
            const typeBadge = dev.device_type
                ? (dev.device_type === 'NVR-CH'
                    ? '<span class="badge badge-light-primary ms-1 fs-9">CH</span>'
                    : '<span class="badge badge-light-warning ms-1 fs-9">' + dev.device_type + '</span>')
                : '';

            // Info nama: jika NVR channel, tampilkan parent name
            const nameDisplay = dev.parent_name
                ? `${dev.name || '-'} <small class="text-muted d-block">${dev.parent_name}</small>`
                : (dev.name || '-');

            tbody.innerHTML += `
                <tr id="row-${rowKey}"${dev.already_added ? ' class="table-light"' : ''}>
                    <td><span class="font-monospace fw-bold text-dark">${dev.serial}</span>${typeBadge}</td>
                    <td>${nameDisplay}</td>
                    <td>${badge}</td>
                    <td>${dev.channel_no}</td>
                    <td class="text-muted fs-7">${dev.adding_time || '-'}</td>
                    <td class="text-end" id="action-${rowKey}">${action}</td>
                </tr>`;
        });
    }
    document.getElementById('deviceTableState').classList.remove('d-none');
}

// ── Buka form import ─────────────────────────────────────────
function openImportForm(serial, name, channelNo, status) {
    document.getElementById('importAkunId').value       = activeAkunId;
    document.getElementById('importSerial').value       = serial;
    document.getElementById('importChannelNo').value    = channelNo;
    document.getElementById('importDeviceStatus').value = status;
    document.getElementById('importNamaCctv').value     = name;
    document.getElementById('importValidCode').value    = '';
    document.getElementById('importPosisi').value       = '';
    document.getElementById('importLokasi').value       = '';
    document.getElementById('importSerialDisplay').textContent = 'Serial: ' + serial;
    document.getElementById('importNameDisplay').textContent   = 'Nama di portal: ' + name;

    bootstrap.Modal.getInstance(document.getElementById('modalDeviceList')).hide();
    new bootstrap.Modal(document.getElementById('modalImportDevice')).show();
}

// ── Kembali ke daftar device ─────────────────────────────────
function backToDeviceList() {
    const im = bootstrap.Modal.getInstance(document.getElementById('modalImportDevice'));
    if (im) im.hide();
    setTimeout(() => {
        const lm = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDeviceList'));
        lm.show();
    }, 300);
}

// ── Simpan import device ─────────────────────────────────────
function doImport() {
    const namaCctv = document.getElementById('importNamaCctv').value.trim();
    const lokasi   = document.getElementById('importLokasi').value;

    if (!namaCctv) { alert('Nama CCTV wajib diisi.'); return; }
    if (!lokasi)   { alert('Lokasi wajib dipilih.');  return; }

    const btn = document.getElementById('btnDoImport');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

    fetch('{{ url("/panel/cctv/importDevice") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({
            id_ezviz_akun: document.getElementById('importAkunId').value,
            device_serial: document.getElementById('importSerial').value,
            channel_no:    document.getElementById('importChannelNo').value,
            device_status: document.getElementById('importDeviceStatus').value,
            nama_cctv:     namaCctv,
            id_lokasi:     lokasi,
            validCode:     document.getElementById('importValidCode').value,
            posisi:        document.getElementById('importPosisi').value,
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambahkan ke Sistem';

        if (data.success) {
            const serial = document.getElementById('importSerial').value;
            const cell = document.getElementById('action-' + serial);
            if (cell) cell.innerHTML = '<span class="badge badge-light-success"><i class="bi bi-check-circle me-1"></i>Sudah ditambahkan</span>';
            const row = document.getElementById('row-' + serial);
            if (row) row.classList.add('table-light');

            backToDeviceList();
            setTimeout(() => alert('✓ ' + data.message), 350);
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
        }
    })
    .catch(e => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambahkan ke Sistem';
        alert('Error: ' + e.message);
    });
}

// ── Tambah Device ke EZVIZ ───────────────────────────────────
function openAddDeviceModal(akunId, akunNama) {
    document.getElementById('addDevAkunId').value        = akunId;
    document.getElementById('addDevAkunNama').textContent = akunNama;
    document.getElementById('addDevSerial').value        = '';
    document.getElementById('addDevCode').value          = '';
    document.getElementById('addDevResult').classList.add('d-none');
    document.getElementById('addDevResult').className    = 'mt-4 d-none';
    new bootstrap.Modal(document.getElementById('modalAddDevice')).show();
}

function doAddDevice() {
    const serial = document.getElementById('addDevSerial').value.trim().toUpperCase();
    const code   = document.getElementById('addDevCode').value.trim();
    const akunId = document.getElementById('addDevAkunId').value;
    const result = document.getElementById('addDevResult');

    if (!serial) { alert('Serial number wajib diisi.'); return; }
    if (!code)   { alert('Verification code wajib diisi.'); return; }

    const btn = document.getElementById('btnDoAddDevice');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghubungi EZVIZ...';
    result.classList.add('d-none');

    fetch('{{ url("/panel/masterData/addDeviceToEzviz") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ id_ezviz_akun: akunId, device_serial: serial, device_code: code })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambahkan';
        result.classList.remove('d-none');
        if (data.success) {
            result.className = 'mt-4 alert alert-success';
            result.innerHTML = '<i class="bi bi-check-circle me-2"></i><strong>Berhasil!</strong> ' +
                'Device <code>' + serial + '</code> berhasil ditambahkan ke akun EZVIZ. ' +
                'Kini Anda bisa import device tersebut ke sistem dengan tombol <i class="bi bi-camera-video"></i>.';
            document.getElementById('addDevSerial').value = '';
            document.getElementById('addDevCode').value   = '';
        } else {
            result.className = 'mt-4 alert alert-danger';
            result.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>' + (data.message || 'Terjadi kesalahan.');
        }
    })
    .catch(e => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambahkan';
        result.classList.remove('d-none');
        result.className = 'mt-4 alert alert-danger';
        result.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Koneksi error: ' + e.message;
    });
}
</script>
