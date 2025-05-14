@extends('layouts.laravel-default')

@section('title', 'APT PRANOTO')

@push('styles')
    <style>

        /* Marquee untuk Kerja Sama Mitra */
        .marquee-container {
            width: 100%;
            overflow: hidden;
            background: #f8f9fa;
            padding: 20px 0;
            position: relative;
        }

        .marquee {
            display: flex;
            animation: marquee 20s linear infinite;
            white-space: nowrap;
        }

        .marquee a {
            display: inline-block;
            margin: 0 20px;
            transition: transform 0.3s ease;
        }

        .marquee img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .marquee a:hover img {
            transform: scale(1.1);
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        /* Styling untuk Widget Cuaca */
        .weather-widget {
            position: absolute;
            top: 30px;
            right: 20px;
            width: 300px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 10;
        }

        .weather-widget h6 {
            margin-bottom: 10px;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .weather-widget h6 i {
            margin-right: 5px;
        }

        .weather-info {
            display: flex;
            align-items: center;
        }

        .weather-info img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .weather-details {
            font-size: 0.85rem;
        }

        .weather-source {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .weather-error {
            font-size: 0.85rem;
            color: #dc3545;
            text-align: center;
        }


        /* Responsivitas */
        @media (max-width: 576px) {
            .weather-widget {
                width: 200px;
                bottom: 10px;
                right: 10px;
                height: fit-content
            }
            .weather-info img {
                width: 25px;
                height: 25px;
            }
            .weather-details {
                font-size: 0.8rem;
            }
            .weather-widget h6 {
                font-size: 0.9rem;
            }        
            .marquee a {
                margin: 0 10px;
            }
            .marquee img {
                width: 80px;
                height: 80px;
            }
            
        }

        .image-chief {
            width: 500px;
            height: auto;
            max-height: 400px;
            /* batas tinggi maksimum */
            object-fit: cover;
            margin: auto;
        }

        @media only screen and (max-width: 750px) {
            .image-chief {
                width: 100%;
                max-height: 300px;
                /* tinggi dikurangi di mobile */
            }
        }

        .sliderh{
            height: 80vh;
            /* tinggi slider */
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div id="carouselExampleSlidesOnly" class="carousel slide sliderh"
                data-bs-ride="carousel">
                <div class="carousel-inner hero">
                    @forelse ($sliders as $key => $slider)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img src="{{ asset('uploads/' . $slider->documents) }}"
                                class="carousel-home d-block w-100 object-fit-cover" alt="Slider Image {{ $key + 1 }}">
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <img src="{{ asset('frontend/assets/tes/tes1.jpg') }}"
                                class="carousel-home d-block w-100 object-fit-cover" alt="Default Slider">
                        </div>
                    @endforelse
                    <!-- Widget Cuaca -->
                    <div class="weather-widget">
                        <h6><i class='bx bx-cloud'></i> Cuaca Saat Ini</h6>
                        @if ($weatherData['success'])
                            <div class="weather-info">
                                <img src="{{ $weatherData['data']['weather_image'] }}"
                                    alt="{{ $weatherData['data']['weather_desc'] }}"
                                    onerror="this.src='{{ asset('frontend/assets/weather/placeholder.png') }}'">
                                <div class="weather-details">
                                    <strong>{{ $weatherData['data']['time'] }} WITA</strong><br>
                                    {{ $weatherData['data']['weather_desc'] }}<br>
                                    Suhu: {{ $weatherData['data']['temperature'] }}°C<br>
                                    Kelembapan: {{ $weatherData['data']['humidity'] }}%<br>
                                    Angin: {{ $weatherData['data']['wind_speed'] }} km/jam ({{ $weatherData['data']['wind_direction'] }})
                                </div>
                            </div>
                            <div class="weather-source">
                                Data: Badan Meteorologi, Klimatologi, dan Geofisika (BMKG)
                            </div>
                        @else
                            <div class="weather-error">
                                {{ $weatherData['message'] }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
            
        <div class="hubud-secondary container">
            <section class="d-flex justify-content-center">
                <div class="row">
                    <!-- Card Lalu Lintas Angkutan Udara -->
                    <div class="col-md-4 mb-4">
                        <div class="card traffic-card shadow-sm">
                            <div class="card-body text-center">
                                <div class="card-icon text-primary">
                                    <i class='bx bx-transfer-alt bx-tada-hover'></i>
                                </div>
                                <h5 class="card-title">Lalu Lintas Angkutan Udara</h5>
                                <div class="card-count">{{ $totalAngkutanUdara }}</div>
                                <div class="card-text-container">
                                    <p class="card-text text-muted">Total pergerakan pesawat hari ini</p>
                                </div>
                                <div class="btn-container">
                                    <a href="{{ route('laluLintas') }}" class="btn btn-primary btn-detail traffic-btn">
                                        Lihat Detail <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Kedatangan -->
                    <div class="col-md-4 mb-4">
                        <div class="card arrival-card shadow-sm">
                            <div class="card-body text-center">
                                <div class="card-icon text-success">
                                    <i class='bx bxs-plane-land bx-tada-hover'></i>
                                </div>
                                <h5 class="card-title">Kedatangan</h5>
                                <div class="card-count">{{$jumlahKedatangan}}</div>
                                <div class="card-text-container">
                                    <p class="card-text text-muted">Jumlah penerbangan tiba hari ini</p>
                                </div>
                                <div class="btn-container">
                                    <a href="{{ route('kedatangan') }}" class="btn btn-success btn-detail arrival-btn">
                                        Lihat Detail <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Keberangkatan -->
                    <div class="col-md-4 mb-4">
                        <div class="card departure-card shadow-sm">
                            <div class="card-body text-center">
                                <div class="card-icon text-warning">
                                    <i class='bx bxs-plane-take-off bx-tada-hover'></i>
                                </div>
                                <h5 class="card-title">Keberangkatan</h5>
                                <div class="card-count">{{$jumlahKeberangkatan}}</div>
                                <div class="card-text-container">
                                    <p class="card-text text-muted">Jumlah penerbangan berangkat hari ini</p>
                                </div>
                                <div class="btn-container">
                                    <a href="{{ route('keberangkatan') }}" class="btn btn-warning btn-detail departure-btn text-white">
                                        Lihat Detail <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="container">
            <section class="d-flex flex-column gap-5 hubud-secondary min-vh-100">
                <h3 class="fs-2 text-center">Kepala BLU Kantor UPBU Kelas I APT. Pranoto Samarinda</h3>
                <div class="d-flex flex-column flex-lg-row gap-5">
                    <img src="{{ asset('frontend/assets/home/sambutan_image.jpg') }}" alt="sambutan"
                        class="object-fit-cover image-chief">
                    <div class="d-flex flex-column gap-5 justify-content-center">
                        <p class="fs-7 lh-base fw-normal" style="text-align: justify">
                            "Dalam era yang penuh tantangan ini, di mana teknologi dan informasi berkembang begitu pesat, kita
                            di
                            BLU Kantor UPBU Kelas Kelas I APT. Pranoto Samarinda merasa penting untuk terus beradaptasi.
                            Teknologi
                            telah membawa kita ke Era Revolusi Industri 4.0, yang menuntut kita untuk memanfaatkannya dengan
                            efektif
                            dan efisien. Sejalan dengan semangat revolusi ini, kami berkomitmen untuk memberikan pelayanan yang
                            terbaik kepada masyarakat. Melalui website ini, kami berharap dapat memberikan kemudahan akses
                            informasi
                            seputar kegiatan, tugas dan fungsi BLU Kantor UPBU Kelas I APT. Pranoto Samarinda. Kami mengundang
                            anda
                            untuk menjelajahi situs web kami, mendapatkan informasi yang berguna, dan memberikan masukan yang
                            konstruktif. Semoga dengan kehadiran situs ini, kita dapat meningkatkan kualitas interaksi,
                            informasi,
                            dan komunikasi antara BLU Kantor UPBU Kelas I APT. Pranoto Samarinda dengan masyarakat."
                        </p>
                        <div class="fw-bold d-flex flex-column gap-0">
                            <span>Maeka Rindra Hariyanto, SE., M.Si </span>
                            <span class="fw-normal fst-italic">Kepala BLU Kantor UPBU Kelas I APT. Pranoto Samarinda</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="container-fluid">
            <section class="classes py-5 bg-black text-white mb-5">
                <div class="container">
                    <div class="mb-4">
                        <h2>Topik Utama</h2>
                    </div>
                    <div class="row g-4">
                        @foreach ($topikUtama as $news)
                            <div class="col-md-4">
                                <a href="{{ route('showNews', $news->slug) }}" class="text-decoration-none text-white">
                                    <div class="ratio ratio-4x3 overflow-hidden pilates">
                                        <img src="{{ asset('uploads/' . $news->image) }}" class="w-100"
                                            style="object-position: center center; object-fit: cover;"
                                            alt="{{ $news->title }}">
                                    </div>
                                    <div class="">
                                        <p class="mt-2 email">{{ $news->title }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('berita') }}"
                            class="other-news d-flex align-items-center text-white text-decoration-none ">
                            <span class="fs-7">Lihat Berita Lainnya</span>
                            <i class="bx bx-right-arrow-alt fs-6"></i>
                        </a>
                    </div>
                </div>
            </section>
            
        </div>
        {{-- Section Kerja sama Mitra --}}
        <div class="container-fluid mb-5">
            <div class="container">
                <div class="mb-4 text-center">
                    <h2>Kerja Sama Mitra</h2>
                </div>
            </div>
            <div class="marquee-container">
                <div class="marquee" id="marquee">
                    <!-- Ulangi logo untuk efek marquee mulus -->
                    @php
                        $partners = [
                            ['name' => 'Kementrian Kelautan dan Perikanan', 'logo' => 'frontend/assets/mitra/logo_kkp.svg', 'url' => 'https://kkp.go.id/'],
                            ['name' => 'AirNav', 'logo' => 'frontend/assets/mitra/logo-airnav.png', 'url' => 'https://www.airnavindonesia.co.id/'],
                            ['name' => 'Batik Air', 'logo' => 'frontend/assets/mitra/logo-batik.png', 'url' => 'https://www.batikair.com/id/'],
                            ['name' => 'BMKG', 'logo' => 'frontend/assets/mitra/logo-BMKG.png', 'url' => 'https://www.bmkg.go.id/'],
                        ];
                    @endphp
                    @foreach ($partners as $partner)
                        <a href="{{ $partner['url'] }}" target="_blank" title="{{ $partner['name'] }}"
                            data-bs-toggle="tooltip" data-bs-placement="top">
                            <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['name'] }}">
                        </a>
                    @endforeach
                    @foreach ($partners as $partner)
                        <a href="{{ $partner['url'] }}" target="_blank" title="{{ $partner['name'] }}"
                            data-bs-toggle="tooltip" data-bs-placement="top">
                            <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['name'] }}">
                        </a>
                    @endforeach
                    <!-- Ulangi untuk efek mulus -->
                    @foreach ($partners as $partner)
                        <a href="{{ $partner['url'] }}" target="_blank" title="{{ $partner['name'] }}"
                            data-bs-toggle="tooltip" data-bs-placement="top">
                            <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['name'] }}">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
