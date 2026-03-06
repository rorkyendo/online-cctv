<!-- Log Aktivitas with DataTable -->
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Log Aktivitas</h3></div>
        @if($data['isSuperuser'])
        <div class="card-toolbar">
            <div class="d-flex align-items-center gap-2">
                <label class="fw-semibold text-gray-600 fs-7">Filter Pengguna:</label>
                <select id="filter-username" class="form-select form-select-sm" style="min-width:200px">
                    <option value="">— Semua Pengguna —</option>
                </select>
            </div>
        </div>
        @endif
    </div>
    <div class="card-body">
        <table id="log-table" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4 w-100">
            <thead>
                <tr class="fw-bold text-muted bg-light">
                    <th class="ps-4 rounded-start">#</th>
                    <th>Waktu</th>
                    <th>Username</th>
                    <th>Aksi</th>
                    <th>Modul</th>
                    <th class="min-w-200px rounded-end">Detail</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#log-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/panel/pengaturan/getLogAktivitas") }}',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: function(d) {
                d.filter_username = $('#filter-username').val() || '';
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'created_time', name: 'created_time' },
            {
                data: 'username', name: 'username',
                render: function(data) {
                    return '<a href="#" class="btn-username-info fw-semibold text-primary" data-username="' + $('<div>').text(data).html() + '">' +
                           '<i class="bi bi-person-circle me-1"></i>' + $('<div>').text(data).html() + '</a>';
                }
            },
            { data: 'aksi', name: 'aksi' },
            { data: 'modul', name: 'modul' },
            { data: 'detail', name: 'detail' },
        ],
        order: [[1, 'desc']],
        responsive: true,
        language: {
            processing: 'Memuat data...',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            paginate: { next: 'Berikutnya', previous: 'Sebelumnya' }
        }
    });

    // Username click → show user info modal
    $('#log-table').on('click', '.btn-username-info', function(e) {
        e.preventDefault();
        var username = $(this).data('username');
        var $modal   = $('#modalUserInfo');
        $modal.find('.modal-body').html(
            '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted small">Memuat data...</p></div>'
        );
        $modal.modal('show');

        $.ajax({
            url: '{{ url("/panel/pengaturan/getUserInfo") }}',
            type: 'GET',
            data: { username: username },
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(data) {
                // Generate avatar initials from nama_lengkap
                var words    = (data.nama_lengkap || data.username || '?').trim().split(/\s+/);
                var initials = words.length >= 2
                    ? (words[0][0] + words[1][0]).toUpperCase()
                    : words[0].substring(0, 2).toUpperCase();

                // Consistent color from username hash
                var colors   = ['#4f46e5','#0891b2','#059669','#d97706','#dc2626','#7c3aed','#db2777','#0284c7'];
                var hash     = 0;
                for (var i = 0; i < (data.username || '').length; i++) { hash += (data.username || '').charCodeAt(i); }
                var bgColor  = colors[hash % colors.length];

                var avatar = '<div style="width:72px;height:72px;border-radius:50%;background:' + bgColor + ';display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:24px;font-weight:700;color:#fff;">' + initials + '</div>';

                var statusBadge = data.status === 'actived'
                    ? '<span class="badge badge-light-success">Aktif</span>'
                    : '<span class="badge badge-light-danger">Non-aktif</span>';

                var onlineBadge = data.activity_status === 'online'
                    ? '<span class="badge badge-light-success"><i class="bi bi-circle-fill fs-9 me-1"></i>Online</span>'
                    : '<span class="badge badge-light-secondary"><i class="bi bi-circle fs-9 me-1"></i>Offline</span>';

                var html = '<div class="text-center mb-5">' + avatar +
                    '<h5 class="fw-bold mb-1">' + (data.nama_lengkap || '-') + '</h5>' +
                    '<span class="text-muted fs-7">@' + data.username + '</span>' +
                    '<div class="d-flex justify-content-center gap-2 mt-2">' + statusBadge + onlineBadge + '</div>' +
                    '</div>' +
                    '<table class="table table-borderless fs-7 mb-0">' +
                    '<tr><td class="text-muted w-40">Hak Akses</td><td><span class="badge badge-light-primary">' + (data.hak_akses || '-') + '</span></td></tr>' +
                    '<tr><td class="text-muted">Email</td><td>' + (data.email || '-') + '</td></tr>' +
                    '<tr><td class="text-muted">No. Telp</td><td>' + (data.no_telp || '-') + '</td></tr>' +
                    '<tr><td class="text-muted">Login Terakhir</td><td>' + (data.last_login || '-') + '</td></tr>' +
                    '<tr><td class="text-muted">Logout Terakhir</td><td>' + (data.last_logout || '-') + '</td></tr>' +
                    '<tr><td class="text-muted">Terdaftar</td><td>' + (data.created_time || '-') + '</td></tr>' +
                    '</table>';
                $modal.find('.modal-body').html(html);
            },
            error: function() {
                $modal.find('.modal-body').html('<div class="text-center text-danger py-4"><i class="bi bi-exclamation-circle fs-2"></i><p class="mt-2">Gagal memuat data pengguna.</p></div>');
            }
        });
    });

    @if($data['isSuperuser'])
    $('#filter-username').select2({
        placeholder: '— Semua Pengguna —',
        allowClear: true,
        minimumInputLength: 0,
        ajax: {
            url: '{{ url("/panel/pengaturan/searchLogUsername") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { q: params.term || '', page: params.page || 1, _token: '{{ csrf_token() }}' };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return { results: data.results, pagination: data.pagination };
            },
            cache: true
        }
    });
    $('#filter-username').on('change', function() {
        table.ajax.reload();
    });
    @endif
});
</script>

<!-- Modal Info Pengguna -->
<div class="modal fade" id="modalUserInfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard me-2 text-primary"></i>Info Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4 pb-5">
            </div>
        </div>
    </div>
</div>
