<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Mazer Admin Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/custom.css') }}">

    <!-- Page-specific CSS -->
    @yield('styles')
</head>
<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            @include('partials.sidebars.' . auth()->user()->role)
        </div>
        <div id="main" class="layout-navbar navbar-fixed">
            @include('partials.header')
            <div id="main-content">
                @yield('content')
            </div>
            @include('partials.footer')
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/custom-sidebar.js') }}"></script>

    <!-- Page-specific JS -->
    @yield('scripts')
</body>
</html>