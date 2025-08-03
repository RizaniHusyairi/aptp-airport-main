@extends('layouts_landing.landing_app')

@section('title', $destination['name'] . ' - Wisata Samarinda')
@push('page-styles')
  <link href="{{ asset('assets_landing/css/pariwisata.css') }}" rel="stylesheet">
@endpush

@section('content')



<section id="pariwisata-detail" class="pariwisata-detail section pt-6">
      
    <div class="container section-title" data-aos="fade-up">
        <h2>Pariwisata</h2>
        <p> <span class="description-title">{{ $destination->name }}</span></p>
        
    </div>

    <div class="container">

      <div class="row gy-4">

        <div class="col-lg-8" data-aos="fade-up">

          <div class="pariwisata-gallery">
            <swiper-container class="gallery-slider" navigation="true" pagination="true" loop="true" centered-slides="true" autoplay-delay="3000">
              @foreach ($destination['gallery'] as $image)
              <swiper-slide>
                <img src="{{ asset('uploads/'.$image) }}" alt="Foto {{ $destination['name'] }}">
              </swiper-slide>
              @endforeach
            </swiper-container>
          </div>

          <div class="pariwisata-description mt-4">
            <h2>Deskripsi</h2>
            <p>
              {{ $destination['description'] }}
            </p>
          </div>
        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="pariwisata-info">
            <h3>Informasi Destinasi</h3>
            <ul>
              <li><strong>Kategori:</strong> <span>{{ $destination['category'] }}</span></li>
              <li><strong>Alamat:</strong> <span>{{ $destination['address'] }}</span></li>
            </ul>

            <div class="mt-3">
              <a href="{{ route('pariwisata.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
            </div>
          </div>
          
          <div class="pariwisata-map mt-4">
            <h3>Peta Lokasi</h3>
            <iframe src="{{ $destination['gmaps_url'] }}" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen="" loading="lazy"></iframe>
          </div>
        </div>

      </div>

    </div>

  </section>


@endsection

@push('page-scripts')
  @endpush