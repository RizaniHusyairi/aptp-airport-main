@extends('layouts_landing.landing_app')

@section('title', 'Pejabat Bandara - Bandara APT Pranoto')

@section('content')
  <!-- About Section -->
  <section id="about" class="about section pt-6 light-background">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Informasi Publik<br></h2>
      <p><span>Pejabat</span> <span class="description-title">Bandara</span></p>
    </div><!-- End Section Title -->

    <div class="container-fluid">
      <div class="container">
        <!-- Daftar Nama Pejabat -->
        <div class="nav nav-tabs justify-content-center mb-4" id="officialTabs" role="tablist">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-kadek" type="button" role="tab" aria-controls="tab-kadek" aria-selected="true">I Kadek Yuli Sastrawan</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-zaldi" type="button" role="tab" aria-controls="tab-zaldi" aria-selected="false">Zaldi Ardian</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mochamad" type="button" role="tab" aria-controls="tab-mochamad" aria-selected="false">Mochamad Ikhsan Fadilah</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-roslan" type="button" role="tab" aria-controls="tab-roslan" aria-selected="false">Roslan</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-murdoko" type="button" role="tab" aria-controls="tab-murdoko" aria-selected="false">Murdoko</button>
        </div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Slide 1: I Kadek Yuli Sastrawan, S.Ikom., S.SiT. -->
                <div class="swiper-slide">
                    <div class="row card official-card card-pejabat">
                        <div class="col-md-4 p-0">
                            <img src="{{ asset('/assets_landing/img/pejabat/Kadek.png') }}" class="card-img-pejabat p-0 " alt="Maeka Rindra Hariyanto" loading="lazy">
                        </div>
                        <div class="card-body text-left col-md-8">
                            <h5 class="card-title">I Kadek Yuli Sastrawan, S.Ikom., S.SiT.</h5>
                            <p class="card-text">Kepala BLU Kantor UPBU Kelas I A.P.T. Pranoto</p>
                            <!-- <button class="btn btn-pejabat read-more" data-target="#detail-maeka">Baca Profil Selengkapnya</button> -->
                            <button class="btn btn-pejabat read-more" data-target="#profile-kadek">Baca Profil Selengkapnya</button>
                            </div>
                    </div>
                    
                </div>

                <!-- Slide 2: Zaldi Ardian -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <div class="col-md-4 p-0">
                            <img src="{{ asset('/assets_landing/img/pejabat/zaldi2.png') }}"  class="card-img-pejabat" alt="Zaldi Ardian" loading="lazy">
                        </div>
                        <div class="card-body col-md-8">
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
                        <div class="col-md-4 p-0">
                            <img src="{{ asset('/assets_landing/img/pejabat/Ikhsan.jpg') }}" class="card-img-pejabat p-0 " alt="Mochamad Ikhsan Fadilah" loading="lazy">
                        </div>
                        <div class="card-body col-md-8">
                            <h5 class="card-title">Mochamad Ikhsan Fadilah, SE, M.M.Tr</h5>
                            <p class="card-text">Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat</p>
                            <button class="btn btn-pejabat read-more" data-target="#profile-mochamad">Baca Profil Selengkapnya</button>
                        </div>
                    </div>
                    
                </div>

                <!-- Slide 4: Denny Armanto -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <div class="col-md-4 p-0">
                            <img src="{{ asset('/assets_landing/img/pejabat/ruslam.png') }}" class="card-img-pejabat p-0 " alt="Denny Armanto" loading="lazy">
                        </div>
                        <div class="card-body col-md-8">
                            <h5 class="card-title">Roslan, S.E.</h5>
                            <p class="card-text">Kepala Seksi Pelayanan dan Kerjasama</p>
                            <button class="btn btn-pejabat read-more" data-target="#profile-roslan">Baca Profil Selengkapnya</button>
                        </div>
                    </div>
                    
                </div>
                <!-- Slide 5: MURDOKO, S.H. -->
                <div class="swiper-slide">
                    <div class="card official-card row card-pejabat">
                        <div class="col-md-4 p-0">
                            <img src="{{ asset('/assets_landing/img/pejabat/murdoko5.png') }}" class="card-img-pejabat p-0 " alt="Murdoko" loading="lazy">
                        </div>
                        <div class="card-body col-md-8">
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
                <img src="" alt="Profile Image" class="profile-img" style="object-position: top;">
                <h5 class="profile-name"></h5>
                <p class="profile-position"></p>
                <div class="profile-details"></div>
            </div>
        </div>
    </div>

    <!-- Hidden Profile Details -->
    <div id="profile-kadek" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> <br> 
