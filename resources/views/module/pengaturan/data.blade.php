<!-- Pengaturan Sistem -->
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title"><h3 class="fw-bold">Pengaturan Sistem</h3></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/panel/pengaturan/pengaturanSistem/save') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label required fw-semibold">Nama Aplikasi</label>
                        <input type="text" name="apps_name" class="form-control"
                               value="{{ old('apps_name', $data['identitas']->apps_name ?? '') }}" required />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Versi Aplikasi</label>
                        <input type="text" name="apps_version" class="form-control"
                               value="{{ old('apps_version', $data['identitas']->apps_version ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Nama Instansi</label>
                        <input type="text" name="agency" class="form-control"
                               value="{{ old('agency', $data['identitas']->agency ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $data['identitas']->address ?? '') }}</textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Kota</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $data['identitas']->city ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="telephon" class="form-control"
                               value="{{ old('telephon', $data['identitas']->telephon ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $data['identitas']->email ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Website</label>
                        <input type="text" name="website" class="form-control"
                               value="{{ old('website', $data['identitas']->website ?? '') }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Logo Saat Ini</label>
                        @if(!empty($data['identitas']->logo))
                            <div class="mb-3">
                                <img src="{{ asset($data['identitas']->logo) }}" class="h-60px" alt="Logo" />
                            </div>
                        @endif
                        <input type="text" name="logo" class="form-control"
                               placeholder="Path logo (contoh: assets/media/logos/logo.png)"
                               value="{{ old('logo', $data['identitas']->logo ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Icon / Favicon</label>
                        @if(!empty($data['identitas']->icon))
                            <div class="mb-3">
                                <img src="{{ asset($data['identitas']->icon) }}" class="h-30px" alt="Icon" />
                            </div>
                        @endif
                        <input type="text" name="icon" class="form-control"
                               placeholder="Path icon (contoh: assets/media/logos/favicon.ico)"
                               value="{{ old('icon', $data['identitas']->icon ?? '') }}" />
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Footer</label>
                        <textarea name="footer" class="form-control" rows="3">{{ old('footer', $data['identitas']->footer ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
