<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Layanan Sedang Dalam Pemeliharaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets_landing/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link href="{{ asset('assets_landing/css/errors.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_landing/css/error-503.css') }}" rel="stylesheet">
</head>
<body class="error-503-page">

    {{-- Latar langit malam bandara yang beranimasi --}}
    <div class="sky-scene" aria-hidden="true">
        <div class="stars"></div>
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
        <div class="holding-pattern">
            <i class="bi bi-airplane-fill plane"></i>
        </div>
    </div>

    <div class="error-container">
        <div class="error-content">
            <div class="maintenance-emblem" aria-hidden="true">
                <span class="gear gear-large"><i class="bi bi-gear-wide-connected"></i></span>
                <span class="gear gear-small"><i class="bi bi-gear-fill"></i></span>
            </div>

            <h1 class="error-code">503</h1>
            <h2 class="error-title">Sedang Dalam Pemeliharaan</h2>
            <p class="error-message">
                Mohon maaf, layanan kami sedang dalam pemeliharaan untuk menghadirkan pengalaman yang lebih baik.
                Pesawat kami sedang dalam <em>holding pattern</em> &mdash; silakan kembali beberapa saat lagi.
            </p>

            <div class="maintenance-bar" aria-hidden="true"><span></span></div>

            <div class="error-actions">
                <a href="javascript:location.reload()" class="btn-error-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i> Coba Lagi
                </a>
                <a href="{{ url('/') }}" class="btn-error-secondary">
                    Kembali ke Beranda
                </a>
            </div>

            <p class="refresh-note">
                Halaman akan dimuat ulang otomatis dalam <span id="countdown">30</span> detik.
            </p>
        </div>
    </div>

    <script>
        (function () {
            var remaining = 30;
            var el = document.getElementById('countdown');
            var timer = setInterval(function () {
                remaining -= 1;
                if (el) el.textContent = remaining > 0 ? remaining : 0;
                if (remaining <= 0) {
                    clearInterval(timer);
                    location.reload();
                }
            }, 1000);
        })();
    </script>
</body>
</html>
