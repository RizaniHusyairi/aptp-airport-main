<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIVE MONITORING - {{ $nataruEvent->name }}</title>

    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/iconly.css') }}">
    <script src="{{ asset('assetsv2/extensions/apexcharts/apexcharts.min.js') }}"></script>

    <style>

        body {
            background-color: #f2f7ff; /* Light Blueish Gray Background */
            color: #333;
            overflow: hidden; /* Mencegah scroll halaman utama */
            font-family: 'Nunito', sans-serif;
            height: 100vh; /* Pastikan body setinggi layar */
            display: flex;
            flex-direction: column;
        }

        /* --- Header Minimalis Light --- */
        .tv-header {
            background: #ffffff;
            padding: 0.6rem 1.5rem;
            border-bottom: 3px solid #f0a500;
            margin-bottom: 0.8rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* Shadow lebih halus */
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .header-logo { height: 45px; width: auto; } /* Logo asli (tidak di-invert) */
        .header-title h2 {
            font-size: 1.3rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #0d2c4a; /* Warna biru tua korporat */
        }
        .header-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Info Tiket di Header (Kompak) */
        .ticket-info-container {
            display: flex;
            gap: 15px; 
            margin-right: 20px;
            padding-right: 20px;
            border-right: 1px solid #e0e0e0;
            align-items: stretch;
        }
        .ticket-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff; /* Background putih bersih */
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03); /* Soft shadow */
            transition: all 0.3s;
            min-width: 200px;
        }
        .ticket-icon {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 8px; /* Kotak rounded */
            color: white;
            flex-shrink: 0;
        }
        .ticket-icon.high { background: linear-gradient(135deg, #17a2b8, #117a8b); box-shadow: 0 4px 6px rgba(23, 162, 184, 0.3); }
        .ticket-icon.low { background: linear-gradient(135deg, #20c997, #198754); box-shadow: 0 4px 6px rgba(32, 201, 151, 0.3); } /* Ubah warna Low jadi Hijau agar beda */

        .ticket-content { display: flex; flex-direction: column; justify-content: center; }
        .ticket-label { 
            font-size: 0.65rem; 
            text-transform: uppercase; 
            color: #888; 
            font-weight: 700; 
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        
        .ticket-value { 
            font-size: 1rem; 
            font-weight: 800; 
            color: #0d2c4a; 
            line-height: 1.1; 
        }

        /* Desain Baru: Detail Maskapai & Rute */
        .ticket-details {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 3px;
            font-size: 0.7rem;
            white-space: nowrap;
        }
        .detail-pill {
            padding: 1px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        .airline-pill { background-color: #eef2f7; color: #0d2c4a; border: 1px solid #dee2e6; }
        .route-pill { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

        .header-right { text-align: right; display: flex; align-items: center; gap: 15px; }

        .live-badge {
            font-size: 0.7rem;
            background: rgba(255, 77, 77, 0.1);
            color: #ff4d4d;
            padding: 1px 6px;
            border-radius: 4px;
            border: 1px solid rgba(255, 77, 77, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 2px;
        }
        .live-dot {
            width: 5px; height: 5px;
            background-color: #ff4d4d;
            border-radius: 50%;
            animation: blink 1s infinite;
        }
        .live-dot.loading { background-color: #f0a500; animation: none; }
        @keyframes blink { 50% { opacity: 0; } }

        .digital-clock { font-size: 1.1rem; font-weight: 700; font-family: monospace; line-height: 1; color: #0d2c4a; }
        .date-display { font-size: 0.75rem; color: #6c757d; }

        /* --- Konten Utama --- */
        #main-content {
            flex-grow: 1;
            padding: 0.3rem 1.5rem 1rem 1.5rem;
            overflow:hidden;
        }

        /* --- Kartu Statistik Light --- */
        .stat-card {
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #eef2f7;
            padding: 1rem 1.2rem;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s; /* Tambahkan transisi border-color */
        }
        .stat-card.active-highlight {
            border-color: #f0a500; /* Highlight border warna emas */
            box-shadow: 0 0 10px rgba(240, 165, 0, 0.2);
            transform: scale(1.02);
        }
        .stat-card.updated::after {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(240, 165, 0, 0.1); animation: flash 0.5s; pointer-events: none;
        }

        .stat-title {
            font-size: 0.8rem; color: #6c757d; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 2px;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: 800; color: #0d2c4a; line-height: 1.1;
        }
        .stat-icon-bg {
            position: absolute; right: 10px; bottom: 10px; font-size: 2rem; opacity: 0.5; transform: rotate(-10deg);
        }

        .diff-badge {
            font-size: 0.7rem; padding: 1px 5px; border-radius: 4px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 3px; margin-top: 2px;
        }
        .diff-up { background: rgba(32, 201, 151, 0.1); color: #198754; }
        .diff-down { background: rgba(255, 107, 107, 0.1); color: #dc3545; }
        .diff-neutral { background: rgba(170, 176, 182, 0.1); color: #6c757d; }

        /* --- Chart Container Light --- */
        .chart-container {
            background: #ffffff;
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #eef2f7;
            height: 100%;
            min-height: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            position: relative; /* Penting untuk progress bar */
        }

        /* Progress bar untuk rotasi chart */
        .chart-progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 3px;
            background-color: #f0a500;
            width: 0%;
            transition: width 0.1s linear;
            border-radius: 10px 10px 0 0;
            z-index: 5;
        }

        /* --- Table Styling Light --- */
        .card-table {
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            min-height: 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }
        .table-header {
            background: #ffffff;
            padding: 8px 15px;
            border-bottom: 1px solid #f2f2f2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            z-index: 10;
        }
        .table-title { font-size: 0.9rem; font-weight: 700; margin: 0; color: #0d2c4a; }

        .table-scroll-container {
            flex-grow: 1;
            overflow-y: hidden;
            position: relative;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .table-scroll-container::-webkit-scrollbar { display: none; }

        .table-tv { width: 100%; border-collapse: separate; border-spacing: 0 2px; }

        /* Header Tabel Sticky */
        .table-tv thead th {
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.7rem;
            padding: 8px 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            background-color: #f8f9fa; /* Abu-abu sangat terang */
            z-index: 5;
            border-bottom: 2px solid #eef2f7;
        }

        .table-tv tbody tr {
            background: #ffffff;
            transition: background-color 0.3s;
            border-bottom: 1px solid #f2f2f2;
        }
        .table-tv tbody tr:nth-child(even) { background-color: #fcfcfc; } /* Zebra striping halus */

        .table-tv tbody tr.new-row { animation: highlightRow 2s ease-out; }
        @keyframes highlightRow {
            0% { background-color: #fff3cd; } /* Kuning muda */
            100% { background-color: #ffffff; }
        }

        .table-tv td {
            padding: 6px 12px;
            vertical-align: middle;
            border: none;
            color: #333;
            font-size: 0.85rem;
            white-space: nowrap;
            border-bottom: 1px solid #f2f2f2;
        }
        .table-tv td:first-child { border-left: 3px solid #f0a500; }

        /* Utilities */
        .fade-out { opacity: 0.5; pointer-events: none; transition: opacity 0.3s; }
        .text-xs { font-size: 0.7rem; }

        .daily-flight-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 8px 15px; /* Padding compact */
            border: 1px solid #eef2f7;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            
            margin-bottom: 1rem;
            height: 75px; /* Tinggi fix agar rapi */
        }
        .daily-label-box {
            background: #0d2c4a;
            color: #fff;
            padding: 0 15px;
            height: 100%;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
            min-width: 70px; /* Lebar dikecilkan sedikit karena isinya cuma H-X */
        }
        .daily-label-h { font-size: 1.2rem; font-weight: 800; line-height: 1; }
        .daily-label-sub { font-size: 0.6rem; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; }
        .daily-stat-group {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-grow: 1;
            justify-content: space-around;
        }

        .daily-group {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            padding: 0 15px;
            gap: 15px;
        }

        .daily-group-divider {
            width: 1px; height: 40px; background: #e0e0e0; margin: 0 5px;
        }

        /* Icon Utama per Group */
        .group-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .group-icon .bi{
                margin-bottom: 18px;
                margin-right: 6px;
        }

        .bg-flight { background: linear-gradient(135deg, #0d6efd, #0a58ca); }
        .bg-pax { background: linear-gradient(135deg, #198754, #157347); }
        .bg-cargo { background: linear-gradient(135deg, #ffc107, #fd7e14); }

        /* Detail Item (Arr/Dep) */
        .detail-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 80px;
        }
        .detail-label { 
            font-size: 0.65rem; color: #6c757d; font-weight: 700; text-transform: uppercase; 
            display: flex; align-items: center; gap: 4px;
        }
        .detail-value { font-size: 1.1rem; font-weight: 800; color: #333; line-height: 1.1; }
        
        /* Badge Perbandingan Kecil */
        .comp-badge {
            font-size: 0.65rem; padding: 1px 4px; border-radius: 3px; font-weight: 600; margin-left: 5px; vertical-align: middle;
        }
        .comp-up { background: rgba(25, 135, 84, 0.1); color: #198754; }
        .comp-down { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .daily-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .daily-icon {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: white;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .daily-info { display: flex; flex-direction: column; }
        .daily-title { font-size: 0.7rem; text-transform: uppercase; color: #6c757d; font-weight: 700; letter-spacing: 0.5px; }
        .daily-val { font-size: 1.2rem; font-weight: 800; color: #333; line-height: 1; }
        .daily-comp { font-size: 0.7rem; color: #999; font-weight: 600; }
        
        .icon-arr { background: linear-gradient(135deg, #198754, #20c997); }
        .icon-dep { background: linear-gradient(135deg, #0d6efd, #0dcaf0); }
    </style>
</head>
<body>

    {{-- Header TV Minimalis --}}
    <div class="tv-header">
        <div class="header-left">
            {{-- Gunakan logo berwarna asli untuk background putih --}}
            <img src="{{ asset('assets_landing/img/logo/logo-apt.svg') }}" alt="Logo" class="header-logo">
            <div class="header-title">
                <h2>POSKO MONITORING</h2>
                <div class="header-subtitle">{{ $nataruEvent->name }}</div>
            </div>
        </div>

        <div class="header-right">
            
            <div class="clock-container">

                <div class="live-badge">
                    <span class="live-dot" id="liveIndicator"></span>
                    <span id="liveText">LIVE</span>
                </div>
                <div class="digital-clock" id="digital-clock">00:00:00</div>
                <div class="date-display" id="current-date">Loading...</div>
            </div>
        </div>
    </div>

    <div class="container-fluid h-100" id="main-content">

        @php
            function tvDiff($val, $isPercent = false) {
                if ($val > 0) {
                    return '<span class="diff-badge diff-up"><i class="bi bi-arrow-up"></i> ' . number_format($val, $isPercent ? 2 : 0) . ($isPercent ? '%' : '') . '</span>';
                } elseif ($val < 0) {
                    return '<span class="diff-badge diff-down"><i class="bi bi-arrow-down"></i> ' . number_format(abs($val), $isPercent ? 2 : 0) . ($isPercent ? '%' : '') . '</span>';
                } else {
                    return '<span class="diff-badge diff-neutral"><i class="bi bi-dash"></i> 0</span>';
                }
            }
        @endphp

        {{-- BARU: Card Ringkasan Harian (Arr vs Dep) --}}
        {{-- CARD RINGKASAN HARIAN (FULL INFO - DENGAN ID AUTO UPDATE) --}}
        <div class="daily-flight-card flex-shrink-0">
            
            {{-- 1. Label Hari (H-X) --}}
            <div class="daily-label-box">
                <span class="daily-label-h">{{ $dailyStats['label_h'] }}</span>
            </div>

            {{-- 2. TEKS JUDUL BARU (Disisipkan Disini) --}}
            <div class="d-flex flex-column justify-content-center me-4 ps-3 border-start" style="height: 45px;">
                <span class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px; line-height: 1;">Ringkasan</span>
                <span class="text-dark fw-bolder" style="font-size: 0.95rem; line-height: 1.1;">DATA PENERBANGAN<br>HARI INI</span>
            </div>
            {{-- ---------------------------------------- --}}

            {{-- 2. Group Pesawat --}}
            <div class="daily-group">
                <div class="group-icon bg-flight"><i class="bi bi-airplane-engines"></i></div>
                
                {{-- Arr Flight --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-arrow-down-left text-primary"></i> Datang</span>
                    <div>
                        {{-- TAMBAHAN ID: d-flight-arr --}}
                        <span class="detail-value" id="d-flight-arr">{{ number_format($dailyStats['flights_arr']) }}</span>
                        
                        
                    </div>
                </div>

                {{-- Dep Flight --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-arrow-up-right text-info"></i> Berangkat</span>
                    <div>
                        <span class="detail-value" id="d-flight-dep">{{ number_format($dailyStats['flights_dep']) }}</span>
                        
                    </div>
                </div>
            </div>

            <div class="daily-group-divider"></div>

            {{-- 3. Group Penumpang --}}
            <div class="daily-group">
                <div class="group-icon bg-pax"><i class="bi bi-people-fill"></i></div>
                
                {{-- Arr Pax --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-arrow-down-left text-success"></i> Datang</span>
                    <div>
                        <span class="detail-value" id="d-pax-arr">{{ number_format($dailyStats['pax_arr']) }}</span>
                        
                    </div>
                </div>

                {{-- Dep Pax --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-arrow-up-right text-success"></i> Berangkat</span>
                    <div>
                        <span class="detail-value" id="d-pax-dep">{{ number_format($dailyStats['pax_dep']) }}</span>
                        
                    </div>
                </div>
            </div>

            <div class="daily-group-divider"></div>

            {{-- 4. Group Kargo --}}
            <div class="daily-group">
                <div class="group-icon bg-cargo"><i class="bi bi-box-seam-fill"></i></div>
                
                {{-- Arr Kargo --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-arrow-down-left text-warning"></i> Datang</span>
                    <div>
                        <span class="detail-value" id="d-cargo-arr">{{ number_format($dailyStats['cargo_arr']) }}</span>
                        {{-- <span id="d-cargo-arr-diff">
                            @if($nataruEvent->compare_event_id)
                                @php $diff = $dailyStats['cargo_arr'] - $dailyStats['comp_cargo_arr']; @endphp
                                <span class="comp-badge {{ $diff >= 0 ? 'comp-up' : 'comp-down' }}">
                                    {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff) }}
                                </span>
                            @endif
                        </span> --}}
                    </div>
                </div>

                {{-- Dep Kargo --}}
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-arrow-up-right text-warning"></i> Berangkat</span>
                    <div>
                        <span class="detail-value" id="d-cargo-dep">{{ number_format($dailyStats['cargo_dep']) }}</span>
                        {{-- <span id="d-cargo-dep-diff">
                            @if($nataruEvent->compare_event_id)
                                @php $diff = $dailyStats['cargo_dep'] - $dailyStats['comp_cargo_dep']; @endphp
                                <span class="comp-badge {{ $diff >= 0 ? 'comp-up' : 'comp-down' }}">
                                    {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff) }}
                                </span>
                            @endif
                        </span> --}}
                    </div>
                </div>
            </div>

        </div>

        {{-- Layout Grid Baru --}}
        <div class="row flex-grow-1 g-3" style="min-height: 0; padding-bottom:10px;">

            {{-- 1. Kolom Kiri (Statistik) --}}
            <div class="col-2 h-100 d-flex flex-column gap-3">
                <!-- Flight -->
                <div class="stat-card flex-fill" id="card-flights">
                    <div class="stat-title">Total Penerbangan</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-flights">{{ number_format($currentStats['total_flights']) }}</div>
                    </div>
                    <div id="diff-pax"><small class="ms-1" style="font-size: 0.8rem">Dari H-9 s/d Hari ini</small></div>

                    <div class="stat-icon-bg text-primary"><i class="bi bi-airplane-engines"></i></div>
                </div>

                <!-- Pax -->
                <div class="stat-card flex-fill" id="card-pax">
                    <div class="stat-title">Total Penumpang</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-pax">{{ number_format($currentStats['total_pax']) }}</div>
                    </div>
                    
                        <div id="diff-pax"><small class="ms-1" style="font-size: 0.8rem">Dari H-9 s/d Hari ini</small></div>
                    
                    <div class="stat-icon-bg text-success"><i class="bi bi-people-fill"></i></div>
                </div>

                <!-- Cargo -->
                <div class="stat-card flex-fill" id="card-cargo">
                    <div class="stat-title">Total Kargo (Kg)</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-cargo">{{ number_format($currentStats['total_cargo']) }}</div>
                    </div>
                    <div id="diff-pax"><small class="ms-1" style="font-size: 0.8rem">Dari H-9 s/d Hari ini</small></div>
                    <div class="stat-icon-bg text-warning"><i class="bi bi-box-seam-fill"></i></div>
                </div>

                <!-- Load Factor -->
                <div class="stat-card flex-fill" id="card-lf">
                    <div class="stat-title">Avg Load Factor</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-lf">{{ number_format($currentStats['avg_lf'], 1) }}%</div>
                    </div>
                    <div id="diff-pax"><small class="ms-1" style="font-size: 0.8rem">Dari H-9 s/d Hari ini</small></div>
                    <div class="stat-icon-bg text-danger"><i class="bi bi-pie-chart-fill"></i></div>
                </div>
            </div>

            {{-- 2. Kolom Kanan (Grafik & Tabel) --}}
            <div class="col-10 h-100 d-flex flex-column gap-3">

                {{-- Bagian Atas: Grafik --}}
                @if($nataruEvent->compare_event_id)
                <div class="chart-container flex-fill" style="height: 200%; min-height: 0;">
                    <div class="chart-progress-bar" id="chart-progress"></div> {{-- Progress Bar untuk rotasi --}}
                    <div class="d-flex justify-content-between align-items-center mb-1 flex-shrink-0">
                        <h6 class="mb-0 text-dark text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;" id="chart-title">Tren Pesawat</h6> {{-- Judul Dinamis --}}

                    </div>
                    <div id="chartPaxTv" style="flex-grow: 1; min-height: 0;"></div>
                </div>
                @endif

                {{-- Bagian Bawah: Tabel Auto Scroll --}}
                <div class="card-table flex-fill" style="min-height: 0;">
                    <div class="table-header">
                        <div class="table-title"><i class="bi bi-clock-history me-2 text-primary"></i>Penerbangan Hari Ini</div> {{-- Judul Diubah --}}
                        <span class="badge bg-success bg-opacity-10 text-success p-2 px-3" style="font-size: 0.75rem;">
                            <i class="bi bi-calendar-check me-1"></i>
                            @php
                            // 1. Ambil Start & End Date (Reset waktu ke 00:00:00 agar akurat)
                            $start = \Carbon\Carbon::parse($nataruEvent->start_date)->startOfDay();
                            $end   = \Carbon\Carbon::parse($nataruEvent->end_date)->startOfDay();

                            // 2. Hitung Titik Tengah (Mean) sebagai H-0
                            // (LOGIKA INI DISAMAKAN DENGAN CONTROLLER)
                            $diffTotal = $start->diffInDays($end);
                            $offset = ceil($diffTotal / 2);
                            $refDate = $start->copy()->addDays($offset);

                            // 3. Hitung Selisih Hari Ini dengan H-0
                            $today = \Carbon\Carbon::now()->startOfDay();

                            // Logika:
                            // diffInDays(target, false): Bernilai positif jika target (Ref) ada di masa depan.
                            // Contoh: Hari ini tgl 18, H-0 tgl 27. Maka diff = 9.
                            // Kita ingin formatnya H-9 (Negatif). Maka dikali -1.
                            $diff = $today->diffInDays($refDate, false) * -1;

                            $label = "";
                            if ($diff == 0) {
                                $label = "Hari H";
                            } elseif ($diff < 0) {
                                $label = "H" . $diff;
                            } else {
                                $label = "H+" . $diff;
                            }
                        @endphp
                            {{ $label }} ({{ $today->translatedFormat('d M') }})
                        </span>
                    </div>
                    <div class="table-scroll-container" id="flights-scroll-container">
                        <table class="table-tv">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Maskapai</th>
                                    <th>No. Flight</th>
                                    <th>Rute</th>
                                    <th>Status</th>
                                    <th>Pax</th>
                                    <th>Kargo</th>
                                </tr>
                            </thead>
                            <tbody id="flights-table-body">
                                @forelse($todaysFlights as $flight)
                                <tr data-id="{{ $flight->id }}">
                                    <td class="fw-bold text-primary">{{ \Carbon\Carbon::parse($flight->flight_time)->format('H:i') }}</td>
                                    <td class="fw-bold text-dark">{{ $flight->airline }}</td>
                                    <td><span class="badge bg-light-secondary text-dark font-monospace text-xs">{{ $flight->flight_number }}</span></td>
                                    <td>{{ $flight->route }}</td>
                                    <td>
                                        @if($flight->direction == 'arrival')
                                            <span class="text-success text-xs fw-bold"><i class="bi bi-arrow-down-left"></i> DATANG</span>
                                        @else
                                            <span class="text-info text-xs fw-bold"><i class="bi bi-arrow-up-right"></i> BERANGKAT</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-dark">{{ $flight->pax_total }}</td>
                                    <td class="text-muted">{{ number_format($flight->cargo) }}</td>
                                </tr>
                                @empty
                                <tr id="empty-row">
                                    <td colspan="7" class="text-center py-4 text-muted text-xs">Belum ada data hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Jam Digital
        function updateClock() {
            const now = new Date();
            document.getElementById('digital-clock').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
            document.getElementById('current-date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. Auto Scroll Tabel Logika
        function startAutoScroll() {
            const container = document.getElementById('flights-scroll-container');
            if(!container) return;

            let scrollPos = 0;
            const scrollSpeed = 0.5;
            const pauseDuration = 3000;
            let isPaused = false;

            function animateScroll() {
                if(container.scrollHeight <= container.clientHeight) {
                    requestAnimationFrame(animateScroll);
                    return;
                }

                if (!isPaused) {
                    scrollPos += scrollSpeed;
                    if (scrollPos >= (container.scrollHeight - container.clientHeight)) {
                        isPaused = true;
                        setTimeout(() => {
                            scrollPos = 0;
                            container.scrollTop = 0;
                            setTimeout(() => { isPaused = false; }, pauseDuration);
                        }, pauseDuration);
                    } else {
                        container.scrollTop = scrollPos;
                    }
                }
                requestAnimationFrame(animateScroll);
            }

            setTimeout(animateScroll, 2000);
        }
        startAutoScroll();


        // 3. Auto Refresh Data (Smooth Update)
        const liveIndicator = document.getElementById('liveIndicator');
        const liveText = document.getElementById('liveText');

        function updateDashboard() {
            liveIndicator.classList.add('loading');
            liveText.style.color = '#f0a500';

            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Update Statistik
                    updateStatIfChanged('val-flights', doc);
                    updateStatIfChanged('val-pax', doc);
                    updateStatIfChanged('val-cargo', doc);
                    updateStatIfChanged('val-lf', doc);


                    updateContentIfChanged('val-max-airline', doc);
                    updateContentIfChanged('val-max-route', doc);
                    
                    updateContentIfChanged('val-min-airline', doc);
                    updateContentIfChanged('val-min-route', doc);

                    updateContentIfChanged('diff-flights', doc);
                    updateContentIfChanged('diff-pax', doc);
                    updateContentIfChanged('diff-cargo', doc);
                    updateContentIfChanged('diff-lf', doc);

                    // 1. Update Angka Utama (Value)
                    updateStatIfChanged('d-flight-arr', doc);
                    updateStatIfChanged('d-flight-dep', doc);
                    updateStatIfChanged('d-pax-arr', doc);
                    updateStatIfChanged('d-pax-dep', doc);
                    updateStatIfChanged('d-cargo-arr', doc);
                    updateStatIfChanged('d-cargo-dep', doc);

                    // 2. Update Badge Perbandingan (Diff)
                    // Pakai updateContent karena badge mengandung class warna & simbol (+/-)
                    updateContentIfChanged('d-flight-arr-diff', doc);
                    updateContentIfChanged('d-flight-dep-diff', doc);
                    updateContentIfChanged('d-pax-arr-diff', doc);
                    updateContentIfChanged('d-pax-dep-diff', doc);
                    updateContentIfChanged('d-cargo-arr-diff', doc);
                    updateContentIfChanged('d-cargo-dep-diff', doc);

                    // Update Tabel
                    const newTableBody = doc.getElementById('flights-table-body');
                    const currentTableBody = document.getElementById('flights-table-body');

                    const firstNewRow = newTableBody.querySelector('tr');
                    const firstCurrentRow = currentTableBody.querySelector('tr');

                    if (firstNewRow && (!firstCurrentRow || firstNewRow.dataset.id !== firstCurrentRow.dataset.id)) {
                        const container = document.getElementById('flights-scroll-container');

                        currentTableBody.style.opacity = '0.5';
                        setTimeout(() => {
                            currentTableBody.innerHTML = newTableBody.innerHTML;
                            currentTableBody.style.opacity = '1';
                            if(container) container.scrollTop = 0;

                            const newRows = currentTableBody.querySelectorAll('tr');
                            if(newRows.length > 0) newRows[0].classList.add('new-row');
                        }, 200);
                    }

                    setTimeout(() => {
                        liveIndicator.classList.remove('loading');
                        liveText.style.color = '#ff4d4d'; // Merah kembali
                    }, 500);
                })
                .catch(err => {
                    console.error('Gagal update:', err);
                    liveIndicator.style.backgroundColor = '#555';
                });
        }

        // Helpers
        function updateStatIfChanged(id, newDoc) {
            const currentEl = document.getElementById(id);
            const newEl = newDoc.getElementById(id);
            if (currentEl && newEl && currentEl.innerText !== newEl.innerText) {
                currentEl.style.color = '#f0a500';
                currentEl.innerText = newEl.innerText;
                const card = currentEl.closest('.stat-card');
                if(card) {
                    card.classList.add('updated');
                    setTimeout(() => card.classList.remove('updated'), 500);
                }
                setTimeout(() => { currentEl.style.color = ''; }, 1000);
            }
        }

        function updateContentIfChanged(id, newDoc) {
             const currentEl = document.getElementById(id);
             const newEl = newDoc.getElementById(id);
             if(currentEl && newEl && currentEl.innerHTML !== newEl.innerHTML) {
                 currentEl.innerHTML = newEl.innerHTML;
             }
        }

        setInterval(updateDashboard, 30000);

        // 4. Grafik Light Mode dengan Auto Update Data & Rotasi
        document.addEventListener('DOMContentLoaded', function () {
            @if($nataruEvent->compare_event_id)
                const chartUrl = "{{ route('public.nataru.chart', $nataruEvent->public_token) }}";
                let chartInstance = null;
                let currentChartIndex = 0; // 0: Flights, 1: Pax, 2: Cargo
                let globalChartData = null;

                // Definisi Konfigurasi (Tetap sama)
                const chartConfigs = [
                    {
                        type: 'flights', title: 'Tren Pesawat (Pergerakan)', cardId: 'card-flights',
                        colors: ['#0d6efd', '#6610f2', '#BF124D', '#76153C']
                    },
                    {
                        type: 'pax', title: 'Tren Penumpang (Orang)', cardId: 'card-pax',
                        colors: ['#0d6efd', '#6610f2', '#BF124D', '#76153C']
                    },
                    {
                        type: 'cargo', title: 'Tren Kargo (Kg)', cardId: 'card-cargo',
                        colors: ['#0d6efd', '#6610f2', '#BF124D', '#76153C']
                    }
                ];

                // --- FUNGSI BARU: Load Data Secara Berkala ---
                function loadChartData() {
                    fetch(chartUrl)
                    .then(response => {
                        if (!response.ok) throw new Error("Gagal memuat grafik");
                        return response.json();
                    })
                    .then(data => {
                        globalChartData = data; // Simpan data terbaru ke variabel global
                        const rangeLabelEl = document.getElementById('chart-range-label');
                        if(rangeLabelEl && data.range_label) {
                            rangeLabelEl.innerText = data.range_label;
                        }
                        if (!chartInstance) {
                            // Jika chart belum ada, buat baru
                            initChart();
                            startRotation();
                        } else {
                            // Jika chart sudah ada, update datanya langsung tanpa menunggu rotasi
                            // Ini agar grafik berubah real-time saat data masuk
                            const config = chartConfigs[currentChartIndex];
                            chartInstance.updateSeries(getSeriesData(config.type));
                        }
                    })
                    .catch(err => {
                        if(!chartInstance) {
                             document.querySelector("#chartPaxTv").innerHTML = '<div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted opacity-50"><small>Grafik tidak tersedia</small></div>';
                        }
                    });
                }

                // Panggil pertama kali
                loadChartData();

                // Panggil setiap 30 detik (Sinkron dengan refresh dashboard)
                setInterval(loadChartData, 30000);

                // ---------------------------------------------
                let h7 = null;
                let h1 = null;

                function initChart() {
                    const config = chartConfigs[currentChartIndex];
                    updateUIForChart(config);

                    var options = {
                        series: getSeriesData(config.type),
                        chart: {
                            type: 'line', height: '100%', toolbar: {show: false},
                            background: 'transparent', animations: { enabled: true, easing: 'easeinout', speed: 800 },
                            fontFamily: 'Nunito, sans-serif', stacked: false
                        },
                        colors: config.colors,
                        
                        stroke: { 
                            show: true,
                            width: 2,              // Lebar celah (makin besar angka, makin jauh jaraknya)
                            colors: ['transparent']
                        },
                        // 1. UBAH DI SINI (PLOT OPTIONS)
                        plotOptions: { 
                            bar: { 
                                columnWidth: '70%',
                                borderRadius: 2,
                                dataLabels: {     
                                    position: 'bottom',
                                    //  Posisi di ujung atas
                                    // orientation: 'vertical',
                                }
                            } 
                        },
                        dataLabels: { 
                            enabled: true,
                            offsetY: -150,
                             // Geser ke bawah sedikit agar masuk ke dalam batang (karena vertikal butuh ruang ke bawah)
                                        // ATAU gunakan minus (-) jika ingin melayang di atas batang
                            style: {
                                fontSize: '10px',
                                 
                            },
                            hideOverflowingLabels: false,
                            // --- BAGIAN UTAMA TRIK ZIG-ZAG ---
                            formatter: function (val, opts) {
                                const index = opts.seriesIndex; // 0, 1, 2, 3
                                let label = [];

                                // Batang 1 (index 0): Tidak ada enter -> Posisi Paling Tinggi (-60)
                                // Batang 2 (index 1): 1 enter -> Turun sedikit
                                // Batang 3 (index 2): 2 enter -> Turun lagi
                                // Batang 4 (index 3): 3 enter -> Paling bawah (dekat sumbu X)
                                for (let i = 0; i < index; i++) {
                                    label.push(""); 
                                    label.push(""); 
                                }
                                label.push(val);
                                return label;
                            }
                            // Hapus 'rotate: -90' di sini, karena sudah dihandle oleh orientation: 'vertical' di atas
                        },
                        xaxis: {
                            type: 'category', 
                            // categories: globalChartData.categories, // <--- HAPUS BARIS INI (Data kategori sudah ada di dalam Series)
                            
                            labels: {
                                style: { colors: '#6c757d', fontSize: '10px', fontFamily: 'Nunito, sans-serif', fontWeight: 600 },
                                formatter: function (val) {
                                    // Logika pencarian tanggal tetap sama, tapi sekarang 'val' dijamin string 'H-7' yang bersih
                                    let realIndex = -1;
                                    if (globalChartData && globalChartData.categories) {
                                        realIndex = globalChartData.categories.indexOf(val);
                                    }
                                    if (realIndex !== -1 && globalChartData.dates_event1) {
                                        return [val, globalChartData.dates_event1[realIndex], globalChartData.dates_event2[realIndex]];
                                    }
                                    return val;
                                }
                            },
                            // ... config axisBorder, tooltip, dll ...
                        },
                        yaxis: { labels: { style: { colors: '#6c757d', fontSize: '10px' } } },

                        annotations: {
                            position: 'back',
                            xaxis: [
                                // 1. PERIODE ARUS MUDIK (H-7 s.d H-1)
                                {
                                    x: globalChartData.categories.indexOf("H-7")-50, 
                                    x2: globalChartData.categories.indexOf("H-1") + 425,
                                    fillColor: '#00E396', // Warna Hijau Muda
                                    opacity: .1, // Transparansi (biar grafik tetap kelihatan)
                                    label: {
                                        borderColor: '#00E396',
                                        style: {
                                            fontSize: '10px',
                                            color: '#fff',
                                            background: '#00E396',
                                        },
                                         // Geser label ke atas
                                        
                                        text: 'ARUS MUDIK',
                                        position: 'top', // Posisi di Atas
                                        orientation: 'horizontal', // Teks Mendatar
                                        offsetY: 0, // Tempel di paling atas
                                        offsetX: 265 // Geser sedikit ke kanan agar tidak nempel garis start
                                    }
                                },

                                // 2. PERIODE NATAL & TAHUN BARU (H s.d H+6)
                                {
                                    x: globalChartData.categories.indexOf("H")+470, 
                                    x2: globalChartData.categories.indexOf("H+6") + 905,
                                    fillColor: '#FEB019', // Warna Kuning/Emas
                                    opacity: .1,
                                    label: {
                                        borderColor: '#FEB019',
                                        style: {
                                            fontSize: '10px',
                                            color: '#fff',
                                            background: '#FEB019',
                                        },
                                        offsetY: -10,
                                        text: 'NATAL & TAHUN BARU',
                                        position: 'top', // Posisi di Atas
                                        orientation: 'horizontal', // Teks Mendatar
                                        offsetY: 0, // Tempel di paling atas
                                        offsetX: 227 // Geser sedikit ke kanan agar tidak nempel garis start
                                    }
                                },

                                // 3. PERIODE ARUS BALIK (H+7 s.d Selesai)
                                // Catatan: x2 dikosongkan atau diisi H terakhir agar sampai ujung
                                {
                                    x: globalChartData.categories.indexOf("H+7")+950, 
                                    x2: globalChartData.categories.indexOf("H+11") + 2185,
                                    fillColor: '#FF4560', // Warna Merah
                                    opacity: 0.1,
                                    label: {
                                        borderColor: '#FF4560',
                                        style: {
                                            fontSize: '10px',
                                            color: '#fff',
                                            background: '#FF4560',
                                        },
                                        text: 'ARUS BALIK', // (Ganti jadi ARUS MUDIK jika memang itu yang diinginkan)
                                        position: 'top', // Posisi di Atas
                                        orientation: 'horizontal', // Teks Mendatar
                                        offsetY: 0, // Tempel di paling atas
                                        offsetX: 150 // Geser sedikit ke kanan agar tidak nempel garis start
                                    }
                                }
                            ]
                        },
                        grid: {
                            borderColor: '#eef2f7',
                            strokeDashArray: 5,
                            padding: {
                                top: 30,
                                left: 30,   // TAMBAHAN: Memberi napas di sisi kiri (untuk batang awal)
                                right: 30, // Tambah padding atas untuk tempat angka
                            }
                        },
                        theme: { mode: 'light' },
                        legend: {
                            position: 'top', horizontalAlign: 'right', fontSize: '10px',
                            markers: { width: 16, radius: [2, 2, 2, 2], strokeWidth: 0 },
                            itemMargin: { horizontal: 5, vertical: 0 },
                            labels: { colors: '#333' }
                        }
                    };
                    chartInstance = new ApexCharts(document.querySelector("#chartPaxTv"), options);
                    chartInstance.render();
                }

                // Fungsi Rotasi Tampilan (Hanya mengganti dataset & warna)
                function updateChartRotation() {
                    const config = chartConfigs[currentChartIndex];
                    updateUIForChart(config);

                    chartInstance.updateOptions({
                        colors: config.colors,
                        series: getSeriesData(config.type) // Mengambil data terbaru dari globalChartData
                    });
                }

                function getSeriesData(type) {

                    // Helper untuk menggabungkan Kategori (H-xx) dengan Data Angka
                    // Ini kuncinya: Kita 'lem' label H-nya langsung ke datanya.
                    const mapToXY = (dataArray) => {
                        return dataArray.map((val, index) => {
                            // Pastikan categories tersedia
                            if (globalChartData && globalChartData.categories) {
                                return {
                                    x: globalChartData.categories[index], // ID Kunci: 'H-7', 'H-6', dst
                                    y: val
                                };
                            }
                            return val;
                        });
                    };
                    return [
                        { 
                            name: '{{ $nataruEvent->name }} (Arr)', 
                            type: 'bar', 
                            data: mapToXY(globalChartData.dataset1[type + '_arrival']) // Gunakan fungsi helper tadi
                        },
                        { 
                            name: '{{ $nataruEvent->name }} (Dep)', 
                            type: 'bar', 
                            data: mapToXY(globalChartData.dataset1[type + '_departure']) 
                        },
                        { 
                            name: '{{ $nataruEvent->compareEvent->name }} (Arr)', 
                            type: 'bar', 
                            data: mapToXY(globalChartData.dataset2[type + '_arrival']) 
                        },
                        { 
                            name: '{{ $nataruEvent->compareEvent->name }} (Dep)', 
                            type: 'bar', 
                            data: mapToXY(globalChartData.dataset2[type + '_departure']) 
                        }
                    ];
                }

                function updateUIForChart(config) {
                    const titleEl = document.getElementById('chart-title');
                    if(titleEl) {
                        titleEl.style.opacity = 0;
                        setTimeout(() => {
                            titleEl.innerText = config.title;
                            titleEl.style.opacity = 1;
                        }, 300);
                    }
                    document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active-highlight'));
                    const activeCard = document.getElementById(config.cardId);
                    if(activeCard) activeCard.classList.add('active-highlight');

                    // Reset Progress Bar
                    const bar = document.getElementById('chart-progress');
                    if(bar) {
                        bar.style.transition = 'none';
                        bar.style.width = '0%';
                        setTimeout(() => {
                            bar.style.transition = 'width 20s linear';
                            bar.style.width = '100%';
                        }, 50);
                    }
                }

                function startRotation() {
                    const bar = document.getElementById('chart-progress');
                    if(bar) {
                        bar.style.transition = 'width 20s linear';
                        bar.style.width = '100%';
                    }

                    // Interval untuk ROTASI tampilan (ganti jenis grafik)
                    setInterval(() => {
                        currentChartIndex = (currentChartIndex + 1) % chartConfigs.length;
                        updateChartRotation();
                    }, 20000);
                }
            @endif
        });
    </script>
</body>
</html>
