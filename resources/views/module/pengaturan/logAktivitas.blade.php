<!-- Log Aktivitas with DataTable -->
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Log Aktivitas</h3></div>
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
    $('#log-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/panel/pengaturan/getLogAktivitas") }}',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'created_time', name: 'created_time' },
            { data: 'username', name: 'username' },
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
});
</script>
