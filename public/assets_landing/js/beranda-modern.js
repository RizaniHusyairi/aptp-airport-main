/**
 * File: public/assets_landing/js/beranda-modern.js
 * Deskripsi: Logika interaktif dan animasi untuk halaman beranda baru.
 */
document.addEventListener('DOMContentLoaded', function() {

    /**
     * 1. EFEK MESIN KETIK PADA HERO SECTION
     */
    if (document.getElementById('typed-destination')) {
        // String yang lebih relevan dan menarik
        new Typed('#typed-destination', {
            strings: ['Kalimantan Timur', 'Destinasi Impian Anda', 'Peluang Bisnis Anda'],
            typeSpeed: 60,
            backSpeed: 40,
            backDelay: 2200,
            loop: true
        });
    }

    /**
     * 2. FETCH & DISPLAY DATA PENERBANGAN
     */
    const arrivalsList = document.getElementById('arrivals-list');
    const departuresList = document.getElementById('departures-list');

    const createFlightCard = (flight) => {
        let statusClass = 'status-ontime';
        let statusText = flight.status || 'ON TIME';
        if (statusText.includes('DELAY')) statusClass = 'status-delayed';
        if (statusText.includes('BOARDING')) statusClass = 'status-boarding';
        if (statusText.includes('LANDED') || statusText.includes('TAKE OFF')) statusClass = 'status-landed';
        
        return `
            <div class="flight-card" data-aos="fade-up" data-aos-delay="50">
                <div class="flight-airline">
                    <img src="${flight.logo || 'https://placehold.co/100x100/png?text=LOGO'}" alt="${flight.airline || ''}" onerror="this.src='https://placehold.co/100x100/png?text=LOGO'">
                    <span>${flight.airline || 'N/A'}</span>
                </div>
                <div class="flight-destination">${flight.kota || 'N/A'}</div>
                <div class="flight-number">${flight.nomor_penerbangan || 'N/A'}</div>
                <div class="flight-time">
                    ${flight.jadwal || '--:--'}
                    ${flight.aktual ? `<br><small class="text-warning">Aktual: ${flight.aktual}</small>` : ''}
                </div>
                <div class="flight-status"><span class="${statusClass}">${statusText}</span></div>
            </div>
        `;
    };
    
    const populateFlightList = (element, data, type) => {
        if (!data || data.length === 0) {
            element.innerHTML = `<p class="text-center text-muted">Tidak ada jadwal ${type} untuk ditampilkan saat ini.</p>`;
            return;
        }
        
        const headerHTML = `
            <div class="flight-card-header d-none d-md-grid">
                <div>Maskapai</div>
                <div>${type === 'Kedatangan' ? 'Dari' : 'Ke'}</div>
                <div>Nomor</div>
                <div>Waktu</div>
                <div>Status</div>
            </div>
        `;
        
        element.innerHTML = headerHTML + data.slice(0, 5).map(createFlightCard).join(''); 
        if(typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    };

    if(arrivalsList){
        fetch('/api/arrivals')
            .then(response => response.json())
            .then(data => populateFlightList(arrivalsList, data.success ? data.data : [], 'Kedatangan'))
            .catch(error => {
                console.error('Error fetching arrivals:', error);
                arrivalsList.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan jaringan.</p>';
            });
    }

    if(departuresList){
        fetch('/api/departures')
            .then(response => response.json())
            .then(data => populateFlightList(departuresList, data.success ? data.data : [], 'Keberangkatan'))
            .catch(error => {
                console.error('Error fetching departures:', error);
                departuresList.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan jaringan.</p>';
            });
    }

    /**
     * 3. FETCH & DISPLAY DATA STATISTIK LALU LINTAS UDARA
     */
    const statsContainer = document.getElementById('monthly-stats-container');
    
    const createStatCard = (icon, value, label, delay) => {
        return `
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="${delay}">
                <div class="stat-item">
                    <div class="stat-icon"><i class="bi ${icon}"></i></div>
                    <div class="stat-value">${value.toLocaleString('id-ID')}</div>
                    <div class="stat-label">${label}</div>
                </div>
            </div>
        `;
    };
    
    if (statsContainer) {
        fetch('/api/monthly-traffic-stats') 
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stats = data.data;
                    let statsHTML = '';
                    statsHTML += createStatCard('bi-airplane', stats.aircraft, 'Pergerakan Pesawat', '100');
                    statsHTML += createStatCard('bi-people-fill', stats.passengers, 'Penumpang', '200');
                    statsHTML += createStatCard('bi-box-seam', stats.cargo, 'Kargo', '300');
                    statsContainer.innerHTML = statsHTML;
                } else {
                    statsContainer.innerHTML = `<p class="text-center text-danger">Gagal memuat data statistik.</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching traffic stats:', error);
                statsContainer.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan jaringan saat memuat statistik.</p>';
            })
            .finally(() => {
                 if(typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            });
    }

    /**
     * 4. LOGIKA PETA RUTE DOMESTIK DENGAN HIGHCHARTS - DIPERBAIKI TOTAL
     */
    const mapContainer = document.getElementById('highcharts-map-container');
    if (mapContainer) {
        
        fetch('/api/routes/domestic')
            .then(response => response.json())
            .then(data => {
                if(data.success && data.data.length > 0) {
                    const samarinda = { name: 'Samarinda (AAP)', lat: -0.37, lon: 117.25, airlines: [] };
                    const destinationPoints = data.data.map(route => ({
                        name: route.kota,
                        lat: route.lat,
                        lon: route.lon,
                        airlines: route.maskapai
                    }));
                    const flightPaths = data.data.map(route => ([
                        { lat: samarinda.lat, lon: samarinda.lon },
                        { lat: route.lat, lon: route.lon }
                    ]));
                    console.log('Samarinda:', samarinda); // Log Samarinda
            console.log('Destinations:', destinationPoints); // Log destinasi
            console.log('Flight Paths:', flightPaths); // Log jalur
                    initializeHighchartsMap(samarinda, destinationPoints, flightPaths);
                } else {
                     mapContainer.innerHTML = '<p class="text-center text-danger">Gagal memuat data rute.</p>';
                }
            })
            .catch(error => {
                console.error("Error fetching domestic routes for map:", error);
                mapContainer.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan saat memuat peta.</p>';
            });

        function initializeHighchartsMap(origin, destinations, paths) {
    Highcharts.mapChart('highcharts-map-container', {
        chart: {
            map: Highcharts.maps['countries/id/id-all'],
            backgroundColor: '#f4f7f9',
            height: '500px', // Pastikan tinggi didefinisikan
            projection: {
                name: 'mercator' // Gunakan proyeksi Mercator
            }
        },
        title: { text: null },
        credits: { enabled: false },
        mapNavigation: {
            enabled: true,
            buttonOptions: { verticalAlign: 'bottom' }
        },
        tooltip: {
            backgroundColor: 'white',
            borderWidth: 1,
            useHTML: true,
            pointFormatter: function () {
                if (!this.name) return false;
                let airlinesHTML = this.airlines && this.airlines.length > 0
                    ? this.airlines.map(a => `<img src="${a.logo}" title="${a.nama}" alt="${a.nama}" style="height: 20px; margin: 2px;">`).join('')
                    : '';
                return `
                    <div style="padding: 10px; background: white; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                        <div style="font-weight: bold; margin-bottom: 5px;">${this.name}</div>
                        ${airlinesHTML}
                    </div>
                `;
            }
        },
        series: [{
            // Peta Dasar
            name: 'Indonesia',
            borderColor: '#a0a0a0',
            nullColor: 'rgba(200, 200, 200, 0.5)',
            showInLegend: false,
            zIndex: 1
        }, {
            // Garis Rute
            type: 'mapline',
            name: 'Rute Penerbangan',
            data: paths.map(p => ({
                path: [[p[0].lon, p[0].lat], [p[1].lon, p[1].lat]]
            })),
            lineWidth: 2,
            color: '#0d2c4a',
            dashStyle: 'Dot',
            zIndex: 2
        }, {
            // Titik Destinasi
            type: 'mappoint',
            name: 'Destinasi',
            data: destinations,
            color: '#0d2c4a',
            marker: {
                radius: 5,
                symbol: 'circle',
                fillColor: '#ff0000' // Warna debugging
            },
            zIndex: 99
        }, {
            // Titik Samarinda
            type: 'mappoint',
            name: 'Bandara APT Pranoto',
            data: [origin],
            color: '#f0a500',
            marker: {
                radius: 10,
                symbol: 'circle',
                lineWidth: 2,
                lineColor: 'white'
            },
            zIndex: 100
        }]
    });
}
    }

    /**
     * 5. LOGIKA UNTUK AI TRIP PLANNER (GEMINI API)
     */
    const plannerForm = document.getElementById('trip-planner-form');
    if(plannerForm) {
        const plannerModal = new bootstrap.Modal(document.getElementById('trip-planner-modal'));
        const plannerLoading = document.getElementById('planner-loading');
        const plannerResult = document.getElementById('planner-result');
        const plannerError = document.getElementById('planner-error');
        const submitButton = plannerForm.querySelector('button[type="submit"]');
        const btnText = submitButton.querySelector('.btn-text');
        const btnSpinner = submitButton.querySelector('.spinner-border');
        
        const converter = new showdown.Converter({
            simplifiedAutoLink: true,
            strikethrough: true,
            tables: true
        });

        plannerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const tujuan = document.getElementById('tujuan').value.trim();
            const durasi = document.getElementById('durasi').value.trim();

            if (!tujuan || !durasi) {
                alert('Harap isi tujuan dan durasi perjalanan.');
                return;
            }

            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
            submitButton.disabled = true;
            
            plannerLoading.classList.remove('d-none');
            plannerResult.classList.add('d-none');
            plannerError.classList.add('d-none');
            plannerModal.show();
            
            try {
                const response = await fetch('/api/ai/generate-trip-plan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({ tujuan, durasi })
                });
                
                if (!response.ok) throw new Error(`Server error with status ${response.status}`);
                
                const result = await response.json();

                if (result.success) {
                    const html = converter.makeHtml(result.plan);
                    plannerResult.innerHTML = html;
                    plannerResult.classList.remove('d-none');
                } else {
                    throw new Error(result.error || 'Invalid response from server.');
                }

            } catch (error) {
                console.error("Error during trip planning:", error);
                plannerError.classList.remove('d-none');
            } finally {
                plannerLoading.classList.add('d-none');
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
                submitButton.disabled = false;
            }
        });
    }

});
