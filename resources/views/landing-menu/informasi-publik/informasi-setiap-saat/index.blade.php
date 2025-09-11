@extends('layouts_landing.landing_app')

@section('title', 'Informasi Setiap Saat - Bandara APT Pranoto')

@push('page-styles')
    {{-- Kita akan menggunakan CSS yang sama dengan Regulasi PPID untuk konsistensi --}}
    <link href="{{ asset('assets_landing/css/regulasi-ppid.css') }}" rel="stylesheet">
@endpush

@section('content')
{{-- PERBAIKAN: Tambahkan class 'light-background' di sini --}}
<section id="informasi-setiap-saat" class="section-modern regulasi-ppid pt-6 light-background">
    
    <div class="container">
        
        <div class="container section-title" data-aos="fade-up">
            {{-- PERBAIKAN: Ubah H2 agar konsisten --}}
            <h2>PPID</h2>
            <p><span>Informasi Setiap Saat</span> <span class="description-title">Bandara A.P.T. Pranoto</span></p>
        </div>
        
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
             {{-- PERBAIKAN: Pindahkan deskripsi ke dalam div col-lg-10 --}}
            <div class="col-lg-10">
                <p class="text-center text-muted mb-5">
                    Informasi yang wajib disediakan oleh Badan Publik dan siap tersedia untuk dapat diakses oleh publik tanpa melalui permohonan.
                </p>

                @if($informationGroups->isEmpty())
                    <div class="alert alert-info text-center">
                        Saat ini belum ada informasi yang tersedia.
                    </div>
                @else
                    <div class="accordion accordion-flush accordion-ppid" id="informationAccordion">
                        @foreach($informationGroups as $category => $informations)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ \Str::slug($category) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Str::slug($category) }}" aria-expanded="false" aria-controls="collapse-{{ \Str::slug($category) }}">
                                    <i class="bi bi-folder2-open me-3"></i>
                                    {{ $category }}
                                </button>
                            </h2>
                            <div id="collapse-{{ \Str::slug($category) }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ \Str::slug($category) }}" data-bs-parent="#informationAccordion">
                                <div class="accordion-body">
                                    <ul class="document-list">
                                        @forelse ($informations as $info)
                                        <li class="document-item">
                                            <div class="document-info">
                                                <i class="bi bi-file-earmark-text-fill"></i>
                                                <div>
                                                    <p class="doc-title">{{ $info->title }}</p>
                                                    @if($info->published_date)
                                                    <span class="doc-date">Dipublikasikan: {{ $info->published_date->translatedFormat('d F Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="doc-action">
                                                <a href="{{ $info->document_link }}" target="_blank" class="btn-view-doc">
                                                    Lihat <i class="bi bi-box-arrow-up-right"></i>
                                                </a>

                                            </div>
                                        </li>
                                        @empty
                                        <li class="text-muted">Tidak ada dokumen dalam kategori ini.</li>
                                        @endforelse
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

