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

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start">#</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Hak Akses</th>
                        <th>Email</th>
                        <th class="text-center">Status</th>
                        <th class="text-end rounded-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['penggunaList'] as $i => $pengguna)
                    <tr>
                        <td class="ps-4">{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $pengguna->username }}</td>
                        <td>{{ $pengguna->nama_lengkap }}</td>
                        <td><span class="badge badge-light-primary">{{ $pengguna->hak_akses ?? '-' }}</span></td>
                        <td class="text-muted">{{ $pengguna->email ?? '-' }}</td>
                        <td class="text-center">
                            @if($pengguna->status === 'actived')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ url('/panel/masterData/updatePengguna/' . $pengguna->id_pengguna) }}"
                               class="btn btn-sm btn-icon btn-light-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ url('/panel/masterData/hapusPengguna/' . $pengguna->id_pengguna) }}"
                               class="btn btn-sm btn-icon btn-light-danger"
                               onclick="return confirm('Hapus pengguna {{ $pengguna->username }}?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>Belum ada data pengguna
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
