<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Tambah CCTV</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/cctv/tambahCCTV/save') }}">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama CCTV</label>
                        <input type="text" name="nama_cctv" class="form-control"
                               placeholder="Contoh: Kamera Pintu Masuk, CCTV Lantai 2"
                               value="{{ old('nama_cctv') }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Lokasi</label>
                        <select name="id_lokasi" class="form-select" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($data['lokasiList'] as $lok)
                                <option value="{{ $lok->id_lokasi }}"
                                        {{ old('id_lokasi') == $lok->id_lokasi ? 'selected' : '' }}>
                                    {{ $lok->nama_group ?? '' }} - {{ $lok->nama_lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Akun Ezviz</label>
                        <select name="id_ezviz_akun" class="form-select" required>
                            <option value="">-- Pilih Akun Ezviz --</option>
                            @foreach($data['ezvizList'] as $akun)
                                <option value="{{ $akun->id_ezviz_akun }}"
                                        {{ old('id_ezviz_akun') == $akun->id_ezviz_akun ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Device Serial Number</label>
                        <input type="text" name="device_serial" class="form-control font-monospace"
                               placeholder="Contoh: D12345678"
                               value="{{ old('device_serial') }}" required />
                        <div class="form-text text-muted">Serial number perangkat dari aplikasi Ezviz</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Channel Number</label>
                        <input type="number" name="channel_no" class="form-control"
                               placeholder="1" value="{{ old('channel_no', 1) }}" min="1" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Stream Type</label>
                        <select name="stream_type" class="form-select">
                            <option value="1" {{ old('stream_type', '1') == '1' ? 'selected' : '' }}>HD (High Definition)</option>
                            <option value="2" {{ old('stream_type') == '2' ? 'selected' : '' }}>SD (Standard Definition)</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Valid Code (Verification Code)</label>
                        <input type="text" name="validCode" class="form-control"
                               placeholder="Opsional - kode verifikasi perangkat"
                               value="{{ old('validCode') }}" />
                        <div class="form-text text-muted">Kode verifikasi dari label perangkat (jika ada)</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
