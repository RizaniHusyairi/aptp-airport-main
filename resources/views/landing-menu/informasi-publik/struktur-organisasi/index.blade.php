@extends('layouts_landing.landing_app')

@section('title', 'Laporan Keuangan - Bandara APT Pranoto')

@section('content')

<!-- About Section -->
<section id="about" class="about section pt-6">

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Informasi Publik<br></h2>
    <p><span>Struktur Organisasi</span> <span class="description-title"> A.P.T. Pranoto Samarinda</span></p>
  </div><!-- End Section Title -->

  <div class="container-fluid light-background" >
    <div class="container">

        <!-- Bagian Gambar Struktur Organisasi -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                <div class="text-center mb-4" data-aos="fade-up">
                    <img src="{{ asset('assets_landing/img/profil/struktur-organisasi.jpg') }}" alt="Struktur Organisasi Bandara A.P.T. Pranoto" class="img-fluid rounded shadow-lg struktur-img" id="struktur-image">
                    <a href="{{ asset('assets_landing/img/profil/struktur-organisasi.jpg') }}" class="btn btn-struktur mt-3 glightbox" data-title="Struktur Organisasi Bandara A.P.T. Pranoto" data-desc="Detail struktur organisasi Bandara A.P.T. Pranoto Samarinda">Lihat Detail</a>
                </div>
                <p class="text-center text-white">Klik tombol di atas untuk melihat struktur organisasi secara lebih jelas.</p>
            </div>
        </div>
    </div>

  </div>
  

</section><!-- /About Section -->
<!-- /news Section -->
@endsection
    
@push('page-styles')
  <link href="{{ asset('assets_landing/css/struktur.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
  <script src="{{ asset('assets_landing/js/assets/js/struktur.js') }}"></script>
@endpush
