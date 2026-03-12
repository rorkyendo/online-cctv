<!-- Daftar CCTV -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar CCTV</h4>
        <span class="text-muted fs-7">Kelola semua perangkat CCTV</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light-info" onclick="openSyncModal()">
            <i class="bi bi-arrow-repeat me-2"></i>Sync Ezviz
        </button>
        <a href="{{ url('/panel/cctv/tambahCCTV') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah CCTV
        </a>
    </div>
</div>

{{-- Filter Card --}}
<div class="card shadow-sm mb-5">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold fs-7 text-muted mb-1">
                    <i class="bi bi-collection me-1"></i>Group
                </label>
                <select id="filter-group" class="form-select form-select-sm">
                    <option value="">Semua Group</option>
                    @foreach($data['grupList'] as $grup)
                        <option value="{{ $grup->id_group }}">{{ $grup->nama_group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label fw-semibold fs-7 text-muted mb-1">
                    <i class="bi bi-geo-alt me-1"></i>Lokasi
                </label>
                <select id="filter-lokasi" class="form-select form-select-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($data['lokasiList'] as $lokasi)
                        <option value="{{ $lokasi->id_lokasi }}" data-group="{{ $lokasi->id_group }}">{{ $lokasi->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label fw-semibold fs-7 text-muted mb-1">
                    <i class="bi bi-wifi me-1"></i>Status
                </label>
                <select id="filter-status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <button type="button" class="btn btn-primary btn-sm" onclick="applyFilter()">
                    <i class="bi bi-search me-1"></i>Terapkan
                </button>
                <button type="button" class="btn btn-light btn-sm" onclick="resetFilter()">
                    <i class="bi bi-x-circle me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

{{-- DataTable Card --}}
<div class="card shadow-sm">
    <div class="card-body pt-4">
        <div class="table-responsive">
            <table id="cctv-table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-40px rounded-start">#</th>
                        <th class="min-w-180px">Nama CCTV</th>
                        <th class="min-w-140px">Lokasi</th>
                        <th class="min-w-120px">Group</th>
                        <th class="min-w-130px">Serial / Ch</th>
                        <th class="min-w-110px">Akun Ezviz</th>
                        <th class="min-w-90px text-center">Status</th>
                        <th class="min-w-110px text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Sync Modal --}}
<div class="modal fade" id="syncModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-arrow-repeat me-2"></i>Sync Perangkat Ezviz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="sync-form">
                    <label class="form-label fw-semibold">Pilih Akun Ezviz</label>
                    <select id="sync-akun" class="form-select mb-4">
                        @foreach($data['ezvizAkunList'] ?? [] as $akun)
                            <option value="{{ $akun->id_ezviz_akun }}">{{ $akun->nama_akun }}</option>
                        @endforeach
                    </select>
                    <p class="text-muted fs-7 mb-0">
                        Sinkronisasi akan mengambil daftar perangkat terbaru dari akun Ezviz yang dipilih dan menambahkan yang belum terdaftar.
                    </p>
                </div>
                <div id="sync-loading" style="display:none;" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 text-muted">Sedang sinkronisasi...</p>
                </div>
                <div id="sync-result" style="display:none;"></div>
            </div>
            <div class="modal-footer" id="sync-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="doSync()">
                    <i class="bi bi-arrow-repeat me-2"></i>Sync
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// -------------------------------------------------------
// Lokasi cascade filter by Group
// -------------------------------------------------------
const allLokasiOptions = Array.from(document.querySelectorAll('#filter-lokasi option[data-group]'));

document.getElementById('filter-group').addEventListener('change', function () {
    const selectedGroup = this.value;
    const lokasiSel = document.getElementById('filter-lokasi');
    const currentVal = lokasiSel.value;

    // Remove all dynamic options
    allLokasiOptions.forEach(opt => opt.remove());

    // Re-add filtered options
    allLokasiOptions.forEach(opt => {
        if (!selectedGroup || opt.getAttribute('data-group') === selectedGroup) {
            lokasiSel.appendChild(opt);
        }
    });

    // Reset lokasi selection if the selected option is no longer visible
    if (selectedGroup && !lokasiSel.querySelector(`option[value="${currentVal}"]`)) {
        lokasiSel.value = '';
    }
});

// -------------------------------------------------------
// DataTable init
// -------------------------------------------------------
let cctvTable;

$(document).ready(function () {
    cctvTable = $('#cctv-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/panel/cctv/datatablesCCTV") }}',
            type: 'GET',
            data: function (d) {
                d.filter_status = $('#filter-status').val();
                d.filter_lokasi = $('#filter-lokasi').val();
                d.filter_group  = $('#filter-group').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',      name: 'DT_RowIndex',   orderable: false, searchable: false, className: 'ps-4' },
            { data: 'nama_cctv_html',   name: 'nama_cctv',     orderable: true },
            { data: 'nama_lokasi_html', name: 'nama_lokasi',   orderable: true },
            { data: 'nama_group_html',  name: 'nama_group',    orderable: true },
            {
                data: null,
                name: 'device_serial',
                orderable: false,
                render: function (data) {
                    const serial  = data.device_serial ?? '-';
                    const channel = data.channel_no ?? '';
                    return '<span class="text-muted fs-7 font-monospace">' + serial + (channel ? ' / Ch' + channel : '') + '</span>';
                }
            },
            { data: 'nama_akun', name: 'nama_akun', orderable: false, className: 'text-muted fs-7',
              render: function (data) { return data ?? '-'; } },
            { data: 'status_html',  name: 'status', orderable: true, className: 'text-center' },
            { data: 'aksi',         name: 'aksi',   orderable: false, searchable: false, className: 'text-end pe-4' },
        ],
        order: [[2, 'asc'], [1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'Semua']],
        language: {
            processing:     '<div class="d-flex align-items-center gap-2"><div class="spinner-border spinner-border-sm text-primary"></div><span class="text-muted ms-2">Memuat data...</span></div>',
            search:         '',
            searchPlaceholder: 'Cari nama, serial...',
            lengthMenu:     'Tampilkan _MENU_ data',
            info:           'Menampilkan _START_ \u2013 _END_ dari _TOTAL_ CCTV',
            infoEmpty:      'Tidak ada data yang ditemukan',
            infoFiltered:   '(difilter dari _MAX_ total data)',
            zeroRecords:    '<div class="text-center py-8 text-muted"><i class="bi bi-camera-video fs-1 d-block mb-3"></i>Tidak ada data CCTV</div>',
            emptyTable:     '<div class="text-center py-8 text-muted"><i class="bi bi-camera-video fs-1 d-block mb-3"></i>Belum ada data CCTV</div>',
            paginate: {
                first:    '&laquo;',
                last:     '&raquo;',
                next:     '&rsaquo;',
                previous: '&lsaquo;',
            },
        },
        dom: "<'row align-items-center mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
             "<'row'<'col-12'tr>>" +
             "<'row align-items-center mt-3'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
        responsive: true,
    });
});

// -------------------------------------------------------
// Filter actions
// -------------------------------------------------------
function applyFilter() {
    if (cctvTable) cctvTable.ajax.reload();
}

function resetFilter() {
    document.getElementById('filter-group').value  = '';
    document.getElementById('filter-status').value = '';

    // Restore all lokasi options then reset
    const lokasiSel = document.getElementById('filter-lokasi');
    allLokasiOptions.forEach(opt => {
        if (!lokasiSel.querySelector(`option[value="${opt.value}"]`)) {
            lokasiSel.appendChild(opt);
        }
    });
    lokasiSel.value = '';

    if (cctvTable) cctvTable.ajax.reload();
}

// -------------------------------------------------------
// Sync Modal
// -------------------------------------------------------
function openSyncModal() {
    document.getElementById('sync-form').style.display = 'block';
    document.getElementById('sync-loading').style.display = 'none';
    document.getElementById('sync-result').style.display = 'none';
    document.getElementById('sync-footer').style.display = 'flex';
    new bootstrap.Modal(document.getElementById('syncModal')).show();
}

function doSync() {
    const idAkun = document.getElementById('sync-akun').value;
    if (!idAkun) { alert('Pilih akun Ezviz terlebih dahulu'); return; }

    document.getElementById('sync-form').style.display = 'none';
    document.getElementById('sync-footer').style.display = 'none';
    document.getElementById('sync-loading').style.display = 'block';
    document.getElementById('sync-result').style.display = 'none';

    fetch('/panel/cctv/syncDevices', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id_ezviz_akun: idAkun })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('sync-loading').style.display = 'none';
        document.getElementById('sync-result').style.display = 'block';
        document.getElementById('sync-footer').style.display = 'flex';
        document.getElementById('sync-footer').innerHTML =
            '<button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="cctvTable && cctvTable.ajax.reload()">OK, Tutup</button>';

        if (data.success) {
            const count = data.data ? (Array.isArray(data.data) ? data.data.length : '') : '';
            document.getElementById('sync-result').innerHTML =
                '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>'
                + 'Sinkronisasi berhasil' + (count ? '. Ditemukan ' + count + ' perangkat.' : '.') + '</div>';
        } else {
            document.getElementById('sync-result').innerHTML =
                '<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>'
                + 'Gagal: ' + (data.message || 'Error tidak diketahui') + '</div>';
        }
    })
    .catch(err => {
        document.getElementById('sync-loading').style.display = 'none';
        document.getElementById('sync-result').style.display = 'block';
        document.getElementById('sync-footer').style.display = 'flex';
        document.getElementById('sync-result').innerHTML =
            '<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>Koneksi error: ' + err.message + '</div>';
    });
}
</script>
