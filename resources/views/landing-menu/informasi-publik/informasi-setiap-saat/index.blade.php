@extends('layouts_landing.landing_app')

@section('title', 'Informasi Setiap Saat - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/informasi-setiap-saat.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="informasi-setiap-saat" class="section-modern informasi-setiap-saat pt-6">

    <div class="container">

        <div class="container section-title" data-aos="fade-up">
            <h2>Informasi Publik</h2>
            <p><span>Informasi Setiap Saat</span> <span class="description-title">Bandara A.P.T. Pranoto</span></p>
        </div>
        
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <p class="text-muted small text-center mb-5">Informasi yang wajib disediakan oleh Badan Publik dan siap tersedia untuk dapat diakses oleh publik tanpa melalui permohonan.</p>
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="info-search" class="search-input" placeholder="Cari berdasarkan judul atau uraian informasi...">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="list-container" id="info-list-container">
                    @forelse ($informations as $info)
                    <div class="info-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="item-number"></div>
                        <div class="item-content">
                            <h4 class="info-title">{{ $info->title }}</h4>
                            <p class="info-date"><i class="bi bi-calendar3"></i> Dipublikasikan pada: {{ $info->published_date->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="item-action">
                            {{-- UBAH BARIS INI --}}
                            <a href="{{ $info->document_path }}" target="_blank" class="btn-view-doc">
                                <i class="bi bi-box-arrow-up-right me-2"></i>Lihat
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">Saat ini belum ada informasi yang tersedia.</p>
                    </div>
                    @endforelse
                </div>

                <div id="no-results-message">
                    <p>Maaf, informasi yang Anda cari tidak ditemukan.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('info-search');
    const listContainer = document.getElementById('info-list-container');
    const allItems = listContainer.querySelectorAll('.info-item');
    const noResultsMessage = document.getElementById('no-results-message');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleItems = 0;

        allItems.forEach(item => {
            const title = item.querySelector('.info-title').textContent.toLowerCase();

            if (title.includes(searchTerm)) {
                item.style.display = 'flex'; // atau 'block' tergantung default
                visibleItems++;
            } else {
                item.style.display = 'none';
            }
        });

        noResultsMessage.style.display = (visibleItems === 0 && allItems.length > 0) ? 'block' : 'none';
    });
});
</script>
@endpush