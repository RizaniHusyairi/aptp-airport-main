<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIVE MONITORING - {{ $nataruEvent->name }}</title>
    
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/iconly.css') }}">
    <script src="{{ asset('assetsv2/extensions/apexcharts/apexcharts.min.js') }}"></script>

    <style>
        body {
            background-color: #151521; /* Dark Navy Background */
            color: #fff;
            overflow: hidden; /* Mencegah scroll halaman utama */
            font-family: 'Nunito', sans-serif;
            height: 100vh; /* Pastikan body setinggi layar */
            display: flex;
            flex-direction: column;
        }
        
        /* --- Header Minimalis --- */
        .tv-header {
            background: #0d2c4a; 
            padding: 0.6rem 1.5rem;
            border-bottom: 3px solid #f0a500; 
            margin-bottom: 0.8rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        .header-left { display: flex; align-items: center; gap: 15px; }
        .header-logo { height: 40px; width: auto; filter: brightness(0) invert(1); }
        .header-title h2 { 
            font-size: 1.3rem; 
            font-weight: 700; 
            margin: 0; 
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header-subtitle { 
            font-size: 0.85rem; 
            color: #f0a500; 
            font-weight: 600; 
            letter-spacing: 0.5px;
        }
        
        .header-right { text-align: right; }
        .live-badge {
            font-size: 0.7rem;
            background: rgba(255, 77, 77, 0.15);
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

        .digital-clock { font-size: 1.1rem; font-weight: 700; font-family: monospace; line-height: 1; }
        .date-display { font-size: 0.75rem; color: #8c8c9e; }

        /* --- Konten Utama --- */
        #main-content {
            flex-grow: 1; /* Mengisi sisa ruang */
            padding: 0 1.5rem 1rem 1.5rem;
            overflow: hidden; /* Pastikan tidak ada scroll di level konten */
        }

        /* --- Kartu Statistik Kompak --- */
        .stat-card {
            background: #1e1e2d;
            border-radius: 10px;
            border: 1px solid #2b2b40;
            padding: 1rem 1.2rem;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        /* Highlight active card */
        .stat-card.active-highlight {
            border-color: #f0a500;
            box-shadow: 0 0 15px rgba(240, 165, 0, 0.2);
            transform: scale(1.02);
        }

        .stat-card.updated::after {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.05); animation: flash 0.5s; pointer-events: none;
        }
        
        .stat-title { 
            font-size: 0.8rem; color: #8c8c9e; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 2px;
        }
        .stat-value { 
            font-size: 1.8rem; 
            font-weight: 800; color: #fff; line-height: 1.1;
        }
        .stat-icon-bg {
            position: absolute; right: 10px; bottom: 10px; font-size: 2rem; opacity: 0.15; transform: rotate(-10deg);
        }

        .diff-badge {
            font-size: 0.7rem; padding: 1px 5px; border-radius: 4px; font-weight: 600;
            display: inline-flex; align-items: center; gap: 3px; margin-top: 2px;
        }
        .diff-up { background: rgba(32, 201, 151, 0.15); color: #20c997; }
        .diff-down { background: rgba(255, 107, 107, 0.15); color: #ff6b6b; }
        .diff-neutral { background: rgba(170, 176, 182, 0.15); color: #aab0b6; }
        
        /* --- Chart Container --- */
        .chart-container {
            background: #1e1e2d;
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #2b2b40;
            height: 100%; 
            min-height: 0; 
            display: flex;
            flex-direction: column;
            position: relative;
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
        }

        /* --- Table Styling & Auto Scroll --- */
        .card-table {
            background: #1e1e2d; 
            border: 1px solid #2b2b40;
            border-radius: 10px;
            overflow: hidden;
            height: 100%; 
            display: flex;
            flex-direction: column;
            min-height: 0; 
        }
        .table-header {
            background: #1e1e2d; 
            padding: 8px 15px;
            border-bottom: 1px solid #2b2b40;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            z-index: 10;
        }
        .table-title { font-size: 0.9rem; font-weight: 700; margin: 0; }

        .table-scroll-container {
            flex-grow: 1;
            overflow-y: hidden; 
            position: relative; 
            scrollbar-width: none; 
            -ms-overflow-style: none; 
        }
        .table-scroll-container::-webkit-scrollbar { display: none; }

        .table-tv { width: 100%; border-collapse: separate; border-spacing: 0 2px; }
        
        .table-tv thead th {
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.7rem;
            padding: 8px 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            background-color: #1e1e2d; 
            z-index: 5;
        }
        
        .table-tv tbody tr {
            background: #252538;
            transition: background-color 0.3s;
        }
        .table-tv tbody tr.new-row { animation: highlightRow 2s ease-out; }
        @keyframes highlightRow {
            0% { background-color: rgba(240, 165, 0, 0.3); }
            100% { background-color: #252538; }
        }

        .table-tv td {
            padding: 6px 12px;
            vertical-align: middle;
            border: none;
            color: #e1e1e6;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        .table-tv td:first-child { border-radius: 4px 0 0 4px; border-left: 3px solid #f0a500; }
        .table-tv td:last-child { border-radius: 0 4px 4px 0; }

        /* Utilities */
        .fade-out { opacity: 0.5; pointer-events: none; transition: opacity 0.3s; }
        .text-xs { font-size: 0.7rem; }
    </style>
</head>
<body>

    {{-- Header TV Minimalis --}}
    <div class="tv-header">
        <div class="header-left">
            <img src="{{ asset('assets_landing/img/logo/logo-apt.svg') }}" alt="Logo" class="header-logo">
            <div class="header-title">
                <h2 class="text-white">POSKO MONITORING</h2>
                <div class="header-subtitle">{{ $nataruEvent->name }}</div>
            </div>
        </div>
        <div class="header-right">
            <div class="live-badge">
                <span class="live-dot" id="liveIndicator"></span> 
                <span id="liveText">LIVE</span>
            </div>
            <div class="digital-clock" id="digital-clock">00:00:00</div>
            <div class="date-display" id="current-date">Loading...</div>
        </div>
    </div>

    <div class="container-fluid h-100" id="main-content" style="padding: 0.2rem 1.5rem 1rem 1.5rem;">
        
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
                
                {{-- Bagian Atas: Grafik Bergantian --}}
                @if($nataruEvent->compare_event_id)
                <div class="chart-container flex-fill" style="height: 150%; min-height: 0;">
                    <div class="chart-progress-bar" id="chart-progress"></div>
                    <div class="d-flex justify-content-between align-items-center mb-1 flex-shrink-0">
                        <h6 class="mb-0 text-white text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.85rem;" id="chart-title">Tren Penumpang</h6>
                        <small class="text-muted text-xs">H-10 s/d H+10</small>
                    </div>
                    <div id="chartTv" style="flex-grow: 1; min-height: 0;"></div>
                </div>
                @endif
                
                {{-- Bagian Bawah: Tabel Auto Scroll --}}
                <div class="card-table flex-fill" style="min-height: 0;">
                    <div class="table-header">
                        <div class="table-title">Penerbangan Terakhir</div>
                        <small class="text-muted text-xs"><i class="bi bi-circle-fill text-success me-1" style="font-size: 6px;"></i>Real-time</small>
                    </div>
                    <div class="table-scroll-container" id="flights-scroll-container">
                        <table class="table-tv">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Maskapai</th>
                                    <th>No. Flight</th>
                                    <th>Rute</th>
                                    <th>Arah</th>
                                    <th>Pax</th>
                                    <th>Kargo</th>
                                </tr>
                            </thead>
                            <tbody id="flights-table-body">
                                @forelse($nataruEvent->flights as $flight)
                                <tr data-id="{{ $flight->id }}">
                                    <td class="fw-bold text-warning">{{ \Carbon\Carbon::parse($flight->flight_time)->format('H:i') }}</td>
                                    <td>{{ $flight->airline }}</td>
                                    <td><span class="badge bg-secondary bg-opacity-25 text-light font-monospace text-xs">{{ $flight->flight_number }}</span></td>
                                    <td>{{ $flight->route }}</td>
                                    <td>
                                        @if($flight->direction == 'arrival')
                                            <span class="text-success text-xs fw-bold"><i class="bi bi-arrow-down-left"></i> DATANG</span>
                                        @else
                                            <span class="text-info text-xs fw-bold"><i class="bi bi-arrow-up-right"></i> BERANGKAT</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $flight->pax_total }}</td>
                                    <td>{{ number_format($flight->cargo) }}</td>
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

        // 2. Auto Scroll Tabel
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
        
        // Variabel Global untuk Menyimpan Data Chart
        let chartDataGlobal = null;

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
                        liveText.style.color = '#fff';
                    }, 500);
                    
                    // Opsional: Panggil update data chart di sini jika ingin real-time juga untuk grafik
                    // fetchChartData();
                })
                .catch(err => {
                    console.error('Gagal update:', err);
                    liveIndicator.style.backgroundColor = '#555';
                });
        }

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

        // 4. Logika Grafik Bergantian (Carousel)
        document.addEventListener('DOMContentLoaded', function () {
            @if($nataruEvent->compare_event_id)
                const chartUrl = "{{ route('public.nataru.chart', $nataruEvent->public_token) }}";
                let chartInstance = null;
                let currentChartIndex = 0; // 0: Flights, 1: Pax, 2: Cargo
                const chartTypes = ['flights', 'pax', 'cargo'];
                const chartTitles = ['Tren Pesawat (Movement)', 'Tren Penumpang (Pax)', 'Tren Kargo (Kg)'];
                const chartColors = {
                    'flights': ['#0d6efd', '#6610f2', '#ffc107', '#ff4d4d'], // Biru tema
                    'pax': ['#20c997', '#0dcaf0', '#ffc107', '#ff4d4d'], // Hijau tema
                    'cargo': ['#ffc107', '#fd7e14', '#ffc107', '#ff4d4d'] // Kuning tema
                };

                // Fungsi untuk mengambil data
                function fetchChartData() {
                    return fetch(chartUrl)
                        .then(response => {
                            if (!response.ok) throw new Error("Gagal memuat grafik");
                            return response.json();
                        })
                        .then(data => {
                            chartDataGlobal = data; // Simpan ke global
                            initChart(); // Render pertama kali
                            startChartRotation(); // Mulai rotasi
                        })
                        .catch(err => {
                            document.querySelector("#chartTv").innerHTML = '<div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted opacity-50"><small>Grafik tidak tersedia</small></div>';
                        });
                }

                // Fungsi untuk update tampilan chart
                function updateChartDisplay() {
                    if (!chartDataGlobal || !chartInstance) return;

                    const type = chartTypes[currentChartIndex];
                    const title = chartTitles[currentChartIndex];
                    
                    // Update Judul & Highlight Card
                    document.getElementById('chart-title').innerText = title;
                    
                    // Reset highlight semua card
                    document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active-highlight'));
                    // Highlight card yang sesuai
                    document.getElementById(`card-${type}`).classList.add('active-highlight');

                    // Siapkan Data Series baru
                    // Struktur data dari controller harus konsisten: dataset1.flights_arrival, etc.
                    // Kita sesuaikan nama property di controller agar dinamis: 
                    // dataset1['pax_arrival'] -> dataset1[type + '_arrival']
                    // Perlu pastikan controller mengirim key yang sesuai ('flights', 'pax', 'cargo')
                    
                    // Mapping nama key dari controller (jika beda)
                    // Controller saat ini mengirim: pax_arrival, pax_departure.
                    // Kita asumsikan controller SUDAH diupdate untuk kirim flights_arrival dan cargo_arrival juga.
                    // Jika belum, kode ini akan error/kosong.
                    // SEMENTARA: Saya akan gunakan logika simulasi jika data belum lengkap, 
                    // TAPI Anda harus update Controller agar mengirim 'flights_arrival', 'cargo_arrival', dll.
                    
                    // Kita pakai trik: Controller Anda sebelumnya hanya kirim PAX.
                    // Agar kode ini jalan, Controller getTvChartData harus dimodifikasi 
                    // untuk mengirim flights_arrival/departure dan cargo_arrival/departure juga.
                    
                    // Namun, untuk mendemonstrasikan fitur ini bekerja dengan data yang ADA (Pax),
                    // Saya akan tampilkan Pax saja dulu jika data lain null.
                    // Nanti Anda wajib update Controller PublicNataruController method getTvChartData.
                    
                    let series = [];
                    
                    // Cek ketersediaan data (Safety check)
                    if(chartDataGlobal.dataset1[type + '_arrival']) {
                        series = [
                             { name: chartDataGlobal.event1_name + ' (Arr)', type: 'bar', data: chartDataGlobal.dataset1[type + '_arrival'] },
                             { name: chartDataGlobal.event1_name + ' (Dep)', type: 'bar', data: chartDataGlobal.dataset1[type + '_departure'] },
                             { name: chartDataGlobal.event2_name + ' (Arr)', type: 'line', data: chartDataGlobal.dataset2[type + '_arrival'] },
                             { name: chartDataGlobal.event2_name + ' (Dep)', type: 'line', data: chartDataGlobal.dataset2[type + '_departure'] }
                        ];
                    } else {
                        // Fallback jika data spesifik belum ada di API (misal flights/cargo belum dikirim terpisah)
                        // Tampilkan data dummy atau pax sebagai placeholder agar tidak crash
                        // (Sebaiknya update controller segera)
                         series = [
                             { name: 'Data ' + type + ' belum tersedia di API', type: 'bar', data: [] }
                        ];
                    }

                    chartInstance.updateOptions({
                        colors: chartColors[type],
                        series: series
                    });

                    // Reset dan jalankan progress bar
                    const progressBar = document.getElementById('chart-progress');
                    progressBar.style.transition = 'none';
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.transition = 'width 20s linear';
                        progressBar.style.width = '100%';
                    }, 50);
                }

                function initChart() {
                    const type = 'pax'; // Default start
                    // Inisialisasi chart kosong dulu
                    var options = {
                        series: [],
                        chart: { 
                            type: 'line', 
                            height: '100%', 
                            toolbar: {show: false}, 
                            background: 'transparent',
                            animations: { enabled: true, easing: 'easeinout', speed: 800 },
                            fontFamily: 'Nunito, sans-serif',
                            stacked: false
                        },
                        dataLabels: { enabled: false },
                        stroke: { width: [0, 0, 2, 2], curve: 'smooth', dashArray: [0, 0, 5, 5] },
                        plotOptions: { bar: { columnWidth: '60%', borderRadius: 2 } },
                        xaxis: { 
                            categories: chartDataGlobal.categories,
                            labels: { 
                                style: { colors: '#8c8c9e', fontSize: '10px' },
                                formatter: function (val, timestamp, index) {
                                    if (typeof index !== 'undefined' && chartDataGlobal.dates_event1 && chartDataGlobal.dates_event2) {
                                        const date1 = chartDataGlobal.dates_event1[index];
                                        const date2 = chartDataGlobal.dates_event2[index];
                                        return [val, date1, date2]; 
                                    }
                                    return val;
                                }
                            },
                            axisBorder: { show: false }, axisTicks: { show: false }
                        },
                        yaxis: { labels: { style: { colors: '#8c8c9e', fontSize: '10px' } } },
                        grid: { borderColor: '#2b2b40', strokeDashArray: 3 },
                        theme: { mode: 'dark' },
                        legend: { position: 'top', horizontalAlign: 'right', fontSize: '10px', markers: { radius: 12 }, itemMargin: { horizontal: 5, vertical: 0 } }
                    };
                    chartInstance = new ApexCharts(document.querySelector("#chartTv"), options);
                    chartInstance.render();
                    
                    // Set index ke Pax (1) karena itu data yang pasti ada sekarang
                    currentChartIndex = 1; 
                    updateChartDisplay();
                }

                function startChartRotation() {
                    setInterval(() => {
                        // Logika rotasi index: 0 -> 1 -> 2 -> 0
                        currentChartIndex = (currentChartIndex + 1) % 3;
                        updateChartDisplay();
                    }, 20000); // 20 Detik
                }

                fetchChartData();
            @endif
        });
    </script>
</body>
</html>