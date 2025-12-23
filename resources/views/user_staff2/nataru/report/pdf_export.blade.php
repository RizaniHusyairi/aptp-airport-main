<!DOCTYPE html>
<html>
<head>
    <title>Laporan Posko - {{ $nataruEvent->name }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d2c4a; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #0d2c4a; font-size: 18pt; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #666; font-size: 10pt; }
        
        .section-title { 
            background-color: #f2f7ff; 
            color: #0d2c4a; 
            padding: 5px 10px; 
            font-weight: bold; 
            border-left: 5px solid #0d2c4a; 
            margin-top: 20px; 
            margin-bottom: 10px;
            font-size: 11pt;
        }

        /* CARD RINGKASAN */
        .summary-box { width: 100%; margin-bottom: 20px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { width: 25%; padding: 10px; vertical-align: top; }
        .card { 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            padding: 10px; 
            text-align: center; 
            background: #fff;
        }
        .card-title { font-size: 9pt; text-transform: uppercase; color: #666; font-weight: bold; margin-bottom: 5px; }
        .card-value { font-size: 16pt; font-weight: bold; color: #0d2c4a; display: block; }
        .card-diff { font-size: 8pt; margin-top: 5px; display: block; font-weight: bold; }
        .text-up { color: #198754; }
        .text-down { color: #dc3545; }
        
        /* GRAFIK */
        .chart-container { width: 100%; text-align: center; margin-bottom: 10px; }
        .chart-img { width: 100%; height: auto; border: 1px solid #eee; }

        /* TABEL HARIAN */
        .table-daily { width: 100%; border-collapse: collapse; font-size: 9pt; }
        .table-daily th, .table-daily td { border: 1px solid #ddd; padding: 6px; text-align: center; }
        .table-daily th { background-color: #0d2c4a; color: white; }
        .table-daily tr:nth-child(even) { background-color: #f9f9f9; }
        .col-h { width: 40px; font-weight: bold; background: #eef2f7; }
        .col-date { font-size: 8pt; color: #555; }
        
        /* Utility */
        /* .page-break { page-break-after: always; } */
        .row::after { content: ""; clear: both; display: table; }
        .col-4 { float: left; width: 33.33%; padding: 5px; box-sizing: border-box; }
        .page-break {
            page-break-before: always;
        }

        /* CSS KHUSUS TABEL DATA LENGKAP (Compact) */
        .table-compact { width: 100%; border-collapse: collapse; font-size: 8pt; } /* Font diperkecil */
        .table-compact th, .table-compact td { border: 1px solid #aaa; padding: 4px; text-align: center; }
        .table-compact th { background-color: #eee; color: #333; font-weight: bold; }
        .text-left { text-align: left !important; }
        .badge { padding: 2px 4px; border-radius: 3px; font-size: 7pt; color: white; }
        .bg-arr { background-color: #198754; } /* Hijau */
        .bg-dep { background-color: #0dcaf0; color: black; } /* Biru Muda */
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Laporan Monitoring Posko</h1>
        <p><strong>{{ $nataruEvent->name }}</strong></p>
        <p>Periode: {{ $nataruEvent->start_date->format('d F Y') }} s/d {{ $nataruEvent->end_date->format('d F Y') }}</p>
        <p>Bandara A.P.T. Pranoto Samarinda</p>
    </div>

    {{-- 1. RINGKASAN TOTAL --}}
    <div class="section-title">1. Ringkasan Statistik Total</div>
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    <div class="card">
                        <div class="card-title">Total Pesawat</div>
                        <span class="card-value">{{ number_format($currentStats['flights']) }}</span>
                        @if($nataruEvent->compareEvent)
                            <span class="card-diff {{ $comparison['flights'] >= 0 ? 'text-up' : 'text-down' }}">
                                {{ $comparison['flights'] >= 0 ? '+' : '' }}{{ number_format($comparison['flights']) }} vs Tahun Lalu
                            </span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Total Penumpang</div>
                        <span class="card-value">{{ number_format($currentStats['pax']) }}</span>
                        @if($nataruEvent->compareEvent)
                            <span class="card-diff {{ $comparison['pax'] >= 0 ? 'text-up' : 'text-down' }}">
                                {{ $comparison['pax'] >= 0 ? '+' : '' }}{{ number_format($comparison['pax']) }} vs Tahun Lalu
                            </span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Total Kargo (Kg)</div>
                        <span class="card-value">{{ number_format($currentStats['cargo']) }}</span>
                        @if($nataruEvent->compareEvent)
                            <span class="card-diff {{ $comparison['cargo'] >= 0 ? 'text-up' : 'text-down' }}">
                                {{ $comparison['cargo'] >= 0 ? '+' : '' }}{{ number_format($comparison['cargo']) }} vs Tahun Lalu
                            </span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="card-title">Avg Load Factor</div>
                        <span class="card-value">{{ number_format($currentStats['lf'], 2) }}%</span>
                        @if($nataruEvent->compareEvent)
                            <span class="card-diff {{ $comparison['lf'] >= 0 ? 'text-up' : 'text-down' }}">
                                {{ $comparison['lf'] >= 0 ? '+' : '' }}{{ number_format($comparison['lf'], 2) }}% vs Tahun Lalu
                            </span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- 2. GRAFIK PERBANDINGAN --}}
    @if($nataruEvent->compareEvent)
    <div class="section-title page-break">2. Grafik Perbandingan Tren</div>
    <div class="row">
        <div class="col-12">
            <div class="chart-container">
                <img src="{{ $chartImages['flight'] }}" class="chart-img">
            </div>
        </div>
        <div class="col-12">
            <div class="chart-container">
                <img src="{{ $chartImages['pax'] }}" class="chart-img">
            </div>
        </div>
        <div class="col-12">
            <div class="chart-container">
                <img src="{{ $chartImages['cargo'] }}" class="chart-img">
            </div>
        </div>
    </div>
    @endif

    {{-- 3. TABEL HARIAN --}}
    <div class="section-title page-break" style="margin-top: 20px;">3. Rincian Data Harian (Comparison)</div>
    <table class="table-daily">
        <thead>
            <tr>
                <th rowspan="2" class="col-h">H</th>
                <th colspan="4">{{ $nataruEvent->name }} (Tahun Ini)</th>
                <th colspan="4">{{ $nataruEvent->compareEvent->name ?? 'Tahun Lalu' }}</th>
                <th colspan="3">Selisih (Diff)</th>
            </tr>
            <tr>
                {{-- Event 1 --}}
                <th>Tanggal</th>
                <th>Flight</th>
                <th>Pax</th>
                <th>Cargo</th>
                
                {{-- Event 2 --}}
                <th>Tanggal</th>
                <th>Flight</th>
                <th>Pax</th>
                <th>Cargo</th>

                {{-- Diff --}}
                <th>Flight</th>
                <th>Pax</th>
                <th>Cargo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyReport as $row)
            <tr>
                <td class="col-h">{{ $row['label'] }}</td>
                
                {{-- Data 1 --}}
                <td class="col-date">{{ $row['date1'] }}</td>
                <td><strong>{{ $row['stats1']['flights'] }}</strong></td>
                <td>{{ number_format($row['stats1']['pax']) }}</td>
                <td>{{ number_format($row['stats1']['cargo']) }}</td>

                {{-- Data 2 --}}
                <td class="col-date">{{ $row['date2'] }}</td>
                <td>{{ $row['stats2']['flights'] }}</td>
                <td>{{ number_format($row['stats2']['pax']) }}</td>
                <td>{{ number_format($row['stats2']['cargo']) }}</td>

                {{-- Diff --}}
                @php 
                    $dFlight = $row['stats1']['flights'] - $row['stats2']['flights'];
                    $dPax = $row['stats1']['pax'] - $row['stats2']['pax'];
                    $dCargo = $row['stats1']['cargo'] - $row['stats2']['cargo'];
                @endphp

                <td class="{{ $dFlight >= 0 ? 'text-up' : 'text-down' }}">
                    {{ $dFlight > 0 ? '+' : '' }}{{ $dFlight }}
                </td>
                <td class="{{ $dPax >= 0 ? 'text-up' : 'text-down' }}">
                    {{ $dPax > 0 ? '+' : '' }}{{ number_format($dPax) }}
                </td>
                <td class="{{ $dCargo >= 0 ? 'text-up' : 'text-down' }}">
                    {{ $dCargo > 0 ? '+' : '' }}{{ number_format($dCargo) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 4. MASTER DATA PENERBANGAN --}}
    <div class="section-title page-break">4. Data Seluruh Penerbangan Selama {{ $nataruEvent->name }}</div>
    
    <table class="table-compact">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 40px;">Jam</th>
                <th>Maskapai & No. Flight</th>
                <th>Rute</th>
                <th style="width: 60px;">Status Penerbangan</th>
                <th>Pax Total</th>
                <th>Kargo (Kg)</th>
                {{-- KOLOM BARU: HARGA --}}
                <th style="width: 80px;">Tiket Tertinggi(T)/Terendah(L)</th>
                
                {{-- KOLOM BARU: PETUGAS --}}
                <th>Petugas Input</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allFlights as $index => $flight)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($flight->flight_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($flight->flight_time)->format('H:i') }}</td>
                <td class="text-left">
                    <strong>{{ $flight->airline }}</strong><br>
                    <span style="color: #555;">{{ $flight->flight_number }}</span>
                </td>
                <td>{{ $flight->route }}</td>
                <td>
                    @if($flight->direction == 'arrival')
                        <span class="badge bg-arr">Datang</span>
                    @else
                        <span class="badge bg-dep">Berangkat</span>
                    @endif
                </td>
                <td>
                    <strong>{{ $flight->pax_total }}</strong>
                    <br><span style="font-size: 7pt; color: #666;">(D:{{$flight->pax_adult}} A:{{$flight->pax_child}} B:{{$flight->pax_infant}})</span>
                </td>
                <td>{{ number_format($flight->cargo) }}</td>

                {{-- ISI KOLOM HARGA (Stacked agar rapi) --}}
                <td style="text-align: right; font-size: 8pt;">
                    <div style="white-space: nowrap;">
                        <span style="color: #555;">H:</span> {{ number_format($flight->ticket_price_high, 0, ',', '.') }}
                    </div>
                    <div style="white-space: nowrap;">
                        <span style="color: #555;">L:</span> {{ number_format($flight->ticket_price_low, 0, ',', '.') }}
                    </div>
                </td>

                {{-- ISI KOLOM PETUGAS --}}
                <td style="font-size: 7pt; text-align: left;">
                    {{ \Illuminate\Support\Str::limit($flight->officer_name, 15) }}
                </td>
                <td>{{ $flight->status_flight }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding: 20px;">Belum ada data penerbangan yang diinput.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 9pt; color: #555;">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WITA</p>
        <p>http://aptpairport.id/ - Bandara APT Pranoto</p>
    </div>

</body>
</html>