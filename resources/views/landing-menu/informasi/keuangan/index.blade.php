@extends('layouts_landing.landing_app')

@section('title', 'Laporan Keuangan - Bandara APT Pranoto')

@section('content')
  <section id="contact" class="section pt-6">
      <div class="container section-title" data-aos="fade-up">
        <h2>Informasi<br></h2>
        <p><span>Laporan Keuangan</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
      </div>
      <div class="container php-email-form light-background">
        <!-- Filter Section -->
        <div class="filter-section row justify-content-center">
          <div class="col-12 col-md-4 col-lg-3">
            <label for="yearFilter" class="form-label">Pilih Tahun:</label>
            <select id="yearFilter" class="form-select">
                <option value="all">Semua Tahun</option>
              @foreach ($years as $year)
                   <option value="{{ $year }}" {{ $year == 2025 ? 'selected' : '' }}>{{ $year }}</option>
                 @endforeach
            </select>
          </div>
          <div id="monthFilterContainer" class="col-12 col-md-4 col-lg-3">
            <label for="monthFilter" class="form-label">Pilih Bulan:</label>
            <select id="monthFilter" class="form-select">
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
          <!-- Grafik Pemasukan (Bar) -->
          <div class="col-12 col-lg-6">
            <h3 class="text-center">Pendapatan</h3>
            <div class="chart-container">
              <canvas id="incomeChart" data-url="{{ route('laporanKeuangan') }}/api/financial-data"></canvas>
              {{-- <canvas id="incomeChart"></canvas> --}}
            </div>
          </div>
          <!-- Grafik Anggaran vs Pengeluaran (Line) -->
          <div class="col-12 col-lg-6">
            <h3 class="text-center">Anggaran dan Belanja</h3>
            <div class="chart-container">
              <canvas id="budgetVsExpenseChart" data-url="{{ route('laporanKeuangan') }}/api/financial-data"></canvas>
              {{-- <canvas id="budgetVsExpenseChart"></canvas> --}}
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection

@push('page-styles')
  <link href="{{ asset('assets/css/keuangan.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="{{ asset('assets/js/keuangan.js') }}"></script>
@endpush