<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <title>{{ config('app.name') }} | @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  {{-- Favicon — logo APT Pranoto (PNG sebagai cadangan untuk peramban tanpa dukungan SVG) --}}
  <link rel="icon" href="{{ asset('assets_landing/img/logo/logo-mini-apt.svg') }}" type="image/svg+xml">
  <link rel="icon" href="{{ asset('assets_landing/img/favicon-apt.png') }}" type="image/png" sizes="64x64">
  <link rel="apple-touch-icon" href="{{ asset('assets_landing/img/apple-touch-icon-apt.png') }}" sizes="180x180">
  @include('layouts.head-css')
</head>

@yield('body')

@yield('content')

@include('layouts.vendor-scripts')
</body>

</html>
