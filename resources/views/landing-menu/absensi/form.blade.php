<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Rapat - {{ $meeting->title }}</title>
    {{-- Menggunakan CSS Landing Page Anda agar tampilan konsisten --}}
    <link href="{{ asset('assets_landing/css/main.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .attendance-card {
            max-width: 500px;
            width: 100%;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(45deg, #0d2c4a, #1a4b7a);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .meeting-title { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem; }
        .meeting-info { font-size: 0.9rem; opacity: 0.9; }

        /* === GAYA KHUSUS UNTUK SIGNATURE PAD === */
        .signature-wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            background-color: #fff;
            margin-bottom: 10px;
        }
        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            border-radius: 8px;
        }
        .signature-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #ccc;
            pointer-events: none; /* Agar tidak mengganggu drawing */
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container p-3">
    <div class="card attendance-card mx-auto">
        <div class="card-header">
            <div class="mb-3">
                {{-- Ganti dengan logo bandara Anda --}}
                <img src="{{ asset('assets_landing/img/logo.png') }}" alt="Logo" style="height: 50px;">
            </div>
            <h1 class="meeting-title">{{ $meeting->title }}</h1>
            <div class="meeting-info">
                <i class="bi bi-calendar"></i> {{ $meeting->date->translatedFormat('d F Y') }} <br>
                <i class="bi bi-geo-alt"></i> {{ $meeting->location }}
            </div>
        </div>
        <div class="card-body p-4">
            
            @if(session('success'))
                <div class="alert alert-success text-center">
                    <h4 class="alert-heading"><i class="bi bi-check-circle-fill"></i> Berhasil!</h4>
                    <p>{{ session('success') }}</p>
                </div>
                <div class="d-grid">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">Kembali ke Beranda</a>
                </div>
            @else
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form id="attendanceForm" action="{{ route('public.absensi.store', $meeting->slug) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Peserta" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Instansi / Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="department" name="department" placeholder="Contoh: Dinas Perhubungan / Bag. Keuangan" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor HP (WhatsApp)</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Opsional">
                    </div>

                    {{-- === INPUT TANDA TANGAN DIGITAL === --}}
                    <div class="mb-4">
                        <label class="form-label">Tanda Tangan <span class="text-danger">*</span></label>
                        <div class="signature-wrapper">
                            <span class="signature-placeholder">Tanda tangan di sini</span>
                            <canvas id="signature-pad" class="signature-pad"></canvas>
                        </div>
                        {{-- Input tersembunyi untuk menyimpan data Base64 --}}
                        <input type="hidden" name="signature" id="signature-input">
                        
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-signature">
                            <i class="bi bi-eraser"></i> Hapus / Ulangi
                        </button>
                        @error('signature')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Hadir & Simpan</button>
                    </div>
                </form>
                
                <div class="text-center mt-3 text-muted small">
                    Pastikan data yang Anda isi sudah benar.
                </div>
            @endif
        </div>
    </div>
</div>

{{-- CDN Signature Pad --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)', // Transparan
            penColor: 'rgb(0, 0, 0)'
        });
        var clearButton = document.getElementById('clear-signature');
        var signatureInput = document.getElementById('signature-input');
        var form = document.getElementById('attendanceForm');
        var placeholder = document.querySelector('.signature-placeholder');

        // Fungsi untuk mengatur ukuran canvas agar responsif
        function resizeCanvas() {
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            
            // Simpan data lama jika ada (agar tidak hilang saat resize/rotate layar)
            var data = signaturePad.toData();

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            // Kembalikan data (clear dulu karena resize menghapus konten)
            signaturePad.clear(); 
            signaturePad.fromData(data);
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        // Sembunyikan placeholder saat mulai menggambar
        signaturePad.addEventListener("beginStroke", () => {
            placeholder.style.display = 'none';
        });

        // Tombol Hapus
        clearButton.addEventListener('click', function() {
            signaturePad.clear();
            signatureInput.value = '';
            placeholder.style.display = 'block';
        });

        // Saat Form Submit
        form.addEventListener('submit', function(e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Harap isi tanda tangan terlebih dahulu.");
            } else {
                // Masukkan data Base64 ke input hidden
                var dataURL = signaturePad.toDataURL('image/png');
                signatureInput.value = dataURL;
            }
        });
    });
</script>

</body>
</html>