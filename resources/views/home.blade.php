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
            transition: transform .5s ease;
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
                transform: translateX(-100%);
            }
        }

        /* Styling untuk Widget Cuaca */
        .weather-widget {
            position: absolute;
            bottom: 30px;
            right: 20px;
            width: 250px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 5;
            text-decoration: none;
            color: inherit;
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .weather-widget:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            background-color: rgb(255, 255, 255);
            color: inherit;
            cursor: pointer;
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

            .contact-icon {
                width: 50px;
                height: 50px;
                bottom: 10px;
                right: 10px;
            }
            .contact-panel {
                width: 90%;
                right: -100%; /* Mulai di luar layar */
            }
            .contact-panel.open {
                right: 5%;
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
            
        /* Styling untuk Widget Kontak */
        .contact-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .contact-icon:hover {
            transform: scale(1.1);
        }

        .contact-panel {
            position: fixed;
            bottom: 90px;
            right: -400px; /* Mulai di luar layar */
            width: 350px;
            max-height: 80vh;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 999;
            overflow-y: auto;
            transition: right 0.3s ease;
        }

        .contact-panel.open {
            right: 20px; /* Slide-in ke posisi */
        }

        .contact-panel .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .contact-panel .form-control.is-invalid {
            border-color: #dc3545;
        }

        .contact-panel .invalid-feedback {
            display: none;
        }

        .contact-panel .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }

        @media only screen and (max-width: 750px) {
            .image-chief {
                width: 100%;
                max-height: 300px;
                /* tinggi dikurangi di mobile */
            }
        }

        .sliderh{
            height: 100vh;
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
                </div>
                <!-- Widget Cuaca -->
                <a href="https://www.bmkg.go.id/cuaca/prakiraan-cuaca/64.72.05.1004" target="_blank" class="weather-widget">
                
                    <h6><i class='bx bx-cloud'></i>Prakiraan Cuaca</h6>
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
                </a>

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
                    <img src="
                {{ asset('frontend/assets/home/sambutan_image.jpg') }}" alt="sambutan"
                        class="object-fit-cover image-chief">
                    <Prakiraandivd-flex flex-column gap-5 justify
                    -content-center">
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
        <!-- Widget Kontak -->
        <div class="contact-icon" id="contactIcon">
            <i class='bx bx-envelope' style="font-size: 1.5rem;"></i>
        </div>
        <div class="contact-panel" id="contactPanel">
            <button class="close-btn" id="closeContact"><i class='bx bx-x'></i></button>
            <h5 class="mb-3"><i class='bx bx-envelope'></i> Kontak Kami</h5>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('pengaduan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <label for="name">Nama Lengkap</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="phone_number" placeholder="Nomor Telepon" value="{{ old('phone_number') }}" required>
                        <label for="phone_number">Nomor Telepon/WA</label>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" id="subject" placeholder="Topik" value="{{ old('subject') }}" required>
                        <label for="subject">Topik</label>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <textarea class="form-control @error('message') is-invalid @enderror" placeholder="Isi Pesan Anda" name="message" id="message" required>{{ old('message') }}</textarea>
                        <label for="message">Isi Pesan Anda</label>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </div>
            </form>
        </div>
    </div>

    
@endsection

