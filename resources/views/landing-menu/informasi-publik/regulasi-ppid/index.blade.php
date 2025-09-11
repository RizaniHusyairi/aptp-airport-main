@extends('layouts_landing.landing_app')

@section('title', 'Regulasi PPID - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/regulasi-ppid.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="regulasi-ppid" class="section-modern regulasi-ppid pt-6 light-background">
    
    <div class="container">
        
        <div class="container section-title" data-aos="fade-up">
            <h2>PPID</h2>
            <p><span>Regulasi</span> <span class="description-title">Keterbukaan Informasi Publik</span></p>
        </div>
        
        <div class="row justify-content-center">
            <p class="text-muted small mb-5 text-center">Dasar hukum dan peraturan terkait pelaksanaan keterbukaan informasi publik di lingkungan bandara.</p>
            <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                @if($regulationCategories->isEmpty())
                    <div class="text-center py-5">
                        <p class="text-muted fs-5">Saat ini belum ada dokumen regulasi yang tersedia.</p>
                    </div>
                @else
                    <div class="accordion accordion-flush accordion-ppid" id="accordionRegulasiPpid">
                        @foreach($regulationCategories as $category => $regulations)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $loop->iteration }}" aria-expanded="false" aria-controls="collapse-{{ $loop->iteration }}">
                                    <i class="bi bi-journal-bookmark-fill me-3"></i>
                                    {{ $category }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $loop->iteration }}" data-bs-parent="#accordionRegulasiPpid">
                                <div class="accordion-body">
                                    <ul class="document-list">
                                        @foreach($regulations as $reg)
                                        <li class="document-item">
                                            <div class="document-info">
                                                <h5 class="doc-title">{{ $reg->title }}</h5>
                                                @if($reg->published_date)
                                                <p class="doc-date">Dipublikasikan: {{ $reg->published_date->translatedFormat('d F Y') }}</p>
                                                @endif
                                            </div>
                                            <div class="doc-action">
                                                <a href="{{ $reg->document_link }}" target="_blank" class="btn-view-doc">
                                                    Lihat Dokumen <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection
