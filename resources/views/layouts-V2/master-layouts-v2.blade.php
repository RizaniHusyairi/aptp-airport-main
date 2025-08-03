<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Mazer Admin Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('assetsv2/compiled/svg/favicon.svg') }}" type="image/x-icon">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/custom.css') }}">

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

    <!-- Page-specific JS -->
    @yield('scripts_admin')
</body>
</html>