<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Edit Group Lokasi</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/grupLokasi/updateGrupLokasi/' . $data['grupLokasi']->id_group . '/save') }}">
            @csrf
            <div class="mb-5">
                <label class="form-label required fw-semibold">Nama Group</label>
                <input type="text" name="nama_group" class="form-control"
                       value="{{ old('nama_group', $data['grupLokasi']->nama_group) }}" required />
            </div>
            <div class="mb-5">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $data['grupLokasi']->deskripsi) }}</textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
