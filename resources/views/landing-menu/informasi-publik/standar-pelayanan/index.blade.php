@extends('layouts_landing.landing_app')

@section('title', 'Standar Pelayanan - Bandara APT Pranoto')
@section('description', 'Dokumen Standar Pelayanan, Maklumat Pelayanan, dan hasil Survei Kepuasan Masyarakat Bandar Udara APT Pranoto Samarinda.')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/informasi-berkala.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_landing/css/skm.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="standar-pelayanan" class="section-modern standar-pelayanan pt-6 light-background">

    <div class="container">

        <div class="container section-title" data-aos="fade-up">
            <h2>PPID</h2>
            <p><span>Standar Pelayanan</span> <span class="description-title">Bandar Udara APT Pranoto</span></p>
        </div>

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <p class="text-center text-muted mb-5">
                    Sesuai Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik, Bandar Udara APT Pranoto menyusun dan memublikasikan Standar Pelayanan sebagai tolok ukur penyelenggaraan pelayanan, Maklumat Pelayanan sebagai pernyataan kesanggupan, serta hasil Survei Kepuasan Masyarakat sebagai bahan evaluasi berkelanjutan.
                </p>

                @if(skmSetting()['active'])
                    <div class="skm-cta-card skm-cta-inline row align-items-center g-4">
                        <div class="col-lg-8">
                            <h2 class="skm-cta-title">Ikut Serta dalam Survei Kepuasan Masyarakat</h2>
                            <p class="skm-cta-text mb-0">Penilaian Anda kami olah menjadi Indeks Kepuasan Masyarakat, dan laporan hasilnya diterbitkan pada halaman ini.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="{{ skmSetting()['url'] }}" target="_blank" rel="noopener" class="skm-cta-btn">
                                {{ skmSetting()['label'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if($documentGroups->isEmpty())
                    <div class="alert alert-info text-center">
                        Saat ini belum ada dokumen standar pelayanan yang tersedia.
                    </div>
                @else
                    <div class="accordion" id="standarPelayananAccordion">
                        @foreach($documentGroups as $type => $documents)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ \Str::slug($type) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Str::slug($type) }}" aria-expanded="false" aria-controls="collapse-{{ \Str::slug($type) }}">
                                    <i class="bi bi-folder2-open me-2"></i>
                                    {{ $type }}
                                </button>
                            </h2>
                            <div id="collapse-{{ \Str::slug($type) }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ \Str::slug($type) }}" data-bs-parent="#standarPelayananAccordion">
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
                                                @foreach ($documents as $doc)
                                                <tr>
                                                    <td>
                                                        {{ $doc->title }}
                                                        @if($doc->document_number)
                                                            <br><small class="text-muted">Nomor: {{ $doc->document_number }}</small>
                                                        @endif
                                                        @if($doc->description)
                                                            <br><small class="text-muted">{{ $doc->description }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $doc->published_date->translatedFormat('d M Y') }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ $doc->document_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-primary">
                                                            @if($doc->is_uploaded)
                                                                <i class="bi bi-download me-1"></i> Unduh
                                                            @else
                                                                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Dokumen
                                                            @endif
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
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
