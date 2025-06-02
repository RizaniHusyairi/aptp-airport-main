@extends('layouts_landing.landing_app')

@section('title', 'Berita - Bandara APT Pranoto')

@section('content')
  <section id="about" class="about section pt-6">
    <div class="container section-title" data-aos="fade-up">
      <h2>Informasi<br></h2>
      <p><span>Berita Seputar</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
    </div>
    
    <div class="container light-background">
      <div class="row justify-content-around">
          <div class="col-lg-9 mb-3">
            <swiper-container class="newsFirstSwiper" autoplay-delay="2500" autoplay-disable-on-interaction="false" init="false" style="height: 100%;">
              @forelse ($topHeadlines as $index => $headline)
              <swiper-slide>
                <div class="card news-card shadow" style="background-image: url('{{ asset($headline->image ?? '/assets/img/bandara/_MG_0131.JPG') }}');">
                  <a href="{{ route('news.show', $headline->slug) }}" class="text-decoration-none text-white">
                    <div class="card-overlay">
                      <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title my-auto mx-3">{{ $headline->title }}</h5>
                        <p class="card-text mt-auto">{{ Str::limit($headline->content, 150) }}</p>
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
            <div class="news-list p-1">
              <swiper-container class="news-swiper" autoplay-delay="5000" autoplay-disable-on-interaction="false" mousewheel="true" free-mode="true" navigation="false" scrollbar="true" slides-per-view="2" space-between="10" direction="horizontal" breakpoints='{
                "768": {"slidesPerView": 2, "spaceBetween": 15, "direction": "horizontal"},
                "992": {"slidesPerView": 3, "spaceBetween": 0, "direction": "vertical", "navigation": "false"}
              }'>
              @forelse ($nextHeadlines as $index => $news)
                <swiper-slide>
                  <div class="card news-card recomd shadow">
                    <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none text-white">
                      <img class="card-overlay-img" src="{{ asset('upload/'.$news->image ?? '/assets/img/bandara/DJI_0038.JPG') }}" alt="foto">
                      <div class="card-overlay-recomd">
                        <div class="card-body d-flex flex-column justify-content-end p-1">
                          <h5 class="card-title-recomd mx-3">{{ $news->title }}</h5>
                        </div>
                      </div>
                    </a>
                  </div>
                </swiper-slide>
              @empty
                <swiper-slide>
                  <div class="card news-card recomd shadow">
                    <div class="card-body text-center">
                      <p>Tidak ada berita headline tambahan saat ini.</p>
                    </div>
                  </div>
                </swiper-slide>
              @endforelse
                
              </swiper-container>
            </div>
          </div>
        </div>
        <div class="container-more mt-3">
          <h3>Berita Lainnya</h3>

          <div class="news-more">
            @forelse ($otherNews as $index => $news)
            <div class="card-news-more shadow row rounded">
              <div class="col-3 p-0">
                <img src="{{ asset($news->image ?? '/assets/img/bandara/APT_1682.JPG') }}" alt="foto" class="img-news">
              </div>
              <div class="col-9 p-3">
                <h5 class="news-more-title">{{ $news->title }}</h5>
                <p class="news-more-content">{{ Str::limit($news->content, 150) }}</p>
              </div>
            </div>
          @empty
            <div class="text-center">
              <p>Tidak ada berita lainnya saat ini.</p>
            </div>
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