<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; background:#f5f8fa; }
        .container { text-align:center; }
        .code { font-size:120px; font-weight:700; color:#7e8299; line-height:1; }
        h2 { font-size:24px; color:#181c32; margin:16px 0 8px; }
        p { color:#7e8299; margin-bottom:32px; }
        .btn { display:inline-block; padding:12px 24px; background:#009ef7; color:#fff; text-decoration:none; border-radius:6px; font-weight:600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">404</div>
        <h2>Halaman Tidak Ditemukan</h2>
        <p>Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="{{ url('/panel/dashboard') }}" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>
