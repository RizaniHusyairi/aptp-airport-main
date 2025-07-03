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
     * 2. FETCH & DISPLAY DATA PENERBANGAN (DIPERBAIKI)
     */
    const arrivalsList = document.getElementById('arrivals-list');
    const departuresList = document.getElementById('departures-list');

    // ### FUNGSI DIPERBARUI UNTUK MEMBACA API ANDA ###
    const createFlightCard = (flight, type) => {
        // Menentukan data berdasarkan tipe (Kedatangan/Keberangkatan)
        const isDeparture = type === 'Keberangkatan';
        const destinationData = isDeparture ? flight.bandara_tujuan : flight.bandara_asal;
        
        // Mengambil data dari struktur JSON yang benar
        const airline = flight.maskapai?.nama || 'N/A';
        const logoFileName = flight.maskapai?.logo || '';
        // Asumsi base URL untuk logo, ganti jika perlu.
        const logoUrl = logoFileName ? `http://103.210.122.2/storage/logo/${logoFileName}` : 'https://placehold.co/100x100/png?text=LOGO';
        const kota = destinationData?.nama || 'N/A';
        const nomor_penerbangan = flight.pesawat?.kode_penerbangan || 'N/A';
        const jadwal = flight.jam || '--:--';
        const statusText = flight.remark?.status || 'SCHEDULED';

        const gate = isDeparture ? (flight.gate?.nama || '-') : '-';

        
        // Menentukan kelas CSS berdasarkan status
        let statusClass = 'status-ontime';
        if (statusText.toLowerCase().includes('delay')) statusClass = 'status-delayed';
        if (statusText.toLowerCase().includes('boarding') || statusText.toLowerCase().includes('check in')) statusClass = 'status-boarding';
        if (statusText.toLowerCase().includes('landed') || statusText.toLowerCase().includes('departured') || statusText.toLowerCase().includes('arrived')) statusClass = 'status-landed';
        
        return `
            <div class="flight-card">
                <div class="flight-airline">
                    <img src="${logoUrl}" alt="${airline}" onerror="this.src='https://placehold.co/100x100/png?text=LOGO'; this.onerror=null;">
                    <span>${airline}</span>
                </div>
                <div class="flight-destination">${kota}</div>
                <div class="flight-number">${nomor_penerbangan}</div>
                <div class="flight-time">${jadwal}</div>
                <div class="flight-gate">${gate}</div>

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
                <div>Pintu</div>
                <div>Status</div>
            </div>
        `;
        
        element.innerHTML = headerHTML + data.map(flight => createFlightCard(flight, type)).join(''); 

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
    const statsSection = document.getElementById('traffic-stats');
    const statsContainer = document.getElementById('monthly-stats-container');
    
    if (statsContainer && statsSection) {
        gsap.registerPlugin(ScrollTrigger);
        const detailUrl = statsSection.dataset.detailUrl;

        fetch('/api/monthly-traffic-stats') 
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const stats = data.data;
                    
                    // Buat HTML untuk 6 item statistik
                    const statsHTML = `
                        <div class="row g-3 g-lg-4 justify-content-center">
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-airplane"></i></div><div class="stat-value" data-value="${stats.aircraft}">0</div><div class="stat-label">Pesawat</div></div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-people-fill"></i></div><div class="stat-value" data-value="${stats.passengers}">0</div><div class="stat-label">Penumpang</div></div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div><div class="stat-value" data-value="${stats.transit}">0</div><div class="stat-label">Transit</div></div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div><div class="stat-value" data-value="${stats.baggage}">0</div><div class="stat-label">Bagasi</div></div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-box-seam"></i></div><div class="stat-value" data-value="${stats.cargo}">0</div><div class="stat-label">Kargo</div></div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-envelope-paper-fill"></i></div><div class="stat-value" data-value="${stats.mail}">0</div><div class="stat-label">Pos</div></div>
                            </div>
                        </div>
                    `;
                    
                    // Buat HTML untuk kartu total aktivitas
                    const totalHTML = `
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="total-stats-card text-center">
                                    <div class="total-stats-label">Total Aktivitas Bulan Ini</div>
                                    <div class="total-stats-value" data-value="${stats.total}">0</div>
                                </div>
                            </div>
                        </div>
                    `;

                    // ### BARIS BARU: Buat HTML untuk Tombol ###
                    const buttonHTML = `
                        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="100">
                            <a href="${detailUrl}" class="btn-modern-outline-dark">Lihat Laporan Lengkap</a>
                        </div>
                    `;

                    // Gabungkan semua HTML dan masukkan ke kontainer
                    statsContainer.innerHTML = statsHTML + totalHTML + buttonHTML;

                    // Inisialisasi animasi count-up dengan GSAP
                    animateNumbers();

                } else {
                    statsContainer.innerHTML = `<p class="text-center text-danger">Gagal memuat data statistik.</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching traffic stats:', error);
                statsContainer.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan jaringan saat memuat statistik.</p>';
            });

        function animateNumbers() {
            const allValues = document.querySelectorAll('.stat-value, .total-stats-value');
            allValues.forEach(el => {
                const endValue = parseInt(el.dataset.value);
                let startValue = { val: 0 };
                
                gsap.to(startValue, {
                    val: endValue,
                    duration: 2,
                    ease: "power2.out",
                    snap: { val: 1 }, // Memastikan angka selalu bulat
                    onUpdate: function() {
                        el.textContent = Math.ceil(startValue.val).toLocaleString('id-ID');
                    },
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%",
                        toggleActions: "play none none none"
                    }
                });
            });
        }
    }


    /**
     * 4. LOGIKA PETA RUTE DOMESTIK DENGAN ANIMASI YANG DIPERBAIKI
     */
    const mapContainer = document.getElementById('route-map');
if (mapContainer) {
    const svg = document.getElementById("map-svg");
    const tooltip = document.getElementById("map-tooltip");
    const samarindaCoords = { cx: 640, cy: 200 }; 

    const createCityElement = (cityData, index, isMain = false) => {
        const group = document.createElementNS("http://www.w3.org/2000/svg", "g");
        group.setAttribute('class', 'map-city-group');
        if (!isMain) {
            group.dataset.routeIndex = index;
        }
        
        const hitArea = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        hitArea.setAttribute('cx', cityData.coords.cx);
        hitArea.setAttribute('cy', cityData.coords.cy);
        hitArea.setAttribute('r', '15');
        hitArea.setAttribute('fill', 'transparent');
        hitArea.setAttribute('pointer-events', 'all');
        hitArea.style.cursor = 'pointer';

        const visibleDot = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        visibleDot.setAttribute('cx', cityData.coords.cx);
        visibleDot.setAttribute('cy', cityData.coords.cy);
        visibleDot.setAttribute('r', isMain ? '8' : '5');
        visibleDot.setAttribute('class', isMain ? 'map-city city-main' : 'map-city');
        visibleDot.style.pointerEvents = 'none';
        
        group.appendChild(visibleDot);
        group.appendChild(hitArea);
        svg.appendChild(group);

        group.dataset.cityData = JSON.stringify(cityData);
        return group;
    };
    
    const createFlightPath = (destinationCoords, index) => {
        const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        const controlX = (samarindaCoords.cx + destinationCoords.cx) / 2;
        const controlY = (samarindaCoords.cy + destinationCoords.cy) / 2 - 50; 
        const pathData = `M ${samarindaCoords.cx} ${samarindaCoords.cy} Q ${controlX} ${controlY} ${destinationCoords.cx} ${destinationCoords.cy}`;
        path.setAttribute('d', pathData);
        path.setAttribute('class', 'flight-path');
        path.dataset.routeIndex = index; 
        svg.insertBefore(path, svg.firstChild); 
        return path;
    };

    fetch('/api/routes/domestic')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                createCityElement({ kota: 'Samarinda (AAP)', coords: samarindaCoords, maskapai: [] }, null, true);
                data.data.forEach((route, index) => {
                    createCityElement(route, index);
                    const path = createFlightPath(route.coords, index);
                    gsap.registerPlugin(ScrollTrigger);
                    const length = path.getTotalLength();
                    gsap.set(path, { strokeDasharray: length, strokeDashoffset: length });
                    gsap.to(path, {
                        strokeDashoffset: 0,
                        duration: 1.5,
                        ease: "power1.inOut",
                        scrollTrigger: { 
                            trigger: mapContainer,
                            start: "top center+=100",
                            toggleActions: "play none none none"
                        }
                    });
                });
                setTimeout(setupTooltipEvents, 100);
            }
        })
        .catch(error => console.error("Error fetching domestic routes:", error));

    function setupTooltipEvents() {
        const cityGroups = svg.querySelectorAll(".map-city-group");
        console.log("City Groups Found:", cityGroups.length);
        if (cityGroups.length === 0) {
            console.error("No .map-city-group elements found. Check SVG rendering.");
            return;
        }

        cityGroups.forEach(group => {
            const routeIndex = group.dataset.routeIndex;
            const targetPath = routeIndex ? svg.querySelector(`.flight-path[data-route-index='${routeIndex}']`) : null;
            const cityData = JSON.parse(group.dataset.cityData || '{}');

            group.addEventListener('mouseenter', () => {
                if (!tooltip) {
                    console.error("Tooltip element not found!");
                    return;
                }
                const airlines = cityData.maskapai || [];
                let airlinesHTML = airlines.map(a => `<img src="${a.logo}" title="${a.nama}" alt="${a.nama}">`).join('');
                
                tooltip.innerHTML = `
                    <div class="tooltip-title">${cityData.kota}</div>
                    ${airlines.length > 0 ? `<div class="tooltip-airlines">${airlinesHTML}</div>` : ''}
                `;
                
                const visibleDot = group.querySelector('.map-city');
                const dotRect = visibleDot.getBoundingClientRect();
                const svgRect = svg.getBoundingClientRect();
                const x = dotRect.left - svgRect.left + (dotRect.width / 2);
                const y = dotRect.top - svgRect.top - 20; // Offset sederhana

                tooltip.classList.add('show');

                tooltip.style.left = `${x-10}px`; // Posisi horizontal
                tooltip.style.top = `${y-45}px`; // Posisi vertikal
                tooltip.style.opacity = '1'; // Pastikan terlihat

                if (targetPath) {
                    targetPath.style.stroke = "var(--secondary-color)";
                    targetPath.style.strokeWidth = "2.5";
                }
            });

            group.addEventListener('mouseleave', () => {
                tooltip.classList.remove('show');
                tooltip.style.opacity = '0';
                if (targetPath) {
                    targetPath.style.stroke = "var(--primary-color)";
                    targetPath.style.strokeWidth = "1.5";
                }
            });
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

        /**
     * LOGIKA BARU UNTUK INFINITE PARTNERS CAROUSEL
     */
    const partnersTrack = document.querySelector('.partners-track');
    if (partnersTrack) {
        // Gandakan semua logo untuk menciptakan efek loop yang mulus
        const logos = partnersTrack.querySelectorAll('.partner-logo');
        logos.forEach(logo => {
            const clone = logo.cloneNode(true);
            partnersTrack.appendChild(clone);
        });
    }
    }

    /**
     * LOGIKA BARU UNTUK SEKSI JELAJAHI (TAB FASILITAS & WISATA)
     */
    const exploreSection = document.getElementById('explore-section');
    if (exploreSection) {
        const tourismContainer = document.getElementById('tourism-cards-container');
        const exploreButtonContainer = document.getElementById('explore-button-container');
        const exploreTabs = document.querySelectorAll('#explore-tab .nav-link');
        let tourismDataLoaded = false;

        // Fungsi untuk membuat kartu pariwisata
        const createTourismCard = (tourism, index) => {
            const imageUrl = `uploads/${tourism.cover_image}`;
            return `
                <div class="col-lg-4 col-md-6">
                    <a href="/pariwisata/${tourism.slug}" class="explore-card">
                        <div class="explore-card-image" style="background-image: url('${imageUrl}');"></div>
                        <div class="explore-card-content">
                            <h3>${tourism.name}</h3>
                            <p>${tourism.short_desc}</p>
                        </div>
                    </a>
                </div>
            `;
        };

        // Event listener untuk setiap tab
        exploreTabs.forEach(tab => {
            tab.addEventListener('show.bs.tab', event => {
                const buttonLink = exploreButtonContainer.querySelector('a');
                
                if (event.target.id === 'facilities-tab') {
                    // Jika tab fasilitas aktif
                    buttonLink.textContent = 'Lihat Semua Fasilitas';
                    buttonLink.href = '#'; // Ganti dengan rute fasilitas Anda
                } else if (event.target.id === 'tourism-tab') {
                    // Jika tab pariwisata aktif
                    buttonLink.textContent = 'Lihat Semua Destinasi';
                    buttonLink.href = "/pariwisata"; // URL dari Blade

                    // Ambil data pariwisata hanya jika belum pernah dimuat
                    if (!tourismDataLoaded) {
                        fetch('/api/pariwisata/unggulan') // Anda perlu membuat API endpoint ini
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.data.length > 0) {
                                    tourismContainer.innerHTML = data.data.map(createTourismCard).join('');
                                    tourismDataLoaded = true;
                                } else {
                                    tourismContainer.innerHTML = '<p class="text-center">Data pariwisata tidak tersedia.</p>';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching tourism data:', error);
                                tourismContainer.innerHTML = '<p class="text-center text-danger">Gagal memuat data.</p>';
                            });
                    }
                }
            });
        });
    }

});