-	Kepala Kantor Otoritas Bandara Wilayah VII Sepinggan – Balikpapan (Juni 2024 – Agustus 2024) <br>
-	Kepala Bidang Pelayanan dan Pengoperasian Bandar Udara Kantor Otoritas Bandara Wilayah IV Bali (2024-2025) <br>
-	Kepala BLU Kantor UPBU Kelas I A.P.T. Pranoto – Samarinda (2025 – sekarang) 
</p>
        <p><strong>Pendidikan:</strong> <br> 
-	D-II Lalu Lintas Udara SLTP Curug <br>
-	D-III ATKP Makassar Jurusan Lalu Lintas Udara <br>
-	D-IV STPI Curug Jurusan Lalu Lintas Udara <br>
-	S-1 Ilmu Komunikasi Universitas Terbuka Palu 
</p>
        <p><strong>Penghargaan:</strong>  <br> 
            - Satya Lancana Karya Satya 10 Tahun (2014) <br>
            - Satya Lancana Karya Satya 20 Tahun (2018)
</p>
    </div>
    <div id="profile-zaldi" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> <br> 
            - Kepala Kantor UPBU Maratua (2020–2024) <br>
            - Kepala Subbagian Tata Usaha (2024–Sekarang) <br>
        </p>
        <p><strong>Pendidikan:</strong> <br> 
            - D-III PTBL PLP Curug.</p>
        <p><strong>Penghargaan:</strong> <br> 
            - Satya Lancana Karya Satya 10.</p>
    </div>
    <div id="profile-mochamad" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> <br> 
            - Kepala Urusan Tata Usaha (2019–2020) <br>
            - Kepala UPBU Kelas III Yuvai Semaring (2020–2024) <br>
            - Kepala Seksi Teknik dan Operasi (2024–2025) <br>
            - Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat (2025–Sekarang). </p>
        <p><strong>Pendidikan:</strong> <br> 
            - D-III Sekolah Tinggi Penerbangan Indonesia Curug<br>
            - S-1 Ilmu Ekonomi <br>
            - S-2 Sekolah Tinggi Manajemen Transportasi Trisakti.</p>
        <p><strong>Penghargaan:</strong> <br> 
            - Satya Lancana Karya Satya 10 Tahun 2021.</p>
    </div>
    <div id="profile-roslan" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> <br> 
-	Kepala Seksi Pelayanan Bandara Juwata Tarakan (2018-2025) <br>
-	Kepala Seksi Pelayanan dan Kerjasama (2025-sekarang) <br></p>
        <p><strong>Pendidikan:</strong> <br> 
            - STIE Bulungan Tarakan Jurusan S-1 Managemen.</p>
        <p><strong>Penghargaan:</strong> <br> 
            -	Satya Lancana Karya Satya 10 Tahun 2012 <br>
            -	Satya Lancana Karya Satya 20 Tahun 2020
</p>
    </div>
    <div id="profile-murdoko" class="hidden-profile">
        <p><strong>Riwayat Jabatan:</strong> <br> 
            - Kepala Seksi Teknik, Operasi, Keamanan dan Pelayanan UPBU Kelas II Iskandar Pangkalan Bun (2019–2023) <br>
            - Kepala Seksi Keamanan Penerbangan dan Pelayann Darurat UPBU Kelas III A.P.T. Pranoto. (2023–2025)</p>
            - Kepala Seksi Teknik dan Operasi UPBU Kelas III A.P.T. Pranoto. (2025–Sekarang)</p>
        <p><strong>Pendidikan:</strong> <br>
            - D-III Sekolah Tinggi Penerbangan Indonesia Curug.</p>
            - S-1 Ilmu Hukum.</p>
        <p><strong>Penghargaan:</strong> <br> 
            - Satya Lancana Karya Satya 10 Tahun 2012. <br>
            - Satya Lancana Karya Satya 20 Tahun 2021
        </p>
    </div>
  </section>
@endsection

@push('page-styles')
  <link href="{{ asset('assets_landing/css/pejabat.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
  <script src="{{ asset('assets_landing/js/pejabat.js') }}"></script>
@endpush