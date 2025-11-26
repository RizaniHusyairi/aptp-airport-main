<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Selesai</title>
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app.css') }}">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="text-center p-5">
        <h1 class="display-1 text-muted"><i class="bi bi-lock-fill"></i></h1>
        <h2 class="mt-4">Posko Telah Ditutup</h2>
        <p class="lead">Masa input data untuk event <strong>{{ $event->name }}</strong> telah berakhir.</p>
        <p class="text-muted">Terima kasih atas partisipasi Anda.</p>
    </div>
</body>
</html>