@extends('layouts_landing.landing_app')

@section('title', 'Bandara APT Pranoto Samarinda (AAP) — Jadwal Penerbangan')
@section('header-class', 'home')

@push('page-styles')
    {{-- Leaflet untuk peta jaringan rute --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    {{--
        Versi berkas ditempelkan pada URL agar peramban mengambil ulang CSS
        setiap kali berkasnya berubah. Tanpa ini, perubahan tampilan tidak
        terlihat sampai pengunjung melakukan hard refresh.
    --}}
    @foreach (['beranda-modern', 'skm', 'tautan-terkait', 'faq', 'flight-home'] as $sheet)
        @php
            $path = public_path("assets_landing/css/{$sheet}.css");
        @endphp
        <link href="{{ asset("assets_landing/css/{$sheet}.css") }}?v={{ file_exists($path) ? filemtime($path) : '1' }}" rel="stylesheet">
    @endforeach
@endpush

@push('page-scripts')
    {{-- CDN untuk library animasi & pemformatan --}}
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/2.1.0/showdown.min.js"></script>
    
    {{-- CDN BARU UNTUK GSAP & SCROLLTRIGGER --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    {{-- === TAMBAHKAN SCRIPT PLUGIN DI SINI === --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ClipPathPlugin.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    
    {{-- File JS kustom untuk halaman ini --}}
    <script src="{{ asset('assets_landing/js/beranda-modern.js') }}"></script>

    {{-- Animasi masuk untuk akordeon FAQ unggulan --}}
    <script src="{{ asset('assets_landing/js/faq.js') }}"></script>

    {{-- Peta jaringan rute (Leaflet) --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('assets_landing/js/route-map.js') }}"></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endpush



@section('content')

<!-- ============================================ -->
<!--           HERO SECTION (VERSI LAMA)          -->
<!-- ============================================ -->
<section id="hero-modern" class="hero-modern">
    {{-- === PERUBAHAN DI SINI: Background Dinamis === --}}
    <div class="hero-background-container">
        @if ($heroSettings['hero_type'] == 'video' && !empty($heroSettings['hero_video_path']))
            <video autoplay muted loop playsinline class="hero-background-media">
                {{-- <source src="{{ Storage::url($heroSettings['hero_video_path']) }}" type="video/{{ pathinfo($heroSettings['hero_video_path'], PATHINFO_EXTENSION) }}"> --}}
                <source src="{{ asset('uploads/' . $heroSettings['hero_video_path']) }}" type="video/{{ pathinfo($heroSettings['hero_video_path'], PATHINFO_EXTENSION) }}">
                Browser Anda tidak mendukung video background.
            </video>
        @elseif (!empty($heroSettings['hero_image_path']))
            <div class="hero-background-media" style="background-image: url('{{ asset('uploads/' . $heroSettings['hero_image_path']) }}')"></div>
        @else
            {{-- Fallback jika tidak ada setting atau file --}}
            <div class="hero-background-media" style="background-image: url('{{ asset('assets_landing/img/bg-1.png') }}');"></div>
        @endif
    </div>
    {{-- === Akhir Perubahan Background === --}}
    {{-- <div class="swiper hero-slider">
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
    </div> --}}

    <div class="hero-background-overlay"></div>

    <div class="container hero-container d-flex flex-column justify-content-center align-items-center">
        {{-- H1 utama halaman (SEO + aksesibilitas); judul hero di bawah bersifat dekoratif/teranimasi --}}
        <h1 class="visually-hidden">Bandara APT Pranoto Samarinda (AAP), gerbang udara resmi Kalimantan Timur</h1>
        {{-- STRUKTUR JUDUL BARU --}}
        <div class="hero-title-reveal-v2">
            <div class="title-line-v2 line-1">
                <span>Bandar Udara</span>
            </div>

            <div class="title-line-v2 line-2">
                <div class="word-reveal">
                    <span class="first-letter">A</span><span class="rest-wrapper"><span class="rest-of-word">ji</span></span>
                </div>
                <div class="word-reveal">
                    <span class="first-letter">P</span><span class="rest-wrapper"><span class="rest-of-word">angeran</span></span>
                </div>
                <div class="word-reveal">
                    <span class="first-letter">T</span><span class="rest-wrapper"><span class="rest-of-word">umenggung</span></span>
                </div>
            </div>
            
            <div class="title-line-v2 line-3">
                <span>Pranoto</span>
            </div>
        </div>
        
        {{-- Akhir Struktur Judul Baru --}}
        {{-- <p class="text-white lead" data-aos="fade-up" data-aos-delay="200">
            Gerbang Udara Anda Menuju <span id="typed-destination"></span>
        </p> --}}
        
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
        {{-- <div class="section-title-modern">
            <h2>Slide Informasi</h2>
            <p>Informasi dan pengumuman terkini dari Bandara APT Pranoto.</p>
        </div> --}}
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
                    {{--
                        Pembungkus seukuran foto, agar lapisan blob menempel pada
                        kotak fotonya — bukan pada lebar kolom yang jauh lebih besar.
                    --}}
                    <div class="welcome-photo">
                        {{-- Latar blob & partikel, seluruhnya di belakang foto (murni CSS) --}}
                        <div class="welcome-blobs" aria-hidden="true">
                            <span class="wb-blob wb-blob--1"></span>
                            <span class="wb-blob wb-blob--2"></span>
                            <span class="wb-blob wb-blob--3"></span>
                            <span class="wb-dot wb-dot--1"></span>
                            <span class="wb-dot wb-dot--2"></span>
                            <span class="wb-dot wb-dot--3"></span>
                            <span class="wb-dot wb-dot--4"></span>
                            <span class="wb-dot wb-dot--5"></span>
                            <span class="wb-dot wb-dot--6"></span>
                        </div>
                        <img src="{{ asset('assets_landing/img/pejabat/Kadek.png') }}" class="img-fluid" alt="Foto Kepala Bandara I Kadek Yuli Sastrawan">
                    </div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
                <div class="welcome-content">
                    <h2 class="welcome-title">Sambutan dari Kepala Bandar Udara</h2>
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
<!--   STATISTIK LALU LINTAS UDARA                -->
<!-- ============================================ -->
<section id="traffic-stats" class="section-modern traffic-stats" data-detail-url="{{ route('lalulintas') }}">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Aktivitas Bandar Udara Bulan Ini</h2>
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
{{--
    Peta rute bergaya layar radar bandara: latar malam, sapuan radar berputar,
    jalur melengkung yang menyala, dan pesawat kecil yang terbang di sepanjang
    tiap rute. Kelas dark-background dipakai ulang dari tema agar judul section
    otomatis berwarna terang.
--}}
<section id="route-map" class="section-modern route-map dark-background">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern text-center">
            <h2>Terhubung ke Seluruh Nusantara</h2>
            <p>Jelajahi jaringan rute kami yang terus berkembang, menghubungkan Samarinda dengan berbagai destinasi.</p>
        </div>

        {{-- Peta geografis Leaflet; seluruh isinya digambar oleh route-map.js --}}
        <div class="map-container">
            <div id="route-map-canvas"></div>

            {{-- Keterangan jenis rute --}}
            <div class="rm-legend">
                <span><i style="--pin:#f0a500"></i> Pusat Jaringan</span>
                <span><i style="--pin:#8ec8ff"></i> Rute Utama</span>
                <span><i style="--pin:#3ddc84"></i> Rute Perintis</span>
            </div>
        </div>

        {{-- Ringkasan jaringan rute, diisi oleh JavaScript dari data yang sama --}}
        <div class="rm-stats" data-aos="fade-up" data-aos-delay="100">
            <div class="rm-stat">
                <i class="bi bi-geo-alt-fill"></i>
                <span class="rm-stat-value" data-rm-destinations>—</span>
                <span class="rm-stat-label">Kota Tujuan</span>
            </div>
            <div class="rm-stat">
                <i class="bi bi-airplane-engines-fill"></i>
                <span class="rm-stat-value" data-rm-airlines>—</span>
                <span class="rm-stat-label">Maskapai</span>
            </div>
            <div class="rm-stat">
                <i class="bi bi-broadcast"></i>
                <span class="rm-stat-value">AAP</span>
                <span class="rm-stat-label">Pusat Jaringan</span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================ -->
<!--   JELAJAHI BANDARA & SEKITARNYA (SEKSI BARU) -->
<!-- ============================================ -->
<section id="explore-section" class="section-modern explore-section">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern text-center">
            <h2>Jelajahi Bandar Udara & Sekitarnya</h2>
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
                    {{-- KARTU FASILITAS SISI UDARA --}}
                    <div class="col-lg-4 col-md-6">
                        <a href="{{ route('fasilitas') }}" class="explore-card">
                            {{-- Memanggil accessor image_url. Jika tidak ada data fasilitas, placeholder akan tampil --}}
                            <div class="explore-card-image" style="background-image: url('{{ optional($facilityImages['udara'])->image_url ?? 'https://placehold.co/600x400/0d2c4a/ffffff?text=Sisi+Udara' }}');"></div>
                            <div class="explore-card-content">
                                <h3>Fasilitas Sisi Udara</h3>
                                <p>Infrastruktur vital kami, mulai dari landasan pacu hingga apron yang modern dan efisien.</p>
                            </div>
                        </a>
                    </div>

                    {{-- KARTU FASILITAS SISI DARAT --}}
                    <div class="col-lg-4 col-md-6">
                         <a href="{{ route('fasilitas') }}" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('{{ optional($facilityImages['darat'])->image_url ?? 'https://placehold.co/600x400/0d2c4a/ffffff?text=Sisi+Darat' }}');"></div>
                            <div class="explore-card-content">
                                <h3>Fasilitas Sisi Darat</h3>
                                <p>Temukan kemegahan arsitektur terminal penumpang, gedung kargo, dan area parkir yang luas.</p>
                            </div>
                        </a>
                    </div>

                    {{-- KARTU FASILITAS UMUM --}}
                    <div class="col-lg-4 col-md-6">
                         <a href="{{ route('fasilitas') }}" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('{{ optional($facilityImages['umum'])->image_url ?? 'https://placehold.co/600x400/0d2c4a/ffffff?text=Fasilitas+Umum' }}');"></div>
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
                <a href="https://www.airnavindonesia.co.id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-airnav.png') }}" alt="Logo AirNav"></a>
                <a href="https://www.batikair.com/id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-batik.png') }}" alt="Logo Batik Air"></a>
                <a href="https://www.bmkg.go.id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-BMKG.png') }}" alt="Logo BMKG"></a>
                <a href="https://www.garuda-indonesia.com/id/id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-garuda.png') }}" alt="Logo Garuda Indonesia"></a>
                <a href="https://kaltimprov.go.id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/Logo-Pemprov.png') }}" alt="Logo Pemprov Kaltim"></a>
                <a href="https://pertamina.com/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-pertamina.png') }}" alt="Logo Pertamina"></a>
                <a href="https://www.superairjet.com/id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-SAJ.png') }}" alt="Logo Super Air Jet"></a>
                <a href="https://kkp.go.id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo_kkp.svg') }}" alt="Logo KKP"></a>
                <a href="https://www.citilink.co.id/" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-citilink.png') }}" alt="Logo Citilink"></a>
                <a href="https://smartaviation.co.id/home" target="_blank" class="partner-logo"><img loading="lazy" decoding="async" src="{{ asset('assets_landing/img/mitra/logo-smart.jpg') }}" alt="Logo Smart Aviation"></a>
            </div>
        </div>
    </div>
</section>


<!-- ============================================ -->
<!--             TAUTAN TERKAIT SECTION           -->
<!-- ============================================ -->
@if(!empty(externalLinks()))
<section id="tautan-terkait" class="section-modern">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Tautan Terkait</h2>
            <p>Akses cepat ke portal resmi pelayanan publik dan aplikasi kedinasan.</p>
        </div>
        @include('landing-menu.partials.tautan-terkait-groups')
    </div>
</section>
@endif


<!-- ============================================ -->
<!--                 FAQ SECTION                  -->
<!-- ============================================ -->
@if($featuredFaqs->isNotEmpty())
<section id="faq-home" class="section-modern light-background">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Pertanyaan yang Sering Diajukan</h2>
            <p>Jawaban cepat untuk hal-hal yang paling sering ditanyakan pengunjung.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                @include('landing-menu.partials.faq-accordion', [
                    'faqs' => $featuredFaqs,
                    'accordionId' => 'faq-home-accordion',
                ])

                <div class="text-center mt-5">
                    <a href="{{ route('faq') }}" class="btn-modern-outline-dark">Lihat Semua FAQ</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


<!-- ============================================ -->
<!--                 NEWS SECTION                 -->
<!-- ============================================ -->
<section id="news-modern" class="section-modern news-modern dark-background">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern">
            <h2>Kabar Terbaru dari Gerbang Udara Anda</h2>
            <p>Ikuti terus informasi, acara, dan pengembangan terbaru langsung dari Bandar Udara APT Pranoto.</p>
        </div>
        <div class="row g-4">
            @forelse ($headlines as $news)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('news.show', $news->slug) }}" class="news-card-modern">
                        <div class="news-image" style="background-image: url('{{ $news->image_url }}');"></div>
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
<!--          SKM CTA (SURVEI KEPUASAN)           -->
<!-- ============================================ -->
@if(skmSetting()['active'])
<section id="skm-cta" class="section-modern">
    <div class="container" data-aos="fade-up">
        <div class="skm-cta-card row align-items-center g-4">
            <div class="col-lg-8">
                <h2 class="skm-cta-title">Bagaimana Pengalaman Anda di Bandara Kami?</h2>
                <p class="skm-cta-text mb-0">Sampaikan penilaian Anda melalui Survei Kepuasan Masyarakat. Masukan Anda menjadi dasar perbaikan layanan kami.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ skmSetting()['url'] }}" target="_blank" rel="noopener" class="skm-cta-btn">
                    {{ skmSetting()['label'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif


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
