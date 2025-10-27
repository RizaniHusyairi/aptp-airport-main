@extends('layouts_landing.landing_app')

@section('title', 'Profil Bandara - Bandara APT Pranoto')

@section('content')
<!-- About Section -->
    <section id="about" class="about section pt-6 light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Informasi Publik<br></h2>
        <p><span>Profil</span> <span class="description-title">Bandar Udara A.P.T. Pranoto</span></p>
      </div><!-- End Section Title -->

      <div class="container-fluid light-background" >
        <div class="container">

            <!-- Sejarah dan Letak Geografis -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up">
                <h2 class="h3 mb-2 text-dark">Sejarah dan Letak Geografis</h2>
                <hr class="my-3">
                <p class="text-muted">Bandar Udara Aji Pangeran Tumenggung Pranoto Samarinda beroperasi sejak tanggal 24 Mei 2018 menggantikan Bandar Udara Temindung yang resmi ditutup pada 23 Mei 2018. Bandara Aji Pangeran Tumenggung (A.P.T.) Pranoto Samarinda bermula dari kebutuhan masyarakat Samarinda dan sekitarnya akan sarana transportasi udara yang lebih memadai. Sebelumnya, Bandara Temindung yang berada di pusat kota Samarinda menjadi satu-satunya pintu gerbang udara, namun kapasitas dan panjang landas pacunya yang terbatas tidak lagi mampu menampung peningkatan jumlah penumpang serta pesawat yang lebih besar. Kondisi ini memicu pemerintah kota dan pemerintah provinsi merencanakan pembangunan bandara baru yang dapat memenuhi standar keselamatan, kapasitas, dan kenyamanan bagi penumpang.</p>
            </section>

            <!-- Status dan Penetapan -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="100">
                <h2 class="h3 mb-2 text-dark">Status dan Penetapan</h2>
                <hr class="my-3">

                <p class="text-muted">Berdasarkan Keputusan Menteri Keuangan No: 63/KMK.05/2023, Bandara A.P.T. Pranoto ditetapkan sebagai Instansi Pemerintah dengan Pola Pengelolaan Keuangan Badan Layanan Umum (BLU), bersama bandara lain seperti Domine Eduard Osok (Sorong) dan Sultan Babullah (Ternate).</p>
            </section>

            <!-- Rute Penerbangan -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="200">
                <h2 class="h3 mb-2 text-dark">Rute Penerbangan</h2>
                <hr class="my-3">
                <p class="text-muted">Bandara ini melayani rute ke: <strong>Jakarta, Surabaya, Yogyakarta, Makassar, Denpasar, Berau</strong>.</p>
                <p class="text-muted">Rute perintis: <strong>Long Apung, Maratua, Datah Dawai, Muara Wahau</strong>.</p>
            </section>

            <!-- Tugas dan Fungsi -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="400">
                <h2 class="h3 mb-2 text-dark">Tugas dan Fungsi</h2>
                <hr class="my-3">

                <h3 class="h4 mb-2 text-dark">Tugas:</h3>
                <p class="text-muted mb-3">Melaksanakan pelayanan jasa kebandarudaraan dan jasa terkait Bandar Udara, kegiatan keamanan, keselamatan, dan ketertiban penerbangan pada Bandar Udara yang belum diusahakan secara komersial dan dikecualikan pengelolaan keuangannya.</p>
                <h3 class="h4 mb-2 text-dark">Fungsi:</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-muted">Pelaksanaan penyusunan rencana dan program, rencana strategi bisnis, dan rencana bisnis dan anggaran;</li>
                    <li class="list-group-item text-muted">pelaksanaan pengoperasian fasilitas keselamatan, sisi udara, sisi darat, dan alat-alat besar Bandar Udara, serta fasilitas penunjang;</li>
                    <li class="list-group-item text-muted">pelaksanaan perawatan dan perbaikan fasilitas keselamatan, sisi udara, sisi darat, dan alat-alat besar Bandar Udara, serta fasilitas penunjang;</li>
                    <li class="list-group-item text-muted">pelaksanaan pelayanan pengaturan pergerakan pesawat udara serta penyusunan jadwal penerbangan;</li>
                    <li class="list-group-item text-muted">pelaksanaan pengamanan pelayanan pengangkutan penumpang, awak pesawat udara, barang, jinjingan, pos dan kargo, serta barang berbahaya dan senjata;</li>
                    <li class="list-group-item text-muted">pelaksanaan pengawasan, pengendalian keamanan dan ketertiban di lingkungan kerja, pelaksanaan pengoperasian, perawatan dan perbaikan fasilitas keamanan penerbangan, dan pelayanan darurat Bandar Udara;</li>
                    <li class="list-group-item text-muted">pelaksanaan kerja sarna dan pengembangan usaha jasa kebandarudaraan dan jasa terkait Bandar Udara;</li>
                    <li class="list-group-item text-muted">pelaksanaan koordinasi dengan instansi/lembaga terkait penyelenggaraan Bandar Udara;</li>
                    <li class="list-group-item text-muted">pelaksanaan pengoperasian dan pelayanan fasilitas terminal penumpang, kargo dan penunjang, serta pengelolaan dan pengendalian higiene dan sanitasi;</li>
                    <li class="list-group-item text-muted">pelaksanaan pemeriksaan intern;</li>
                    <li class="list-group-item text-muted">pelaksanaan pengelolaan keuangan dan barang milik negara, pelaksanaan urusan kepegawaian, ketatausahaan, kerumahtanggaan, hukum dan hubungan masyarakat; dan</li>
                    <li class="list-group-item text-muted">pelaksanaan evaluasi dan pelaporan.</li>
                    
                </ul>
            </section>

            <!-- Visi dan Misi -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up" data-aos-delay="500">
                <h2 class="h3 mb-2 text-dark">Visi dan Misi</h2>
                <hr class="my-3">

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
      

    </section><!-- /About Section -->
    <!-- /news Section -->
@endsection

