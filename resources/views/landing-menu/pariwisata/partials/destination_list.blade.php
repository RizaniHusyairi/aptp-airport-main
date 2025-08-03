{{-- File: resources/views/landing-menu/pariwisata/partials/destination_list.blade.php --}}

<div class="row gy-4 portfolio-container" data-aos="fade-up" data-aos-delay="200">
    @forelse ($destinations as $item)
    <div class="col-lg-4 col-md-6 portfolio-item">
        <a href="{{ route('pariwisata.show', $item->slug) }}" class="tourism-card">
            <div class="card-img">
                <img src="{{ asset('uploads/'. $item->cover_image) }}" class="img-fluid" alt="{{ $item->name }}">
                <div class="category-badge">{{ $item->category }}</div>
            </div>
            <div class="card-info">
                <h4>{{ $item->name }}</h4>
                <p>{{ $item->short_desc }}</p>
                <span class="details-link">Lihat Detail <i class="bi bi-arrow-right"></i></span>
            </div>
        </a>
    </div>@empty
    <div class="col-12 text-center">
        <p class="lead">Maaf, destinasi yang Anda cari tidak ditemukan.</p>
    </div>
    @endforelse
</div>{{-- Link Paginasi --}}
<div class="row mt-5">
    <div class="col-12 d-flex justify-content-center">
        {{-- Penting: Kita menambahkan appends agar parameter filter tidak hilang saat pindah halaman --}}
        {{ $destinations->appends(request()->query())->links() }}
    </div>
</div>