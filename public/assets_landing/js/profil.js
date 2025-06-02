
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi Swiper
            const swiper = new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
            });

            // Inisialisasi AOS
            AOS.init({
                duration: 1000,
                once: true,
            });
        });
    