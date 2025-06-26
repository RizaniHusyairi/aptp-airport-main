<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', 'Bandara APT Pranoto')</title>
  <meta name="description" content="@yield('description', '')">
  <meta name="keywords" content="@yield('keywords', '')">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <!-- Favicons -->
  <link href="{{ asset('assets_landing/img/logo/Logo_Kementerian_Perhubungan_Indonesia_(Kemenhub).png') }}" rel="icon">
  <link href="{{ asset('assets_landing/img/logo/Logo_Kementerian_Perhubungan_Indonesia_(Kemenhub).png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  @include('partials.styles')
  @stack('page-styles')
</head>

<body class="index-page">
  @include('layouts_landing.header')

  <main class="main">
    @yield('content')
  </main>

  @include('layouts_landing.footer')

  <!-- Preloader -->
  <div id="preloader">
    <img src="{{ asset('assets_landing/img/logo/logo-mini-apt.svg') }}" alt="mini">
    <div id="loader"></div>
  </div>

  <!-- Scripts -->
  @include('partials.scripts')
  @stack('page-scripts')
</body>
</html>