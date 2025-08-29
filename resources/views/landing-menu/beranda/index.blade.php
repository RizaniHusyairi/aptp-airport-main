@extends('layouts_landing.landing_app')

@section('title', 'Selamat Datang di Bandara APT Pranoto')
@section('header-class', 'home')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/beranda-modern.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
    {{-- CDN untuk library animasi & pemformatan --}}
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
    
    {{-- CDN BARU UNTUK GSAP & SCROLLTRIGGER --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    
    {{-- File JS kustom untuk halaman ini --}}
    <script src="{{ asset('assets_landing/js/beranda-modern.js') }}"></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endpush



@section('content')

<!-- ============================================ -->
<!--           HERO SECTION (VERSI LAMA)          -->
<!-- ============================================ -->
<section id="hero-modern" class="hero-modern">
    <div class="swiper hero-slider">
        <div class="swiper-wrapper">
            @forelse ($sliders as $slider)
                <div class="swiper-slide">
                    <div class="hero-background-image" style="background-image: url('{{ asset('uploads/' . $slider->documents) }}')"></div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="hero-background-image" style="background-image: url('{{asset('assets_landing/img/bg-1.png')}}')"></div>
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="hero-background-overlay"></div>

    <div class="container hero-container d-flex flex-column justify-content-center align-items-center">
        <h1 class="text-white" data-aos="fade-down">Bandara APT Pranoto</h1>
        <p class="text-white lead" data-aos="fade-up" data-aos-delay="200">
            Gerbang Udara Anda Menuju <span id="typed-destination"></span>
        </p>
        
        @if ($weather)
        <a href="https://www.bmkg.go.id/cuaca/prakiraan-cuaca/64.72.05.1004" target="_blank">
            <div class="weather-widget-modern" data-aos="fade-up" data-aos-delay="400">
                <img src="{{ $weather['weather_icon'] }}" alt="Ikon Cuaca">
                <span>{{ $weather['temperature'] }}°C, {{ $weather['weather_desc'] }} di Samarinda</span>
            </div>
        </a>
        @endif
    </div>

    <div class="hero-quick-nav-wrapper">
        <div class="hero-quick-nav" data-aos="fade-up" data-aos-delay="600">
            <a href="#flight-info" class="hero-nav-link">
                <i class="bi bi-airplane-engines-fill"></i>
                <span>Jadwal Penerbangan</span>
            </a>
            <a href="#explore-section" class="hero-nav-link" data-tab-target="#facilities-tab">
                <i class="bi bi-gem"></i>
                <span>Fasilitas</span>
            </a>
            <a href="#explore-section" class="hero-nav-link" data-tab-target="#tourism-tab">
                <i class="bi bi-compass-fill"></i>
                <span>Pariwisata</span>
            </a>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!--         SLIDE INFORMASI (SEKSI BARU)         -->
<!-- ============================================ -->

@if($infoSlides->isNotEmpty())

<section id="info-slider-section" class="section-modern info-slider-section">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Slide Informasi</h2>
            <p>Informasi dan pengumuman terkini dari Bandara APT Pranoto.</p>
        </div>
        <div class="swiper info-slider">
            <div class="swiper-wrapper">
                {{-- @forelse ($infoSlides as $slide) --}}
                @forelse ($infoSlides as $slide)
                <div class="swiper-slide">
                        <a href="{{ $slide->link_url ?? '#' }}" target="_blank" class="info-slide-card">
                            {{-- <img src="{{ Storage::url($slide->image_path) }}" alt="{{ $slide->title }}"> --}}
                            <img src="{{ asset('uploads/' . $slide->image_path) }}" alt="t">
                            
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="info-slide-card-empty">
                            <p>Tidak ada informasi untuk ditampilkan saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

@endif
<!-- ============================================ -->
<!--   SEKSI SAMBUTAN KEPALA BANDARA (BARU)       -->
<!-- ============================================ -->
<section id="welcome-section" class="section-modern welcome-section">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-5 text-center" data-aos="fade-right">
                <div class="welcome-image-container">
                    <img src="{{ asset('assets_landing/img/pejabat/Kadek.png') }}" class="img-fluid" alt="Foto Kepala Bandara I Kadek Yuli Sastrawan">
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
                <div class="welcome-content">
                    <h2 class="welcome-title">Sambutan dari Kepala Bandara</h2>
                    <div class="welcome-text-body">
                        <p>
                            "Dalam era yang penuh tantangan ini, di mana teknologi dan informasi berkembang begitu pesat, kita di BLU Kantor UPBU Kelas I APT. Pranoto Samarinda merasa penting untuk terus beradaptasi. Teknologi telah membawa kita ke Era Revolusi Industri 4.0, yang menuntut kita untuk memanfaatkannya dengan efektif dan efisien."
                        </p>
                        <p>
                            "Sejalan dengan semangat revolusi ini, kami berkomitmen untuk memberikan pelayanan yang terbaik kepada masyarakat. Melalui website ini, kami berharap dapat memberikan kemudahan akses informasi seputar kegiatan, tugas, dan fungsi BLU Kantor UPBU Kelas I APT. Pranoto Samarinda."
                        </p>
                        <p>
                            "Kami mengundang Anda untuk menjelajahi situs web kami, mendapatkan informasi yang berguna, dan memberikan masukan yang konstruktif. Semoga dengan kehadiran situs ini, kita dapat meningkatkan kualitas interaksi, informasi, dan komunikasi antara BLU Kantor UPBU Kelas I APT. Pranoto Samarinda dengan masyarakat."
                        </p>
                    </div>
                    <div class="welcome-signature">
                        <h4 class="signature-name">I Kadek Yuli Sastrawan, S.Ikom., S.SiT.</h4>
                        <p class="signature-title">Kepala BLU Kantor UPBU Kelas I APT. Pranoto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!--   STATISTIK LALU LINTAS UDARA (DESAIN BARU)  -->
<!-- ============================================ -->
<section id="traffic-stats" class="section-modern traffic-stats" data-detail-url="{{ route('lalulintas') }}">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Aktivitas Bandara Bulan Ini</h2>
            <p>Data Lalu Lintas Udara hingga hari ini di bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>
        
        {{-- Kontainer ini akan diisi oleh JavaScript. --}}
        <div id="monthly-stats-container">
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat statistik...</p>
            </div>
        </div>
        
    </div>
</section>




<!-- ============================================ -->
<!--              LIVE FLIGHT INFO                -->
<!-- ============================================ -->
<section id="flight-info" class="section-modern flight-info dark-background">
     <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Jadwal Penerbangan Hari Ini</h2>
            <p>Informasi Kedatangan & Keberangkatan Langsung dari Genggaman Anda</p>
        </div>

        <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-arrivals-tab" data-bs-toggle="pill" data-bs-target="#pills-arrivals" type="button" role="tab" aria-controls="pills-arrivals" aria-selected="true">
                    <i class="bi bi-airplane-fill kedatangan"></i> Kedatangan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-departures-tab" data-bs-toggle="pill" data-bs-target="#pills-departures" type="button" role="tab" aria-controls="pills-departures" aria-selected="false">
                    <i class="bi bi-airplane-fill"></i> Keberangkatan
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent" data-aos="fade-up" data-aos-delay="50">
            <div class="tab-pane fade show active" id="pills-arrivals" role="tabpanel" aria-labelledby="pills-arrivals-tab">
                <div id="arrivals-list" class="flight-list">
                    <div class="text-center p-5"><span class="spinner-border"></span><p class="mt-2 text-white-50">Memuat data kedatangan...</p></div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-departures" role="tabpanel" aria-labelledby="pills-departures-tab">
                 <div id="departures-list" class="flight-list">
                    <div class="text-center p-5"><span class="spinner-border"></span><p class="mt-2 text-white-50">Memuat data keberangkatan...</p></div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<!-- ============================================ -->
<!--   PETA SVG ANIMASI (MENGGANTIKAN CARDS)      -->
<!-- ============================================ -->
<section id="route-map" class="section-modern route-map">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern text-center">
            <h2>Terhubung ke Seluruh Nusantara</h2>
            <p>Jelajahi jaringan rute kami yang terus berkembang, menghubungkan Samarinda dengan berbagai destinasi.</p>
        </div>
        <div class="map-container" style="background-image: url({{ asset('assets_landing/img/map-indonesia-simple.svg') }})">
        {{-- <div class="map-container" style="background-image: url({{ asset('assets_landing/img/mapsimple.svg') }})"> --}}
        {{-- <div class="map-container"> --}}
            {{-- Kontainer SVG di mana peta akan digambar oleh JavaScript --}}
            <svg id="map-svg" viewBox="0 0 1000 500" preserveAspectRatio="xMidYMid meet">
                <!-- Titik utama bandara & rute akan ditambahkan oleh JS -->
            </svg>
            {{-- Tooltip untuk menampilkan info saat hover --}}
            <div id="map-tooltip" style="z-index: 10000"></div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!--   JELAJAHI BANDARA & SEKITARNYA (SEKSI BARU) -->
<!-- ============================================ -->
<section id="explore-section" class="section-modern explore-section">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern text-center">
            <h2>Jelajahi Bandara & Sekitarnya</h2>
            <p>Temukan kenyamanan di dalam terminal dan keindahan destinasi di sekitar kami.</p>
        </div>

        <!-- Tombol Tab Navigasi -->
        <ul class="nav nav-pills justify-content-center mb-5" id="explore-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="facilities-tab" data-bs-toggle="pill" data-bs-target="#facilities-content" type="button" role="tab" aria-controls="facilities-content" aria-selected="true">
                    <i class="bi bi-gem"></i> Fasilitas Unggulan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tourism-tab" data-bs-toggle="pill" data-bs-target="#tourism-content" type="button" role="tab" aria-controls="tourism-content" aria-selected="false">
                    <i class="bi bi-compass-fill"></i> Wisata Terdekat
                </button>
            </li>
        </ul>

        <!-- Konten Tab -->
        <div class="tab-content" id="explore-tab-content">
            <!-- Konten Fasilitas -->
            <div class="tab-pane fade show active" id="facilities-content" role="tabpanel" aria-labelledby="facilities-tab">
                <div class="row g-4">
                    {{-- ### KONTEN KARTU DIPERBARUI ### --}}
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('fasilitas') }}" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('https://images.unsplash.com/photo-1569154941061-e231b4725ef1?q=80&w=2070&auto=format&fit=crop');"></div>
                            <div class="explore-card-content">
                                <h3>Fasilitas Sisi Udara</h3>
                                <p>Infrastruktur vital kami, mulai dari landasan pacu hingga apron yang modern dan efisien.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                         <a href="{{ route('fasilitas') }}" class="explore-card">
                            
                            <div class="explore-card-image" style="background-image: url({{ asset('assets_landing/img/fasilitas/darat/temindung-penumpang.JPG') }});"></div>
                            <div class="explore-card-content">
                                <h3>Fasilitas Sisi Darat</h3>
                                <p>Temukan kemegahan arsitektur terminal penumpang, gedung kargo, dan area parkir yang luas.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                         <a href="{{ route('fasilitas') }}" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop');"></div>
                            <div class="explore-card-content">
                                <h3>Fasilitas Umum</h3>
                                <p>Nikmati berbagai layanan mulai dari check-in counter, kuliner, hingga ruang ibadah yang nyaman.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Konten Pariwisata -->
            <div class="tab-pane fade" id="tourism-content" role="tabpanel" aria-labelledby="tourism-tab">
                <div id="tourism-cards-container" class="row g-4">
                    {{-- Konten akan diisi oleh JavaScript --}}
                    <div class="col-12 text-center"><div class="spinner-border text-primary" role="status"></div></div>
                </div>
            </div>
        </div>

        <!-- Tombol "Lihat Semua" yang Dinamis -->
        <div id="explore-button-container" class="text-center mt-5">
            <a href="{{ route('fasilitas') }}" class="btn-modern-outline-dark">Lihat Semua Fasilitas</a>
        </div>

    </div>
</section>
<!-- ============================================ -->
<!--              SEKSI MITRA KAMI (BARU)         -->
<!-- ============================================ -->
<section id="partners-section" class="section-modern partners-section">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern text-center">
            <h2>Dipercaya oleh Mitra Terkemuka</h2>
            <p>Kami bangga dapat bekerja sama dengan berbagai maskapai dan instansi terbaik di bidangnya.</p>
        </div>

        <div class="partners-carousel">
            <div class="partners-track">
                {{-- Logo akan digandakan oleh JavaScript untuk efek tak terbatas --}}
                <a href="https://www.airnavindonesia.co.id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-airnav.png') }}" alt="Logo AirNav"></a>
                <a href="https://www.batikair.com/id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-batik.png') }}" alt="Logo Batik Air"></a>
                <a href="https://www.bmkg.go.id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-BMKG.png') }}" alt="Logo BMKG"></a>
                <a href="https://www.garuda-indonesia.com/id/id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-garuda.png') }}" alt="Logo Garuda Indonesia"></a>
                <a href="https://kaltimprov.go.id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/Logo-Pemprov.png') }}" alt="Logo Pemprov Kaltim"></a>
                <a href="https://pertamina.com/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-pertamina.png') }}" alt="Logo Pertamina"></a>
                <a href="https://www.superairjet.com/id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-SAJ.png') }}" alt="Logo Super Air Jet"></a>
                <a href="https://kkp.go.id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo_kkp.svg') }}" alt="Logo KKP"></a>
                <a href="https://www.citilink.co.id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-citilink.png') }}" alt="Logo Citilink"></a>
                <a href="https://smartaviation.co.id/home" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-smart.jpg') }}" alt="Logo Smart Aviation"></a>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!--                 NEWS SECTION                 -->
<!-- ============================================ -->
<section id="news-modern" class="section-modern news-modern dark-background">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Kabar Terbaru dari Gerbang Udara Anda</h2>
            <p>Ikuti terus informasi, acara, dan pengembangan terbaru langsung dari Bandara APT Pranoto.</p>
        </div>
        <div class="row g-4">
            @forelse ($headlines as $news)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('news.show', $news->slug) }}" class="news-card-modern">
                        <div class="news-image" style="background-image: url('{{ asset('uploads/' . $news->image) }}');"></div>
                        <div class="news-content">
                            <span class="news-date">{{ $news->created_at->translatedFormat('d M Y') }}</span>
                            <h3 class="news-title">{{ Str::limit($news->title, 55) }}</h3>
                            <p class="news-excerpt">{{ Str::limit(strip_tags($news->content), 80) }}</p>
                            <span class="news-read-more">Baca Selengkapnya <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-white-50">Tidak ada berita utama saat ini.</p>
                </div>
            @endforelse
        </div>
         <div class="text-center mt-5">
             <a href="{{ route('berita') }}" class="btn-modern-outline">Lihat Semua Berita</a>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!--    FLOATING ACTION BUTTON (FAB) KONTAK BARU  -->
