@extends('layouts_landing.landing_app')

@section('title', $news->title)
@section('description', Str::limit(strip_tags($news->content), 160))

@push('page-styles')
    <link href="{{ asset('assets_landing/css/berita-detail.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- 1. Scroll Progress Bar --}}
<div id="scroll-progress-bar"></div>

<main class="main">
    <section id="news-detail" class="news-detail section">
        <div class="container">
            <div class="row">
                {{-- 2. Konten Utama Berita (Kolom Kiri) --}}
                <div class="col-lg-8" data-aos="fade-up">
                    <article class="article-content">
                        {{-- Header Artikel --}}
                        <header class="article-header">
                            <h1 class="article-title">{{ $news->title }}</h1>
                            <div class="article-meta">
                                <span><i class="bi bi-person-fill"></i> Oleh Admin</span>
                                <span class="mx-2">|</span>
                                <span><i class="bi bi-calendar3"></i> {{ $news->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </header>

                        {{-- Gambar Utama --}}
                        <figure class="article-figure my-4">
                            <img src="{{ asset('uploads/'.$news->image) }}" class="img-fluid rounded" alt="{{ $news->title }}">
                        </figure>

                        {{-- Isi Konten Berita --}}
                        <div class="article-body">
                            {!! $news->content !!}
                        </div>
                    </article>
                </div>

                {{-- 3. Sidebar (Kolom Kanan) --}}
                <div class="col-lg-4" data-aos="fade-left" data-aos-delay="100">
                    <div class="sidebar">
                        {{-- Widget Berbagi Sosial --}}
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Bagikan Berita</h3>
                            <div class="social-share">
                                <a href="#" class="social-share-btn facebook" id="share-facebook" target="_blank" title="Bagikan ke Facebook"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="social-share-btn twitter" id="share-twitter" target="_blank" title="Bagikan ke X/Twitter"><i class="bi bi-twitter-x"></i></a>
                                <a href="#" class="social-share-btn whatsapp" id="share-whatsapp" target="_blank" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp"></i></a>
                                <a href="#" class="social-share-btn telegram" id="share-telegram" target="_blank" title="Bagikan ke Telegram"><i class="bi bi-telegram"></i></a>
                            </div>
                        </div>

                        {{-- Anda bisa menambahkan widget lain di sini nanti, misal: Berita Populer --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Berita Terkait --}}
    @if($relatedNews->isNotEmpty())
    <section id="related-news" class="related-news section light-background">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h3>Berita Terkait</h3>
            </div>
            <div class="row gy-4">
                @foreach($relatedNews as $related)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <a href="{{ route('news.show', $related->slug) }}" class="latest-news-card">
                        <div class="card-img-container">
                            <img src="{{ asset('uploads/' . $related->image) }}" class="img-fluid" alt="{{ $related->title }}">
                        </div>
                        <div class="card-content">
                            <span class="card-date">{{ $related->created_at->translatedFormat('d M Y') }}</span>
                            <h4 class="card-title">{{ $related->title }}</h4>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</main>
@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Logika untuk Scroll Progress Bar
    const progressBar = document.getElementById('scroll-progress-bar');
    window.addEventListener('scroll', () => {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollProgress = (scrollTop / scrollHeight) * 100;
        progressBar.style.width = scrollProgress + '%';
    });

    // 2. Logika untuk Tombol Berbagi Sosial
    const pageUrl = window.location.href;
    const pageTitle = document.title;
    const encodedUrl = encodeURIComponent(pageUrl);
    const encodedTitle = encodeURIComponent(pageTitle);

    document.getElementById('share-facebook').href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
    document.getElementById('share-twitter').href = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`;
    document.getElementById('share-whatsapp').href = `https://api.whatsapp.com/send?text=${encodedTitle} ${encodedUrl}`;
    document.getElementById('share-telegram').href = `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`;
});
</script>
@endpush