@extends('layouts_landing.landing_app')

@section('title', 'Informasi Berkala - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/informasi-berkala.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="informasi-berkala" class="section-modern informasi-berkala pt-6">
    
    <div class="container">
        
        <div class="container section-title" data-aos="fade-up">
            <h2>Informasi Publik</h2>
            <p><span>Informasi Berkala</span> <span class="description-title">Bandara A.P.T. Pranoto</span></p>
        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <p class="text-center text-muted mb-5">
                    Informasi yang wajib disediakan dan diumumkan secara berkala adalah informasi yang telah dikuasai dan didokumentasikan oleh Badan Publik untuk diumumkan secara teratur dan rutin tanpa adanya permintaan.
                </p>

                @if($documentCategories->isEmpty())
                    <div class="alert alert-info text-center">
                        Saat ini belum ada dokumen informasi berkala yang tersedia.
                    </div>
                @else
                    <div class="accordion" id="informasiBerkalaAccordion">
                        @foreach($documentCategories as $category => $documents)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ \Str::slug($category) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Str::slug($category) }}" aria-expanded="false" aria-controls="collapse-{{ \Str::slug($category) }}">
                                    <i class="bi bi-folder2-open me-2"></i>
                                    {{ $category }}
                                </button>
                            </h2>
                            <div id="collapse-{{ \Str::slug($category) }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ \Str::slug($category) }}" data-bs-parent="#informasiBerkalaAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Dokumen</th>
                                                    <th scope="col" class="text-center">Tanggal Terbit</th>
                                                    <th scope="col" class="text-end">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($documents as $doc)
                                                <tr>
                                                    <td>{{ $doc->title }}</td>
                                                    <td class="text-center">{{ $doc->published_date->translatedFormat('d M Y') }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ Storage::url($doc->document_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-download me-1"></i> Unduh
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Tidak ada dokumen dalam kategori ini.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
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
