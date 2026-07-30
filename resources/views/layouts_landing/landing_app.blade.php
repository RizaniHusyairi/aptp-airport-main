<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  @php
    $seoDefaultDescription = 'Situs resmi Bandara APT Pranoto Samarinda (AAP). Cek jadwal keberangkatan dan kedatangan real-time, fasilitas, layanan, dan informasi penerbangan resmi Kalimantan Timur.';
    $seoImage = asset('assets_landing/img/hero-img.png');
  @endphp
  <title>@yield('title', 'Bandara APT Pranoto Samarinda (AAP) — Informasi Resmi')</title>

  {{-- ===== SEO Meta (default situs; tiap halaman bisa override via @section) ===== --}}
  <meta name="description" content="@yield('description', $seoDefaultDescription)">
  <meta name="keywords" content="@yield('keywords', 'Bandara APT Pranoto, Bandara Samarinda, jadwal penerbangan APT Pranoto, rute penerbangan Samarinda, fasilitas Bandara APT Pranoto, transportasi bandara Samarinda, AAP, WALS')">
  <meta name="robots" content="@yield('robots', 'index, follow')">
  <meta name="author" content="Bandara APT Pranoto Samarinda">
  <meta name="theme-color" content="#0d6efd">
  <link rel="canonical" href="{{ url()->current() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- ===== Open Graph (Facebook / WhatsApp) ===== --}}
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Bandara APT Pranoto Samarinda">
  <meta property="og:locale" content="id_ID">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="{{ View::yieldContent('title', 'Bandara APT Pranoto Samarinda (AAP)') }}">
  <meta property="og:description" content="{{ View::yieldContent('description', $seoDefaultDescription) }}">
  <meta property="og:image" content="@yield('og-image', $seoImage)">

  {{-- ===== Twitter Card ===== --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@aptp_airport">
  <meta name="twitter:title" content="{{ View::yieldContent('title', 'Bandara APT Pranoto Samarinda (AAP)') }}">
  <meta name="twitter:description" content="{{ View::yieldContent('description', $seoDefaultDescription) }}">
  <meta name="twitter:image" content="@yield('og-image', $seoImage)">

  {{-- ===== Structured Data: Airport (verifikasi ulang telepon/alamat resmi bila perlu) ===== --}}
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Airport",
    "name": "Bandara APT Pranoto Samarinda",
    "alternateName": "Aji Pangeran Tumenggung Pranoto International Airport",
    "iataCode": "AAP",
    "icaoCode": "WALS",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('assets_landing/img/logo/Logo_Kementerian_Perhubungan_Indonesia_(Kemenhub).png') }}",
    "image": "{{ $seoImage }}",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Jl. Poros Samarinda - Bontang, Kel. Sungai Siring",
      "addressLocality": "Samarinda",
      "addressRegion": "Kalimantan Timur",
      "postalCode": "75119",
      "addressCountry": "ID"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": -0.37450,
      "longitude": 117.25010
    },
    "sameAs": [
      "https://www.instagram.com/aptpranotoairport",
      "https://www.youtube.com/@aptpranotoairport",
      "https://www.tiktok.com/@aptpranotoairport",
      "https://x.com/aptp_airport"
    ]
  }
  </script>
  @stack('schema')


  {{--
    Favicon — logo APT Pranoto.
    logo-mini-apt.svg dipakai sebagai ikon utama. PNG di bawahnya adalah
    cadangan untuk peramban yang tidak mendukung favicon SVG, dan
    apple-touch-icon WAJIB berformat PNG karena iOS tidak mendukung SVG.
    Kedua PNG dibuat dari logo yang sama, sudah dipangkas agar terbaca.
  --}}
  <link href="{{ asset('assets_landing/img/logo/logo-mini-apt.svg') }}" rel="icon" type="image/svg+xml">
  <link href="{{ asset('assets_landing/img/favicon-apt.png') }}" rel="icon" type="image/png" sizes="64x64">
  <link href="{{ asset('assets_landing/img/apple-touch-icon-apt.png') }}" rel="apple-touch-icon" sizes="180x180">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  @include('partials.styles')
  @stack('page-styles')
</head>

<body class="index-page">
  <div id="page-wrapper">
    @include('layouts_landing.header')
    <main class="main">
      @yield('content')
    </main>
  
    @include('layouts_landing.footer')
    <div id="preloader">
      <img src="{{ asset('assets_landing/img/logo/logo-mini-apt.svg') }}" alt="mini">
      <div id="loader"></div>
    </div>

  </div>


  <!-- Preloader -->

  {{-- =============================================== --}}
  {{-- =     KODE BARU: WIDGET AKSESIBILITAS         = --}}
  {{-- =============================================== --}}
  <div class="accessibility-widget">
    <div class="accessibility-panel" id="accessibility-panel">
        <button class="panel-option" data-action="text-to-speech">
            <i class="bi bi-ear-fill"></i>
            <span>Mode Suara</span>
        </button>
        <button class="panel-option" data-action="grayscale">
            <i class="bi bi-palette-fill"></i>
            <span>Kejenuhan</span>
        </button>
        <button class="panel-option" data-action="high-contrast">
            <i class="bi bi-circle-half"></i>
            <span>Kontras+</span>
        </button>
        <div class="panel-option text-size-controls">
            <i class="bi bi-fonts"></i>
            <span>Ukuran Teks</span>
            <div class="size-buttons">
                <button data-action="text-decrease">A-</button>
                <button data-action="text-increase">A+</button>
            </div>
        </div>
        <button class="panel-option" data-action="reset">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Reset</span>
        </button>
    </div>
    <button class="accessibility-toggle" id="accessibility-toggle" title="Alat Aksesibilitas">
        <i class="bi bi-universal-access"></i>
    </button>
  </div>
  {{-- =============================================== --}}
  {{-- =          AKHIR DARI KODE BARU               = --}}
  {{-- =============================================== --}}


  <!-- Scripts -->
  @include('partials.scripts')
  @stack('page-scripts')
</body>
</html>