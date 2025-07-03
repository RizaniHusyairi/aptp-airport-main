@extends('layouts_landing.landing_app')

@section('title', 'Laporan Keuangan - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/keuangan.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- ============================================ -->
<!--      LAPORAN REALISASI (BAGIAN ATAS)         -->
<!-- ============================================ -->
<section id="realization-report" class="section-modern financial-report pt-6">
    <div class="container">
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

<!-- ============================================ -->
<!--     KOMPOSISI ANGGARAN (BAGIAN BAWAH)        -->
<!-- ============================================ -->
<section id="composition-report" class="section-modern financial-report light-background">
    <div class="container">
        <div class="section-title-modern text-center" data-aos="fade-up">
            <h2>Komposisi Alokasi Anggaran</h2>
            <p><span>Tahun Anggaran 2025</span> <span class="description-title">Total Pagu: Rp 110.228.576.000</span></p>
        </div>

        <div class="row g-4 g-lg-5">
            <!-- KARTU 1: BERDASARKAN JENIS PROGRAM -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="report-card">
                    <h3 class="report-card-title">Berdasarkan Jenis Program</h3>
                    <div class="chart-container-small">
                        <canvas id="programChart"></canvas>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table modern-table-compact">
                            <thead><tr><th>Jenis Program</th><th class="text-end">Jumlah (Rp)</th><th class="text-center">%</th></tr></thead>
                            <tbody>
                                <tr><td><span class="color-indicator" style="background-color: #36a2eb;"></span>Infrastruktur Konektivitas</td><td class="text-end">55.702.543.000</td><td class="text-center">50,53%</td></tr>
                                <tr><td><span class="color-indicator" style="background-color: #ffcd56;"></span>Dukungan Manajemen</td><td class="text-end">54.526.033.000</td><td class="text-center">49,47%</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KARTU 2: BERDASARKAN JENIS BELANJA -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="report-card">
                    <h3 class="report-card-title">Berdasarkan Jenis Belanja</h3>
                    <div class="chart-container-small"><canvas id="belanjaChart"></canvas></div>
                    <div class="table-responsive mt-3">
                        <table class="table modern-table-compact">
                            <thead><tr><th>Jenis Belanja</th><th class="text-end">Jumlah (Rp)</th><th class="text-center">%</th></tr></thead>
                            <tbody>
                                <tr><td><span class="color-indicator" style="background-color: #36a2eb;"></span>Belanja Pegawai</td><td class="text-end">9.947.052.000</td><td class="text-center">9,02%</td></tr>
                                <tr><td><span class="color-indicator" style="background-color: #ff6384;"></span>Belanja Barang</td><td class="text-end">76.804.764.000</td><td class="text-center">69,68%</td></tr>
                                <tr><td><span class="color-indicator" style="background-color: #4bc0c0;"></span>Belanja Modal</td><td class="text-end">23.476.760.000</td><td class="text-center">21,30%</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KARTU 3: BERDASARKAN JENIS KEGIATAN -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="report-card">
                    <h3 class="report-card-title">Berdasarkan Jenis Kegiatan</h3>
                    <div class="chart-container-small"><canvas id="kegiatanChart"></canvas></div>
                    <div class="table-responsive mt-3">
                        <table class="table modern-table-compact">
                            <thead><tr><th>Jenis Kegiatan</th><th class="text-end">Jumlah (Rp)</th></tr></thead>
                            <tbody>
                                <tr><td>Pelayanan Transportasi Udara</td><td class="text-end">24.310.000.000</td></tr>
                                <tr><td>Infrastruktur Transportasi Udara</td><td class="text-end">22.000.000.000</td></tr>
                                <tr><td>Keselamatan & Keamanan</td><td class="text-end">1.300.000.000</td></tr>
                                <tr><td>Penunjang Teknis</td><td class="text-end">8.000.000.000</td></tr>
                                <tr><td>Pengelolaan Keuangan & SDM</td><td class="text-end">53.987.000.000</td></tr>
                                <tr><td>Perencanaan & Umum</td><td class="text-end">631.576.000</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KARTU 4: BERDASARKAN SUMBER DANA -->
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="report-card">
                    <h3 class="report-card-title">Berdasarkan Sumber Dana</h3>
                    <div class="chart-container-small"><canvas id="sumberDanaChart"></canvas></div>
                    <div class="table-responsive mt-3">
                        <table class="table modern-table-compact">
                            <thead><tr><th>Sumber Dana</th><th class="text-end">Jumlah (Rp)</th><th class="text-center">%</th></tr></thead>
                            <tbody>
                                <tr><td><span class="color-indicator" style="background-color: #4bc0c0;"></span>Rupiah Murni</td><td class="text-end">69.804.875.000</td><td class="text-center">63,33%</td></tr>
                                <tr><td><span class="color-indicator" style="background-color: #ff9f40;"></span>PNBP BLU</td><td class="text-end">40.423.701.000</td><td class="text-center">36,67%</td></tr>
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
