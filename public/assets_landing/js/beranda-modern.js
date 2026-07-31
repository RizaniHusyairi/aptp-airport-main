/**
 * File: public/assets_landing/js/beranda-modern.js
 * Deskripsi: Logika interaktif dan animasi untuk halaman beranda baru.
 */


document.addEventListener('DOMContentLoaded', function() {

    /**
     * EFEK MESIN KETIK (DIKEMBALIKAN)
     */
    if (document.getElementById('typed-destination')) {
        new Typed('#typed-destination', {
            strings: ['Kalimantan Timur', 'Destinasi Impian Anda', 'Peluang Bisnis Anda'],
            typeSpeed: 60,
            backSpeed: 40,
            backDelay: 2200,
            loop: true
        });
    }

    /**
     * INISIALISASI HERO SLIDER
     */
    const heroSlider = new Swiper('.hero-slider', {
        loop: true,
        effect: 'fade',
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
    });

    // // --- ANIMASI BARU: MASKING REVEAL ---
    // const titleReveal = document.querySelector('.hero-title-reveal');
    // if(titleReveal) {
    //     ScrollTrigger.create({
    //         trigger: titleReveal,
    //         start: "top 80%",
    //         once: true, // Hanya animasi sekali
    //         onEnter: () => {
    //             titleReveal.classList.add('is-visible');
    //         }
    //     });
    // }
    // Daftarkan plugin di sini agar GSAP tahu cara menganimasikan clip-path
    // gsap.registerPlugin(ClipPathPlugin);
    // GANTI BLOK ANIMASI GSAP YANG LAMA DENGAN INI
    window.startHeroAnimation = function() {
        if (document.querySelector('.hero-title-reveal-v2')) {
            const tl = gsap.timeline({
                defaults: { ease: "power3.out" },
                delay: 0.2
            });

            // 1. Baris 1 ("Bandara") muncul
            tl.to('.line-1 span', { y: 0, duration: 1 });
            tl.to('.line-2 span', { y: 0, duration: 1 });

            // 2. Huruf pertama (A, P, T) muncul satu per satu
            tl.to('.first-letter', { 
                opacity: 1, 
                stagger: 0.15, 
                duration: 0.5 
            }, "-=0.8");

            // 3. Sisa teks ("ji", "angeran", "emenggung") meluncur keluar dari huruf pertama
            tl.to('.rest-wrapper', {
                width: 'auto', // GSAP akan otomatis menghitung lebar yang dibutuhkan
                stagger: 0.15,
                duration: 1
            }, "-=0.4");

            // 4. Baris 3 ("Pranoto") muncul sebagai penutup
            tl.to('.line-3 span', { 
                y: 0, 
                duration: 1 
            }, "-=1.0");
        }
    };

    // --- AKHIR BLOK ANIMASI BARU ---

    /**
     * LOGIKA QUICK NAV (DIKEMBALIKAN)
     */
    const heroNavLinks = document.querySelectorAll('.hero-nav-link');
    heroNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetSectionId = this.getAttribute('href');
            if (targetSectionId.startsWith('#')) {
                e.preventDefault();
                const targetSection = document.querySelector(targetSectionId);
                if (targetSection) {
                    targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    const tabTargetId = this.dataset.tabTarget;
                    if (tabTargetId) {
                        const tabButton = document.querySelector(tabTargetId);
                        if (tabButton) {
                            setTimeout(() => { new bootstrap.Tab(tabButton).show(); }, 500);
                        }
                    }
                }
            }
        });
    });
    /**
     * INISIALISASI INFO SLIDER (BARU)
     */
    // autoplay: { delay: 5000, disableOnInteraction: false },
    const infoSlider = new Swiper('.info-slider', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 30,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
      
    });
    // const heroNavLinks = document.querySelectorAll('.hero-nav-link');
    // heroNavLinks.forEach(link => {
    //     link.addEventListener('click', function(e) {
    //         e.preventDefault();
            
    //         const targetSectionId = this.getAttribute('href');
    //         const targetSection = document.querySelector(targetSectionId);

    //         if (targetSection) {
    //             // Scroll ke seksi yang dituju
    //             targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

    //             // Cek apakah link ini punya target tab
    //             const tabTargetId = this.dataset.tabTarget;
    //             if (tabTargetId) {
    //                 const tabButton = document.querySelector(tabTargetId);
    //                 if (tabButton) {
    //                     // Tunggu sejenak agar scroll selesai sebelum mengaktifkan tab
    //                     setTimeout(() => {
    //                         const tab = new bootstrap.Tab(tabButton);
    //                         tab.show();
    //                     }, 500); // Jeda 500ms
    //                 }
    //             }
    //         }
    //     });
    // });


    /**
     * 2. FETCH & DISPLAY DATA PENERBANGAN (DIPERBAIKI)
     */
    const arrivalsList = document.getElementById('arrivals-list');
    const departuresList = document.getElementById('departures-list');

    // Jumlah penerbangan yang ditampilkan di beranda; selebihnya diarahkan
    // ke halaman papan jadwal lengkap.
    const FLIGHT_LIMIT = 8;

    const escapeHtml = (value) => String(value === null || value === undefined ? '' : value)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

    // Warna merek maskapai dari API, hanya diterima bila berupa hex yang sah
    const brandColor = (value) => /^#[0-9a-f]{3,8}$/i.test(String(value || '')) ? value : '#0d2c4a';

    /*
     * Logo maskapai berada di host API yang hanya melayani HTTP sehingga
     * diblokir sebagai mixed content pada situs HTTPS. Berkasnya diambil ulang
     * lewat proxy di sisi server. Nilai dari API berupa path lengkap
     * ("/storage/airlines/xxx.png"), jadi cukup nama berkasnya yang dikirim.
     */
    const logoUrlFor = (logoPath) => {
        if (!logoPath) return '';
        const file = String(logoPath).split('/').pop();
        return file ? '/api/image-proxy/' + encodeURIComponent(file) : '';
    };

    const statusStyle = (raw) => {
        const s = String(raw || '').toLowerCase();
        if (s.includes('cancel') || s.includes('batal')) return { cls: 'fx-pill--danger', icon: 'bi-x-octagon-fill' };
        if (s.includes('delay') || s.includes('tunda')) return { cls: 'fx-pill--warn', icon: 'bi-exclamation-triangle-fill' };
        if (s.includes('check-in') || s.includes('check in')) return { cls: 'fx-pill--info', icon: 'bi-person-badge-fill' };
        if (s.includes('board') || s.includes('gate')) return { cls: 'fx-pill--info', icon: 'bi-door-open-fill' };
        if (s.includes('depart') || s.includes('land') || s.includes('arriv')) return { cls: 'fx-pill--ok', icon: 'bi-check-circle-fill' };
        if (s.includes('schedule') || s.includes('jadwal')) return { cls: 'fx-pill--ok', icon: 'bi-clock-fill' };
        return { cls: 'fx-pill--neutral', icon: 'bi-info-circle-fill' };
    };

    const createFlightCard = (flight, type, index) => {
        const isDeparture = type === 'Keberangkatan';
        const place = isDeparture ? flight.bandara_tujuan : flight.bandara_asal;

        const airline = flight.maskapai?.nama || '—';
        const airlineCode = flight.maskapai?.kode || '';
        const color = brandColor(flight.maskapai?.kode_warna);
        const logo = logoUrlFor(flight.maskapai?.logo);

        const city = place?.kota_provinsi || '';
        const airport = place?.nama || '—';
        const iata = place?.iata || '';
        const registration = flight.pesawat?.kode_penerbangan || '—';
        const time = flight.jam || '--:--';
        const statusText = flight.remark?.status || 'Terjadwal';
        const st = statusStyle(statusText);

        /*
         * Posisi layanan penumpang.
         * Kedatangan  : `conveyor` skalar ("1"/"2"), tersedia di semua data.
         * Keberangkatan: `gate` objek {id, nama} hanya pada sebagian penerbangan,
         *                dan konter/konter2/konter3 berupa angka (0 = kosong).
         *                Keduanya ditampilkan karena konter (lapor diri) dan
         *                gate (ruang tunggu) adalah tahap yang berbeda.
         */
        let deskLabel;
        let deskHtml;

        if (isDeparture) {
            deskLabel = 'Gate / Konter';
            const counters = [flight.konter, flight.konter2, flight.konter3]
                .filter((v) => Number(v) > 0)
                .join(', ');
            const chips = [];
            if (flight.gate?.nama) {
                chips.push(`<span class="fx-desk"><i class="bi bi-door-open-fill"></i>${escapeHtml(flight.gate.nama)}</span>`);
            }
            if (counters) {
                chips.push(`<span class="fx-desk fx-desk--counter"><i class="bi bi-person-badge-fill"></i>${escapeHtml(counters)}</span>`);
            }
            deskHtml = chips.length ? chips.join(' ') : '<span class="fx-desk fx-desk--empty">—</span>';
        } else {
            deskLabel = 'Conveyor';
            deskHtml = flight.conveyor
                ? `<span class="fx-desk"><i class="bi bi-suitcase-lg-fill"></i>${escapeHtml(flight.conveyor)}</span>`
                : '<span class="fx-desk fx-desk--empty">—</span>';
        }

        const logoBlock = logo
            ? `<span class="fx-logo" data-code="${escapeHtml(airlineCode || airline.slice(0, 2))}">
                   <img src="${logo}" alt="Logo ${escapeHtml(airline)}" loading="lazy"
                        onerror="this.parentNode.classList.add('fx-logo--fallback'); this.remove();">
               </span>`
            : `<span class="fx-logo fx-logo--fallback" data-code="${escapeHtml(airlineCode || airline.slice(0, 2))}"></span>`;

        return `
            <article class="fx-card" style="--brand: ${escapeHtml(color)}; --i: ${index}">
                <div class="fx-cell fx-cell--airline">
                    ${logoBlock}
                    <span class="fx-airline">
                        <span class="fx-airline-name">${escapeHtml(airline)}</span>
                        <span class="fx-airline-sub">${escapeHtml(airlineCode)}${airlineCode ? ' · ' : ''}${escapeHtml(registration)}</span>
                    </span>
                </div>
                <div class="fx-cell fx-cell--route">
                    <span class="fx-label">${isDeparture ? 'Tujuan' : 'Asal'}</span>
                    <span class="fx-city">${iata ? `<span class="fx-iata">${escapeHtml(iata)}</span>` : ''}${escapeHtml(city || airport)}</span>
                    ${city ? `<span class="fx-airport">${escapeHtml(airport)}</span>` : ''}
                </div>
                <div class="fx-cell fx-cell--desk">
                    <span class="fx-label">${deskLabel}</span>
                    <span class="fx-desk-group">${deskHtml}</span>
                </div>
                <div class="fx-cell fx-cell--time">
                    <span class="fx-label">Waktu</span>
                    <span class="fx-clock">${escapeHtml(time)}</span>
                </div>
                <div class="fx-cell fx-cell--status">
                    <span class="fx-pill ${st.cls}"><i class="bi ${st.icon}"></i>${escapeHtml(statusText)}</span>
                </div>
            </article>
        `;
    };

    const flightSkeleton = (rows) => {
        let html = '';
        for (let i = 0; i < rows; i++) html += '<div class="fx-skeleton"></div>';
        return html;
    };

    const populateFlightList = (element, data, type) => {
        if (!data || data.length === 0) {
            element.innerHTML = `
                <div class="fx-state">
                    <i class="bi bi-calendar-x"></i>
                    <p class="fx-state-title">Belum ada jadwal</p>
                    <p class="mb-0">Tidak ada jadwal ${type.toLowerCase()} yang tersedia saat ini.</p>
                </div>`;
            return;
        }

        // Urutkan menurut jam agar papan terbaca runtut
        const sorted = data.slice().sort((a, b) => String(a.jam || '').localeCompare(String(b.jam || '')));
        const shown = sorted.slice(0, FLIGHT_LIMIT);
        const isDeparture = type === 'Keberangkatan';

        const header = `
            <div class="fx-head d-none d-lg-grid">
                <span>Maskapai</span>
                <span>${isDeparture ? 'Tujuan' : 'Asal'}</span>
                <span>${isDeparture ? 'Gate / Konter' : 'Conveyor'}</span>
                <span>Waktu</span>
                <span>Status</span>
            </div>`;

        const more = sorted.length > shown.length
            ? `<div class="fx-more">
                   <a href="/jadwal-penerbangan?tab=${isDeparture ? 'keberangkatan' : 'kedatangan'}" class="btn-modern-outline">
                       Lihat Semua ${sorted.length} Penerbangan
                   </a>
               </div>`
            : '';

        element.innerHTML = header
            + shown.map((flight, i) => createFlightCard(flight, type, i)).join('')
            + more;
    };

    const loadFlights = (element, endpoint, type) => {
        if (!element) return;

        element.innerHTML = flightSkeleton(5);

        fetch(endpoint, { headers: { Accept: 'application/json' } })
            .then((response) => response.json())
            .then((data) => populateFlightList(element, data.success ? data.data : [], type))
            .catch((error) => {
                console.error('Gagal memuat jadwal ' + type + ':', error);
                element.innerHTML = `
                    <div class="fx-state">
                        <i class="bi bi-wifi-off"></i>
                        <p class="fx-state-title">Gagal memuat data</p>
                        <p class="mb-0">Periksa koneksi Anda lalu muat ulang halaman.</p>
                    </div>`;
            });
    };

    /*
     * `recent=1` meminta server menyaring penerbangan yang sudah berstatus
     * selesai lebih dari 2 jam lalu, sehingga papan ringkas di beranda hanya
     * memuat jadwal yang masih relevan. Halaman jadwal lengkap tidak memakai
     * parameter ini agar seluruh jadwal hari ini tetap terlihat.
     */
    loadFlights(arrivalsList, '/api/arrivals?recent=1', 'Kedatangan');
    loadFlights(departuresList, '/api/departures?recent=1', 'Keberangkatan');

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
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div><div class="stat-value" data-value="${stats.baggage}">0</div><div class="stat-label">Bagasi</div></div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="stat-item-compact"><div class="stat-icon"><i class="bi bi-box-seam"></i></div><div class="stat-value" data-value="${stats.cargo}">0</div><div class="stat-label">Kargo</div></div>
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


    /*
     * 4. PETA JARINGAN RUTE
     *
     * Sudah dipindahkan ke assets_landing/js/route-map.js yang memakai
     * Leaflet. Peta gambar statis beserta penggambaran SVG manual tidak
     * dipakai lagi.
     */

    

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
                    buttonLink.href = '/fasilitas';
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

    /**
     * LOGIKA BARU UNTUK FLOATING ACTION BUTTON (FAB) KONTAK
     */
    const fabContainer = document.getElementById('contact-fab-container');
    if (fabContainer) {
        const toggleButton = document.getElementById('contact-fab-toggle');
        const closeButton = document.getElementById('close-contact-form');
        const contactForm = document.getElementById('contact-fab-form');
        const responseDiv = document.getElementById('fab-form-response');

        // Fungsi untuk membuka/menutup form
        const toggleForm = () => {
            fabContainer.classList.toggle('fab-open');
        };

        toggleButton.addEventListener('click', toggleForm);
        closeButton.addEventListener('click', toggleForm);

        // Logika submit form dengan AJAX
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...`;
            responseDiv.innerHTML = '';

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    // Handle validation errors (422)
                    if (response.status === 422 && result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('<br>');
                        throw new Error(errorMessages);
                    }
                    throw new Error(result.message || 'Terjadi kesalahan server.');
                }
                
                // Handle success
                responseDiv.innerHTML = `<div class="text-success">${result.message}</div>`;
                this.reset();
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
                // Tutup form setelah 3 detik
                setTimeout(toggleForm, 3000);

            } catch (error) {
                responseDiv.innerHTML = `<div class="text-danger">${error.message}</div>`;
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }

});
