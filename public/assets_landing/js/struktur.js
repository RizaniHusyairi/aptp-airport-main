        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi AOS
            AOS.init({
                duration: 1000,
                once: true,
            });

            // Inisialisasi GLightbox dengan pengecekan
            try {
                const lightbox = GLightbox({
                    touchNavigation: true,
                    loop: true,
                    width: '90%',
                    height: 'auto',
                    onOpen: () => console.log('GLightbox opened'),
                    onClose: () => console.log('GLightbox closed')
                });
                console.log('GLightbox initialized successfully');
            } catch (error) {
                console.error('GLightbox initialization failed:', error);
            }
        });
