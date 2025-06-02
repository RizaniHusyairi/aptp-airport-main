@extends('layouts_landing.landing_app')

@section('title', "Surat $type - Bandara APT Pranoto")

@section('content')
<section id="surat-{{ $type }}" class="section pt-6">
    <div class="container section-title" data-aos="fade-up">
        <h2>Regulasi<br></h2>
        <p><span>{{ $type == 'utusan' ? 'Surat Utusan' : 'Surat Edaran' }}</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
    </div>
    <div class="container light-background table-container">
        <div id="surat{{ $type == 'utusan' ? 'Utusan' : 'Edaran' }}Table"></div>
    </div>
</section>
@endsection

@push('page-styles')
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">
<link href="{{ asset('assets_landing/css/edaran.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
<script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="{{ asset('assets_landing/js/edaran.js') }}"></script>
@endpush