@extends('layouts_landing.landing_app')

@section('title', 'Lalu Lintas Angkutan Udara - Bandara APT Pranoto')

@section('content')
<section id="traffic" class="section light-background pt-6">
    <div class="container section-title" data-aos="fade-up">
        <h2>Lalu Lintas<br></h2>
        <p><span>Angkutan Udara</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
    </div>
    <div class="container php-email-form">
        <!-- Filter Section -->
        <div class="filter-section row justify-content-center">
            <div class="col-12 col-md-4 col-lg-3">
                <label for="yearFilter" class="form-label">Pilih Tahun:</label>
                <select id="yearFilter" class="form-select" aria-label="Pilih tahun lalu lintas angkutan udara">
                    <option value="all">Semua Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div id="monthFilterContainer" class="col-12 col-md-4 col-lg-3">
                <label for="monthFilter" class="form-label">Pilih Bulan:</label>
                <select id="monthFilter" class="form-select" aria-label="Pilih bulan lalu lintas angkutan udara">
                    <option value="all">Semua Bulan</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
        </div>
        <!-- Grafik Section -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center">Lalu Lintas Angkutan Udara</h3>
                <div class="chart-container">
                    <div id="lineChartContainer" class="chart-wrapper">
                        <canvas id="lineChart"></canvas>
                    </div>
                    <div id="barChartContainer" class="chart-wrapper hidden">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Download Button -->
        <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ asset('assets_landing/pdf/lalu-lintas-angkutan-udara-2025.pdf') }}" class="btn btn-primary" download>Unduh Laporan (PDF)</a>
        </div>
    </div>
</section>
@endsection

@push('page-styles')
<link href="{{ asset('assets_landing/css/lalu-lintas.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('assets_landing/js/lalu-lintas.js') }}"></script>
<script src="{{ asset('assets_landing/js/line-chart.js') }}"></script>
<script src="{{ asset('assets_landing/js/bar-chart.js') }}"></script>
@endpush