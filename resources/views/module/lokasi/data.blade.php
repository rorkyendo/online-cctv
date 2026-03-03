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

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-50px rounded-start">#</th>
                        <th class="min-w-150px">Nama Lokasi</th>
                        <th class="min-w-120px">Group</th>
                        <th class="min-w-150px">Alamat</th>
                        <th class="min-w-80px text-center">CCTV</th>
                        <th class="min-w-100px text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['lokasiList'] as $i => $lokasi)
                    <tr>
                        <td class="ps-4">{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ url('/panel/lokasi/detailLokasi/' . $lokasi->id_lokasi) }}"
                               class="text-dark text-hover-primary fw-bold">
                                {{ $lokasi->nama_lokasi }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-light-primary">{{ $lokasi->nama_group ?? '-' }}</span>
                        </td>
                        <td class="text-muted">{{ $lokasi->deskripsi ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge badge-light">{{ $lokasi->total_cctv ?? 0 }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ url('/panel/lokasi/detailLokasi/' . $lokasi->id_lokasi) }}"
                               class="btn btn-sm btn-icon btn-light-primary me-1" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ url('/panel/lokasi/updateLokasi/' . $lokasi->id_lokasi) }}"
                               class="btn btn-sm btn-icon btn-light-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ url('/panel/lokasi/hapusLokasi/' . $lokasi->id_lokasi) }}"
                               class="btn btn-sm btn-icon btn-light-danger"
                               onclick="return confirm('Hapus lokasi {{ $lokasi->nama_lokasi }}?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-muted">
                            <i class="bi bi-geo-alt fs-1 d-block mb-3"></i>
                            Belum ada data lokasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
