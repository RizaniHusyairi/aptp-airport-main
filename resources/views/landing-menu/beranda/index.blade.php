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
@endpush



@section('content')

<!-- ============================================ -->
<!--            HERO SECTION (REVISED)            -->
<!-- ============================================ -->
<section id="hero-modern" class="hero-modern">
    <div class="hero-background-image" style="background-image: url({{asset('assets_landing/img/bg-1.png')  }})"></div>
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

    <div class="hero-scroll-indicator">
        <a href="#quick-access">
            <i class="bi bi-mouse"></i>
        </a>
    </div>
</section>

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
<!--              QUICK ACCESS SECTION            -->
<!-- ============================================ -->
<section id="quick-access" class="section-modern quick-access">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('keberangkatan') }}" class="access-card">
                    <div class="icon-wrapper"><i class="bi bi-airplane-engines"></i></div>
                    <span>Cek Penerbangan</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="#" class="access-card">
                    <div class="icon-wrapper"><i class="bi bi-p-circle"></i></div>
                    <span>Akses & Parkir</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="#" class="access-card">
                    <div class="icon-wrapper"><i class="bi bi-shop"></i></div>
                    <span>Kuliner & Belanja</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="#" class="access-card">
                    <div class="icon-wrapper"><i class="bi bi-map"></i></div>
                    <span>Navigasi Terminal</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('berita') }}" class="access-card">
                    <div class="icon-wrapper"><i class="bi bi-newspaper"></i></div>
                    <span>Info & Berita</span>
                </a>
            </div>
             <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('pariwisata.index') }}" class="access-card">
                    <div class="icon-wrapper"><i class="bi bi-compass"></i></div>
                    <span>Jelajahi Kaltim</span>
                </a>
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
                    <i class="bi bi-airplane-land"></i> Kedatangan
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
<!--         ✨ AI TRIP PLANNER (GEMINI API)        -->
<!-- ============================================ -->
<section id="trip-planner" class="section-modern trip-planner">
    <div class="container" data-aos="fade-up">
        <div class="section-title-modern text-center">
            <h2>✨ Rencanakan Petualangan Anda dengan AI</h2>
            <p>Masukkan tujuan dan lama perjalanan. Biarkan asisten AI kami menyusun itinerary tak terlupakan untuk Anda.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form id="trip-planner-form" class="p-4 rounded-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <label for="tujuan" class="form-label">Saya ingin terbang ke:</label>
                            <input type="text" class="form-control form-control-lg" id="tujuan" placeholder="Contoh: Surabaya, Bali" required>
                        </div>
                        <div class="col-md-4">
                            <label for="durasi" class="form-label">Selama:</label>
                             <div class="input-group">
                                <input type="number" class="form-control form-control-lg" id="durasi" placeholder="Contoh: 3" min="1" max="10" required>
                                <span class="input-group-text">hari</span>
                            </div>
                        </div>
                        <div class="col-md-3 d-grid">
                             <label for="durasi" class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="submit" class="btn-gemini-planner">
                                <span class="btn-text">Rancang Sekarang!</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Trip Planner Modal -->
<div class="modal fade" id="trip-planner-modal" tabindex="-1" aria-labelledby="tripPlannerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tripPlannerModalLabel">✨ Rencana Petualangan Anda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="planner-loading" class="text-center p-5 d-none">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">AI sedang meracik rencana perjalanan terbaik untuk Anda...</p>
        </div>
        <div id="planner-result"></div>
        <div id="planner-error" class="alert alert-danger d-none" role="alert">
            Maaf, terjadi kesalahan saat membuat rencana. Silakan coba lagi.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


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
                    {{-- ### PERBAIKAN: Setiap kartu dibungkus dengan tag <a> ### --}}
                    <div class="col-lg-4 col-md-6">
                        <a href="#" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=2047&auto=format&fit=crop');"></div>
                            <div class="explore-card-content">
                                <h3>Ruang Tunggu Premium</h3>
                                <p>Nikmati kenyamanan eksklusif dengan fasilitas lengkap selagi menunggu penerbangan Anda.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                         <a href="#" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop');"></div>
                            <div class="explore-card-content">
                                <h3>Kuliner & Restoran</h3>
                                <p>Cicipi beragam hidangan lezat, dari cita rasa lokal hingga internasional, di area food court kami.</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                         <a href="#" class="explore-card">
                            <div class="explore-card-image" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=2070&auto=format&fit=crop');"></div>
                            <div class="explore-card-content">
                                <h3>Area Belanja & Retail</h3>
                                <p>Temukan berbagai kebutuhan perjalanan, suvenir, dan produk gaya hidup dari brand terkemuka.</p>
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
            <a href="#" class="btn-modern-outline-dark">Lihat Semua Fasilitas</a>
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
                <a href="https://www.lionair.co.id/" target="_blank" class="partner-logo"><img src="{{ asset('assets_landing/img/mitra/logo-lion.png') }}" alt="Logo Lion Air"></a>
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

@endsection
