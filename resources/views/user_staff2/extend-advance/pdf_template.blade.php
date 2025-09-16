<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Pernyataan Extend Advance</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3, .header h4 { margin: 0; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 8px; vertical-align: top; }
        .section-title { font-weight: bold; }
        .statement { font-style: italic; text-align: justify; padding-left: 20px;}
        .signature-section { margin-top: 50px; }
        .signature-box { text-align: center; width: 33%; float: left; }
        .signature-box .name { margin-top: 60px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h3>SURAT PERNYATAAN</h3>
        <h4>PERMOHONAN SLOT CLEARANCE - EXTEND ADVANCE HOUR AIRPORT</h4>
        <p>DI BANDAR UDARA AJI PANGERAN TUMENGGUNG PRANOTO SAMARINDA</p>
    </div>

    <p>Yang bertanda tangan di bawah ini:</p>

    <table border="0">
        <tr>
            <td colspan="3" class="section-title">I. Pesawat Udara</td>
        </tr>
        <tr>
            <td style="width: 35%;">a. Operator (Pemilik/Penyewa)</td>
            <td style="width: 1%;">:</td>
            <td>{{ $submission->operator }}</td>
        </tr>
        <tr>
            <td>b. Tipe</td>
            <td>:</td>
            <td>{{ $submission->aircraft_type }}</td>
        </tr>
        <tr>
            <td>c. Tanda Pendaftaran / No. Penerbangan</td>
            <td>:</td>
            <td>{{ $submission->registration_and_flight_number }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-title" style="padding-top: 15px;">II. Penerbangan</td>
        </tr>
        <tr>
            <td>a. Tanggal</td>
            <td>:</td>
            <td>{{ $submission->flight_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>b. Jam Keberangkatan (EOBT)</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($submission->eobt)->format('H:i') }} UTC</td>
        </tr>
         <tr>
            <td>c. Jam Kedatangan (AOBT)</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($submission->aobt)->format('H:i') }} UTC</td>
        </tr>
        <tr>
            <td>d. Rute</td>
            <td>:</td>
            <td>{{ $submission->route }}</td>
        </tr>
        <tr>
            <td>e. Alternate Take Off</td>
            <td>:</td>
            <td>{{ $submission->take_off_alternate ?? '-' }}</td>
        </tr>
        <tr>
            <td>f. Keperluan Terbang</td>
            <td>:</td>
            <td>{{ $submission->purpose_of_flight }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-title" style="padding-top: 15px;">III. Pernyataan</td>
        </tr>
        <tr>
            <td colspan="3" class="statement">{{ $submission->statement_notes }}</td>
        </tr>
    </table>

    <div class="signature-section">
        <div style="float: right; text-align: center;">
            Samarinda, {{ $submission->flight_date->format('d F Y') }}<br>
            PEMOHON<br>
            Pilot in Command
            <div style="height: 60px;"></div>
            ( {{ $submission->pic_name }} )
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="signature-section" style="margin-top: 80px;">
        <p style="text-align: center;">Mengetahui,</p>
        <div class="signature-box">
            PIC - PERUM LPPNPI<br>KCP SAMARINDA
            <div class="name">(...................................)</div>
        </div>
        <div class="signature-box">
            PIC - BANDAR UDARA<br>APT PRANOTO SAMARINDA
            <div class="name">(...................................)</div>
        </div>
    </div>

</body>
</html>
