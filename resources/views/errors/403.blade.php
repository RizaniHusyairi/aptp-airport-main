<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets_landing/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link href="{{ asset('assets_landing/css/errors.css') }}" rel="stylesheet">
</head>
<body>
    <div class="error-container">
        <div class="error-shield">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div class="error-content">
            <h1 class="error-code">403</h1>
            <h2 class="error-title">Akses Ditolak</h2>
            <p class="error-message">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Area ini hanya untuk personel yang berwenang.
            </p>
            <div class="error-actions">
                <a href="{{ route('home') }}" class="btn-error-primary">
                    <i class="bi bi-house-door-fill me-2"></i> Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn-error-secondary">
                    Kembali ke Halaman Sebelumnya
                </a>
            </div>
        </div>
    </div>
</body>
</html>
