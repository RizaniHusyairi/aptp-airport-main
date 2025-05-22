@extends('layouts.laravel-default')

@section('title', 'Profil Bandara | APT PRANOTO')
@push('styles')
    <style>
        .swiper-slide img {
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .swiper-slide:hover img {
            transform: scale(1.05);
        }

        .swiper-button-prev, .swiper-button-next {
            color: #2ECC71;
        }

        .swiper-pagination-bullet {
            background: #2ECC71;
        }

        @media (max-width: 768px) {
            .swiper-slide img {
                height: 200px;
            }

            .display-4 {
                font-size: 2rem;
            }

            .h3 {
                font-size: 1.5rem;
            }

            .h4 {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #87CEEB 0%, #2ECC71 100%);">
        <div class="container">
            <h1 class="display-4 text-center text-white mb-5" data-aos="fade-down">Profil Bandara A.P.T. Pranoto Samarinda</h1>

            <!-- Slider Gambar -->
            <div class="swiper-container mb-5">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/bandara/slide1.jpg') }}" alt="Bandara A.P.T. Pranoto" class="img-fluid rounded shadow-lg">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/bandara/slide2.jpg') }}" alt="Terminal Bandara" class="img-fluid rounded shadow-lg">
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('frontend/assets/bandara/slide3.jpg') }}" alt="Runway Bandara" class="img-fluid rounded shadow-lg">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>

            <!-- Sejarah dan Letak Geografis -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up">
                <h2 class="h3 mb-3 text-dark">Sejarah dan Letak Geografis</h2>
                <p class="text-muted">Bandar Udara A.P.T. Pranoto – Samarinda diresmikan oleh Presiden Republik Indonesia pada 25 Oktober 2018, dan mulai beroperasi untuk pesawat berbadan sempit (B737 Series dan A320 Series) pada 20 November 2018. Bandara ini melayani wilayah Samarinda, Tenggarong, Bontang, Kutai Timur, dan Kutai Kartanegara serta menjadi penunjang Ibu Kota Nusantara melalui konsep Multiple Airport Systems.</p>
            </section>

            <!-- Status dan Penetapan -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="100">
                <h2 class="h3 mb-3 text-dark">Status dan Penetapan</h2>
                <p class="text-muted">Berdasarkan Keputusan Menteri Keuangan No: 63/KMK.05/2023, Bandara A.P.T. Pranoto ditetapkan sebagai Instansi Pemerintah dengan Pola Pengelolaan Keuangan Badan Layanan Umum (BLU), bersama bandara lain seperti Domine Eduard Osok (Sorong) dan Sultan Babullah (Ternate).</p>
            </section>

            <!-- Rute Penerbangan -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="200">
                <h2 class="h3 mb-3 text-dark">Rute Penerbangan</h2>
                <p class="text-muted">Bandara ini melayani rute ke: <strong>Jakarta, Surabaya, Yogyakarta, Makassar, Denpasar, Berau</strong>.</p>
                <p class="text-muted">Rute perintis: <strong>Long Apung, Maratua, Datah Dawai, Muara Wahau</strong>.</p>
            </section>

            <!-- Alamat Lengkap -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="300">
                <h2 class="h3 mb-3 text-dark">Alamat Lengkap</h2>
                <p class="text-muted">Jl. Poros Samarinda – Bontang, Sungai Siring, Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119</p>
            </section>

            <!-- Tugas dan Fungsi -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="400">
                <h2 class="h3 mb-3 text-dark">Tugas dan Fungsi</h2>
                <h3 class="h4 mb-2 text-dark">Tugas:</h3>
                <p class="text-muted mb-3">Melaksanakan pelayanan jasa kebandarudaraan dan terkait, keamanan, keselamatan, serta pengelolaan keuangan yang mandiri dan fleksibel, mendukung praktik bisnis yang sehat.</p>
                <h3 class="h4 mb-2 text-dark">Fungsi:</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-muted">Penyusunan rencana pelayanan jasa berdasarkan masterplan & rencana strategis BLU</li>
                    <li class="list-group-item text-muted">Pengoperasian & perawatan fasilitas keselamatan, keamanan, sisi udara/darat, serta alat berat</li>
                    <li class="list-group-item text-muted">Kerja sama & pengembangan usaha jasa kebandarudaraan secara mandiri dan bertanggung jawab</li>
                    <li class="list-group-item text-muted">Peningkatan pelayanan pengguna jasa (excellent passenger experience)</li>
                    <li class="list-group-item text-muted">Menjadi pusat kargo udara Kalimantan Timur & Kalimantan Utara</li>
                </ul>
            </section>

            <!-- Visi dan Misi -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="500">
                <h2 class="h3 mb-3 text-dark">Visi dan Misi</h2>
                <h3 class="h4 mb-2 text-dark">Visi:</h3>
                <p class="text-muted mb-3">“Menjadi bandar udara yang mewujudkan konektivitas transportasi udara yang Handal, Berdaya Saing dan Memberikan Nilai Tambah serta menjadi salah satu operator bandar udara terbaik di regional Asia Tenggara.”</p>
                <h3 class="h4 mb-2 text-dark">Misi:</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-muted">Menyediakan prasarana dan sarana bandar udara yang handal dan terintegrasi</li>
                    <li class="list-group-item text-muted">Mewujudkan kelembagaan efektif dan SDM yang profesional</li>
                    <li class="list-group-item text-muted">Meningkatkan kinerja pelayanan & pengalaman penumpang</li>
                    <li class="list-group-item text-muted">Transparansi dalam pengelolaan administrasi & keuangan</li>
                    <li class="list-group-item text-muted">Membangun kerja sama strategis yang bertanggung jawab</li>
                </ul>
            </section>
        </div>
