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

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start">#</th>
                        <th>Nama Hak Akses</th>
                        <th class="text-center">Modul Diizinkan</th>
                        <th class="text-center">Akses CCTV Group</th>
                        <th class="text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['hakAksesList'] as $i => $ha)
                    @php
                        $modulAkses     = $ha->modul_akses ? (is_array(json_decode($ha->modul_akses,true)) ? count(json_decode($ha->modul_akses,true)) : 0) : 0;
                        $cctvGroupAkses = $ha->cctv_group_akses ? json_decode($ha->cctv_group_akses, true) : null;
                    @endphp
                    <tr>
                        <td class="ps-4">{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $ha->nama_hak_akses }}</td>
                        <td class="text-center">
                            <span class="badge badge-light-success">{{ $modulAkses }} Modul</span>
                        </td>
                        <td class="text-center">
                            @if($cctvGroupAkses === null)
                                <span class="badge badge-success">Semua Group</span>
                            @else
                                <span class="badge badge-warning">{{ count($cctvGroupAkses) }} Group</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ url('/panel/masterData/updateHakAkses/' . $ha->id_hak_akses) }}"
                               class="btn btn-sm btn-icon btn-light-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ url('/panel/masterData/hapusHakAkses/' . $ha->id_hak_akses) }}"
                               class="btn btn-sm btn-icon btn-light-danger"
                               onclick="return confirm('Hapus hak akses {{ $ha->nama_hak_akses }}?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-muted">
                            <i class="bi bi-shield fs-1 d-block mb-3"></i>Belum ada hak akses
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
