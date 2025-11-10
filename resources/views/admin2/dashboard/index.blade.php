@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/custom.css') }}">
    <style>
        .icon-dashboard {
            font-size: 2.5rem;
        }
        /* CSS untuk efek klik pada widget */
        .card-widget {
            transition: all 0.3s ease;
            text-decoration: none; /* Menghilangkan garis bawah dari link */
        }
        .card-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        /* Memastikan warna teks di dalam link widget tetap normal */
        .card-widget .text-muted,
        .card-widget .font-extrabold {
            color: inherit !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('root')],
                        ['label' => 'Dashboard', 'active' => true]
                    ]" />
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <section class="row">
            {{-- === PERUBAHAN DI SINI: Mengubah layout kolom & menambahkan widget === --}}
            
            <!-- Total Pengunjung Card -->
            <div class="col-6 col-lg-4 col-md-6">
                {{-- Kita buat non-klilkable untuk saat ini, atau Anda bisa tambahkan route ke halaman statistik pengunjung jika ada --}}
                <div class="card card-widget" style="cursor: default;"> 
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="icon-dashboard iconly-boldShow"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Pengunjung</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($totalVisitors) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WIDGET BARU: Total Pengguna -->
            <div class="col-6 col-lg-4 col-md-6">
                <a href="{{ route('customers.index') }}" class="card-widget">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="icon-dashboard iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Pengguna</h6>
                                    <h6 class="font-extrabold mb-0">{{ number_format($totalUsers) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- WIDGET BARU: Total Role -->
            <div class="col-6 col-lg-4 col-md-6">
                <a href="{{ route('roles.index') }}" class="card-widget">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="icon-dashboard bi bi-shield-lock-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Role</h6>
                                    <h6 class="font-extrabold mb-0">{{ number_format($totalRoles) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            {{-- === Akhir Perubahan === --}}
        </section>
        
            <!-- Charts -->
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Grafik pengunjung (7 hari terakhir)</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-profile-visit" 
                            data-categories='@json($visitorCategories)' 
                            data-series='@json($visitorSeries)'>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </section>
    </div>
    <script>
            
    </script>
@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assetsv2/static/js/pages/admin-dashboard.js') }}"></script>
@endsection