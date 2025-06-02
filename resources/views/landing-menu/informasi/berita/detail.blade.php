@extends('layouts_landing.landing_app')

@section('title', $news->title)
@section('description', Str::limit($news->content, 160))
@section('keywords', 'bandara, apt pranoto, samarinda, berita, ' . Str::slug($news->title))

@section('content')
  <section id="news-detail" class="news-detail section pt-6">
    <div class="container section-title" data-aos="fade-up">
        <h2>Berita</h2>
        <p> <span class="description-title">{{ $news->title }}</span></p>
        
    </div>
    <div class="container section-title" data-aos="fade-up">
    </div>
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset($news->image) }}" class="img-fluid" alt="{{ $news->title }}">
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="content">
                    <p>{!! nl2br(e($news->content)) !!}</p>
                    <p class="text-muted">Diterbitkan pada: {{ $news->created_at->format('d M Y') }}</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
  </section>
@endsection