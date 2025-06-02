@extends('layouts_landing.landing_app')

@section('title', 'Laporan Keuangan - Bandara APT Pranoto')

@section('content')
<!-- About Section -->
    <section id="about" class="about section pt-6">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Informasi Publik<br></h2>
        <p><span>Profil PPID BLU</span> <span class="description-title">Kantor UPBU Kelas I A.P.T. Pranoto</span></p>
      </div><!-- End Section Title -->

      <div class="container-fluid light-background" >
        <div class="container">

            <!-- Sejarah dan Letak Geografis -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up">
                <p class="text-muted">Sejak Undang-Undang Nomor 14 Tahun 2008 Tentang Keterbukaan Informasi Publik (UU KIP) diberlakukan secara efektif pada tanggal 30 April 2010 telah mendorong bangsa Indonesia satu langkah maju ke depan, menjadi bangsa yang transparan dan akuntabel dalam mengelola sumber daya publik. UU KIP sebagai instrumen hukum yang mengikat merupakan tonggak atau dasar bagi seluruh rakyat Indonesia untuk bersama-sama mengawasi secara langsung pelayanan publik yang diselenggarakan oleh Badan Publik.</p>
                <p class="text-muted">Keterbukaan informasi adalah salah satu pilar penting yang akan mendorong terciptanya iklim transparansi. Terlebih di era yang serba terbuka ini, keinginan masyarakat untuk memperoleh informasi semakin tinggi. Diberlakukannya UU KIP merupakan perubahan yang mendasar dalam kehidupan bermasyarakat, berbangsa dan bernegara, oleh sebab itu perlu adanya kesadaran dari seluruh elemen bangsa agar setiap lembaga dan badan pemerintah dalam pengelolaan informasi harus dengan prinsip good governance, tata kelola yang baik dan akuntabilitas.</p>
            </section>

            <!-- Status dan Penetapan -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="100">
                <h2 class="h3 mb-2 text-dark">Visi</h2>
                <hr class="my-3">
                <p class="text-muted">Terwujudnya layanan informasi publik yang Transparan, Objektif dan Prima untuk meningkatkan peran serta aktif masyarakat dalam penyelenggaraan pembangunan sektor transportasi.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-muted">
                      <strong>Layanan Informasi Publik</strong> <br>
                     Suatu usaha untuk memberikan informasi publik sesuai Undang-Undang No. 14 tahun 2008 tentang Keterbukaan Informasi Publik di lingkungan Kementerian Perhubungan.
                    </li>
                    <li class="list-group-item text-muted">
                      <strong>Transparan</strong> <br>
                     Memberikan akses seluas-luasnya kepada masyarakat dalam memperoleh informasi publik dengan cepat dan tepat waktu, biaya ringan, dan cara yang sederhana.
                    </li>
                    <li class="list-group-item text-muted">
                      <strong>Objektif</strong> <br>
                     Memberikan akses informasi kepada setiap kalangan, baik Perorangan, Kelompok, maupun Badan Hukum.
                    </li>
                    <li class="list-group-item text-muted">
                      <strong>Prima</strong> <br>
                     Terus berupaya penuh dalam peningkatan pelayanan, pengelolaan dan pendokumentasian informasi publik secara akuntabel, efisien dan mudah diakses.
                    </li>
                    
                </ul>
            </section>

            <!-- Rute Penerbangan -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="200">
                <h2 class="h3 mb-2 text-dark">Misi</h2>
                <hr class="my-3">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-muted">Menjamin akses informasi publik sesuai Undang-Undang No. 14 tahun 2008 tentang Keterbukaan Informasi Publik.</li>
                    <li class="list-group-item text-muted">Meningkatkan kualitas layanan informasi publik.</li>
                    <li class="list-group-item text-muted">Meningkatkan profesionalisme SDM layanan informasi publik.</li>
                    <li class="list-group-item text-muted">Meningkatkan sarana-prasarana dalam rangka efisiensi dan efektivitas layanan informasi publik.</li>
                    <li class="list-group-item text-muted">Meningkatkan pengelolaan informasi dan dokumentasi secara baik, efisien, mudah diakses dan bersifat desentralisasi.</li>
                </ul>
                
            </section>

            <!-- Tugas dan Fungsi -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="400">
                <h2 class="h3 mb-2 text-dark">Struktur Organisasi PPID</h2>
                <hr class="my-3">
                <img src="/assets/img/profil/struktur-organisasi.jpg" alt="" class="img-fluid rounded shadow-lg" id="struktur-image">
            </section>

            
        </div>
      </div>
    </section>
@endsection