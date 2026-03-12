<!-- Daftar Lokasi -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar Lokasi</h4>
        <span class="text-muted fs-7">Kelola lokasi-lokasi CCTV</span>
    </div>
    <a href="{{ url('/panel/lokasi/tambahLokasi') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Lokasi
    </a>
</div>

{{-- Filter Card --}}
<div class="card shadow-sm mb-5">
    <div class="card-body py-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
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
            <table id="lokasi-table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3 w-100">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-40px rounded-start">#</th>
                        <th class="min-w-180px">Nama Lokasi</th>
                        <th class="min-w-130px">Group</th>
                        <th class="min-w-180px">Deskripsi / Alamat</th>
                        <th class="min-w-80px text-center">CCTV</th>
                        <th class="min-w-110px text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
let lokasiTable;

$(document).ready(function () {
    lokasiTable = $('#lokasi-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/panel/lokasi/daftarLokasi") }}',
            type: 'GET',
            data: function (d) {
                d.filter_group = $('#filter-group').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',       name: 'DT_RowIndex',    orderable: false, searchable: false, className: 'ps-4' },
            { data: 'nama_lokasi_html',  name: 'nama_lokasi',    orderable: true },
            { data: 'nama_group_html',   name: 'nama_group',     orderable: true },
            { data: 'deskripsi',         name: 'deskripsi',      orderable: false, className: 'text-muted',
              render: function (data) { return data ?? '-'; } },
            { data: 'total_cctv_html',   name: 'total_cctv',     orderable: true,  searchable: false, className: 'text-center' },
            { data: 'aksi',              name: 'aksi',           orderable: false, searchable: false, className: 'text-end pe-4' },
        ],
        order: [[2, 'asc'], [1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'Semua']],
        language: {
            processing:     '<div class="d-flex align-items-center gap-2"><div class="spinner-border spinner-border-sm text-primary"></div><span class="text-muted ms-2">Memuat data...</span></div>',
            search:         '',
            searchPlaceholder: 'Cari nama lokasi, deskripsi...',
            lengthMenu:     'Tampilkan _MENU_ data',
            info:           'Menampilkan _START_ \u2013 _END_ dari _TOTAL_ lokasi',
            infoEmpty:      'Tidak ada data yang ditemukan',
            infoFiltered:   '(difilter dari _MAX_ total data)',
            zeroRecords:    '<div class="text-center py-8 text-muted"><i class="bi bi-geo-alt fs-1 d-block mb-3"></i>Tidak ada data lokasi</div>',
            emptyTable:     '<div class="text-center py-8 text-muted"><i class="bi bi-geo-alt fs-1 d-block mb-3"></i>Belum ada data lokasi</div>',
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

function applyFilter() {
    if (lokasiTable) lokasiTable.ajax.reload();
}

function resetFilter() {
    document.getElementById('filter-group').value = '';
    if (lokasiTable) lokasiTable.ajax.reload();
}
</script>
