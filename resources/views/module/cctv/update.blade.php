<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Edit CCTV</h3></div>
        <div class="card-toolbar">
            <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/cctv/updateCCTV/' . $data['cctv']->id_cctv . '/save') }}">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama CCTV</label>
                        <input type="text" name="nama_cctv" class="form-control"
                               value="{{ old('nama_cctv', $data['cctv']->nama_cctv) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Lokasi</label>
                        <select name="id_lokasi" class="form-select" required>
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($data['lokasiList'] as $lok)
                                <option value="{{ $lok->id_lokasi }}"
                                        {{ $data['cctv']->id_lokasi == $lok->id_lokasi ? 'selected' : '' }}>
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
                                        {{ $data['cctv']->id_ezviz_akun == $akun->id_ezviz_akun ? 'selected' : '' }}>
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
                               value="{{ old('device_serial', $data['cctv']->device_serial) }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Channel Number</label>
                        <input type="number" name="channel_no" class="form-control"
                               value="{{ old('channel_no', $data['cctv']->channel_no ?? 1) }}" min="1" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Stream Type</label>
                        <select name="stream_type" class="form-select">
                            <option value="1" {{ ($data['cctv']->stream_type ?? 1) == 1 ? 'selected' : '' }}>HD</option>
                            <option value="2" {{ ($data['cctv']->stream_type ?? 1) == 2 ? 'selected' : '' }}>SD</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Valid Code</label>
                        <input type="text" name="validCode" class="form-control"
                               value="{{ old('validCode', $data['cctv']->validCode) }}" />
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ url('/panel/cctv/daftarCCTV') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-lg me-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
