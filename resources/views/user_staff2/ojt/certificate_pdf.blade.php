<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat OJT - {{ $student->name }}</title>
    <style>
        /* Mengatur margin halaman jadi 0 agar background full page */
        @page {
            margin: 0cm;
        }

        body {
            font-family: 'times new roman', Times, serif;
            /* Menggunakan gambar background */
            background-image: url('{{ public_path("assetsv2/image/sertifikat/bg.png") }}');
            background-position: center center;
            background-repeat: no-repeat;
            /* Memastikan gambar memenuhi area A4 Landscape */
            background-size: 100% 100%;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Wrapper untuk mengatur posisi konten agar pas di dalam bingkai background */
        .content-wrapper {
            padding-top: 60px;  /* Jarak dari atas agar tidak kena logo */
            padding-left: 100px; /* Jarak dari kiri dalam bingkai */
            padding-right: 100px;/* Jarak dari kanan dalam bingkai */
            text-align: center;
            color: #000;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px; /* Warna biru tua menyesuaikan logo */
            letter-spacing: 5px;
        }

        .subtitle {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .content-text {
            font-size: 18px;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .long-text{
            text-align: center;
            margin: 0 80px;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0 30px 0;
            text-decoration: underline;
        }

        .institution {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .units-box {
            margin-top: 15px;
            padding: 10px;
            display: inline-block;
            width: 80%;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .units-title {
            font-weight: bold;
            font-size: 16px;
        }

        .unit-badge {
            display: inline-block;
            margin: 5px;
            font-weight: bold;
        }

        .footer {
            position: absolute;
            bottom: 230px; /* Sesuaikan posisi tanda tangan dari bawah */
            width: 100%;
            font-size: 13px;
        }

        .signature-box {
            float: right;
            width: 300px;
            text-align: left;
            margin-right: 100px; /* Jarak dari kanan */
        }

        .date { margin-bottom: 0px; }
        .date.mb-80 {
            margin-right: 30px;
         }
        .signer-name { font-weight: bold; text-decoration: underline; font-size: 12px; }
        .signer-nip { font-weight: bold; font-size: 12px; }

        /* Posisi Pas Foto */
        .photo {
            position: absolute;
            bottom: 120px;
            left: 120px;
            width: 110px;
            height: 130px;
            border: 2px solid #fff;
            box-shadow: 3px 3px 5px rgba(0,0,0,0.3);
            object-fit: cover;

        }
        .kop-surat {

            text-align: center;
            font-family: 'times new roman';

        }

        .kop-surat .line1 {
            font-size: 20px;
            font-weight: bold;
        }
        .kop-surat .line2 {
            font-size: 16px;
            font-weight: bold;
        }
        .kop-surat .line3 {
            font-size: 14px;
            font-weight: bold;
        }
        .kop-surat .line4 {
            font-size: 14px;
        }
    </style>
</head>
<body>
    {{-- Konten dimasukkan dalam wrapper agar posisinya pas di tengah bingkai --}}
    <div class="content-wrapper">
        <div class="kop-surat">
            <div class="line1">KEMENTRIAN PERHUBUNGAN</div>
            <div class="line2">DIREKTORAT JENDRAL PERHUBUNGAN UDARA</div>
            <div class="line2">BADAN LAYANAN UMUM</div>
            <div class="line2">KANTOR UNIT PENYELENGGARA BANDAR UDARA KELAS I</div>
            <div class="line3">AJI PANGERAN TUMENGGUNG PRANOTO - SAMARINDA</div>
            <div class="line4">Jalan Poros Bontang Samarinda Kel.Sungai Siring Samarinda  - Kalimantan Timur</div>
            <div class="line4">Telp. (0541) 2831593 Email : mail.aptpranotoairport@gmail.com</div>
            <hr>

        </div>

        <div class="title">SERTIFIKAT</div>
        {{-- Nomor sertifikat bisa dibuat dinamis jika ada format penomorannya --}}
        <div class="subtitle">No: SM.304/...../APTP/{{ date('Y') }}</div>

        <div class="content-text">
            Diberikan kepada:
        </div>

        <div class="student-name">{{ $student->name }}</div>

        <div class="content-text long-text">
            dari {{ $student->institution }} Jurusan {{ $student->major }} Telah Menyelesaikan Program <strong>On the Job Training (OJT)</strong> pada Kantor UPBU Kelas I A.P.T. Pranoto Samarinda selama <strong>{{ $student->duration }}</strong> mulai dari <strong>{{ $student->start_date->translatedFormat('d F Y') }}</strong> s/d <strong>{{ $student->end_date->translatedFormat('d F Y') }}</strong> dengan predikat <strong>{{ $student->predicate }}</strong>.
        </div>

    </div>

    {{-- Pas Foto di Kiri Bawah --}}
    @if($student->photo_path)
        <img src="{{ public_path('uploads/' . $student->photo_path) }}" class="photo">
    @endif

    <div class="footer">
        <div class="signature-box">
            <div class="date">Samarinda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div class="date mb-80">KEPALA BADAN LAYANAN UMUM KANTOR UPBU KELAS I A.P.T. PRANOTO SAMARINDA</div>
            <br><br><br><br> {{-- Ruang untuk tanda tangan basah/cap --}}
            <div class="signer-name">I KADEK YULI SASTRAWAN</div>
            <div class="signer-nip">NIP. 19760704 199803 1 001</div>
        </div>
    </div>
</body>
</html>
