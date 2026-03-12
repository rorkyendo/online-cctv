<!-- Daftar Hak Akses -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar Hak Akses</h4>
        <span class="text-muted fs-7">Kelola peran dan hak akses pengguna</span>
    </div>
    <a href="{{ url('/panel/masterData/tambahHakAkses') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Hak Akses
    </a>
</div>

<!-- Filter Card -->
<div class="card shadow-sm mb-5">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold fs-7 mb-1">Akses CCTV Group</label>
                <select id="filter-cctv-group" class="form-select form-select-sm">
                    <option value="all">Semua</option>
                    <option value="semua">Akses Semua Group</option>
                    <option value="terbatas">Akses Terbatas</option>
                </select>
            </div>
            <div class="col-md-4">
                <button id="btn-reset-filter" class="btn btn-sm btn-light-secondary">
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
            <table id="table-hak-akses" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start">#</th>
                        <th>Nama Hak Akses</th>
                        <th class="text-center">Modul Diizinkan</th>
                        <th class="text-center">Akses CCTV Group</th>
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
    const table = $('#table-hak-akses').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url: '{{ url("/panel/masterData/daftarHakAkses") }}',
            data: function (d) {
                d.filter_cctv_group = $('#filter-cctv-group').val();
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + m.settings._iDisplayStart + 1, orderable: false, searchable: false },
            { data: 'nama_hak_akses_html', name: 'nama_hak_akses', orderable: true },
            { data: 'modul_count_html',    name: 'modul_akses',    orderable: false, searchable: false, className: 'text-center' },
            { data: 'cctv_group_html',     name: 'cctv_group_akses', orderable: false, searchable: false, className: 'text-center' },
            { data: 'aksi',               name: 'aksi',            orderable: false, searchable: false, className: 'text-end pe-4' },
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        language: {
            processing:  '<span class="text-muted">Memuat data...</span>',
            emptyTable:  '<i class="bi bi-shield fs-1 d-block mb-2 text-muted"></i><span class="text-muted">Belum ada hak akses</span>',
            zeroRecords: '<span class="text-muted">Tidak ada data yang sesuai filter</span>',
            lengthMenu:  'Tampilkan _MENU_ data',
            info:        'Menampilkan _START_-_END_ dari _TOTAL_ hak akses',
            infoEmpty:   'Tidak ada data',
            search:      'Cari:',
            paginate:    { previous: '&lsaquo;', next: '&rsaquo;' },
        },
    });

    // Reload on filter change
    $('#filter-cctv-group').on('change', function () {
        table.ajax.reload();
    });

    // Reset filter
    $('#btn-reset-filter').on('click', function () {
        $('#filter-cctv-group').val('all');
        table.search('').ajax.reload();
    });
});
</script>
