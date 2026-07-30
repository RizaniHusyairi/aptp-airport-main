@extends('layouts_landing.landing_app')

@section('title', 'Profil Bandara APT Pranoto Samarinda (AAP)')
@section('description', 'Profil resmi Bandara APT Pranoto Samarinda (AAP): sejarah, letak geografis, status BLU, rute penerbangan, tugas dan fungsi, serta visi dan misi UPBU Kelas I APT Pranoto di bawah Kementerian Perhubungan.')

@push('page-styles')
    <link href="{{ asset('assets_landing/css/profil-modern.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
    {{-- Kanvas partikel dipakai ulang dari halaman papan jadwal --}}
    <script src="{{ asset('assets_landing/js/flight-particles.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sorot otomatis tautan navigasi sesuai bagian yang sedang dibaca
            var nav = document.getElementById('pf-nav');
            if (nav && window.bootstrap && bootstrap.ScrollSpy) {
                new bootstrap.ScrollSpy(document.body, {
                    target: '#pf-nav',
                    rootMargin: '0px 0px -55%'
                });
            }
        });
    </script>
@endpush

@section('content')
<div class="pf">

    {{-- ================= HERO ================= --}}
    <div class="pf-hero">
        <canvas class="fb-canvas" aria-hidden="true"></canvas>

        <div class="container pf-hero-inner">
            <div class="row">
                <div class="col-lg-9">
                    <span class="pf-eyebrow">
                        <i class="bi bi-buildings-fill"></i> Informasi Publik
                    </span>

                    <h1 class="pf-title">
                        Bandar Udara <span>Aji Pangeran Tumenggung Pranoto</span>
                    </h1>

                    <p class="pf-lead">
                        Gerbang udara Kota Samarinda, Kalimantan Timur — dikelola Kantor Unit Penyelenggara
                        Bandar Udara Kelas I di bawah Kementerian Perhubungan Republik Indonesia.
                    </p>
                </div>
            </div>

            {{--
                Fakta singkat ini ditulis di blade (bukan dari tabel settings),
                diambil dari isi halaman profil yang sudah ada. Bila datanya
                berubah, sunting langsung di bagian ini.
            --}}
            <div class="pf-facts">
                <div class="pf-fact">
                    <i class="bi bi-airplane-engines-fill"></i>
                    <span class="pf-fact-label">Kode IATA</span>
                    <span class="pf-fact-value pf-fact-value--mono">AAP</span>
                </div>
                <div class="pf-fact">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span class="pf-fact-label">Beroperasi Sejak</span>
                    <span class="pf-fact-value">24 Mei 2018</span>
                </div>
                <div class="pf-fact">
                    <i class="bi bi-bank2"></i>
                    <span class="pf-fact-label">Pola Pengelolaan</span>
                    <span class="pf-fact-value">Badan Layanan Umum</span>
                </div>
                <div class="pf-fact">
                    <i class="bi bi-award-fill"></i>
                    <span class="pf-fact-label">Klasifikasi</span>
                    <span class="pf-fact-value">UPBU Kelas I</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= BADAN HALAMAN ================= --}}
    <div class="pf-body">
        <div class="container">
            <div class="row g-4">

                {{-- Navigasi dalam halaman --}}
                <div class="col-lg-3">
                    <div class="pf-nav" id="pf-nav-wrapper">
                        <p class="pf-nav-title">Pada Halaman Ini</p>
                        <nav class="nav nav-pills flex-column" id="pf-nav">
                            <a class="nav-link" href="#pf-sejarah"><i class="bi bi-clock-history"></i> Sejarah</a>
                            <a class="nav-link" href="#pf-status"><i class="bi bi-patch-check-fill"></i> Status</a>
                            <a class="nav-link" href="#pf-rute"><i class="bi bi-signpost-split-fill"></i> Rute</a>
                            <a class="nav-link" href="#pf-tugas"><i class="bi bi-clipboard-check-fill"></i> Tugas &amp; Fungsi</a>
                            <a class="nav-link" href="#pf-visi"><i class="bi bi-flag-fill"></i> Visi &amp; Misi</a>
                        </nav>
                    </div>
                </div>

                {{-- Isi --}}
                <div class="col-lg-9">

                    {{-- Sejarah dan Letak Geografis --}}
                    <section class="pf-card pf-card--story" id="pf-sejarah" data-aos="fade-up">
                        <div class="pf-card-head">
                            <span class="pf-card-icon"><i class="bi bi-clock-history"></i></span>
                            <h2 class="pf-card-title">
                                Sejarah dan Letak Geografis
                                <small class="pf-card-sub">Dari Temindung ke gerbang udara baru Samarinda</small>
                            </h2>
                        </div>
                        <div class="pf-prose">
                            @if(!empty($settings['profile_sejarah']))
                                {!! $settings['profile_sejarah'] !!}
                            @else
                                <p>Bandar Udara Aji Pangeran Tumenggung Pranoto Samarinda beroperasi sejak tanggal 24 Mei 2018 menggantikan Bandar Udara Temindung yang resmi ditutup pada 23 Mei 2018. Bandara Aji Pangeran Tumenggung (A.P.T.) Pranoto Samarinda bermula dari kebutuhan masyarakat Samarinda dan sekitarnya akan sarana transportasi udara yang lebih memadai. Sebelumnya, Bandara Temindung yang berada di pusat kota Samarinda menjadi satu-satunya pintu gerbang udara, namun kapasitas dan panjang landas pacunya yang terbatas tidak lagi mampu menampung peningkatan jumlah penumpang serta pesawat yang lebih besar. Kondisi ini memicu pemerintah kota dan pemerintah provinsi merencanakan pembangunan bandara baru yang dapat memenuhi standar keselamatan, kapasitas, dan kenyamanan bagi penumpang.</p>
                            @endif
                        </div>
                    </section>

                    {{-- Status dan Penetapan --}}
                    <section class="pf-card" id="pf-status" data-aos="fade-up">
                        <div class="pf-card-head">
                            <span class="pf-card-icon"><i class="bi bi-patch-check-fill"></i></span>
                            <h2 class="pf-card-title">
                                Status dan Penetapan
                                <small class="pf-card-sub">Dasar hukum pengelolaan keuangan</small>
                            </h2>
                        </div>
                        <div class="pf-prose">
                            @if(!empty($settings['profile_status']))
                                {!! $settings['profile_status'] !!}
                            @else
                                <p>Berdasarkan Keputusan Menteri Keuangan No: 63/KMK.05/2023, Bandara A.P.T. Pranoto ditetapkan sebagai Instansi Pemerintah dengan Pola Pengelolaan Keuangan Badan Layanan Umum (BLU), bersama bandara lain seperti Domine Eduard Osok (Sorong) dan Sultan Babullah (Ternate).</p>
                            @endif
                        </div>
                    </section>

                    {{-- Rute Penerbangan --}}
                    <section class="pf-card" id="pf-rute" data-aos="fade-up">
                        <div class="pf-card-head">
                            <span class="pf-card-icon"><i class="bi bi-signpost-split-fill"></i></span>
                            <h2 class="pf-card-title">
                                Rute Penerbangan
                                <small class="pf-card-sub">Kota tujuan yang terhubung dari Samarinda</small>
                            </h2>
                        </div>
                        <div class="pf-prose">
                            @if(!empty($settings['profile_rute']))
                                {!! $settings['profile_rute'] !!}
                            @else
                                <p>Bandara ini melayani rute ke: <strong>Jakarta, Surabaya, Yogyakarta, Makassar, Denpasar, Berau</strong>.</p>
                                <p>Rute perintis: <strong>Long Apung, Maratua, Datah Dawai, Muara Wahau</strong>.</p>
                            @endif
                        </div>

                        <a href="{{ route('keberangkatan') }}" class="pf-next-card mt-3" style="max-width: 420px;">
                            <i class="bi bi-clock-fill"></i>
                            <span class="pf-next-text">
                                Lihat Jadwal Keberangkatan
                                <small>Data penerbangan hari ini</small>
                            </span>
                        </a>
                    </section>

                    {{-- Tugas dan Fungsi --}}
                    <section class="pf-card" id="pf-tugas" data-aos="fade-up">
                        <div class="pf-card-head">
                            <span class="pf-card-icon"><i class="bi bi-clipboard-check-fill"></i></span>
                            <h2 class="pf-card-title">
                                Tugas dan Fungsi
                                <small class="pf-card-sub">Mandat penyelenggaraan bandar udara</small>
                            </h2>
                        </div>

                        <h3 class="pf-subhead">Tugas</h3>
                        <div class="pf-prose">
                            @if(!empty($settings['profile_tugas']))
                                {!! $settings['profile_tugas'] !!}
                            @else
                                <p>Melaksanakan pelayanan jasa kebandarudaraan dan jasa terkait Bandar Udara, kegiatan keamanan, keselamatan, dan ketertiban penerbangan pada Bandar Udara yang belum diusahakan secara komersial dan dikecualikan pengelolaan keuangannya.</p>
                            @endif
                        </div>

                        <h3 class="pf-subhead">Fungsi</h3>
                        <div class="pf-prose">
                            @if(!empty($settings['profile_fungsi']))
                                {!! $settings['profile_fungsi'] !!}
                            @else
                                <ul>
                                    <li>Pelaksanaan penyusunan rencana dan program, rencana strategi bisnis, dan rencana bisnis dan anggaran;</li>
                                    <li>pelaksanaan pengoperasian fasilitas keselamatan, sisi udara, sisi darat, dan alat-alat besar Bandar Udara, serta fasilitas penunjang;</li>
                                    <li>pelaksanaan perawatan dan perbaikan fasilitas keselamatan, sisi udara, sisi darat, dan alat-alat besar Bandar Udara, serta fasilitas penunjang;</li>
                                    <li>pelaksanaan pelayanan pengaturan pergerakan pesawat udara serta penyusunan jadwal penerbangan;</li>
                                    <li>pelaksanaan pengamanan pelayanan pengangkutan penumpang, awak pesawat udara, barang, jinjingan, pos dan kargo, serta barang berbahaya dan senjata;</li>
                                    <li>pelaksanaan pengawasan, pengendalian keamanan dan ketertiban di lingkungan kerja, pelaksanaan pengoperasian, perawatan dan perbaikan fasilitas keamanan penerbangan, dan pelayanan darurat Bandar Udara;</li>
                                    <li>pelaksanaan kerja sarna dan pengembangan usaha jasa kebandarudaraan dan jasa terkait Bandar Udara;</li>
                                    <li>pelaksanaan koordinasi dengan instansi/lembaga terkait penyelenggaraan Bandar Udara;</li>
                                    <li>pelaksanaan pengoperasian dan pelayanan fasilitas terminal penumpang, kargo dan penunjang, serta pengelolaan dan pengendalian higiene dan sanitasi;</li>
                                    <li>pelaksanaan pemeriksaan intern;</li>
                                    <li>pelaksanaan pengelolaan keuangan dan barang milik negara, pelaksanaan urusan kepegawaian, ketatausahaan, kerumahtanggaan, hukum dan hubungan masyarakat; dan</li>
                                    <li>pelaksanaan evaluasi dan pelaporan.</li>
                                </ul>
                            @endif
                        </div>
                    </section>

                    {{-- Visi dan Misi --}}
                    <section class="pf-card" id="pf-visi" data-aos="fade-up">
                        <div class="pf-card-head">
                            <span class="pf-card-icon"><i class="bi bi-flag-fill"></i></span>
                            <h2 class="pf-card-title">
                                Visi dan Misi
                                <small class="pf-card-sub">Arah dan komitmen penyelenggaraan</small>
                            </h2>
                        </div>

                        <div class="pf-vision">
                            <span class="pf-vision-label"><i class="bi bi-eye-fill"></i> Visi</span>
                            <div class="pf-prose">
                                @if(!empty($settings['profile_visi']))
                                    {!! $settings['profile_visi'] !!}
                                @else
                                    <p>“Menjadi bandar udara yang mewujudkan konektivitas transportasi udara yang Handal, Berdaya Saing dan Memberikan Nilai Tambah serta menjadi salah satu operator bandar udara terbaik di regional Asia Tenggara.”</p>
                                @endif
                            </div>
                        </div>

                        <div class="pf-mission">
                            <h3 class="pf-subhead mt-4">Misi</h3>
                            <div class="pf-prose">
                                @if(!empty($settings['profile_misi']))
                                    {!! $settings['profile_misi'] !!}
                                @else
                                    <ul>
                                        <li>Menyediakan prasarana dan sarana bandar udara yang handal dan terintegrasi</li>
                                        <li>Mewujudkan kelembagaan efektif dan SDM yang profesional</li>
                                        <li>Meningkatkan kinerja pelayanan &amp; pengalaman penumpang</li>
                                        <li>Transparansi dalam pengelolaan administrasi &amp; keuangan</li>
                                        <li>Membangun kerja sama strategis yang bertanggung jawab</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </section>

                    {{-- Tautan lanjutan --}}
                    <div class="pf-next">
                        <h3 class="pf-subhead">Jelajahi Lebih Lanjut</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('strukturOrganisasi') }}" class="pf-next-card">
                                    <i class="bi bi-diagram-3-fill"></i>
                                    <span class="pf-next-text">
                                        Struktur Organisasi
                                        <small>Susunan unit kerja bandara</small>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('pejabatBandara') }}" class="pf-next-card">
                                    <i class="bi bi-people-fill"></i>
                                    <span class="pf-next-text">
                                        Pejabat Bandara
                                        <small>Pimpinan dan pejabat struktural</small>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('fasilitas') }}" class="pf-next-card">
                                    <i class="bi bi-gem"></i>
                                    <span class="pf-next-text">
                                        Fasilitas Bandara
                                        <small>Sisi udara, sisi darat, dan umum</small>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('standarPelayanan') }}" class="pf-next-card">
                                    <i class="bi bi-patch-check-fill"></i>
                                    <span class="pf-next-text">
                                        Standar Pelayanan
                                        <small>Standar, maklumat, dan hasil SKM</small>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
