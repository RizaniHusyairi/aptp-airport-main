@extends('layouts_landing.landing_app')

@section('title', 'Berita - Bandara APT Pranoto')

@section('content')
  <section id="about" class="about section pt-6 light-background">
    <div class="container section-title" data-aos="fade-up">
      <h2>Informasi<br></h2>
      <p><span>Berita Seputar</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
    </div>
    
    <div class="container ">
      <div class="row justify-content-around">
          <div class="col-lg-9 mb-3">
            <swiper-container class="newsFirstSwiper" style="height: 500px;" autoplay-delay="2500" autoplay-disable-on-interaction="false" init="false" style="height: 100%;">
              @forelse ($topHeadlines as $index => $headline)
              <swiper-slide>
                {{-- <div class="card news-card shadow" style="background-image: url('{{ asset('uploads/'.$headline->image) ?? asset('/assets_landing/img/bandara/APT04947.JPG') }}');"> --}}
                <div class="card news-card shadow" style="background-image: url('{{ asset('/assets_landing/img/bandara/APT04947.JPG') }}');">
                  <a href="{{ route('news.show', $headline->slug) }}" class="text-decoration-none text-white">
                    <div class="card-overlay">
                      <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title my-auto mx-3">{{ $headline->title }}</h5>
                        <p class="card-text mt-auto">{!! Str::limit($headline->content, 150) !!}</p>
                        <div class="utility-info">
                          <ul class="utility-list">
                            <li class="date">{{ $headline->created_at->format('d.m.Y') }}</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </swiper-slide>
            @empty
              <swiper-slide>
                <div class="card news-card shadow">
                  <div class="card-body text-center">
                    <p>Tidak ada berita headline saat ini.</p>
                  </div>
                </div>
              </swiper-slide>
            @endforelse
              
            </swiper-container>
          </div>
          <div class="col-lg-3">
            <h2>Berita Terbaru</h2>

            <div class="news-aside" id="news-aside" aria-label="Daftar Berita Terbaru">
              @forelse ($nextHeadlines as $index => $news)
                  <a href="{{ route('news.show', $news->slug) }}" class="news-chip text-decoration-none" aria-label="Baca: {{ $news->title }}">
                    <figure class="news-chip-media">
                        <img
                            {{-- src="{{ asset('uploads/'.$news->image) ?? asset('/assets_landing/img/bandara/DJI_0038.JPG') }}" --}}
                            src="{{ asset('/assets_landing/img/bandara/DJI_0038.JPG') }}"
                            alt="Gambar {{ $news->title }}"
                            loading="lazy"
                            size="(max-width: 992px) 280px, 260px"
                        />
                        <figcaption class="visually-hidden">{{ $news->title }}</figcaption>

                        <div class="news-chip-meta">
                            {{-- Judul berita sekarang di sini --}}
                            <h6 class="news-chip-title text-center">{{ $news->title }}</h6>
                            
                            {{-- <div class="news-chip-details">
                                <span class="news-chip-source">
                                    <i class="bi bi-newspaper"></i>
                                    APT Pranoto
                                </span>
                                <span class="news-chip-dot">·</span>
                                <time class="news-chip-time">{{ $news->created_at->diffForHumans() }}</time>
                            </div>
                        </div> --}}
                    </figure>
                    
                    {{-- div.news-chip-body telah dihapus --}}
                </a>
              
              @empty
                <div class="text-center text-muted py-3">Tidak ada berita headline tambahan saat ini.</div>
              @endforelse
            </div>
          </div>

        </div>
        <div class="container-more mt-3">
          <h3>Berita Lainnya</h3>

          <div class="news-more-grid" id="other-news-list">
            @forelse ($otherNews as $news)
              <a href="{{ route('news.show', $news->slug) }}" class="news-tile" aria-label="Baca: {{ $news->title }}">
                <figure class="news-tile-media">
                  <img
                    {{-- src="{{ asset('uploads/'.$news->image) ?? asset('/assets_landing/img/bandara/APT_1682.JPG') }}" --}}
                    src="{{ asset('/assets_landing/img/bandara/APT_1682.JPG') }}"
                    alt="Gambar: {{ $news->title }}"
                    loading="lazy"
                  >
                </figure>
                <div class="news-tile-body">
                  <div class="news-tile-meta">
                    <i class="bi bi-calendar3"></i>
                    <time datetime="{{ $news->created_at->toDateString() }}">
                      {{ $news->created_at->translatedFormat('d M Y') }}
                    </time>
                  </div>
                  <h5 class="news-tile-title">{{ $news->title }}</h5>
                  <p class="news-tile-excerpt">
                    {!! Str::limit(strip_tags($news->content), 140) !!}
                  </p>
                  <span class="news-tile-cta">
                    Baca selengkapnya <i class="bi bi-arrow-right"></i>
                  </span>
                </div>
              </a>
            @empty
              <div class="text-center py-4 text-muted">Tidak ada berita lainnya saat ini.</div>
            @endforelse
          </div>

        </div>
    </div>
  </section>
@endsection

@push('page-styles')
  <link href="{{ asset('assets_landing/css/berita-tes.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
  <script src="{{ asset('assets_landing/js/berita.js') }}"></script>
@endpush