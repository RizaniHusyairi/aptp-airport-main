@extends('layouts_landing.landing_app')

@section('title', 'Destinasi Wisata - Bandara APT Pranoto')
@push('page-styles')
  <link href="{{ asset('assets_landing/css/pariwisata.css') }}" rel="stylesheet">
  <style>

  </style>
@endpush

@section('content')

<main class="main">

  <div class="page-title" data-aos="fade">...</div>

  <section id="pariwisata-list" class="pariwisata-list section">
    <div class="container">

      <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
        <div class="col-lg-12">
            <form id="filter-form">
                <div class="d-lg-flex gap-2">
                    <div class="search-form flex-grow-1 mb-3 mb-lg-0">
                        <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari destinasi...">
                        <button type="submit"><i class="bi bi-search"></i></button>
                    </div>
                    <div class="filter-form d-flex gap-2">
                        <select name="category" id="category-select" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="Alam" {{ request('category') == 'Alam' ? 'selected' : '' }}>Alam</option>
                            <option value="Budaya" {{ request('category') == 'Budaya' ? 'selected' : '' }}>Budaya</option>
                            <option value="Religi" {{ request('category') == 'Religi' ? 'selected' : '' }}>Religi</option>
                            <option value="Kuliner" {{ request('category') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                        </select>
                        <a href="{{ route('pariwisata.index') }}" id="reset-btn" class="btn-reset">Reset</a>
                    </div>
                </div>
            </form>
        </div>
      </div>
      <div id="destination-wrapper" style="position: relative;">
          {{-- Loading Spinner --}}
          <div class="loading-overlay">
              <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
              </div>
          </div>
          {{-- Konten akan dimuat di sini --}}
          <div id="destination-container">
            @include('landing-menu.pariwisata.partials.destination_list', ['destinations' => $destinations])
          </div>
      </div>

    </div>
  </section>

</main>

@endsection

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const searchInput = document.getElementById('search-input');
    const categorySelect = document.getElementById('category-select');
    const container = document.getElementById('destination-container');
    const wrapper = document.getElementById('destination-wrapper');
    const loadingOverlay = wrapper.querySelector('.loading-overlay');
    let searchTimeout;

    // Fungsi utama untuk mengambil data dengan AJAX
    async function fetchDestinations(url) {
        // Tampilkan loading spinner
        loadingOverlay.classList.add('visible');

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Header penting untuk dideteksi Laravel
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const html = await response.text();
            
            // Ganti konten dengan hasil baru
            container.innerHTML = html;
            
            // Update URL di browser tanpa reload
            history.pushState(null, '', url);

        } catch (error) {
            console.error('Fetch error:', error);
            container.innerHTML = '<p class="text-center text-danger">Terjadi kesalahan saat memuat data.</p>';
        } finally {
            // Sembunyikan loading spinner
            loadingOverlay.classList.remove('visible');
        }
    }

    // Fungsi untuk membuat URL berdasarkan filter saat ini
    function getFilteredUrl() {
        const params = new URLSearchParams();
        if (searchInput.value) {
            params.append('search', searchInput.value);
        }
        if (categorySelect.value) {
            params.append('category', categorySelect.value);
        }
        return `{{ route('pariwisata.index') }}?${params.toString()}`;
    }

    // Event listener untuk input pencarian (dengan debounce)
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchDestinations(getFilteredUrl());
        }, 500); // Tunggu 500ms setelah user berhenti mengetik
    });

    // Event listener untuk select kategori
    categorySelect.addEventListener('change', function() {
        fetchDestinations(getFilteredUrl());
    });

    // Mencegah form submit default
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchDestinations(getFilteredUrl());
    });

    // Event listener untuk link paginasi (menggunakan event delegation)
    document.body.addEventListener('click', function(e) {
        // Cek jika yang diklik adalah link di dalam .pagination
        if (e.target.closest('.pagination') && e.target.tagName === 'A') {
            e.preventDefault();
            const url = e.target.href;
            if(url) {
                fetchDestinations(url);
            }
        }
    });

    // Handle tombol reset
    document.getElementById('reset-btn').addEventListener('click', function(e){
        e.preventDefault();
        searchInput.value = '';
        categorySelect.value = '';
        fetchDestinations(`{{ route('pariwisata.index') }}`);
    });

});
</script>
@endpush