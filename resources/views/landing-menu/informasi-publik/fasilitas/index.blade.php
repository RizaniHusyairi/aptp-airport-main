@extends('layouts_landing.landing_app')

@section('title', 'Fasilitas Bandara - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/fasilitas.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="facilities-page" class="section-modern facilities-page pt-6">
    <div class="container">
        <div class="container section-title" data-aos="fade-up">
            <h2>Informasi<br></h2>
            <p><span>Fasilitas Lengkap</span> <span class="description-title">Bandara A.P.T. Pranoto</span></p>
        </div><!-- End Section Title -->
        {{-- <div class="section-title-modern text-center" data-aos="fade-up">
            <h2>Kenyamanan Anda Prioritas Kami</h2>
            <p><span></span> <span class="description-title"></span></p>
        </div> --}}

        {{-- Seksi Fasilitas Udara --}}
        <div class="facility-category" data-aos="fade-up" data-aos-delay="100">
            <h3 class="category-title">Fasilitas Sisi Udara</h3>
            <div class="row g-4">
                @foreach($facilities['udara'] as $facility)
                <div class="col-lg-3 col-md-6">
                    <div class="facility-card">
                        <div class="facility-icon"><i class="bi {{ $facility['icon'] }}"></i></div>
                        <h4 class="facility-name">{{ $facility['name'] }}</h4>
                        <ul class="facility-details">
                            @foreach($facility['details'] as $detail)
                            <li>{{ $detail }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Seksi Fasilitas Darat --}}
        <div class="facility-category" data-aos="fade-up" data-aos-delay="200">
            <h3 class="category-title">Fasilitas Sisi Darat</h3>
            <div class="row g-4">
                @foreach($facilities['darat'] as $facility)
                <div class="col-lg-3 col-md-6">
                    <div class="facility-card">
                        <div class="facility-icon"><i class="bi {{ $facility['icon'] }}"></i></div>
                        <h4 class="facility-name">{{ $facility['name'] }}</h4>
                        <ul class="facility-details">
                            @foreach($facility['details'] as $detail)
                            <li>{{ $detail }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Seksi Fasilitas Umum --}}
        <div class="facility-category" data-aos="fade-up" data-aos-delay="300">
            <h3 class="category-title">Fasilitas Umum</h3>
            <div class="row g-4">
                 @foreach($facilities['umum'] as $facility)
                <div class="col-lg-3 col-md-6">
                    <div class="facility-card">
                        <div class="facility-icon"><i class="bi {{ $facility['icon'] }}"></i></div>
                        <h4 class="facility-name">{{ $facility['name'] }}</h4>
                        <ul class="facility-details">
                            @foreach($facility['details'] as $detail)
                            <li>{{ $detail }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>
@endsection
