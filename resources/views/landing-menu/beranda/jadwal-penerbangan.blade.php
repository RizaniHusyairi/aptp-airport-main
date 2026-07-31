@extends('layouts_landing.landing_app')

@section('title', 'Jadwal Penerbangan — Bandara APT Pranoto Samarinda (AAP)')
@section('description', 'Jadwal keberangkatan dan kedatangan penerbangan real-time Bandara APT Pranoto Samarinda (AAP). Lihat maskapai, kota tujuan dan asal, gate, konter, conveyor, serta status penerbangan terkini.')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/flight-board.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
    <script src="{{ asset('assets_landing/js/flight-particles.js') }}"></script>
    <script src="{{ asset('assets_landing/js/flight-board.js') }}"></script>
@endpush

@section('content')
<section class="fb">

    {{-- ================= HERO ================= --}}
    <div class="fb-hero">
        <canvas class="fb-canvas" aria-hidden="true"></canvas>

        <div class="container fb-hero-inner">
            <div class="row">
                <div class="col-lg-8">
                    <span class="fb-eyebrow">
                        <i class="bi bi-clock-history"></i> Papan Jadwal
                    </span>

                    <h1 class="fb-title">Jadwal <span>Penerbangan</span></h1>
                    <p class="fb-subtitle">
                        Informasi keberangkatan dan kedatangan penerbangan di Bandar Udara A.P.T. Pranoto
                        Samarinda, diperbarui otomatis mengikuti data operasional bandara.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10">
                    <div class="fb-meta">
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Kode Bandara</span>
                            <span class="fb-meta-value">AAP</span>
                        </div>
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Waktu Setempat</span>
                            <span class="fb-meta-value" data-fb-clock>--:--:--</span>
                        </div>
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Keberangkatan</span>
                            <span class="fb-meta-value" data-fb-count="departure">0</span>
                        </div>
                        <div class="fb-meta-card">
                            <span class="fb-meta-label">Kedatangan</span>
                            <span class="fb-meta-value" data-fb-count="arrival">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= PAPAN JADWAL ================= --}}
    <div class="fb-board-section">
        <div class="container">

            {{-- Tab pemilih arah penerbangan --}}
            <ul class="nav fb-tabs" id="fb-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-departure" data-bs-toggle="tab"
                            data-bs-target="#panel-departure" type="button" role="tab"
                            aria-controls="panel-departure" aria-selected="true">
                        <i class="bi bi-airplane-engines-fill"></i> Keberangkatan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-arrival" data-bs-toggle="tab"
                            data-bs-target="#panel-arrival" type="button" role="tab"
                            aria-controls="panel-arrival" aria-selected="false">
                        <i class="bi bi-airplane-fill"></i> Kedatangan
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="panel-departure" role="tabpanel" aria-labelledby="tab-departure">
                    @include('landing-menu.beranda.partials.flight-board', [
                        'mode' => 'departure',
                        'endpoint' => route('api.departures'),
                    ])
                </div>
                <div class="tab-pane fade" id="panel-arrival" role="tabpanel" aria-labelledby="tab-arrival">
                    @include('landing-menu.beranda.partials.flight-board', [
                        'mode' => 'arrival',
                        'endpoint' => route('api.arrivals'),
                    ])
                </div>
            </div>

        </div>
    </div>

</section>
@endsection
