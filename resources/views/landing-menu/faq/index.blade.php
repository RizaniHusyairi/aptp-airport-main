@extends('layouts_landing.landing_app')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan | Bandara APT Pranoto')
@section('description', 'Jawaban atas pertanyaan yang sering diajukan seputar penerbangan, fasilitas, layanan, perizinan, dan informasi publik di Bandar Udara APT Pranoto Samarinda.')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/faq.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
    <script src="{{ asset('assets_landing/js/faq.js') }}"></script>
@endpush

@if($faqs->isNotEmpty())
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq->question,
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $faq->plain_answer,
        ],
    ])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endif

@section('content')
<section id="faq-page" class="section pt-6 light-background">

    <div class="container">

        <div class="container section-title" data-aos="fade-up">
            <h2>FAQ</h2>
            <p><span>Pertanyaan yang Sering Diajukan</span> <span class="description-title">Bandar Udara APT Pranoto</span></p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">

                @if($faqs->isEmpty())
                    <div class="alert alert-info text-center">
                        Saat ini belum ada pertanyaan yang tersedia.
                    </div>
                @else
                    <div class="faq-toolbar" data-aos="fade-up" data-aos-delay="100">
                        <div class="faq-search-wrap">
                            <i class="bi bi-search faq-search-icon"></i>
                            <input type="text" id="faq-search" class="faq-search"
                                   placeholder="Cari pertanyaan atau kata kunci..."
                                   autocomplete="off" aria-label="Cari pertanyaan">
                        </div>

                        <div class="faq-pills">
                            <button type="button" class="faq-pill active" data-category="all">Semua</button>
                            @foreach($categories as $category)
                                <button type="button" class="faq-pill" data-category="{{ $category }}">{{ $category }}</button>
                            @endforeach
                        </div>

                        <p class="faq-count" id="faq-count">Menampilkan {{ $faqs->count() }} pertanyaan</p>
                    </div>

                    @include('landing-menu.partials.faq-accordion', [
                        'faqs' => $faqs,
                        'accordionId' => 'faq-accordion',
                    ])

                    <div class="faq-no-results" id="faq-no-results">
                        <i class="bi bi-search"></i>
                        <p class="mb-0">Tidak ada pertanyaan yang cocok dengan pencarian Anda.</p>
                    </div>
                @endif

                {{-- CTA: pertanyaan yang belum terjawab --}}
                <div class="faq-cta row align-items-center g-4" data-aos="fade-up">
                    <div class="col-lg-7">
                        <h2 class="faq-cta-title">Masih Ada Pertanyaan?</h2>
                        <p class="faq-cta-text mb-0">
                            Hubungi kami langsung, atau ajukan permohonan informasi publik melalui layanan PPID.
                        </p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <a href="mailto:mail.aptpranotoairport@gmail.com" class="faq-cta-btn">
                            <i class="bi bi-envelope me-1"></i> Email Kami
                        </a>
                        <a href="tel:+62811551944" class="faq-cta-btn">
                            <i class="bi bi-telephone me-1"></i> +62 811 551 944
                        </a>
                        <a href="{{ route('layanan.show', 'informasi-publik') }}" class="faq-cta-btn">
                            <i class="bi bi-file-earmark-text me-1"></i> Ajukan Informasi Publik
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
