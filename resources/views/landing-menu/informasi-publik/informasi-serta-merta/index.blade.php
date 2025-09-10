@extends('layouts_landing.landing_app')

@section('title', 'Informasi Serta Merta - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/informasi-serta-merta.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="informasi-serta-merta" class="section-modern informasi-serta-merta pt-6">

    <div class="container">
        
        <div class="container section-title" data-aos="fade-up">
            <h2>Informasi Publik</h2>
            <p><span>Informasi Serta Merta</span> <span class="description-title">Bandara A.P.T. Pranoto</span></p>
        </div>
        
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-8">
                <p class="text-muted small text-center mb-5">Informasi yang wajib diumumkan secara serta merta yaitu informasi yang dapat mengancam hajat hidup orang banyak dan ketertiban umum.</p>
                
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="info-search" class="search-input" placeholder="Cari informasi...">
                </div>
            </div>
        </div>

        <div class="row" id="info-card-container">
            @forelse ($informations as $info)
            <div class="col-lg-12 info-card-item" data-aos="fade-up" data-aos-delay="{{ 100 + ($loop->iteration * 50) }}">
                <div class="info-card position-relative">
                    <div class="info-header">
                        <h3 class="uraian-title">{{ $info->uraian }}</h3>
                    </div>
                    <div class="info-body">
                        <p class="keterangan-text">{{ Str::limit($info->keterangan, 150) }}</p>
                    </div>
                    <div class="info-footer">
                        <a href="{{ $info->link_url }}" target="_blank" class="btn-link-detail">
                            {{ $info->link_text }} <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Saat ini belum ada informasi serta merta yang tersedia.</p>
            </div>
            @endforelse
        </div>

        <div id="no-results-message">
            <p>Maaf, informasi yang Anda cari tidak ditemukan.</p>
        </div>

    </div>
</section>
@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('info-search');
    const infoCardContainer = document.getElementById('info-card-container');
    const allCards = infoCardContainer.querySelectorAll('.info-card-item');
    const noResultsMessage = document.getElementById('no-results-message');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCards = 0;

        allCards.forEach(card => {
            const uraian = card.querySelector('.uraian-title').textContent.toLowerCase();
            const keterangan = card.querySelector('.keterangan-text').textContent.toLowerCase();

            if (uraian.includes(searchTerm) || keterangan.includes(searchTerm)) {
                card.style.display = '';
                visibleCards++;
            } else {
                card.style.display = 'none';
            }
        });

        // Tampilkan atau sembunyikan pesan 'tidak ditemukan'
        if (visibleCards === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    });
});
</script>
@endpush