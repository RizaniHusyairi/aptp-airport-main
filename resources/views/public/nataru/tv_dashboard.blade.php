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
            gap: 15px; /* Gap diperkecil */
            margin-right: 20px;
            padding-right: 20px;
            border-right: 1px solid #e0e0e0;
        }
        .ticket-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fa;
            padding: 4px 10px; /* Padding badge diperkecil */
            border-radius: 6px; /* Radius diperkecil */
            border: 1px solid #e9ecef;
        }
        .ticket-icon {
            font-size: 1rem; /* Ikon diperkecil */
            writing-mode: vertical-lr;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px; /* Ukuran diperkecil */
            border-radius: 50%;
            color: white;
        }
        .ticket-icon.high { background: linear-gradient(45deg, #17a2b8, #117a8b); }
        .ticket-icon.low { background: linear-gradient(45deg, #6c757d, #495057); }
        
        .ticket-content { display: flex; flex-direction: column; }
        .ticket-label { font-size: 0.6rem; text-transform: uppercase; color: #6c757d; font-weight: 700; }
        .ticket-value { font-size: 0.9rem; font-weight: 800; color: #333; line-height: 1; } /* Value diperkecil */
        
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
            <div class="ticket-info-container">
                <div class="ticket-badge">
                    <div class="ticket-icon high"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="ticket-content">
                        <span class="ticket-label">Tiket Tertinggi</span>
                        <span class="ticket-value" id="val-max-price">Rp {{ number_format($currentStats['max_ticket'] ?? 0) }}</span>
                    </div>
                </div>
                <div class="ticket-badge">
                    <div class="ticket-icon low"><i class="bi bi-graph-down-arrow"></i></div>
                    <div class="ticket-content">
                        <span class="ticket-label">Tiket Terendah</span>
                        <span class="ticket-value" id="val-min-price">Rp {{ number_format($currentStats['min_ticket'] ?? 0) }}</span>
                    </div>
                </div>
            </div>
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

        {{-- Layout Grid Baru --}}
        <div class="row h-100 g-3">
            
            {{-- 1. Kolom Kiri (Statistik) --}}
            <div class="col-2 h-100 d-flex flex-column gap-3">
                <!-- Flight -->
                <div class="stat-card flex-fill" id="card-flights">
                    <div class="stat-title">Total Penerbangan</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-flights">{{ number_format($currentStats['total_flights']) }}</div>
                    </div>
                    @if($comparison) 
                        <div id="diff-flights">{!! tvDiff($comparison['flights']) !!} <small class="text-muted ms-1" style="font-size: 0.7rem">vs lalu</small></div> 
                    @endif
                    <div class="stat-icon-bg text-primary"><i class="bi bi-airplane-engines"></i></div>
                </div>

                <!-- Pax -->
                <div class="stat-card flex-fill" id="card-pax">
                    <div class="stat-title">Total Penumpang</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-pax">{{ number_format($currentStats['total_pax']) }}</div>
                    </div>
                    @if($comparison) 
                        <div id="diff-pax">{!! tvDiff($comparison['pax']) !!} <small class="text-muted ms-1" style="font-size: 0.7rem">vs lalu</small></div> 
                    @endif
                    <div class="stat-icon-bg text-success"><i class="bi bi-people-fill"></i></div>
                </div>

                <!-- Cargo -->
                <div class="stat-card flex-fill" id="card-cargo">
                    <div class="stat-title">Total Kargo (Kg)</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-cargo">{{ number_format($currentStats['total_cargo']) }}</div>
                    </div>
                    @if($comparison) 
                        <div id="diff-cargo">{!! tvDiff($comparison['cargo']) !!} <small class="text-muted ms-1" style="font-size: 0.7rem">vs lalu</small></div> 
                    @endif
                    <div class="stat-icon-bg text-warning"><i class="bi bi-box-seam-fill"></i></div>
                </div>

                <!-- Load Factor -->
                <div class="stat-card flex-fill" id="card-lf">
                    <div class="stat-title">Avg Load Factor</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="stat-value" id="val-lf">{{ number_format($currentStats['avg_lf'], 1) }}%</div>
                    </div>
                    @if($comparison) 
                        <div id="diff-lf">{!! tvDiff($comparison['lf'], true) !!} <small class="text-muted ms-1" style="font-size: 0.7rem">vs lalu</small></div> 
                    @endif
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
                                // Tentukan tanggal referensi (H-0). Asumsi Nataru: 25 Desember tahun start_date.
                                // Anda bisa menyesuaikan logika ini jika referensi H-0 bukan 25 Des.
                                $refDate = \Carbon\Carbon::create($nataruEvent->start_date->year, 12, 25);
                                $today = \Carbon\Carbon::now();
                                
                                // Hitung selisih hari
                                // diffInDays(target, false): 
                                // Jika target > date (date sebelum H): hasil positif. (Misal 25 - 15 = 10) -> H-10
                                // Jika target < date (date sesudah H): hasil negatif. (Misal 25 - 26 = -1) -> H+1
                                // Kita kalikan -1 agar sesuai dengan logika controller sebelumnya
                                $diff = $today->diffInDays($refDate, false) * -1;

                                $label = "";
                                if ($diff == 0) {
                                    $label = "Hari H";
                                } elseif ($diff < 0) {
                                    $label = "H" . $diff; // H-10
                                } else {
                                    $label = "H+" . $diff; // H+10
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
                    
                    updateStatIfChanged('val-max-price', doc);
                    updateStatIfChanged('val-min-price', doc);

                    updateContentIfChanged('diff-flights', doc);
                    updateContentIfChanged('diff-pax', doc);
                    updateContentIfChanged('diff-cargo', doc);
                    updateContentIfChanged('diff-lf', doc);

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
                        colors: ['#20c997', '#198754', '#C59560', '#E67E22'] 
                    },
                    { 
                        type: 'cargo', title: 'Tren Kargo (Kg)', cardId: 'card-cargo',
                        colors: ['#ffc107', '#fd7e14', '#E83C91', '#FF8FB7'] 
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
                        console.log(err);
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
                        dataLabels: { enabled: true },
                        stroke: { width: [0, 0, 2, 2], curve: 'smooth', dashArray: [0, 0, 5, 5] },
                        plotOptions: { bar: { columnWidth: '60%', borderRadius: 2 } },
                        xaxis: { 
                            categories: globalChartData.categories, // Menggunakan data global
                            labels: { 
                                style: { colors: '#6c757d', fontSize: '10px' }, 
                                formatter: function (val, timestamp, index) {
                                    // Pastikan data tanggal tersedia
                                    console.log(globalChartData);
                                    if (typeof index !== 'undefined' && globalChartData.dates_event1 && globalChartData.dates_event2) {
                                        const date1 = globalChartData.dates_event1[index]; // Tanggal Event Sekarang
                                        const date2 = globalChartData.dates_event2[index]; // Tanggal Event Pembanding
                                        
                                        // Return dalam bentuk Array String untuk membuat baris baru (multi-line)
                                        // Baris 1: H-xx
                                        // Baris 2: Tanggal Event 1
                                        // Baris 3: Tanggal Event 2
                                        return [val, date1, date2]; 
                                    }
                                    return val;
                                },
                                
                            },
                            axisBorder: { show: true, color: '#eef2f7' },
                            axisTicks: { show: false }
                        },
                        yaxis: { labels: { style: { colors: '#6c757d', fontSize: '10px' } } },
                        grid: { 
                            borderColor: '#eef2f7', 
                            strokeDashArray: 3, 
                        }, 
                        theme: { mode: 'light' }, 
                        legend: { 
                            position: 'top', horizontalAlign: 'right', fontSize: '10px', 
                            markers: { width: 16, height: [12, 12, 3, 3], radius: [2, 2, 0, 0], offsetY: [0, 0, -1,-1 ], strokeWidth: 0 },
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
                    return [
                        { name: '{{ $nataruEvent->name }} (Arr)', type: 'bar', data: globalChartData.dataset1[type + '_arrival'] },
                        { name: '{{ $nataruEvent->name }} (Dep)', type: 'bar', data: globalChartData.dataset1[type + '_departure'] },
                        { name: '{{ $nataruEvent->compareEvent->name }} (Arr)', type: 'line', data: globalChartData.dataset2[type + '_arrival'] }, 
                        { name: '{{ $nataruEvent->compareEvent->name }} (Dep)', type: 'line', data: globalChartData.dataset2[type + '_departure'] }
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