</div>
<section class="pb-5">
  <div class="container">
    <h2 class="mb-4 fw-bold fs-1">Profil Bandara A.P.T. Pranoto Samarinda</h2>

    <div class="mb-4">
      <h4>Sejarah dan Letak Geografis</h4>
      <p>
        Bandar Udara A.P.T Pranoto – Samarinda diresmikan oleh Presiden Republik Indonesia pada 25 Oktober 2018, 
        dan mulai beroperasi untuk pesawat berbadan sempit (B737 Series dan A320 Series) pada 20 November 2018. 
        Bandara ini melayani wilayah Samarinda, Tenggarong, Bontang, Kutai Timur, dan Kutai Kartanegara serta 
        menjadi penunjang Ibu Kota Nusantara melalui konsep Multiple Airport Systems.
      </p>
    </div>

    <div class="mb-4">
      <h4>Status dan Penetapan</h4>
      <p>
        Berdasarkan Keputusan Menteri Keuangan No: 63/KMK.05/2023, Bandara A.P.T. Pranoto ditetapkan sebagai Instansi 
        Pemerintah dengan Pola Pengelolaan Keuangan Badan Layanan Umum (BLU), bersama bandara lain seperti Domine 
        Eduard Osok (Sorong) dan Sultan Babullah (Ternate).
      </p>
    </div>

    <div class="mb-4">
      <h4>Rute Penerbangan</h4>
      <p>
        Bandara ini melayani rute ke: Jakarta, Surabaya, Yogyakarta, Makassar, Denpasar, Berau. <br>
        Rute perintis: Long Apung, Maratua, Datah Dawai, Muara Wahau.
      </p>
    </div>

    <div class="mb-4">
      <h4>Alamat Lengkap</h4>
      <p>
        Jl. Poros Samarinda – Bontang, Sungai Siring, Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119
      </p>
    </div>

    <div class="mb-4">
      <h4>Tugas</h4>
      <p>
        Melaksanakan pelayanan jasa kebandarudaraan dan terkait, keamanan, keselamatan, serta pengelolaan keuangan 
        yang mandiri dan fleksibel, mendukung praktik bisnis yang sehat.
      </p>
    </div>

    <div class="mb-4">
      <h4>Fungsi</h4>
      <ul>
        <li>Penyusunan rencana pelayanan jasa berdasarkan masterplan & rencana strategis BLU</li>
        <li>Pengoperasian & perawatan fasilitas keselamatan, keamanan, sisi udara/darat, serta alat berat</li>
        <li>Kerja sama & pengembangan usaha jasa kebandarudaraan secara mandiri dan bertanggung jawab</li>
        <li>Peningkatan pelayanan pengguna jasa (excellent passenger experience)</li>
        <li>Menjadi pusat kargo udara Kalimantan Timur & Kalimantan Utara</li>
      </ul>
    </div>

    <div class="mb-4">
      <h4>Visi</h4>
      <blockquote class="blockquote fst-italic">
        “Menjadi bandar udara yang mewujudkan konektivitas transportasi udara yang Handal, Berdaya Saing dan Memberikan Nilai Tambah serta menjadi salah satu operator bandar udara terbaik di regional Asia Tenggara.”
      </blockquote>
    </div>

    <div class="mb-4">
      <h4>Misi</h4>
      <ul>
        <li>Menyediakan prasarana dan sarana bandar udara yang handal dan terintegrasi</li>
        <li>Mewujudkan kelembagaan efektif dan SDM yang profesional</li>
        <li>Meningkatkan kinerja pelayanan & pengalaman penumpang</li>
        <li>Transparansi dalam pengelolaan administrasi & keuangan</li>
        <li>Membangun kerja sama strategis yang bertanggung jawab</li>
      </ul>
    </div>
  </div>
</section>
@endsection
@push('scripts')
    
@endpush