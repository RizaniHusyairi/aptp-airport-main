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

   

});