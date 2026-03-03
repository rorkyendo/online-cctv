<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Tambah Group Lokasi</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/grupLokasi/tambahGrupLokasi/save') }}">
            @csrf
            <div class="mb-5">
                <label class="form-label required fw-semibold">Nama Group</label>
                <input type="text" name="nama_group" class="form-control"
                       placeholder="Contoh: Gedung A, Lantai 1, Area Parkir"
                       value="{{ old('nama_group') }}" required />
                @error('nama_group')
                    <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-5">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Deskripsi singkat tentang group lokasi ini">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/grupLokasi/daftarGrupLokasi') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
