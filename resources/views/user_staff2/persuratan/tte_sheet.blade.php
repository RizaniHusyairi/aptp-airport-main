<!DOCTYPE html>
<html>
<head>
    <title>Lembar Pengesahan Elektronik</title>
    <style>
        body { font-family: sans-serif; text-align: center; border: 3px double #000; padding: 20px; }
        .header { margin-bottom: 30px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .content { margin-top: 50px; }
        .qr-box { margin: 20px auto; border: 1px solid #ddd; display: inline-block; padding: 10px; }
        .footer { margin-top: 50px; font-size: 12px; color: #555; }
        .details { margin-top: 20px; text-align: left; width: 80%; margin-left: auto; margin-right: auto; }
        .details td { padding: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>KEMENTERIAN PERHUBUNGAN</h3>
        <h4>DIREKTORAT JENDERAL PERHUBUNGAN UDARA</h4>
        <p>KANTOR UPBU KELAS I APT PRANOTO SAMARINDA</p>
    </div>

    <div class="content">
        <h2>LEMBAR PENGESAHAN ELEKTRONIK</h2>
        <p>Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), BSSN.</p>

        <div class="qr-box">
            {{-- Tampilkan QR Code Base64 --}}
            <img src="data:image/svg+xml;base64, {{ $qrCode }}" alt="QR Code TTE" width="150">
        </div>

        <p><strong>{{ $signerName }}</strong></p>
        <p>{{ $signerNip }}</p>

        <table class="details">
            <tr>
                <td>Perihal Surat</td>
                <td>: {{ $surat->subject }}</td>
            </tr>
            <tr>
                <td>Nomor/Tgl</td>
                <td>: {{ $surat->letter_date->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Ditandatangani</td>
                <td>: {{ $date }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini sah dan mengikat secara hukum sesuai dengan UU ITE.</p>
        <p>Scan QR Code untuk memvalidasi keaslian dokumen ini.</p>
    </div>
</body>
</html>
