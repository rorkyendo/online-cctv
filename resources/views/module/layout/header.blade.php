<!DOCTYPE html>
<html lang="id">
<!--begin::Head-->
<head>
    <base href="">
    <title>{{ $data['title'] }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ $data['identitas']->apps_name }}" />
    <meta name="keywords"  content="{{ $data['identitas']->apps_name }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $data['identitas']->apps_name }}" />
    <meta property="og:url" content="{{ URL::to('/') }}" />
    <meta property="og:site_name" content="{{ $data['identitas']->apps_name }}" />
    <link rel="canonical" href="{{ URL::to('/') }}" />
    <link rel="shortcut icon" href="{{ asset($data['identitas']->icon ?? 'assets/media/logos/favicon.ico') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <!--end::Global Stylesheets Bundle-->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <!-- HLS.js for CCTV streaming -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        @media print { .print-hide { display: none; } }
        .cctv-card { transition: transform .2s; cursor: pointer; }
        .cctv-card:hover { transform: scale(1.02); box-shadow: 0 4px 20px rgba(0,0,0,.15); }
        .status-badge-online { background-color: #50cd89; }
        .status-badge-offline { background-color: #f1416c; }
        .video-container { position: relative; width: 100%; background: #000; border-radius: 8px; overflow: hidden; }
        .video-container video { width: 100%; }
        .video-overlay { position:absolute; top:0; left:0; right:0; bottom:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.5); color:#fff; }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->
<body data-kt-name="metronic" id="kt_app_body" data-kt-app-layout="dark-sidebar"
      data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
      data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
      data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
      data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
      class="app-default">
