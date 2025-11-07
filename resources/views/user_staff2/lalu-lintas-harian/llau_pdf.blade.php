<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekapitulasi LLAU - {{ $periode }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10px; /* Ukuran font lebih kecil agar muat */
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .header h3 {
            margin: 0;
            font-size: 14px;
            text-decoration: underline;
        }
        .header h4 {
            margin: 3px 0;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        /* === PERUBAHAN DI SINI: Mengatur tata letak tanda tangan === */
        .signature-section {
            margin-top: 30px;
            width: 100%;
            font-size: 10px; /* Ukuran font normal untuk ttd */
        }
        .signature-box {
            text-align: center;
            width: 45%; /* Beri lebar agar tidak bertabrakan */
        }
        .signature-box.left {
            float: left;
        }
        .signature-box.right {
            float: right;
        }
        .signature-box .date {
            margin-bottom: 50px; /* Jarak untuk tanda tangan */
        }
        .signature-name {
            margin-top: 60px; 
            text-decoration: underline; 
            font-weight: bold;
        }
        .clear {
            clear: both;
        }
        /* === Akhir Perubahan === */
    </style>
</head>
<body>
    <div class="header">
        <h4>BADAN LAYANAN UMUM</h4>
        <h4>KANTOR UNIT PENYELENGGARA BANDAR UDARA KELAS I</h4> 
         <h4>AJI PANGERAN TUMENGGUNG PRANOTO - SAMARINDA</h4>
        <h4>UNIT INFORMASI</h4>
        <h4>BULAN: {{ strtoupper($periode) }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">NO</th>
                <th colspan="3">PESAWAT</th>
                <th colspan="3">PENUMPANG</th>
                <th colspan="3">BAGASI (KG)</th>
                <th colspan="3">KARGO (KG)</th>
            </tr>
            <tr>
                <th>ARR</th>
                <th>DEPT</th>
                <th>JUMLAH</th>
                <th>ARR</th>
                <th>DEPT</th>
                <th>JUMLAH</th>
                <th>ARR</th>
                <th>DEPT</th>
                <th>JUMLAH</th>
                <th>ARR</th>
                <th>DEPT</th>
                <th>JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($traffics as $traffic)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    {{-- Pesawat --}}
                    <td>{{ number_format($traffic->aircraft_arrival, 0, ',', '.') }}</td>
                    <td>{{ number_format($traffic->aircraft_departure, 0, ',', '.') }}</td>
                    <td style="font-weight:bold">{{ number_format($traffic->aircraft_arrival + $traffic->aircraft_departure, 0, ',', '.') }}</td>
                    {{-- Penumpang --}}
                    <td>{{ number_format($traffic->passenger_arrival, 0, ',', '.') }}</td>
                    <td>{{ number_format($traffic->passenger_departure, 0, ',', '.') }}</td>
                    <td style="font-weight:bold">{{ number_format($traffic->passenger_arrival + $traffic->passenger_departure, 0, ',', '.') }}</td>
                    {{-- Bagasi --}}
                    <td>{{ number_format($traffic->baggage_arrival, 0, ',', '.') }}</td>
                    <td>{{ number_format($traffic->baggage_departure, 0, ',', '.') }}</td>
                    <td style="font-weight:bold">{{ number_format($traffic->baggage_arrival + $traffic->baggage_departure, 0, ',', '.') }}</td>
                    {{-- Kargo --}}
                    <td>{{ number_format($traffic->cargo_arrival, 0, ',', '.') }}</td>
                    <td>{{ number_format($traffic->cargo_departure, 0, ',', '.') }}</td>
                    <td style="font-weight:bold">{{ number_format($traffic->cargo_arrival + $traffic->cargo_departure, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            
            {{-- BARIS TOTAL --}}
            <tr class="total-row">
                <td>TOTAL</td>
                <td>{{ number_format($totals['aircraft_arrival'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['aircraft_departure'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['aircraft_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['passenger_arrival'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['passenger_departure'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['passenger_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['baggage_arrival'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['baggage_departure'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['baggage_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['cargo_arrival'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['cargo_departure'], 0, ',', '.') }}</td>
                <td>{{ number_format($totals['cargo_total'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- === PERUBAHAN DI SINI: Menambahkan Tanda Tangan Koordinator === --}}
    <div class="signature-section">
        
        <div class="signature-box left">
            <br>
            <div>KOORDINATOR UNIT INFORMASI</div>
            <div class="signature-name">( ............................... )</div>
        </div>

        <div class="signature-box right">
            <div>Samarinda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>PETUGAS INFORMASI</div>
            <div class="signature-name">( ............................... )</div>
        </div>

        <div class="clear"></div>
    </div>
    {{-- === Akhir Perubahan === --}}

</body>
</html>