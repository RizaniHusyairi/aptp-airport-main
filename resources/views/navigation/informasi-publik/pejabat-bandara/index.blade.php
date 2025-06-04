@extends('layouts.laravel-default')

@section('title', 'Pejabat Bandara | APT PRANOTO')


@push('styles')
    <style>
        .official-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .official-card:hover {
            transform: translateY(-5px);
        }

        .card-img-top {
            height: 300px;
            object-fit: cover;
        }

        .profile-detail {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
            display: none;
            color: #2C3E50;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { max-height: 0; opacity: 0; }
            to { max-height: 500px; opacity: 1; }
        }

        .swiper-container {
            width: 100%;
            padding: 20px 0;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-button-prev, .swiper-button-next {
            color: #2ECC71;
        }

        .swiper-pagination-bullet {
            background: #2ECC71;
        }

        @media (max-width: 768px) {
            .card-img-top {
                height: 200px;
            }
            .nav-tabs {
                flex-direction: column;
            }
            .nav-link {
                margin-bottom: 10px;
            }
        }


    
        @media only screen and (max-width: 750px) {
            #responsible .image-responsible {
                width: 80%;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #E3F2FD 0%, #2ECC71 100%);">
        <div class="container">
            <h1 class="text-center text-dark mb-4">Profil Pejabat Bandara APT Pranoto</h1>
            <p class="text-center text-muted mb-5">Jajaran Komisaris InJourney Airports yang merupakakan perwakilan dari pemegang saham.</p>

            <!-- Daftar Nama Pejabat -->
            <div class="nav nav-tabs justify-content-center mb-4" id="officialTabs" role="tablist">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-maeka" type="button" role="tab" aria-controls="tab-maeka" aria-selected="true">Maeka Rindra Hariyanto</button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-zaldi" type="button" role="tab" aria-controls="tab-zaldi" aria-selected="false">Zaldi Ardian</button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mochamad" type="button" role="tab" aria-controls="tab-mochamad" aria-selected="false">Mochamad Ikhsan Fadilah</button>
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-denny" type="button" role="tab" aria-controls="tab-denny" aria-selected="false">Denny Armanto</button>
            </div>

            <!-- Slider Profil Pejabat -->
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- Slide 1: Maeka Rindra Hariyanto -->
                    <div class="swiper-slide">
                        <div class="card official-card">
                            <img src="{{ asset('frontend/assets/pejabat/maeka.jpg') }}" class="card-img-top" alt="Maeka Rindra Hariyanto" loading="lazy">
                            <div class="card-body text-center">
                                <h5 class="card-title">Maeka Rindra Hariyanto, SE. M.Si</h5>
                                <p class="card-text">Kepala BLU Kantor UPBU Kelas I A.P.T. Pranoto</p>
                                <button class="btn btn-primary read-more" data-target="#detail-maeka">Baca Profil Selengkapnya</button>
                            </div>
                        </div>
                        <div id="detail-maeka" class="profile-detail" style="display: none;">
                            <p><strong>Riwayat Jabatan:</strong> Plt. Kepala Seksi Angkutan Udara Dishub Kaltim (2012–2014), berbagai posisi struktural lainnya, Kepala Kantor UPBU A.P.T. Pranoto (2023–Sekarang).</p>
                            <p><strong>Pendidikan:</strong> S2 Magister Ekonomi Universitas Mulawarman.</p>
                            <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 & 20 Tahun.</p>
                        </div>
                    </div>

                    <!-- Slide 2: Zaldi Ardian -->
                    <div class="swiper-slide">
                        <div class="card official-card">
                            <img src="{{ asset('frontend/assets/pejabat/zaldi.jpg') }}" class="card-img-top" alt="Zaldi Ardian" loading="lazy">
                            <div class="card-body text-center">
                                <h5 class="card-title">Zaldi Ardian, A.Md</h5>
                                <p class="card-text">Kepala Subbagian Keuangan dan Tata Usaha</p>
                                <button class="btn btn-primary read-more" data-target="#detail-zaldi">Baca Profil Selengkapnya</button>
                            </div>
                        </div>
                        <div id="detail-zaldi" class="profile-detail" style="display: none;">
                            <p><strong>Riwayat Jabatan:</strong> Kepala Kantor UPBU Maratua (2020–2024), Kepala Subbagian Tata Usaha A.P.T. Pranoto.</p>
                            <p><strong>Pendidikan:</strong> D-III PTBL PLP Curug.</p>
                            <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 Tahun.</p>
                        </div>
                    </div>

                    <!-- Slide 3: Mochamad Ikhsan Fadilah -->
                    <div class="swiper-slide">
                        <div class="card official-card">
                            <img src="{{ asset('frontend/assets/pejabat/mochamad.jpg') }}" class="card-img-top" alt="Mochamad Ikhsan Fadilah" loading="lazy">
                            <div class="card-body text-center">
                                <h5 class="card-title">Mochamad Ikhsan Fadilah, SE, M.M.Tr</h5>
                                <p class="card-text">Kepala Seksi Teknik dan Operasi</p>
                                <button class="btn btn-primary read-more" data-target="#detail-mochamad">Baca Profil Selengkapnya</button>
                            </div>
                        </div>
                        <div id="detail-mochamad" class="profile-detail" style="display: none;">
                            <p><strong>Riwayat Jabatan:</strong> Kepala UPBU Yuvai Semaring (2020–2024), Kepala Seksi Teknik dan Operasi.</p>
                            <p><strong>Pendidikan:</strong> S2 Sekolah Tinggi Manajemen Transportasi Trisakti.</p>
                            <p><strong>Penghargaan:</strong> Satya Lancana Karya Satya 10 Tahun.</p>
                        </div>
                    </div>

                    <!-- Slide 4: Denny Armanto -->
                    <div class="swiper-slide">
                        <div class="card official-card">
                            <img src="{{ asset('frontend/assets/pejabat/denny.jpg') }}" class="card-img-top" alt="Denny Armanto" loading="lazy">
                            <div class="card-body text-center">
                                <h5 class="card-title">Roslan, S.E.</h5>
                                <p class="card-text">Kepala Seksi Pelayanan dan Kerjasama</p>
                                <button class="btn btn-primary read-more" data-target="#detail-denny">Baca Profil Selengkapnya</button>
                            </div>
                        </div>
                        <div id="detail-denny" class="profile-detail" style="display: none;">
                            <p><strong>Riwayat Jabatan:</strong> -	Pegawai Perintis Subsidi (2001 – 2004) <br>
-	Bendahara Proyek Prop. Kaltim Bandara Kelas I Khusus Juwata (2003-2004) <br>
-	Bendahara Bandara Long Apung (2005 – 2009) <br>
-	Petugas Pelayanan Jasa Kebandarudaraan Bandara Juwata Tarakan (2013-2015) <br>
-	Penyusun Rencana dan Program Bandara Juwata Tarakan (2015-2018) <br>
-	Kepala Seksi Pelayanan Bandara Juwata Tarakan (2018-2025) <br>
-	Kepala Seksi Pelayanan dan Kerjasama (2025-sekarang) <br>
</p>
                            <p><strong>Pendidikan:</strong> STIE Bulungan Tarakan Jurusan S-1 Managemen.</p>
                            <p><strong>Penghargaan:</strong> -	Satya Lancana Karya Satya 10 Tahun (2012) <br>
-	Satya Lancana Karya Satya 20 Tahun (2020)
</p>
                        </div>
                    </div>
                </div>
                <!-- Swiper Pagination -->
                <div class="swiper-pagination"></div>
                <!-- Swiper Navigation -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
    <section class="container pb-5" id="responsible">
        <h2 class="fw-bold fs-1">Profil Pejabat</h2>

        {{-- Pejabat 1 --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/DSC08182.png"
                    class="img-fluid rounded image-responsible" alt="Maeka Rindra Hariyanto">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>MAEKA RINDRA HARIYANTO, SE. M.Si</h4>
                <p><strong>Jabatan:</strong> Kepala BLU Kantor UPBU Kelas I A.P.T. Pranoto</p>
                <p>
                    Pernah menjabat sebagai Plt. Kepala Seksi Angkutan Udara Dishub Kaltim (2012–2014), berbagai posisi
                    struktural lainnya, dan saat ini menjabat Kepala Kantor UPBU A.P.T. Pranoto (2023–Sekarang). Pendidikan
                    terakhir S2 Magister Ekonomi Universitas Mulawarman. Penghargaan: Satya Lancana Karya Satya 10 & 20
                    Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 2 --}}
        <div class="row align-items-center mb-3 flex-md-row-reverse">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/DSC06491-removebg-preview.png"
                    class="img-fluid rounded image-responsible" alt="Zaldi Ardian">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>ZALDI ARDIAN, A.Md</h4>
                <p><strong>Jabatan:</strong> Kepala Subbagian Keuangan dan Tata Usaha</p>
                <p>
                    Pernah menjabat Kepala Kantor UPBU Maratua (2020–2024), kini menjabat Kepala Subbagian Tata Usaha di
                    A.P.T. Pranoto. Latar belakang pendidikan D-III PTBL PLP Curug. Penghargaan: Satya Lancana Karya Satya
                    10 Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 3 --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/Desain-tanpa-judul-1-e1738056917715.png"
                    class="img-fluid rounded image-responsible" alt="Mochamad Ikhsan Fadilah">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>MOCHAMAD IKHSAN FADILAH, SE, M.M.Tr</h4>
                <p><strong>Jabatan:</strong> Kepala Seksi Teknik dan Operasi</p>
                <p>
                    Pernah menjabat Kepala UPBU Yuvai Semaring (2020–2024), kini sebagai Kepala Seksi Teknik dan Operasi.
                    Pendidikan S2 di Sekolah Tinggi Manajemen Transportasi Trisakti. Penghargaan: Satya Lancana Karya Satya
                    10 Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 4 --}}
        <div class="row align-items-center mb-3 flex-md-row-reverse">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/APT01069-removebg-preview-e1738055903513.png"
                    class="img-fluid rounded image-responsible" alt="Denny Armanto">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>DENNY ARMANTO, S.E, M.A</h4>
                <p><strong>Jabatan:</strong> Kepala Seksi Pelayanan dan Kerjasama</p>
                <p>
                    Pernah menjabat di berbagai posisi pelayanan bandara sejak 2018, kini sebagai Kepala Seksi Pelayanan dan
                    Kerjasama. Pendidikan terakhir S2 Ilmu Administrasi. Penghargaan: Satya Lancana Karya Satya 10 Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 5 --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/m-e1738056241761.png" class="img-fluid rounded image-responsible"
                    alt="Murdoko">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>MURDOKO, S.H.</h4>
                <p><strong>Jabatan:</strong> Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat</p>
                <p>
                    Pernah menjabat sebagai Kepala Seksi Teknik dan Keamanan (2019–2023), kini menangani Keamanan
                    Penerbangan. Pendidikan terakhir S1 Hukum Universitas Terbuka. Penghargaan: Satya Lancana Karya Satya 10
                    & 20 Tahun.
                </p>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi Swiper
            const swiper = new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // Sinkronisasi tab dengan slide
            const tabs = document.querySelectorAll('#officialTabs .nav-link');
            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    swiper.slideTo(index);
                });
            });

            swiper.on('slideChange', () => {
                const activeIndex = swiper.activeIndex % (swiper.slides.length - 1) || 0;
                tabs.forEach((tab, idx) => tab.classList.toggle('active', idx === activeIndex));
            });

            // Animasi detail profil
            document.querySelectorAll('.read-more').forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    const detail = document.querySelector(targetId);
                    if (detail.style.display === 'none' || !detail.style.display) {
                        detail.style.display = 'block';
                        gsap.from(detail, { height: 0, opacity: 0, duration: 0.5, ease: 'power2.out' });
                    } else {
                        gsap.to(detail, { height: 0, opacity: 0, duration: 0.5, ease: 'power2.out', onComplete: () => detail.style.display = 'none' });
                    }
                });
            });
        });
    </script>
@endpush