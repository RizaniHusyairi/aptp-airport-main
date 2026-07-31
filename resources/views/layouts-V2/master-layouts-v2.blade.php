<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Dashboard</title>
    {{-- Favicon — logo APT Pranoto (PNG sebagai cadangan untuk peramban tanpa dukungan SVG) --}}
    <link rel="icon" href="{{ asset('assets_landing/img/logo/logo-mini-apt.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('assets_landing/img/favicon-apt.png') }}" type="image/png" sizes="64x64">
    <link rel="apple-touch-icon" href="{{ asset('assets_landing/img/apple-touch-icon-apt.png') }}" sizes="180x180">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/custom.css') }}">
    {{-- Tampilan sidebar & tooltip bertema penerbangan; dimuat terakhir agar menimpa tema --}}
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/sidebar-modern.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_landing/vendor/glightbox/css/glightbox.min.css') }}">

    <!-- Page-specific CSS -->
    @yield('styles_admin')
</head>
<body>
    <script src="{{ asset('assetsv2/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            @if (auth()->check())
                @admin
                    @include('layouts-V2.sidebars.admin')
                @endadmin
                @staff
                @include('layouts-V2.sidebars.staff')
                @endstaff
                @notadmin
                @notstaff
                    @if (auth()->user()->is_accepted)
                        @include('layouts-V2.sidebars.pengaju')
                    @else
                        <div class="alert alert-warning">Akun Anda belum disetujui.</div>
                    @endif

                @endnotstaff
                @endnotadmin
            @endif
        
        </div>
        <div id="main" class="layout-navbar navbar-fixed">
            @include('layouts-V2.header')
            <div id="main-content">
                @yield('content')
            </div>
            @include('layouts-V2.footer')
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assetsv2/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/custom-sidebar.js') }}"></script>
    <script src="{{ asset('assets_landing/vendor/glightbox/js/glightbox.min.js') }}"></script>

    <!-- Page-specific JS -->
    @yield('scripts_admin')
</body>
</html>