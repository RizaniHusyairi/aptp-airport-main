@extends('layouts_landing.landing_app')

@section('title', 'Jadwal Kedatangan — Bandara APT Pranoto Samarinda (AAP)')
@section('description', 'Jadwal kedatangan penerbangan real-time di Bandara APT Pranoto Samarinda (AAP). Lihat maskapai, kota asal, dan jam kedatangan terkini.')

@push('page-styles')
<link href="{{ asset('assets_landing/css/flight-board.css') }}" rel="stylesheet">
@endpush

@section('content')
    @include('landing-menu.beranda.partials.flight-board', [
        'mode' => 'arrival',
        'endpoint' => route('api.arrivals'),
        'eyebrow' => 'Papan Kedatangan',
        'titleLead' => 'Jadwal',
        'titleWord' => 'Kedatangan',
        'subtitle' => 'Informasi kedatangan penerbangan di Bandar Udara A.P.T. Pranoto Samarinda, diperbarui otomatis mengikuti data operasional bandara.',
    ])
@endsection

@push('page-scripts')
<script src="{{ asset('assets_landing/js/flight-particles.js') }}"></script>
<script src="{{ asset('assets_landing/js/flight-board.js') }}"></script>
@endpush
