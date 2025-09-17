@extends('layouts_landing.landing_app')

@section('title', 'Fasilitas Bandara - Bandara APT Pranoto')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/fasilitas.css') }}" rel="stylesheet">
@endpush

@section('content')
<section id="facilities-page" class="section-modern facilities-page pt-6 light-background">
    <div class="container">
        <div class="container section-title" data-aos="fade-up">
            <h2>Informasi<br></h2>
            <p><span>Fasilitas Lengkap</span> <span class="description-title">Bandara A.P.T. Pranoto</span></p>
        </div>

        {{-- Seksi Fasilitas Udara --}}
        @if(isset($facilities['udara']) && $facilities['udara']->isNotEmpty())
        <div class="facility-category" data-aos="fade-up" data-aos-delay="200">
            <h3 class="category-title">Fasilitas Sisi Udara</h3>
            <div class="row g-4">
                @foreach($facilities['udara'] as $facility)
                {{-- ### PERBAIKAN: Menambahkan kelas col-6 untuk mobile ### --}}
                <div class="col-lg-3 col-md-4 col-6">
                    <button type="button" class="facility-card" data-bs-toggle="modal" data-bs-target="#facilityDetailModal" 
                            data-name="{{ $facility->name }}" 
                            data-image="{{ $facility->image_url }}" 
                            data-details="{{ json_encode($facility->details) }}">
                        <div class="facility-card-image">
                            <img src="{{ $facility->image_url }}" alt="Foto {{ $facility->name }}" onerror="this.onerror=null;this.src='https://placehold.co/600x400/0d2c4a/ffffff?text=Fasilitas';">
                        </div>
                        <div class="facility-card-content">
                            <h4 class="facility-name">{{ $facility->name }}</h4>
                        </div>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        {{-- Seksi Fasilitas Darat --}}
        @if(isset($facilities['darat']) && $facilities['darat']->isNotEmpty())
        <div class="facility-category" data-aos="fade-up" data-aos-delay="200">
            <h3 class="category-title">Fasilitas Sisi Darat</h3>
            <div class="row g-4">
                @foreach($facilities['darat'] as $facility)
                {{-- ### PERBAIKAN: Menambahkan kelas col-6 untuk mobile ### --}}
                <div class="col-lg-3 col-md-4 col-6">
                    <button type="button" class="facility-card" data-bs-toggle="modal" data-bs-target="#facilityDetailModal" 
                            data-name="{{ $facility->name }}" 
                            data-image="{{ $facility->image_url }}" 
                            data-details="{{ json_encode($facility->details) }}">
                        <div class="facility-card-image">
                            <img src="{{ $facility->image_url }}" alt="Foto {{ $facility->name }}" onerror="this.onerror=null;this.src='https://placehold.co/600x400/0d2c4a/ffffff?text=Fasilitas';">
                        </div>
                        <div class="facility-card-content">
                            <h4 class="facility-name">{{ $facility->name }}</h4>
                        </div>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Seksi Fasilitas Umum --}}
        @if(isset($facilities['umum']) && $facilities['umum']->isNotEmpty())
        <div class="facility-category" data-aos="fade-up" data-aos-delay="300">
            <h3 class="category-title">Fasilitas Umum</h3>
            <div class="row g-4">
                 @foreach($facilities['umum'] as $facility)
                <div class="col-lg-3 col-md-4 col-6">
                    <button type="button" class="facility-card" data-bs-toggle="modal" data-bs-target="#facilityDetailModal"
                            data-name="{{ $facility->name }}" 
                            data-image="{{ $facility->image_url }}" 
                            data-details='@json($facility->details)'>
                         <div class="facility-card-image">
                            <img src="{{ $facility->image_url }}" alt="Foto {{ $facility->name }}" onerror="this.onerror=null;this.src='https://placehold.co/600x400/0d2c4a/ffffff?text=Fasilitas';">
                        </div>
                        <div class="facility-card-content">
                            <h4 class="facility-name">{{ $facility->name }}</h4>
                        </div>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- ============================================ -->
<!--         MODAL DETAIL FASILITAS (BARU)        -->
<!-- ============================================ -->
<div class="modal fade" id="facilityDetailModal" tabindex="-1" aria-labelledby="facilityDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="facilityDetailModalLabel">Detail Fasilitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" class="img-fluid rounded mb-4 modal-image" alt="Foto Fasilitas">
        <div class="modal-details">
            <h4>Detail Informasi</h4>
            <ul class="modal-details-list">
                {{-- Detail akan diisi oleh JavaScript --}}
            </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
    {{-- Tambahkan file JS baru untuk logika modal --}}
    <script src="{{ asset('assets_landing/js/fasilitas.js') }}"></script>
@endpush
