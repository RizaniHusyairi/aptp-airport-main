<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Ditutup - {{ $meeting->title }}</title>
    {{-- Menggunakan CSS Landing Page agar konsisten --}}
    <link href="{{ asset('assets_landing/css/main.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .closed-card {
            max-width: 500px;
            width: 100%;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
        }
        .card-body {
            padding: 3rem 2rem;
        }
        .icon-wrapper {
            font-size: 4rem;
            color: #dc3545; /* Warna merah Bootstrap */
            margin-bottom: 1rem;
        }
        .meeting-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .meeting-details {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
            font-size: 0.9rem;
        }
        .meeting-details p { margin-bottom: 5px; }
        .btn-home {
            background-color: #0d2c4a; /* Warna biru gelap tema Anda */
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-home:hover {
            background-color: #1a4b7a;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="container p-3">
    <div class="card closed-card mx-auto">
        <div class="card-body">
            <div class="icon-wrapper">
                <i class="bi bi-file-earmark-x"></i>
            </div>
            
            <h2 class="h4 mb-3">Absensi Ditutup</h2>
            <p class="text-muted">Maaf, formulir absensi untuk kegiatan ini sudah tidak menerima respons lagi.</p>

            <div class="meeting-details">
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-journal-bookmark me-2 text-primary"></i>
                    <div>
                        <strong>Judul Kegiatan:</strong><br>
                        {{ $meeting->title }}
                    </div>
                </div>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-calendar-event me-2 text-primary"></i>
                    <div>
                        <strong>Waktu:</strong><br>
                        {{ $meeting->date->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <i class="bi bi-person-workspace me-2 text-primary"></i>
                    <div>
                        <strong>Penyelenggara:</strong><br>
                        {{ $meeting->organizer }}
                    </div>
                </div>
            </div>

            <p class="small text-muted mb-4">
                Jika Anda merasa ini adalah kesalahan atau Anda belum sempat mengisi absen, silakan hubungi panitia penyelenggara rapat secara langsung.
            </p>

            <a href="{{ route('home') }}" class="btn btn-home">
                <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</body>
</html>