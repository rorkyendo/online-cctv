<!-- Daftar CCTV -->
<div class="d-flex justify-content-between align-items-center mb-6">
    <div>
        <h4 class="fw-bold mb-1">Daftar CCTV</h4>
        <span class="text-muted fs-7">Kelola semua perangkat CCTV</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/panel/cctv/syncDevices') }}" class="btn btn-light-info"
           onclick="return confirm('Sinkronisasi perangkat dari Ezviz?')">
            <i class="bi bi-arrow-repeat me-2"></i>Sync Ezviz
        </a>
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
