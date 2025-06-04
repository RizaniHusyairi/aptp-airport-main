@extends('layouts_landing.landing_app')

@section('title', 'Pejabat Bandara - Bandara APT Pranoto')

@section('content')
  <!-- About Section -->
  <section id="about" class="about section pt-6">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Informasi Publik<br></h2>
      <p><span>Pejabat</span> <span class="description-title">Bandara</span></p>
    </div><!-- End Section Title -->

    <div class="container-fluid light-background">
      <div class="container">
        <!-- Daftar Nama Pejabat -->
        <div class="nav nav-tabs justify-content-center mb-4" id="officialTabs" role="tablist">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-maeka" type="button" role="tab" aria-controls="tab-maeka" aria-selected="true">Maeka Rindra Hariyanto</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-zaldi" type="button" role="tab" aria-controls="tab-zaldi" aria-selected="false">Zaldi Ardian</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mochamad" type="button" role="tab" aria-controls="tab-mochamad" aria-selected="false">Mochamad Ikhsan Fadilah</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-roslan" type="button" role="tab" aria-controls="tab-roslan" aria-selected="false">Roslan</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-murdoko" type="button" role="tab" aria-controls="tab-murdoko" aria-selected="false">Murdoko</button>
        </div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Slide 1: Maeka Rindra Hariyanto -->
                <div class="swiper-slide">
                    <div class="row card official-card card-pejabat">
                        <img src="{{ asset('/assets_landing/img/pejabat/maeka.JPG') }}" class="card-img-pejabat p-0 col-lg-4" alt="Maeka Rindra Hariyanto" loading="lazy">
                        <div class="card-body text-left col-lg-8">
                            <h5 class="card-title">Maeka Rindra Hariyanto, SE. M.Si</h5>
                            <p class="card-text">Kepala BLU Kantor UPBU Kelas I A.P.T. Pranoto</p>
                            <!-- <button class="btn btn-pejabat read-more" data-target="#detail-maeka">Baca Profil Selengkapnya</button> -->
                            <button class="btn btn-pejabat read-more" data-target="#profile-maeka">Baca Profil Selengkapnya</button>
                            </div>
                    </div>
                    
                </div>

                <!-- Slide 2: Zaldi Ardian -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <img src="{{ asset('/assets_landing/img/pejabat/zaldi.JPG') }}" class="card-img-pejabat p-0 col-lg-4" alt="Zaldi Ardian" loading="lazy">
                        <div class="card-body col-lg-8">
                            <h5 class="card-title">Zaldi Ardian, A.Md</h5>
                            <p class="card-text">Kepala Subbagian Keuangan dan Tata Usaha</p>
                            <!-- <button class="btn btn-pejabat read-more" data-target="#detail-zaldi">Baca Profil Selengkapnya</button> -->
                            <button class="btn btn-pejabat read-more" data-target="#profile-zaldi">Baca Profil Selengkapnya</button>
                        
                            </div>
                    </div>
                    
                </div>

                <!-- Slide 3: Mochamad Ikhsan Fadilah -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <img src="{{ asset('/assets_landing/img/pejabat/Ikhsan.jpg') }}" class="card-img-pejabat p-0 col-lg-4" alt="Mochamad Ikhsan Fadilah" loading="lazy">
                        <div class="card-body col-lg-8">
                            <h5 class="card-title">Mochamad Ikhsan Fadilah, SE, M.M.Tr</h5>
                            <p class="card-text">Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat</p>
                            <button class="btn btn-pejabat read-more" data-target="#profile-mochamad">Baca Profil Selengkapnya</button>
                        </div>
                    </div>
                    
                </div>

                <!-- Slide 4: Denny Armanto -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <img src="{{ asset('/assets_landing/img/pejabat/denny2.JPG') }}" class="card-img-pejabat p-0 col-lg-4" alt="Denny Armanto" loading="lazy">
                        <div class="card-body col-lg-8">
                            <h5 class="card-title">Roslan, S.E.</h5>
                            <p class="card-text">Kepala Seksi Pelayanan dan Kerjasama</p>
                            <button class="btn btn-pejabat read-more" data-target="#profile-roslan">Baca Profil Selengkapnya</button>
                        </div>
                    </div>
                    
                </div>
                <!-- Slide 5: MURDOKO, S.H. -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <img src="{{ asset('/assets_landing/img/pejabat/murdoko.JPG') }}" class="card-img-pejabat p-0 col-lg-4" alt="Murdoko" loading="lazy">
                        <div class="card-body col-lg-8">
                            <h5 class="card-title">MURDOKO, S.H.</h5>
                            <p class="card-text">Kepala Seksi Teknik dan Operasi</p>
                            
                            <button class="btn btn-pejabat read-more" data-target="#profile-murdoko">Baca Profil Selengkapnya</button>
                        </div>
                    </div>
                    
                </div>
                    <!-- Swiper Pagination -->
            </div>
        </div>
    </div>
  </div>
  <!-- Popup Container -->
    <div id="profile-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="profile-card">
                <img src="" alt="Profile Image" class="profile-img">
                <h5 class="profile-name"></h5>
                <p class="profile-position"></p>
                <div class="profile-details"></div>
            </div>
        </div>
    </div>

    <!-- Hidden Profile Details -->
    <div id="profile-maeka" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> Plt. Kepala Seksi Angkutan Udara Dishub Kaltim (2012–2014), berbagai posisi struktural lainnya, Kepala Kantor UPBU A.P.T. Pranoto (2023–Sekarang).</p>
        <p><strong>Pendidikan:</strong> S2 Magister Ekonomi Universitas Mulawarman.</p>
        <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 & 20 Tahun.</p>
    </div>
    <div id="profile-zaldi" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> Kepala Kantor UPBU Maratua (2020–2024), kini Kepala Subbagian Tata Usaha A.P.T. Pranoto.</p>
        <p><strong>Pendidikan:</strong> D-III PTBL PLP Curug.</p>
        <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 Tahun.</p>
    </div>
    <div id="profile-mochamad" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> Kepala UPBU Yuvai Semaring (2020–2024), kini menangani Keamanan Penerbangan. </p>
        <p><strong>Pendidikan:</strong> S2 Sekolah Tinggi Manajemen Transportasi Trisakti.</p>
        <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 Tahun.</p>
    </div>
    <div id="profile-roslan" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> <br>- Penyusun Rencana dan Program Bandara Juwata Tarakan (2015-2018) <br>
-	Kepala Seksi Pelayanan Bandara Juwata Tarakan (2018-2025) <br>
-	Kepala Seksi Pelayanan dan Kerjasama (2025-sekarang) <br></p>
        <p><strong>Pendidikan:</strong> STIE Bulungan Tarakan Jurusan S-1 Managemen.</p>
        <p><strong>Penghargaan:</strong> -	Satya Lancana Karya Satya 10 Tahun (2012) <br>
-	Satya Lancana Karya Satya 20 Tahun (2020)
</p>
    </div>
    <div id="profile-murdoko" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> Kepala Seksi Teknik dan Keamanan (2019–2023), kini Kepala Seksi Teknik dan Operasi.</p>
        <p><strong>Pendidikan:</strong> S1 Hukum Universitas Terbuka.</p>
        <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 & 20 Tahun.</p>
    </div>
  </section>
@endsection

@push('page-styles')
  <link href="{{ asset('assets_landing/css/pejabat.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
  <script src="{{ asset('assets_landing/js/pejabat.js') }}"></script>
@endpush