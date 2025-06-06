<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
    <title>@yield('title', 'APT Pranoto')</title>
    @stack('styles')
    <style>
      .content-wrapper {
        /* margin-top: 80px; */
      }
    </style>
</head>

<body>
    <main style="overflow-x: hidden;">
        <nav class="navbar navbar-expand-lg  px-md-3 py-md-2 scrolled {{ request()->routeIs('home') ? 'home' : '' }}" id="navbar">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand d-flex" href="{{ route('home') }}">
                    <img src="{{ asset('frontend/assets/logo.png') }}" alt="mind & body" style="width: 8rem;" />
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class='bx bx-menu'></i>
                </button>

                <!-- Navbar Items -->
                <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
                    <ul class="navbar-nav gap-lg-4 text-center text-lg-start">
                        {{-- @foreach ($menuItems['header'] as $item)
                        <li class="nav-item dropdown">

                            @if (isset($item['dropdown']))
                                    <a class="nav-link dropdown-toggle navigation" href="#"
                                        id="dropdownMenuLink{{ $loop->index }}" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $item['name'] }}
                                    </a>
                                    <ul class="dropdown-menu animated-dropdown" aria-labelledby="dropdownMenuLink{{ $loop->index }}">
                                        @foreach ($item['dropdown'] as $dropdownItem)
                                            <li><a class="dropdown-item"
                                                    href="{{ $dropdownItem['route'] }}">{{ $dropdownItem['name'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @else
                            <a class="nav-link navigation" href="{{ $item['route'] }}">{{ $item['name'] }}</a>

                            
                            @endif
                        </li>
                        @endforeach --}}

                    </ul>
                </div>
                @auth
                    <div class="justify-content-end">
                        @if (Auth::user()->is_admin)
                            <a href="{{ route('root') }}" class="nav-link">Dashboard</a>
                        @else
                            <a href="{{ route('root') }}/profile" class="nav-link">Dashboard</a>
                        @endif
                    </div>
                @else
                    <div class="justify-content-end">
                        <a href="{{ route('login') }}" class="nav-link nav-login">Masuk</a>
                    </div>
                @endauth
            </div>
        </nav>
        <div class="content-wrapper">
          @yield('content')
        </div>
        <footer class="bg-black text-white">
            <div class="footer row">
                <div id="carouselExampleSlidesOnly" class="carousel slide col-lg-6 d-flex align-items-end "
                    data-bs-ride="carousel">
                    
                </div>
                <div class="col align-content-end">
                    @if ($topikUtama->isNotEmpty())
                        <section class="text-white">
                            <div class="container">
                                <div class="row">
                                    {{-- @foreach ($topikUtama as $news)
                                        <div class="col-md-4">
                                            <a href="{{ route('showNews', $news->slug) }}"
                                                class="text-decoration-none text-white">
                                                <div class="ratio ratio-4x3 overflow-hidden pilates">
                                                    <img src="{{ asset('uploads/' . $news->image) }}" class="w-100"
                                                        style="object-position: center center; object-fit: cover;"
                                                        alt="{{ $news->title }}">
                                                </div>
                                                <div class="fs-8 text-start">
                                                    <p class="mt-2 email">{{ $news->title }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach --}}
                                </div>

                                <div class="d-flex justify-content-center mt-2">
                                    <a href="{{ route('berita') }}"
                                        class="other-news d-flex align-items-center text-white text-decoration-none">
                                        <span class="fs-9">Lihat Berita Lainnya</span>
                                        <i class="bx bx-right-arrow-alt fs-8"></i>
                                    </a>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            </div>

            <div class="footer row pb-5">
                <div class="col mb-5 mb-md-0">
                    <h4 class="text-start fs-3 mb-4">Navigasi Halaman</h4>
                    <div class="accordion accordion-flush p-0" id="accordionFlushExample">
                        {{-- @foreach ($menuItems['header'] as $menu)
                            @if (isset($menu['dropdown']))
                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header p-0">
                                        <button
                                            class="accordion-button collapsed bg-transparent text-white p-0 lh-lg fs-7"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse{{ $loop->index }}" aria-expanded="false"
                                            aria-controls="flush-collapse{{ $loop->index }}">
                                            {{ $menu['name'] }}
                                        </button>
                                    </h2>
                                    <div id="flush-collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading{{ $loop->index }}"
                                        data-bs-parent="#accordionFlushExample">
                                        @foreach ($menu['dropdown'] as $dropdown)
                                            <a href="{{ $dropdown['route'] }}"
                                                class="accordion-body d-flex text-decoration-none text-white pilates">{{ $dropdown['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header p-0">
                                        <a href="{{ $menu['route'] }}"
                                            class="accordion-button text-decoration-none fw-normal collapsed bg-transparent text-white p-0 lh-lg fs-7">
                                            {{ $menu['name'] }}
                                        </a>
                                    </h2>
                                </div>
                            @endif
                        @endforeach --}}
                    </div>
                </div>
                <div class="col text-end fs-8">
                    <img src="{{ asset('frontend/assets/logo-badan-layanan-umum.png') }}" alt="logo BLU" style="width: 4rem;" >

                    <h4 class="fs-3 mb-4 pb-2">Info Kontak</h4>

                    <div class="d-flex flex-column">
                        <a href="https://maps.app.goo.gl/SBcNQFzbxBuyMTE8A" class="email text-white"
                            target="_blank">Jl. Poros Samarinda – Bontang, Kel. Sungai Siring, Samarinda – Kalimantan
                            Timur 75119</a>
                        <a href="https://wa.me/62811551944" target="_blank" class="email text-white">+62 811 551
                            944</a>
                        <a href="mailto:mail.aptpranotoairport@gmail.com" target="_blank"
                            class="email text-white">mail.aptpranotoairport@gmail.com</a>
                        <div class="fs-4 d-flex gap-3 justify-content-end mt-5">
                            <a href="https://wa.me/62811551944" target="_blank" class="text-white social-media">
                                <i class="bx bxl-whatsapp"></i>
                            </a>
                            <a href="https://www.facebook.com/aptpranotoairport/" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-facebook"></i>
                            </a>
                            <a href="https://www.twitter.com/aptp_airport" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-twitter"></i>
                            </a>
                            <a href="https://www.youtube.com/@aptpranotoairport" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-youtube"></i>
                            </a>
                            <a href="https://www.instagram.com/aptpranotoairport/" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-instagram "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer text-start fs-9 mt-5 d-flex justify-content-between align-items-center"
                style="background-color: #0b0b0b;">
                <span>Kantor Unit Penyelenggara Bandara Udara Kelas I A.P.T Pranoto Samarinda</span>
            </div>
        </footer>


    </main>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="{{ asset('frontend/script.js') }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @stack('scripts')
    <script>
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
                const activeIndex = swiper.activeIndex % (swiper.slides.length - 1) || 0;
                tabs.forEach((tab, idx) => tab.classList.toggle('active', idx === activeIndex));
            });

            // Animasi detail profil
            document.querySelectorAll('.read-more').forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    const detail = document.querySelector(targetId);
                    if (detail.style.display === 'none' || !detail.style.display) {
                        detail.style.display = 'block';
                        gsap.from(detail, { height: 0, opacity: 0, duration: 0.5, ease: 'power2.out' });
                    } else {
                        gsap.to(detail, { height: 0, opacity: 0, duration: 0.5, ease: 'power2.out', onComplete: () => detail.style.display = 'none' });
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.from('.news-card', {
                opacity: 0,
                y: 50,
                duration: 1,
                stagger: 0.2, // Animasi bertahap untuk setiap kartu
                scrollTrigger: {
                    trigger: '.news-section',
                    start: 'top 80%',
                    toggleActions: 'play none none reverse'
                }
            });
        });
    

        
document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Animasi Dropdown dengan GSAP
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        if (!dropdown.querySelector('.dropdown-toggle')) {
            console.error('Dropdown toggle not found in dropdown:', dropdown);
        }else{

            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            const toggle = dropdown.querySelector('.dropdown-toggle');
    
            toggle.addEventListener('click', () => {
                if (!dropdownMenu.classList.contains('show')) {
                    // Animasi saat dropdown muncul
                    gsap.fromTo(dropdownMenu, {
                        opacity: 0,
                        y: -5,
                        
                        scale: 1
                    }, {
                        opacity: 1,
                        y: 0,
                        scale: 1,
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                }
            });
        }
    });


  // Logika Toggle Contact Panel
    const contactIcon = document.getElementById('contactIcon');
    const contactPanel = document.getElementById('contactPanel');
    const closeContact = document.getElementById('closeContact');

    contactIcon.addEventListener('click', () => {
        contactPanel.classList.toggle('open');
    });

    closeContact.addEventListener('click', () => {
        contactPanel.classList.remove('open');
    });

    // Tutup panel saat klik di luar
    document.addEventListener('click', (e) => {
        if (!contactPanel.contains(e.target) && !contactIcon.contains(e.target)) {
            contactPanel.classList.remove('open');
        }
    });

  });
              // Animasi Background Navbar di Beranda
            const navbar = document.getElementById('navbar');
            const isHomePage = navbar.classList.contains('home');

            if (isHomePage) {
                const updateNavbar = () => {
                    if (window.scrollY > 300) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                };

                window.addEventListener('scroll', updateNavbar);
                updateNavbar(); // Panggil sekali saat load
            } else {
                navbar.classList.add('scrolled'); // Selalu biru tua di halaman lain
            }


    </script>
</body>

</html>
