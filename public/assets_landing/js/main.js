/**
* Template Name: Yummy
* Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    // if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
    const navbar = document.getElementById('header');
    const isHomePage = navbar.classList.contains('home');
    if (isHomePage) {
      if (window.scrollY > 300) {
        selectHeader.classList.add('scrolled');
      } else {
        selectHeader.classList.remove('scrolled');
      }
    }

  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  if (preloader) {
  // Fungsi untuk menyembunyikan preloader
  const hidePreloader = () => {
    console.log('Hiding preloader');
    // preloader.classList.add('hidden');
    setTimeout(() => {
      preloader.remove();
      // TAMBAHKAN KODE INI: Panggil animasi hero jika ada
      if (typeof window.startHeroAnimation === 'function') {
          window.startHeroAnimation();
      }


      
      console.log('Preloader removed');
    }, 300); // Tunggu transisi opacity selesai
  };
  // Sembunyikan preloader saat halaman selesai dimuat
  window.addEventListener('load', hidePreloader);
}

  /**
   * Scroll top button
   */
  // let scrollTop = document.querySelector('.scroll-top');

  // function toggleScrollTop() {
  //   if (scrollTop) {
  //     window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
  //   }
  // }
  // scrollTop.addEventListener('click', (e) => {
  //   e.preventDefault();
  //   window.scrollTo({
  //     top: 0,
  //     behavior: 'smooth'
  //   });
  // });

  // window.addEventListener('load', toggleScrollTop);
  // document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener('load', function(e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

    /* ====================================================== */
  /* ==   KODE BARU: LOGIKA UNTUK WIDGET AKSESIBILITAS   == */
  /* ====================================================== */
  const accessibilityWidget = document.querySelector('.accessibility-widget');
  if (accessibilityWidget) {
    const toggleButton = document.getElementById('accessibility-toggle');
    const panel = document.getElementById('accessibility-panel');
    const options = panel.querySelectorAll('.panel-option, .size-buttons button');
    const body = document.body;

    // --- Inisialisasi dari Local Storage ---
    const settings = {
        grayscale: localStorage.getItem('accessibility-grayscale') === 'true',
        highContrast: localStorage.getItem('accessibility-high-contrast') === 'true',
        textSize: localStorage.getItem('accessibility-text-size') || 'normal',
        tts: false // Mode suara tidak disimpan, selalu mati saat reload
    };

    // Terapkan pengaturan saat halaman dimuat
    function applyInitialSettings() {
        if (settings.grayscale) body.classList.add('accessibility-grayscale');
        if (settings.highContrast) body.classList.add('accessibility-high-contrast');
        if (settings.textSize !== 'normal') body.classList.add(`text-${settings.textSize}`);
        updateActiveButtons();
    }
    applyInitialSettings();

    // --- Fungsi Utama ---
    toggleButton.addEventListener('click', () => {
        accessibilityWidget.classList.toggle('open');
    });
    
    // Klik di luar panel akan menutup panel
    document.addEventListener('click', (e) => {
        if (!accessibilityWidget.contains(e.target)) {
            accessibilityWidget.classList.remove('open');
        }
    });

    options.forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();
            const action = e.currentTarget.dataset.action;
            handleAction(action);
        });
    });

    function handleAction(action) {
        switch(action) {
            case 'grayscale':
                settings.grayscale = !settings.grayscale;
                body.classList.toggle('accessibility-grayscale');
                localStorage.setItem('accessibility-grayscale', settings.grayscale);
                break;
            case 'high-contrast':
                settings.highContrast = !settings.highContrast;
                body.classList.toggle('accessibility-high-contrast');
                localStorage.setItem('accessibility-high-contrast', settings.highContrast);
                break;
            case 'text-increase':
                changeTextSize(true);
                break;
            case 'text-decrease':
                changeTextSize(false);
                break;
            case 'text-to-speech':
                settings.tts = !settings.tts;
                toggleTTS();
                break;
            case 'reset':
                resetAccessibility();
                break;
        }
        updateActiveButtons();
    }

    // --- Fungsi Helper ---

    function changeTextSize(increase) {
        const sizes = ['xsmall', 'small', 'normal', 'large', 'xlarge'];
        let currentIndex = sizes.indexOf(settings.textSize);
        
        body.classList.remove(`text-${settings.textSize}`);

        if (increase) {
            currentIndex = Math.min(currentIndex + 1, sizes.length - 1);
        } else {
            currentIndex = Math.max(currentIndex - 1, 0);
        }
        
        settings.textSize = sizes[currentIndex];
        if (settings.textSize !== 'normal') {
            body.classList.add(`text-${settings.textSize}`);
        }
        localStorage.setItem('accessibility-text-size', settings.textSize);
    }

    function resetAccessibility() {
        body.classList.remove('accessibility-grayscale', 'accessibility-high-contrast');
        body.classList.remove('text-xsmall', 'text-small', 'text-large', 'text-xlarge');
        
        settings.grayscale = false;
        settings.highContrast = false;
        settings.textSize = 'normal';
        settings.tts = false;

        localStorage.removeItem('accessibility-grayscale');
        localStorage.removeItem('accessibility-high-contrast');
        localStorage.removeItem('accessibility-text-size');
        
        toggleTTS(false); // Pastikan TTS mati
        updateActiveButtons();
        accessibilityWidget.classList.remove('open');
    }

    function updateActiveButtons() {
        document.querySelector('[data-action="grayscale"]').classList.toggle('active', settings.grayscale);
        document.querySelector('[data-action="high-contrast"]').classList.toggle('active', settings.highContrast);
        document.querySelector('[data-action="text-to-speech"]').classList.toggle('active', settings.tts);
    }

    // --- Logika Text-to-Speech (TTS) ---
    let speech = new SpeechSynthesisUtterance();
    let currentHighlight = null;
    const readableElements = 'p, h1, h2, h3, h4, h5, h6, a, li, span';

    function toggleTTS(forceOff = null) {
        const ttsElements = document.querySelectorAll(readableElements);
        if (forceOff !== null) settings.tts = !forceOff;

        if (settings.tts) {
            ttsElements.forEach(el => {
                el.addEventListener('mouseenter', handleTTSHighlight);
                el.addEventListener('mouseleave', clearTTSHighlight);
            });
        } else {
            speechSynthesis.cancel();
            clearTTSHighlight();
            ttsElements.forEach(el => {
                el.removeEventListener('mouseenter', handleTTSHighlight);
                el.removeEventListener('mouseleave', clearTTSHighlight);
            });
        }
    }

    function handleTTSHighlight(e) {
        if (!settings.tts) return;
        const target = e.currentTarget;
        clearTTSHighlight();
        target.classList.add('tts-highlight');
        currentHighlight = target;
        
        // Baca teks setelah jeda singkat
        setTimeout(() => {
            if (currentHighlight === target) { // Pastikan kursor masih di elemen yang sama
                speech.text = target.textContent;
                speechSynthesis.cancel(); // Hentikan pembacaan sebelumnya
                speechSynthesis.speak(speech);
            }
        }, 300);
    }

    function clearTTSHighlight() {
        if(currentHighlight) {
            currentHighlight.classList.remove('tts-highlight');
        }
        currentHighlight = null;
    }
  }

})();