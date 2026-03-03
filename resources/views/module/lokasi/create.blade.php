<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold">Tambah Lokasi</h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/lokasi/daftarLokasi') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/lokasi/tambahLokasi/save') }}">
            @csrf
            <div class="mb-5">
                <label class="form-label required fw-semibold">Nama Lokasi</label>
                <input type="text" name="nama_lokasi" class="form-control"
                       placeholder="Contoh: Lobby Utama, Ruang Server, Area Parkir B"
                       value="{{ old('nama_lokasi') }}" required />
            </div>
            <div class="mb-5">
                <label class="form-label required fw-semibold">Group Lokasi</label>
                <select name="id_group" class="form-select" required>
                    <option value="">-- Pilih Group --</option>
                    @foreach($data['grupList'] as $grup)
                        <option value="{{ $grup->id_group }}"
                                {{ old('id_group') == $grup->id_group ? 'selected' : '' }}>
                            {{ $grup->nama_group }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="form-label fw-semibold">Deskripsi / Keterangan</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                          placeholder="Deskripsi atau keterangan lokasi">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/lokasi/daftarLokasi') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
