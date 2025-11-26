<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daftar Hadir - {{ $meeting->title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; }
        
        /* Header Style */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        .header p { margin: 2px 0; font-size: 10px; }

        /* Info Rapat Style */
        .meeting-info { margin-bottom: 15px; width: 100%; }
        .meeting-info td { padding: 3px; vertical-align: top; font-size: 11px; }
        .label { width: 120px; font-weight: bold; }
        
        /* Tabel Daftar Hadir */
        table.attendance {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.attendance th, table.attendance td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }
        table.attendance th {
            background-color: #f2f2f2;
            font-size: 13px;
            text-align: center;
            font-weight: bold;
        }
        table.attendance td{
            font-size: 12px;
        }
        .text-center { text-align: center; }
        
        /* Style Tanda Tangan */
        .signature-img {
            height: 40px;
            width: auto;
            max-width: 80px;
            display: block;
            margin: 0 auto;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            width: 100%;
            text-align: right;
        }
        .footer-signature {
            float: right;
            width: 200px;
            text-align: center;
        }
        .footer-signature .name {
            margin-top: 60px;
            text-decoration: underline;
            font-weight: bold;
            font-size: 12px;
        }
        /* CSS UNTUK NIP */
        .footer-signature .nip {
            margin-top: 2px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR RAPAT</h2>
        <h3>KANTOR UPBU KELAS I A.P.T. PRANOTO - SAMARINDA</h3>
    </div>

    <table class="meeting-info">
        <tr>
            <td class="label">Judul Rapat</td>
            <td>: {{ $meeting->title }}</td>
        </tr>
        <tr>
            <td class="label">Hari / Tanggal</td>
            <td>: {{ $meeting->date->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Waktu</td>
            <td>: {{ \Carbon\Carbon::parse($meeting->start_time)->format('H:i') }} WITA</td>
        </tr>
        <tr>
            <td class="label">Lokasi</td>
            <td>: {{ $meeting->location }}</td>
        </tr>
        <tr>
            <td class="label">Pimpinan Rapat</td>
            <td>: {{ $meeting->organizer }}</td>
        </tr>
    </table>

    <table class="attendance">
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Nama Peserta</th>
                <th>Instansi / Unit Kerja</th>
                <th>Kontak</th>
                <th style="width: 100px;">Waktu Hadir</th>
                <th style="width: 80px;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $data)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $data->name }}</td>
                    <td>
                        {{ $data->department }}<br>
                        
                    </td>
                    <td>
                        {{ $data->phone }}

                    </td>
                    <td class="text-center">{{ $data->created_at->format('H:i') }}</td>
                    <td class="text-center">
                        @if($data->signature && file_exists(public_path('uploads/' . $data->signature)))
                            <img src="{{ public_path('uploads/' . $data->signature) }}" class="signature-img" alt="TTD">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada peserta yang mengisi daftar hadir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-signature">
            <p>Samarinda, {{ $meeting->date->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="name">{{ $meeting->organizer }}</div>
            {{-- Menampilkan NIP --}}
            <div class="nip">NIP. {{ $meeting->organizer_nip ?? '-' }}</div>
        </div>
    </div>
</body>
</html>