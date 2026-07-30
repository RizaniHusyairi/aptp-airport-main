@extends('layouts_landing.landing_app')

@section('title', 'Jadwal Keberangkatan — Bandara APT Pranoto Samarinda (AAP)')
@section('description', 'Jadwal keberangkatan penerbangan real-time dari Bandara APT Pranoto Samarinda (AAP). Lihat maskapai, kota tujuan, gate, dan jam keberangkatan terkini.')

@push('page-styles')
<link href="{{ asset('assets_landing/css/flight-board.css') }}" rel="stylesheet">
@endpush

@section('content')
    @include('landing-menu.beranda.partials.flight-board', [
        'mode' => 'departure',
        'endpoint' => route('api.departures'),
        'eyebrow' => 'Papan Keberangkatan',
        'titleLead' => 'Jadwal',
        'titleWord' => 'Keberangkatan',
        'subtitle' => 'Informasi keberangkatan penerbangan dari Bandar Udara A.P.T. Pranoto Samarinda, diperbarui otomatis mengikuti data operasional bandara.',
    ])
@endsection

@push('page-scripts')
<script src="{{ asset('assets_landing/js/flight-particles.js') }}"></script>
<script src="{{ asset('assets_landing/js/flight-board.js') }}"></script>
@endpush
