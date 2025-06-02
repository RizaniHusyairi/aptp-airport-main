
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
    });

    // Sinkronisasi tab dengan slide
    const tabs = document.querySelectorAll('#officialTabs .nav-link');
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            swiper.slideTo(index);
        });
    });
    
    swiper.on('slideChange', () => {
        const activeIndex = swiper.activeIndex % (swiper.slides.length) || 0;
        tabs.forEach((tab, idx) => tab.classList.toggle('active', idx === activeIndex));
    });

    // Popup Custom
    const modal = document.getElementById('profile-modal');
    const closeBtn = document.querySelector('.close-btn');
    const readMoreButtons = document.querySelectorAll('.read-more');

    readMoreButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const profileData = document.querySelector(targetId);
            const profileImg = button.closest('.official-card').querySelector('.card-img-pejabat').src;
            const profileName = button.closest('.card-body').querySelector('.card-title').textContent;
            const profilePosition = button.closest('.card-body').querySelector('.card-text').textContent;

            modal.querySelector('.profile-img').src = profileImg;
            modal.querySelector('.profile-name').textContent = profileName;
            modal.querySelector('.profile-position').textContent = profilePosition;
            modal.querySelector('.profile-details').innerHTML = profileData.innerHTML;

            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10); // Trigger animasi
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300); // Sinkron dengan animasi
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }
    });
});
