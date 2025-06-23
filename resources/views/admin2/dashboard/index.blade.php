@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/custom.css') }}">
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
            <!-- Total Pengunjung Card -->
            <div class="col-6 col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="iconly-boldShow"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Pengunjung website</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($totalVisitors) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts -->
            <div class="row">
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Grafik Pemasukan</h4>
                            <select id="pemasukan-filter" class="form-select w-auto">
                                <option value="all">Semua Tahun</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="card-body">
                            <div id="chart-pemasukan"
                            data-pemasukan='@json($pemasukanData)'></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Grafik Perbandingan Anggaran dan Belanja</h4>
                            <select id="anggaran-belanja-filter" class="form-select w-auto">
                                <option value="all">Semua Tahun</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="card-body">
                            <div id="chart-anggaran-belanja"
                            data-anggaran='@json($anggaranBelanjaData)'
                            ></div>
                        </div>
                    </div>
                </div>
                <!-- Other charts... -->
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