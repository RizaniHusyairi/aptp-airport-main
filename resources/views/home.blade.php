@extends('layouts.laravel-default')

@section('title', 'APT PRANOTO')

@push('styles')
    <style>
        /* Section Topik Utama */
        .news-section {
            background: linear-gradient(135deg, #1A252F 0%, #2C3E50 100%);
            padding: 60px 20px;
            color: #ECF0F1;
            position: relative;
            overflow: hidden;
        }

        .news-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
            color: #F1C40F;
            text-transform: uppercase;
        }

        .news-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .news-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: opacity 0.3s ease;
        }

        .news-card .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
        }

        .news-card:hover .card-overlay {
            opacity: 1;
        }

        .news-card .card-content {
            padding: 15px;
            position: relative;
            z-index: 1;
        }

        .news-card .card-content h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #F1C40F;
        }

        .news-card .card-content a {
            color: #ECF0F1;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .news-card .card-content a:hover {
            color: #F1C40F;
        }

        /* Dekorasi */
        .decoration-line {
            width: 50px;
            height: 3px;
            background-color: #F1C40F;
            margin: 10px auto;
        }

        /* Link Lainnya */
        .news-more {
            text-align: center;
            margin-top: 30px;
        }

        .news-more a {
            color: #F1C40F;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .news-more a:hover {
            color: #ECF0F1;
        }

        /* Responsivitas */
        @media (max-width: 768px) {
            .news-section h2 {
                font-size: 2rem;
            }

            .news-card img {
                height: 150px;
            }

            .news-card .card-content h3 {
                font-size: 1rem;
            }
        }


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

        /* Latar Belakang Dinamis */
        .home-section {
            background: linear-gradient(135deg, #E3F2FD 0%, #FFFFFF 100%);
            background-size: cover;
            padding: 60px 20px;
            position: relative;
            min-height: 80vh;
        }

        /* Kontainer Utama */
        .welcome-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 40px;
            padding: 20px;
        }

        /* Gambar Kepala BLU */
        .chief-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .chief-image:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Teks Sambutan */
        .welcome-text {
            color: #2C3E50;
            max-width: 600px;
        }

        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #2980B9;
        }

        .welcome-text h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #34495E;
        }

        .welcome-text p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            text-align: justify;
        }

        .welcome-text .signature {
            font-weight: 600;
            color: #2980B9;
        }

        .welcome-text .title {
            font-style: italic;
            color: #7F8C8D;
        }

        /* Tombol Aksi */
        .action-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2980B9;
            color: #FFFFFF;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #1A5276;
            color: #FFFFFF;
        }

        /* Responsivitas */
        @media (max-width: 768px) {
            .welcome-container {
                flex-direction: column;
                text-align: center;
            }

            .chief-image {
                max-width: 100%;
                max-height: 300px;
            }

            .welcome-text h1 {
                font-size: 2rem;
            }

            .welcome-text h2 {
                font-size: 1.2rem;
            }
        }

        /* Dekorasi Tambahan */
        .decoration-line {
            width: 100px;
            height: 4px;
            background-color: #2980B9;
            margin: 20px auto;
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
        <!-- Section Sambutan Kepala BLU -->
        <div class="home-section">
            <div class="welcome-container">
                <img src="{{ asset('frontend/assets/home/sambutan_image.jpg') }}" alt="Kepala BLU"
                    class="chief-image object-fit-cover">

                <div class="welcome-text">
                    <h1>Kepala BLU Kantor UPBU Kelas I APT. Pranoto Samarinda</h1>
                    <div class="decoration-line"></div>
                    <h2>Sambutan</h2>
                    <p>"Dalam era yang penuh tantangan ini, di mana teknologi dan informasi berkembang begitu pesat, kita di BLU Kantor UPBU Kelas I APT. Pranoto Samarinda merasa penting untuk terus beradaptasi. Teknologi telah membawa kita ke Era Revolusi Industri 4.0, yang menuntut kita untuk memanfaatkannya dengan efektif dan efisien."</p>
                    <p>Sejalan dengan semangat revolusi ini, kami berkomitmen untuk memberikan pelayanan yang terbaik kepada masyarakat. Melalui website ini, kami berharap dapat memberikan kemudahan akses informasi seputar kegiatan, tugas, dan fungsi BLU Kantor UPBU Kelas I APT. Pranoto Samarinda."</p>
                    <p>Kami mengundang Anda untuk menjelajahi situs web kami, mendapatkan informasi yang berguna, dan memberikan masukan yang konstruktif. Semoga dengan kehadiran situs ini, kita dapat meningkatkan kualitas interaksi, informasi, dan komunikasi antara BLU Kantor UPBU Kelas I APT. Pranoto Samarinda dengan masyarakat."</p>
                    <div class="signature">Maeka Rindra Hariyanto, SE., M.Si</div>
                    <div class="title">Kepala BLU Kantor UPBU Kelas I APT. Pranoto Samarinda</div>
                    <a class="action-button mt-3">Hubungi Kami</a>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <section class="news-section py-5">
                <h2>Topik Utama</h2>
                <div class="decoration-line"></div>
                <div class="news-container">
                @foreach ($topikUtama as $news)
                    <div class="news-card">
                        <img src="{{ asset('uploads/' . $news->image) }}" class="w-100" alt="{{ $news->title }}" loading="lazy">
                        <div class="card-overlay"></div>
                        <div class="card-content">
                            <h3>{{ $news->title }}</h3>
                            <a href="{{ route('showNews', $news->slug) }}" class="text-decoration-none">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="news-more">
                    <a href="{{ route('berita') }}">Lihat Berita Lainnya <i class='bx bx-right-arrow-alt'></i></a>
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

