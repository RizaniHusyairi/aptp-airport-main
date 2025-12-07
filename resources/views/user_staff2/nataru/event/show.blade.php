@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Event Posko')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    <style>
        .stat-card {
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stat-card.primary { border-left-color: #435ebe; }
        .stat-card.success { border-left-color: #198754; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.info { border-left-color: #0dcaf0; }
        
        .diff-indicator { font-size: 0.85rem; font-weight: 600; margin-top: 5px; display: block;}
        .text-up { color: #198754; } /* Hijau */
        .text-down { color: #dc3545; } /* Merah */
        .text-neutral { color: #6c757d; } /* Abu-abu */
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $nataruEvent->name }}</h3>
                <p class="text-subtitle text-muted">
                    Periode: {{ $nataruEvent->start_date->format('d M Y') }} - {{ $nataruEvent->end_date->format('d M Y') }}
                    @if($nataruEvent->is_active)
                        <span class="badge bg-success ms-2">Aktif</span>
                    @else
                        <span class="badge bg-secondary ms-2">Selesai</span>
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                        ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                        ['label' => 'Event Posko', 'url' => route('staff.nataru-events.index')],
                        ['label' => 'Detail Event', 'active' => true]
                    ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    
    {{-- 1. Info Link & Ringkasan Statistik --}}
    <div class="row mb-4">
        {{-- Link Card --}}
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="text-muted mb-1">Link Input Data (Publik)</h6>
                        <small>Bagikan kepada petugas lapangan.</small>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ route('public.nataru.form', $nataruEvent->public_token) }}" id="publicLink" readonly>
                        <button class="btn btn-primary" onclick="copyLink()">
                            <i class="bi bi-clipboard"></i> Salin
                        </button>
                        <a href="{{ route('public.nataru.form', $nataruEvent->public_token) }}" target="_blank" class="btn btn-outline-secondary" title="Buka Form">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        
                        {{-- TOMBOL EXPORT EXCEL BARU --}}
                        <a href="{{ route('staff.nataru-events.export', $nataruEvent->id) }}" class="btn btn-success ms-2 text-white fw-bold" title="Download Excel">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </a>

                        {{-- TOMBOL TV --}}
                        <a href="{{ route('public.nataru.tv', $nataruEvent->public_token) }}" target="_blank" class="btn btn-warning text-dark fw-bold ms-2" title="Buka Tampilan TV">
                            <i class="bi bi-display"></i> Mode TV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Cards --}}
        {{-- Helper function untuk menampilkan indikator --}}
        @php
            function showDiff($val, $isPercent = false) {
                if ($val > 0) {
                    return '<span class="diff-indicator text-up"><i class="bi bi-arrow-up"></i> +' . number_format($val, $isPercent ? 2 : 0) . ($isPercent ? '%' : '') . '</span>';
                } elseif ($val < 0) {
                    return '<span class="diff-indicator text-down"><i class="bi bi-arrow-down"></i> ' . number_format($val, $isPercent ? 2 : 0) . ($isPercent ? '%' : '') . '</span>';
                } else {
                    return '<span class="diff-indicator text-neutral"><i class="bi bi-dash"></i> 0</span>';
                }
            }
        @endphp

        <div class="col-6 col-lg-3">
            <div class="card stat-card primary">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue mb-2"><i class="bi bi-airplane-engines"></i></div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Penerbangan</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($currentStats['total_flights']) }}</h4>
                            @if($comparison)
                                {!! showDiff($comparison['flights']) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card success">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green mb-2"><i class="bi bi-people-fill"></i></div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Penumpang</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($currentStats['total_pax']) }}</h4>
                            @if($comparison)
                                {!! showDiff($comparison['pax']) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card warning">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple mb-2"><i class="bi bi-box-seam"></i></div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Kargo (Kg)</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($currentStats['total_cargo']) }}</h4>
                            @if($comparison)
                                {!! showDiff($comparison['cargo']) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card info">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red mb-2"><i class="bi bi-pie-chart-fill"></i></div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Avg Load Factor</h6>
                            <h4 class="font-extrabold mb-0">{{ number_format($currentStats['avg_lf'], 2) }}%</h4>
                            @if($comparison)
                                {!! showDiff($comparison['lf'], true) !!} {{-- true untuk format persen --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Grafik Perbandingan (Jika Ada Pembanding) --}}
    @if($nataruEvent->compareEvent)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart-line me-2 text-primary"></i> 
                        Perbandingan vs {{ $nataruEvent->compareEvent->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pax-tab" data-bs-toggle="tab" href="#pax" role="tab">Penumpang</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="flight-tab" data-bs-toggle="tab" href="#flight" role="tab">Pesawat</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cargo-tab" data-bs-toggle="tab" href="#cargo" role="tab">Kargo</a>
                        </li>
                    </ul>
                    <div class="tab-content pt-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="pax" role="tabpanel">
                            <div id="chartPax"></div>
                        </div>
                        <div class="tab-pane fade" id="flight" role="tabpanel">
                            <div id="chartFlights"></div>
                        </div>
                        <div class="tab-pane fade" id="cargo" role="tabpanel">
                            <div id="chartCargo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 3. Tabel Data Penerbangan --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Data Penerbangan Masuk</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped" id="table-flights">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Maskapai / Flight</th>
                            <th>Rute</th>
                            <th>Arah</th>
                            <th>Pax</th>
                            <th>Kargo</th>
                            <th>Harga (H/L)</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nataruEvent->flights as $flight)
                        <tr>
                            <td>{{ $flight->flight_date->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($flight->flight_time)->format('H:i') }}</td>
                            <td>
                                <strong>{{ $flight->airline }}</strong><br>
                                <small class="text-muted">{{ $flight->flight_number }}</small>
                            </td>
                            <td>{{ $flight->route }}</td>
                            <td>
                                @if($flight->direction == 'arrival')
                                    <span class="badge bg-success"><i class="bi bi-arrow-down-left"></i> Datang</span>
                                @else
                                    <span class="badge bg-info"><i class="bi bi-arrow-up-right"></i> Berangkat</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $flight->pax_total }}</strong>
                                @if($flight->load_factor > 0)
                                    <br><small class="text-muted">LF: {{ number_format($flight->load_factor, 1) }}%</small>
                                @endif
                            </td>
                            <td>{{ number_format($flight->cargo) }}</td>
                            <td>
                                <small>
                                    T: {{ number_format($flight->ticket_price_high) }}<br>
                                    R: {{ number_format($flight->ticket_price_low) }}
                                </small>
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 100px;" title="{{ $flight->officer_name }}">
                                    {{ $flight->officer_name }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('staff.nataru.destroy', $flight->id) }}" method="POST" onsubmit="return confirm('Hapus data penerbangan ini?')">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="redirect_to_event" value="{{ $nataruEvent->id }}">
                                    <button class="btn btn-sm btn-danger" title="Hapus Data"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function copyLink() {
        var copyText = document.getElementById("publicLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999); 
        navigator.clipboard.writeText(copyText.value);
        alert("Link berhasil disalin!");
    }
</script>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/apexcharts/apexcharts.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#table-flights').DataTable({
                order: [[0, 'desc'], [1, 'desc']],
                language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" }
            });

            // === LOGIKA RENDER CHART (Sama seperti sebelumnya) ===
            @if($nataruEvent->compareEvent)
                $.ajax({
                    url: "{{ route('staff.nataru.dashboard.data') }}",
                    type: "GET",
                    data: { 
                        event_id_1: "{{ $nataruEvent->id }}", 
                        event_id_2: "{{ $nataruEvent->compareEvent->id }}" 
                    },
                    success: function(response) {
                        const labels = response.labels;
                        const name1 = response.event1_name;
                        const name2 = response.event2_name;

                        function renderChart(elId, seriesData, titleY) {
                            var options = {
                                series: seriesData,
                                chart: { type: 'line', height: 300, toolbar: {show: false} },
                                stroke: { curve: 'smooth', width: 3 },
                                colors: ['#435ebe', '#dc3545'],
                                xaxis: { categories: labels },
                                yaxis: { title: { text: titleY } },
                                tooltip: { y: { formatter: function (val) { return val + " " + titleY } } }
                            };
                            new ApexCharts(document.querySelector("#" + elId), options).render();
                        }

                        renderChart('chartPax', [
                            { name: name1, data: response.dataset1.pax },
                            { name: name2, data: response.dataset2.pax }
                        ], 'Orang');

                        renderChart('chartFlights', [
                            { name: name1, data: response.dataset1.flights },
                            { name: name2, data: response.dataset2.flights }
                        ], 'Pergerakan');

                        renderChart('chartCargo', [
                            { name: name1, data: response.dataset1.cargo },
                            { name: name2, data: response.dataset2.cargo }
                        ], 'Kg');
                    }
                });
            @endif
        });
    </script>
@endsection