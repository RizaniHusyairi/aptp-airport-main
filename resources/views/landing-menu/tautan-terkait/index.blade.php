@extends('layouts_landing.landing_app')

@section('title', 'Tautan Terkait - Bandara APT Pranoto')
@section('description', 'Kumpulan tautan portal resmi pelayanan publik dan aplikasi kedinasan terkait Bandar Udara APT Pranoto Samarinda.')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/tautan-terkait.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="tautan-terkait-page" class="section-modern pt-6 light-background">

    <div class="container">

        <div class="container section-title" data-aos="fade-up">
            <h2>Tautan Terkait</h2>
            <p><span>Portal Resmi</span> <span class="description-title">Bandar Udara APT Pranoto</span></p>
        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <p class="text-center text-muted mb-5">
                    Berikut portal resmi pemerintah yang berkaitan dengan penyelenggaraan pelayanan publik dan kedinasan di Bandar Udara APT Pranoto. Seluruh tautan akan terbuka di tab baru.
                </p>

                @if(empty(externalLinks()))
                    <div class="alert alert-info text-center">
                        Saat ini belum ada tautan terkait yang tersedia.
                    </div>
                @else
                    @include('landing-menu.partials.tautan-terkait-groups')
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