<!-- ============================================ -->
<div id="contact-fab-container">
    <!-- Formulir Kontak yang Tersembunyi -->
    <div id="contact-form-wrapper">
        <div class="contact-form-header">
            <h5>Hubungi Kami</h5>
            <button type="button" id="close-contact-form" class="btn-close btn-close-white"></button>
        </div>
        <div class="contact-form-body">
            <form id="contact-fab-form" action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                </div>
                <div class="mb-2">
                    <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                </div>
                 <div class="mb-2">
                    <input type="text" name="phone_number" class="form-control" placeholder="Nomor Telepon" required>
                </div>
                <div class="mb-2">
                    <select name="subject" class="form-select" required>
                        <option value="">Pilih Kategori...</option>
                        <option value="Informasi">Informasi</option>
                        <option value="Keluhan">Keluhan</option>
                        <option value="Saran">Saran</option>
                        <option value="Apresiasi">Apresiasi</option>
                    </select>
                </div>
                <div class="mb-2">
                    <textarea name="message" class="form-control" rows="3" placeholder="Pesan Anda..." required></textarea>
                </div>
                <div class="mb-2">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </div>
            </form>
            <div id="fab-form-response" class="mt-3"></div>
        </div>
    </div>

    <!-- Tombol FAB Utama -->
    <button id="contact-fab-toggle" class="btn">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

@endsection
