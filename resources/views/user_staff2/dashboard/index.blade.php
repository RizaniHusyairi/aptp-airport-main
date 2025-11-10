@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dasbor Staff')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/apexcharts/apexcharts.css') }}">
    
    <style>

        .icon-dashboard {
            font-size: 2.5rem;
        }

        /* === CSS BARU UNTUK EFEK KLIK === */
        .card-widget {
            transition: all 0.3s ease;
        }
        .card-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard Staff</h3>
                <p class="text-subtitle text-muted">Ringkasan aktivitas dan tugas Anda, {{ Auth::user()->name }}.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dasbor', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        {{-- ====================================================== --}}
        {{-- ===            KARTU RINGKASAN (WIDGETS)           === --}}
        {{-- ====================================================== --}}

        @if($permissions->contains('Manajemen Tenant'))
        <div class="col-6 col-lg-3 col-md-6">
            {{-- === PERUBAHAN: Dibungkus <a> === --}}
            <a href="{{ route('tenant.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget"> {{-- Tambah class card-widget --}}
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-shop"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Ajuan Tenant</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_tenant_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif
        
        @if($permissions->contains('Manajemen Sewa'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('staffSewa.index') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-building"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Ajuan Sewa</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_rental_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($permissions->contains('Manajemen Extend Advance'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('extend-advance.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-clock-history"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Extend Advance</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_extend_advance_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($permissions->contains('Manajemen Slot Charter'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('slot.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-calendar2-check"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Slot Charter</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_slot_charter_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($permissions->contains('Manajemen Perijinan Usaha'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('perijinan.index') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-card-checklist"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Perizinan Usaha</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_license_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($permissions->contains('Manajemen Pengiklanan'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('pengiklanan.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-badge-ad-fill"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Pengiklanan</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_ad_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($permissions->contains('Manajemen Field Trip'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('fieldtrip.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-bus-front-fill"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Field Trip</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_fieldtrip_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($permissions->contains('Manajemen Lelang'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('lelang.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2"><i class="icon-dashboard bi bi-briefcase-fill"></i></div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Lelang</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_lelang_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Widget Pengaduan Menunggu --}}
        @if($permissions->contains('Manajemen Pengaduan'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('pengaduan.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
                                    <i class="icon-dashboard bi bi-exclamation-octagon-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Pengaduan</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_complaints_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Widget Ajuan Informasi Publik Menunggu --}}
        @if($permissions->contains('Manajemen Ajuan Informasi Publik'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('informasiPublik.staffIndex') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="icon-dashboard bi bi-info-circle-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Ajuan Informasi</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['pending_public_info_count']) }} <span class="text-sm ">Menunggu</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Widget Data LLAU Bulan Ini --}}
        @if($permissions->contains('Manajemen Lalu Lintas Angkutan Udara'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('staff.air-traffic.index') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="icon-dashboard bi bi-graph-up-arrow"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Log LLAU</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['llau_logs_this_month']) }} <span class="text-sm ">Bulan Ini</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Widget Inventaris --}}
        @if($permissions->contains('Manajemen Inventaris'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('staff.inventories.index') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="icon-dashboard bi bi-box-seam"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class=" font-semibold">Inventaris</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($data['maintenance_inventory_count']) }} <span class="text-sm ">Pemeliharaan</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Widget Program Kerja --}}
        @if($permissions->contains('Manajemen Program Kerja'))
        <div class="col-6 col-lg-3 col-md-6">
            <a href="{{ route('staff.work-programs.index') }}" class="text-decoration-none">
                <div class="card card-widget">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="icon-dashboard bi bi-clipboard-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                @php
                                    $kanitRoles = [
                                        "Kanit"
                                    ];
                                @endphp
                                @if(Auth::user()->hasRole($kanitRoles))
                                    <h6 class=" font-semibold">Tugas Perlu Verifikasi</h6>
                                    <h6 class="font-extrabold mb-0">{{ number_format($data['tasks_awaiting_verification']) }} <span class="text-sm ">Tugas</span></h6>
                                @else
                                    <h6 class=" font-semibold">Tugas Perlu Revisi</h6>
                                    <h6 class="font-extrabold mb-0">{{ number_format($data['tasks_needing_revision'] ?? 0) }} <span class="text-sm ">Tugas</span></h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </section>

    <section class="row">
        {{-- ====================================================== --}}
        {{-- ===              DAFTAR TUGAS TERBARU              === --}}
        {{-- ====================================================== --}}
        {{-- === GRAFIK BARU: LLAU 7 HARI TERAKHIR === --}}
        @if($permissions->contains('Manajemen Lalu Lintas Angkutan Udara'))
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Aktivitas LLAU (7 Hari Terakhir)</h4>
                </div>
                <div class="card-body">
                    {{-- ID chart-llau-7hari akan digunakan oleh JavaScript --}}
                    <div id="chart-llau-7hari" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        @endif

        {{-- === GRAFIK BARU: PEMASUKAN === --}}
        @if($permissions->contains('Manajemen Kinerja Keuangan'))
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Grafik Pemasukan</h4>
                    <select id="pemasukan-filter" class="form-select w-auto">
                        <option value="all">Semua Tahun</option>
                        @foreach ($data['finance_years'] as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body">
                    {{-- Berikan data ke div untuk diambil oleh JavaScript --}}
                    <div id="chart-pemasukan" 
                         data-pemasukan='@json($data['pemasukan_chart_data'])' 
                         style="height: 350px;">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Grafik Anggaran vs Belanja --}}
        @if($permissions->contains('Manajemen Kinerja Keuangan'))
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Grafik Perbandingan Anggaran dan Belanja</h4>
                    <select id="anggaran-belanja-filter" class="form-select w-auto">
                        <option value="all">Semua Tahun</option>
                         @if(isset($data['finance_years']))
                            @foreach ($data['finance_years'] as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="card-body">
                    <div id="chart-anggaran-belanja"
                         data-anggaran='@json($data['anggaran_chart_data'] ?? [])'
                         data-belanja='@json($data['belanja_chart_data'] ?? [])'
                         style="height: 350px;">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Kolom Ajuan Informasi Publik Terbaru --}}
        @if($permissions->contains('Manajemen Ajuan Informasi Publik') && $data['recent_public_info']->isNotEmpty())
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ajuan Informasi Publik Terbaru (Menunggu)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Pengaju</th>
                                    <th>Informasi Diminta</th>
                                    <th>Tgl. Ajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_public_info'] as $info)
                                <tr>
                                    <td>{{ $info->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('informasiPublik.show', $info->id) }}">
                                            {{ Str::limit($info->information_details, 50) }}
                                        </a>
                                    </td>
                                    <td>{{ $info->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Kolom Pengaduan Terbaru --}}
        @if($permissions->contains('Manajemen Pengaduan') && $data['recent_complaints']->isNotEmpty())
        <div class="col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengaduan Terbaru (Menunggu)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Tgl. Aduan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_complaints'] as $complaint)
                                <tr>
                                    <td>{{ $complaint->name }}</td>
                                    <td>
                                        {{-- Ganti 'pengaduan.staffShow' jika nama route-nya berbeda --}}
                                        <a href="{{ route('pengaduan.staffIndex') }}?complaint_id={{ $complaint->id }}">
                                            {{ $complaint->subject }}
                                        </a>
                                    </td>
                                    <td>{{ $complaint->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </section>
</div>
@endsection

@section('scripts_admin')
    {{-- === SCRIPT BARU UNTUK GRAFIK === --}}
    <script src="{{ asset('assetsv2/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Periksa jika elemen grafik LLAU ada
            const llauChartEl = document.getElementById('chart-llau-7hari');
            
            @if($permissions->contains('Manajemen Lalu Lintas Angkutan Udara'))
            if (llauChartEl) {
                // Ambil data dari controller yang sudah diformat
                const llauData = @json($data['llau_7day_chart']);
                
                var llauChartOptions = {
                    series: llauData.series,
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: { show: false },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: llauData.labels,
                    },
                    yaxis: {
                        title: {
                            text: 'Jumlah'
                        }
                    },
                    tooltip: {
                        x: {
                            format: 'dd MMM'
                        },
                    },
                    legend: {
                        position: 'top'
                    }
                };

                var llauChart = new ApexCharts(llauChartEl, llauChartOptions);
                llauChart.render();
            }
            @endif
            // ======================================================
        // ===          GRAFIK PEMASUKAN (BARU)               ===
        // ======================================================
        const pemasukanChartEl = document.getElementById('chart-pemasukan');
        @if($permissions->contains('Manajemen Kinerja Keuangan'))
        if (pemasukanChartEl) {
            // Ambil data JSON dari atribut data-
            const pemasukanData = JSON.parse(pemasukanChartEl.dataset.pemasukan);
            
            const pemasukanChartOptions = {
                series: [{
                    name: 'Pemasukan (Rp)',
                    data: pemasukanData['all'] // Tampilkan 'Semua Tahun' secara default
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: true }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)'
                    },
                    labels: {
                        formatter: function (val) {
                            // Format ke Juta, Miliar, dll.
                            if (val >= 1000000000) {
                                return (val / 1000000000).toFixed(1) + ' M';
                            }
                            if (val >= 1000000) {
                                return (val / 1000000).toFixed(1) + ' Jt';
                            }
                            return val.toLocaleString('id-ID');
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            var pemasukanChart = new ApexCharts(pemasukanChartEl, pemasukanChartOptions);
            pemasukanChart.render();

            // Tambahkan event listener ke filter dropdown
            const pemasukanFilter = document.getElementById('pemasukan-filter');
            pemasukanFilter.addEventListener('change', function() {
                const selectedYear = this.value;
                // Update data series pada grafik berdasarkan tahun yang dipilih
                pemasukanChart.updateSeries([{
                    data: pemasukanData[selectedYear]
                }]);
            });
        }
        @endif

        // ======================================================
        // ===       GRAFIK ANGGARAN VS BELANJA (BARU)        ===
        // ======================================================
        const anggaranChartEl = document.getElementById('chart-anggaran-belanja');
        @if($permissions->contains('Manajemen Kinerja Keuangan') && isset($data['anggaran_chart_data']))
        if (anggaranChartEl) {
            const anggaranData = JSON.parse(anggaranChartEl.dataset.anggaran);
            const belanjaData = JSON.parse(anggaranChartEl.dataset.belanja);

            const anggaranChartOptions = {
                series: [
                    { name: 'Anggaran (Rp)', data: anggaranData['all'] },
                    { name: 'Belanja (Rp)', data: belanjaData['all'] }
                ],
                chart: { type: 'line', height: 350, toolbar: { show: true }, zoom: { enabled: false } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                },
                yaxis: {
                    title: { text: 'Rupiah (Rp)' },
                    labels: {
                        formatter: function (val) {
                            if (val >= 1000000000) return (val / 1000000000).toFixed(1) + ' M';
                            if (val >= 1000000) return (val / 1000000).toFixed(1) + ' Jt';
                            return val.toLocaleString('id-ID');
                        }
                    }
                },
                legend: { position: 'top' },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            var anggaranChart = new ApexCharts(anggaranChartEl, anggaranChartOptions);
            anggaranChart.render();

            const anggaranFilter = document.getElementById('anggaran-belanja-filter');
            if(anggaranFilter) {
                anggaranFilter.addEventListener('change', function() {
                    const selectedYear = this.value;
                    anggaranChart.updateSeries([
                        { data: anggaranData[selectedYear] },
                        { data: belanjaData[selectedYear] }
                    ]);
                });
            }
        }
        @endif
        });
    </script>
@endsection