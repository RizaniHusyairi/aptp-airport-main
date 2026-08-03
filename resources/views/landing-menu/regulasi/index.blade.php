@extends('layouts_landing.landing_app')

@section('title', 'Regulasi Surat ' . ucfirst($type) . ' - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/regulasi.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="regulasi-page" class="section-modern regulasi-page pt-6 light-background">
    
    <div class="container">
        
        <div class="container section-title" data-aos="fade-up">
            <h2>Regulasi</h2>
            <p><span>Surat {{ ucfirst($type) }}</span> <span class="description-title">Bandar Udara APT Pranoto</span></p>
        </div>

        <!-- Search Bar -->
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="doc-search" class="search-input" placeholder="Cari berdasarkan judul atau nomor surat...">
                </div>
            </div>
        </div>
        
        <!-- Document List -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div id="document-list">
                    @forelse ($letters as $letter)
                    <div class="document-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="doc-meta">
                            <i class="bi bi-file-earmark-text-fill doc-icon"></i>
                            <span class="doc-date">{{ $letter->issue_date->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="doc-content">
                            <h5 class="doc-title">{{ $letter->title }}</h5>
                            <p class="doc-number">Nomor: {{ $letter->number }}</p>
                        </div>
                        <div class="doc-action">
                            {{-- file_url bernilai null bila berkasnya tidak ada di disk --}}
                            <a href="{{ $letter->file_url }}" target="_blank" rel="noopener" class="btn-view-doc">
                                Lihat Dokumen
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <p class="text-muted fs-5">Saat ini belum ada Surat {{ ucfirst($type) }} yang tersedia.</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pesan jika tidak ada hasil pencarian -->
                <div id="no-results-message">
                    <p>Maaf, dokumen yang Anda cari tidak ditemukan.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('doc-search');
    if (searchInput) {
        const listContainer = document.getElementById('document-list');
        const allItems = listContainer.querySelectorAll('.document-item');
        const noResultsMessage = document.getElementById('no-results-message');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleItems = 0;

            allItems.forEach(item => {
                const title = item.querySelector('.doc-title').textContent.toLowerCase();
                const number = item.querySelector('.doc-number').textContent.toLowerCase();

                if (title.includes(searchTerm) || number.includes(searchTerm)) {
                    item.style.display = 'flex';
                    visibleItems++;
                } else {
                    item.style.display = 'none';
                }
            });

            noResultsMessage.style.display = (visibleItems === 0 && allItems.length > 0) ? 'block' : 'none';
        });
    }
});
</script>
@endpush
