<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat OJT - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; border: 10px solid #ddd; padding: 20px; height: 90%; }
        .header { margin-bottom: 30px; }
        .title { font-size: 32px; font-weight: bold; color: #2c3e50; text-transform: uppercase; margin-bottom: 5px; }
        .subtitle { font-size: 18px; color: #7f8c8d; margin-bottom: 30px; }

        .content { font-size: 16px; line-height: 1.6; margin: 0 50px; }
        .name { font-size: 28px; font-weight: bold; margin: 10px 0; color: #000; text-decoration: underline; }
        .institution { font-weight: bold; }

        .units-box { margin-top: 20px; border: 1px dashed #aaa; padding: 10px; display: inline-block; width: 80%; }
        .units-title { font-weight: bold; font-size: 14px; margin-bottom: 5px; text-decoration: underline; }
        .unit-badge { display: inline-block; margin: 2px 5px; }

        .footer { margin-top: 50px; width: 100%; }
        .signature-box { float: right; width: 300px; text-align: center; }
        .date { margin-bottom: 60px; }
        .signer-name { font-weight: bold; text-decoration: underline; }

        .photo { position: absolute; bottom: 50px; left: 50px; width: 100px; height: 130px; border: 1px solid #000; object-fit: cover; }
    </style>
</head>
<body>
    <div class="header">
        {{-- Anda bisa menambahkan LOGO Bandara di sini menggunakan <img src> --}}
        <h3>KEMENTERIAN PERHUBUNGAN<br>DIREKTORAT JENDERAL PERHUBUNGAN UDARA</h3>
        <div class="title">SERTIFIKAT OJT</div>
        <div class="subtitle">Nomor: .../OJT/APT/{{ date('Y') }}</div>
    </div>

    <div class="content">
        <p>Kepala Kantor Unit Penyelenggara Bandar Udara Kelas I APT Pranoto Samarinda memberikan sertifikat ini kepada:</p>

        <div class="name">{{ $student->name }}</div>
        <div>{{ $student->institution }} - {{ $student->major }}</div>

        <p>Telah melaksanakan <strong>On the Job Training (OJT)</strong> dengan baik dan disiplin<br>
        selama <strong>{{ $student->duration }}</strong> terhitung mulai tanggal
        <strong>{{ $student->start_date->translatedFormat('d F Y') }}</strong> sampai dengan
        <strong>{{ $student->end_date->translatedFormat('d F Y') }}</strong>.</p>

        <div class="units-box">
            <div class="units-title">Unit Kerja Penempatan:</div>
            @foreach($student->work_units as $unit)
                <span class="unit-badge">• {{ $unit }}</span>
            @endforeach
        </div>

        <p style="margin-top: 20px;">
            <strong>Pembimbing:</strong><br>
            {{ implode(', ', $student->supervisors) }}
        </p>
    </div>

    {{-- Pas Foto di Kiri Bawah --}}
    @if($student->photo_path)
        <img src="{{ public_path('uploads/' . $student->photo_path) }}" class="photo">
    @endif

    <div class="footer">
        <div class="signature-box">
            <div class="date">Samarinda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Kepala Kantor UPBU Kelas I<br>APT Pranoto Samarinda</div>
            <br><br><br>
            <div class="signer-name">MAULANA HAFIZ, S.E., M.T.</div>
            <div>NIP. 19xxxxxxxxxxxx</div>
        </div>
    </div>
</body>
</html>
