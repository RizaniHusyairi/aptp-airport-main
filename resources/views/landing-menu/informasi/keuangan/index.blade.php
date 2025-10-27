@extends('layouts_landing.landing_app')

@section('title', 'Laporan Keuangan - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/keuangan.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- ============================================ -->
<!--      LAPORAN REALISASI (BAGIAN ATAS)         -->
<!-- ============================================ -->
<section id="realization-report" class="section-modern financial-report pt-6 light-background">
    <div class="container">
        <div class="container section-title" data-aos="fade-up">
            <h2>Informasi<br></h2>
            <p><span>Kinerja Keuangan</span> <span class="description-title">Bandar Udara APT Pranoto Samarinda</span></p>
        </div>
        <div class="container php-email-form ">
            <!-- Filter Section -->
            <div class="filter-section row justify-content-center">
                <div class="col-12 col-md-4 col-lg-3">
                    <label for="yearFilter" class="form-label">Pilih Tahun:</label>
                    <select id="yearFilter" class="form-select">
                        <option value="all">Semua Tahun</option>
                        @foreach ($years as $year)
                            {{-- Menggunakan now()->year untuk tahun saat ini --}}
                            <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Filter Bulan sudah dihapus --}}
            </div>
            <!-- Grafik Section -->
            <div class="row">
                <!-- Grafik Pemasukan (Bar) -->
                <div class="col-12 mt-4 col-lg-6">
                    <div class="report-card">
                        <h3 class="text-center">Pendapatan</h3>
                        <div class="chart-container">
                            <canvas id="incomeChart" data-url="{{ route('informasiKeuangan.data') }}"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Grafik Anggaran vs Pengeluaran (Line) -->
                <div class="col-12 mt-4 col-lg-6">
                    <div class="report-card">
                        <h3 class="text-center">Anggaran dan Belanja</h3>
                        <div class="chart-container">
                            <canvas id="budgetVsExpenseChart" data-url="{{ route('informasiKeuangan.data') }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <!-- KARTU SUMBER DANA -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="report-card">
                        <h3 class="report-card-title">Realisasi Berdasarkan Sumber Dana</h3>
                        <div class="chart-container-small"><canvas id="sumberDanaChart"></canvas></div>
                        <div class="table-responsive mt-3">
                            <table class="table modern-table-compact">
                                <thead><tr><th>Sumber Dana</th><th class="text-end">Jumlah (Rp)</th><th class="text-center">%</th></tr></thead>
                                <tbody id="sumberDanaTableBody">
                                    {{-- Baris data akan digenerate di sini --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('assets_landing/js/keuangan.js') }}"></script>
@endpush

