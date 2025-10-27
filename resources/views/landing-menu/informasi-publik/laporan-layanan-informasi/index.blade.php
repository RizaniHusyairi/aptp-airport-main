@extends('layouts_landing.landing_app')

@section('title', 'Laporan Layanan Informasi - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/laporan-layanan-informasi.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="laporan-layanan" class="section-modern laporan-layanan light-background pt-6">

    <div class="container">

        <div class="container section-title" data-aos="fade-up">
            <h2>PPID</h2>
            <p><span>Laporan Layanan Informasi</span> <span class="description-title">Bandar Udara APT Pranoto</span></p>
        </div>
        
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <p class="text-muted small text-center mb-5">Dokumen laporan tahunan mengenai layanan informasi publik yang telah kami sediakan.</p>
                <div class="search-bar">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="report-search" class="search-input" placeholder="Cari berdasarkan judul laporan...">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div id="report-list-container">
                    @forelse ($reports as $report)
                    <div class="report-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="report-year">{{ $report->publication_year }}</div>
                        <h4 class="report-title">{{ $report->title }}</h4>
                        <div class="report-action">
                            <a href="{{ $report->document_link }}" target="_blank" class="btn-view-report">
                                <i class="bi bi-eye-fill me-2"></i>Lihat Dokumen
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5" data-aos="fade-up" data-aos-delay="200">
                        <p class="text-muted fs-5">Saat ini belum ada laporan yang tersedia.</p>
                    </div>
                    @endforelse
                </div>

                <div id="no-results-message">
                    <p>Maaf, laporan yang Anda cari tidak ditemukan.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('report-search');
    const listContainer = document.getElementById('report-list-container');
    const allItems = listContainer.querySelectorAll('.report-item');
    const noResultsMessage = document.getElementById('no-results-message');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleItems = 0;

        allItems.forEach(item => {
            const title = item.querySelector('.report-title').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                item.style.display = 'flex';
                visibleItems++;
            } else {
                item.style.display = 'none';
            }
        });

        // Tampilkan atau sembunyikan pesan 'tidak ditemukan'
        if (visibleItems === 0 && allItems.length > 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    });
});
</script>
@endpush