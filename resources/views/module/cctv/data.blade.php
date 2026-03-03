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

<div class="card shadow-sm">
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px rounded-start">#</th>
                        <th class="min-w-150px">Nama CCTV</th>
                        <th class="min-w-120px">Lokasi</th>
                        <th class="min-w-100px">Group</th>
                        <th class="min-w-120px">Serial</th>
                        <th class="min-w-80px">Akun Ezviz</th>
                        <th class="min-w-80px text-center">Status</th>
                        <th class="min-w-100px text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['cctvList'] as $i => $cctv)
                    <tr>
                        <td class="ps-4">{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ url('/panel/cctv/detailCCTV/' . $cctv->id_cctv) }}"
                               class="text-dark text-hover-primary fw-bold">
                                <i class="bi bi-camera-video me-2 text-primary"></i>{{ $cctv->nama_cctv }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $cctv->nama_lokasi ?? '-' }}</td>
                        <td>
                            <span class="badge badge-light-primary">{{ $cctv->nama_group ?? '-' }}</span>
                        </td>
                        <td class="text-muted fs-7 font-monospace">{{ $cctv->device_serial ?? '-' }}</td>
                        <td class="text-muted fs-7">{{ $cctv->nama_akun ?? '-' }}</td>
                        <td class="text-center">
                            @if($cctv->status === 'online')
                                <span class="badge badge-success"><i class="bi bi-wifi me-1"></i>Online</span>
                            @elseif($cctv->status === 'offline')
                                <span class="badge badge-danger"><i class="bi bi-wifi-off me-1"></i>Offline</span>
                            @else
                                <span class="badge badge-secondary">Unknown</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ url('/panel/cctv/detailCCTV/' . $cctv->id_cctv) }}"
                               class="btn btn-sm btn-icon btn-light-primary me-1" title="Live View">
                                <i class="bi bi-play-circle"></i>
                            </a>
                            <a href="{{ url('/panel/cctv/updateCCTV/' . $cctv->id_cctv) }}"
                               class="btn btn-sm btn-icon btn-light-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ url('/panel/cctv/hapusCCTV/' . $cctv->id_cctv) }}"
                               class="btn btn-sm btn-icon btn-light-danger"
                               onclick="return confirm('Hapus CCTV {{ $cctv->nama_cctv }}?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-muted">
                            <i class="bi bi-camera-video fs-1 d-block mb-3"></i>
                            Belum ada data CCTV
                        </td>
                    </tr>
                    @endforelse
                </tbody>
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
        document.getElementById('sync-footer').innerHTML = '<button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="location.reload()">OK, Tutup</button>';

        if (data.success) {
            const count = data.data ? (Array.isArray(data.data) ? data.data.length : '') : '';
            document.getElementById('sync-result').innerHTML =
                '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>' +
                'Sinkronisasi berhasil' + (count ? '. Ditemukan ' + count + ' perangkat.' : '.') +
                '</div>';
        } else {
            document.getElementById('sync-result').innerHTML =
                '<div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i>' +
                'Gagal: ' + (data.message || 'Error tidak diketahui') + '</div>';
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
