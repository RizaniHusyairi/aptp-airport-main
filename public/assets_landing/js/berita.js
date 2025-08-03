document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Swiper utama (yang besar)
    const swiperEl = document.querySelector(".newsFirstSwiper");
    if (swiperEl) {
        Object.assign(swiperEl, {
            grabCursor: true,
            effect: "creative",
            creativeEffect: {
                prev: { shadow: true, translate: [0, 0, -400] },
                next: { translate: ["100%", 0, 0] },
            },
            pagination: { clickable: true },
        });
        swiperEl.initialize();
    }

    // --- LOGIKA BARU UNTUK SWIPER KEDUA ---

    const newsSwiperEl = document.querySelector('.news-swiper');
    let newsSwiperInstance; // Variabel untuk menyimpan instance swiper

    // Fungsi untuk debounce, agar tidak menjalankan fungsi berkali-kali saat resize
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    // Fungsi untuk menginisialisasi atau memperbarui swiper kedua
    function initOrUpdateNewsSwiper() {
        if (!newsSwiperEl) return;

        const screenWidth = window.innerWidth;
        let config;

        // Tentukan konfigurasi berdasarkan lebar layar
        if (screenWidth < 992) {
            // Konfigurasi untuk Mobile/Tablet (Horizontal)
            config = {
                direction: 'horizontal',
                slidesPerView: 'auto', // Biarkan swiper menentukan jumlah slide yang terlihat
                spaceBetween: 15,
                freeMode: true,
                scrollbar: {
                    el: '.swiper-scrollbar',
                    hide: false,
                },
                mousewheel: true,
            };
        } else {
            // Konfigurasi untuk Desktop (Vertikal)
            config = {
                direction: 'vertical',
                slidesPerView: 3,
                spaceBetween: 0,
                freeMode: true,
                navigation: false, // Navigasi tidak diperlukan untuk vertikal
                scrollbar: {
                    el: '.swiper-scrollbar',
                    hide: false,
                },
                mousewheel: true,
            };
        }

        // Hancurkan instance swiper yang ada jika ada
        if (newsSwiperInstance) {
            newsSwiperInstance.destroy(true, true);
        }

        // Buat instance baru dengan konfigurasi yang tepat
        newsSwiperInstance = new Swiper(newsSwiperEl, config);
    }

    // Panggil fungsi saat halaman pertama kali dimuat
    initOrUpdateNewsSwiper();

    // Panggil fungsi lagi setiap kali ukuran jendela diubah (dengan debounce)
    window.addEventListener('resize', debounce(initOrUpdateNewsSwiper, 250));

});