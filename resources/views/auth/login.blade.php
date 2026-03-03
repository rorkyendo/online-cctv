<!DOCTYPE html>
<html lang="id">
<head>
    <base href="">
    <title>{{ $data['identitas']->apps_name ?? 'Online CCTV' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset($data['identitas']->icon ?? 'assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body data-kt-name="metronic" id="kt_body" class="app-blank bgi-size-cover bgi-position-center">
    <script>
        if (document.documentElement) {
            const defaultThemeMode = "light";
            const name = document.body.getAttribute("data-kt-name");
            let themeMode = localStorage.getItem("kt_" + (name !== null ? name + "_" : "") + "theme_mode_value");
            if (!themeMode) themeMode = defaultThemeMode;
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>
    <style>
        body { background-image: url('{{ asset("assets/media/auth/bg10.jpeg") }}'); }
        [data-theme="dark"] body { background-image: url('{{ asset("assets/media/auth/bg4-dark.jpg") }}'); }
    </style>
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <!--begin::Aside-->
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <div class="d-flex flex-column text-center">
                    <a href="{{ URL::to('/') }}" class="mb-7">
                        @if(!empty($data['identitas']->logo))
                            <img alt="Logo" class="h-70px" src="{{ asset($data['identitas']->logo) }}" />
                        @else
                            <h2 class="text-white fw-bold">Online CCTV</h2>
                        @endif
                    </a>
                    <h2 class="text-white fw-bold fs-1 mb-3">CCTV Management System</h2>
                    <p class="text-white opacity-75">Monitor and manage all your CCTV cameras in one place</p>
                </div>
            </div>
            <!--end::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-center w-lg-50 p-10">
                <div class="card rounded-3 w-md-550px">
                    <div class="card-body p-10 p-lg-20">
                        <form class="form w-100" method="POST" action="{{ url('/doLogin') }}">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-dark fw-bolder mb-3">Masuk Aplikasi</h1>
                                <div class="text-gray-500 fw-semibold fs-6">
                                    {{ $data['identitas']->apps_name ?? 'Online CCTV' }}
                                </div>
                            </div>
                            @if(session('error'))
                                <div class="alert alert-danger mb-5">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success mb-5">
                                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif
                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Username" name="username"
                                       value="{{ old('username') }}" autocomplete="off"
                                       class="form-control bg-transparent" required />
                            </div>
                            <div class="fv-row mb-3">
                                <input type="password" placeholder="Password" name="password"
                                       autocomplete="off" class="form-control bg-transparent" required />
                            </div>
                            <div class="d-grid mb-10 mt-8">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Aplikasi
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Body-->
        </div>
    </div>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
