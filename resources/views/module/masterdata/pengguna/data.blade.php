<!-- Daftar Pengguna -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar Pengguna</h4>
        <span class="text-muted fs-7">Kelola akun pengguna sistem</span>
    </div>
    <a href="{{ url('/panel/masterData/tambahPengguna') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Pengguna
    </a>
</div>

<!-- Filter Card -->
<div class="card shadow-sm mb-5">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 mb-1">Status</label>
                <select id="filter-status" class="form-select form-select-sm">
                    <option value="all">Semua Status</option>
                    <option value="actived">Aktif</option>
                    <option value="not_actived">Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 mb-1">Hak Akses</label>
                <select id="filter-hak-akses" class="form-select form-select-sm">
                    <option value="all">Semua Hak Akses</option>
                    @foreach($data['hakAksesList'] as $ha)
                    <option value="{{ $ha->nama_hak_akses }}">{{ $ha->nama_hak_akses }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button id="btn-reset-filter" class="btn btn-sm btn-light-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-pengguna" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start">#</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Hak Akses</th>
                        <th>Email</th>
                        <th class="text-center">Status</th>
                        <th>Login Terakhir</th>
                        <th class="text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = $('#table-pengguna').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/panel/masterData/daftarPengguna") }}',
            data: function (d) {
                d.filter_status     = $('#filter-status').val();
                d.filter_hak_akses  = $('#filter-hak-akses').val();
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + m.settings._iDisplayStart + 1, orderable: false, searchable: false },
            { data: 'username_html',    name: 'username',     orderable: true },
            { data: 'nama_lengkap',     name: 'nama_lengkap', orderable: true },
            { data: 'hak_akses_html',   name: 'hak_akses',    orderable: true },
            { data: 'email',            name: 'email',        render: d => d ? `<span class="text-muted">${d}</span>` : '<span class="text-muted">-</span>' },
            { data: 'status_html',      name: 'status',       orderable: true, className: 'text-center' },
            { data: 'last_login_html',  name: 'last_login',   orderable: true },
            { data: 'aksi',             name: 'aksi',         orderable: false, searchable: false, className: 'text-end pe-4' },
        ],
        order: [[2, 'asc']],
        pageLength: 25,
        language: {
            processing:  '<span class="text-muted">Memuat data...</span>',
            emptyTable:  '<i class="bi bi-people fs-1 d-block mb-2 text-muted"></i><span class="text-muted">Belum ada data pengguna</span>',
            zeroRecords: '<span class="text-muted">Tidak ada data yang sesuai filter</span>',
            lengthMenu:  'Tampilkan _MENU_ data',
            info:        'Menampilkan _START_-_END_ dari _TOTAL_ pengguna',
            infoEmpty:   'Tidak ada data',
            search:      'Cari:',
            paginate:    { previous: '&lsaquo;', next: '&rsaquo;' },
        },
    });

    // Reload on filter change
    $('#filter-status, #filter-hak-akses').on('change', function () {
        table.ajax.reload();
    });

    // Reset filter
    $('#btn-reset-filter').on('click', function () {
        $('#filter-status').val('all');
        $('#filter-hak-akses').val('all');
        table.search('').ajax.reload();
    });
});
</script>
