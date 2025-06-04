@extends('layouts_landing.landing_app')

@section('title', 'Bandara APT Pranoto')
@section('description', 'Website resmi Bandara APT Pranoto Samarinda')
@section('keywords', 'bandara, apt pranoto, samarinda, penerbangan')

@section('header-class', 'home')

@section('content')
<!-- Weather Widget -->
  <div class="weather-widget">
    <a href="https://www.bmkg.go.id/cuaca/prakiraan-cuaca/64.72.05.1004" target="_blank">
      @if ($weather)
        <div class="weather-content">
          <img src="{{ $weather['weather_icon'] }}" alt="Weather Icon" class="weather-icon">
          <div class="weather-info">
            <h4>Cuaca Saat Ini</h4>
            <p>{{ $weather['weather_desc'] }} - {{ $weather['temperature'] }}°C</p>
            {{-- <p>Kelembapan: {{ $weather['humidity'] }}% | Angin: {{ $weather['wind_speed'] }} km/jam ({{ $weather['wind_direction'] }})</p> --}}
            <p class="weather-time">{{ \Carbon\Carbon::parse($weather['local_datetime'])->format('H:i') }} WITA</p>
          </div>
        </div>
      @else
        <div class="weather-content">
          <p>Data cuaca tidak tersedia saat ini.</p>
        </div>
      @endif
    </a>
  </div>
  <!-- Hero Section -->
  <section id="hero" class="hero section light-background">
    <!-- Konten hero section dari file asli -->
    <div class="container-fluid p-0">
      <swiper-container class="myslider" pagination="true" space-between="30"
        centered-slides="true" autoplay-delay="2500" autoplay-disable-on-interaction="false" loop="true">
        @foreach ($sliders as $index => $slider)
        <swiper-slide style="background-image:url({{ asset('uploads/' . $slider->documents) }});">
        </swiper-slide>

        @endforeach
        
      </swiper-container>
    </div>
    <!-- Sisanya sesuai dengan kode asli -->
    <div class="container-fluid p-0 hero-content">
        <div class="hero-text" 
        data-aos="zoom-in" data-aos-anchor="#example-anchor"
        data-aos-duration="1500">
          <div class="title-Slider">BANDARA A.P.T PRANOTO</div>
          <div class="subtitle-Slider">- Temukan Jadwal Penerbangan, Layanan, dan Informasi di Sini! -</div>
        </div>
        <div class="hero-pesawat row justify-content-center align-items-end mb-3">

          <div class="col-md-4">
            <a href='{{ route("lalulintas") }}' class="info-dekstop-pesawat">

              <div class="card-body text-center" data-aos="fade-up">
                <div class="row align-items-center self-around">
                  <div class="col-md-12 text-white btn-lihat-pesawat">
                    <span >Lihat Detail</span>
                     <i class="bi bi-chevron-right"></i>
                  </div>
                  <div class="col-md-6 text-end">
                    <div class="card-icon text-primary">
                        <i class='bx bx-transfer-alt bx-tada-hover'></i>
                    </div>
    
                  </div>
                  <div class="col-md-6 text-start">
                    <div class="card-count">{{ $totalAngkutanUdara }}</div>
                    
                  </div>
                  
                </div>
                <div class="card-text-container">
                  <p class="card-text text-muted">Pergerakan Pesawat</p>
                </div>
              
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route("kedatangan") }}" class="info-dekstop-pesawat">

              <div class="card-body text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="row align-items-center self-around">
                  <div class="col-md-12 text-white btn-lihat-pesawat">
                    <span >Lihat Detail</span>
                     <i class="bi bi-chevron-right"></i>

                  </div>
                  <div class="col-md-6 text-end">
                    <div class="card-icon text-primary" >
                        <i class='bx bxs-plane-land bx-tada-hover'></i>
                    </div>
    
                  </div>
                  <div class="col-md-6 text-start">
                    <div class="card-count arrivals-count"></div>
                    
                  </div>
                  
                </div>
                <div class="card-text-container">
                  <p class="card-text text-muted">Kedatangan Pesawat</p>
                </div>
                
              </div>
            </a>
          </div>
          <div class="col-md-4">
            <a href="{{ route("keberangkatan") }}" class="info-dekstop-pesawat">
              <div class="card-body text-center" data-aos="fade-up" data-aos-delay="150">
                <div class="row align-items-center self-around">
                  <div class="col-md-12 text-white btn-lihat-pesawat">
                    <span >Lihat Detail</span>
                     <i class="bi bi-chevron-right"></i>

                  </div>
                  <div class="col-md-6 text-end">
                    <div class="card-icon text-primary">
                        <i class='bx bxs-plane-take-off bx-tada-hover'></i>
                      </div>
    
                  </div>
                  <div class="col-md-6 text-start">
                    <div class="card-count departures-count"></div>
                    
                  </div>
                  
                </div>
                <div class="card-text-container">
                  <p class="card-text text-muted">Keberangkatan Pesawat</p>
                </div>
                
              </div>
            </a>
          </div>
        </div>
      </div>
  </section>

  <!-- Why Us Section -->
  <section id="why-us" class="why-us section light-background info-pesawat">
    <div class="container">
        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box" style="text-align: center;">
              <div class="icon-pesawat">
                <i class='bx bx-transfer-alt bx-tada-hover' style="font-size: 69px;"></i>
              </div>
              <h3>{{ $totalAngkutanUdara }}</h3>
              <p style="font-size: 30px;font-weight: 500;">Pergerakan Pesawat</p>
              <div class="text-center">
                <a href="#" class="more-btn"><span>Lihat Detail</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div>
          <!-- End Why Box -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="box-kedatangan">
              <div class="icon-pesawat">
                  <i class='bx bxs-plane-land bx-tada-hover' style="font-size: 69px;"></i>

              </div>
              
              <h3 class="arrivals-count"></h3>
              <p style="font-size: 25px;font-weight: 500;">Kedatangan Pesawat Hari ini</p>
              <div class="text-center">
                <a href="#" class="more-btn"><span>Lihat Detail</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box" style="text-align: center;">
              <div class="icon-pesawat">
                <i class='bx bxs-plane-take-off bx-tada-hover' style="font-size: 69px;"></i>

              </div>
              
              <h3 class="departures-count"></h3>
              <p style="font-size: 25px;font-weight: 500;">Keberangkatan Pesawat Hari ini</p>
              <div class="text-center">
                <a href="#" class="more-btn"><span>Lihat Detail</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          

        </div>

      </div>
  </section>

  <!-- About Section -->
  <section id="about" class="about section">
    <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Sambutan<br></h2>
        <p><span>Kepala BLU Kantor UPBU Kelas I</span> <span class="description-title">APT. Pranoto Samarinda</span></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">
          <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
            <img src="{{ asset('assets_landing/img/pejabat/DSC08182.JPG') }}" class="chief-image object-fit-cover text-center" alt="">
            
          </div>
          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
            <div class="content ps-0 sambutan">
              <p class="fst-italic">
                "Dalam era yang penuh tantangan ini, di mana teknologi dan informasi berkembang begitu pesat, kita di BLU Kantor UPBU Kelas I APT. Pranoto Samarinda merasa penting untuk terus 
                beradaptasi. Teknologi telah membawa kita ke Era Revolusi Industri 4.0, yang menuntut kita untuk memanfaatkannya 
                dengan efektif dan efisien."
              </p>
              
              <p class="fst-italic">
                "Sejalan dengan semangat revolusi ini, kami berkomitmen untuk memberikan pelayanan yang 
                terbaik kepada masyarakat. Melalui website ini, kami berharap dapat memberikan kemudahan akses 
                informasi seputar kegiatan, tugas, dan fungsi BLU Kantor UPBU Kelas I APT. Pranoto Samarinda."
              </p>
              <p class="fst-italic">
                "Kami mengundang Anda untuk menjelajahi situs web kami, mendapatkan informasi yang berguna, dan 
                memberikan masukan yang konstruktif. Semoga dengan kehadiran situs ini, kita dapat meningkatkan 
                kualitas interaksi, informasi, dan komunikasi antara BLU Kantor UPBU Kelas I APT. Pranoto Samarinda dengan masyarakat."
              </p>
              <div class="signature">Maeka Rindra Hariyanto, SE., M.Si</div>
              <div class="title">Kepala BLU Kantor UPBU Kelas I APT. Pranoto Samarinda</div>

              
            </div>
          </div>
        </div>

      </div>
  </section>

  <!-- News Section -->
  <section id="news" class="news section light-background">
    <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Berita</h2>
        <p><span>Topik</span> <span class="description-title">Utama</span></p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="wrapper">

            <div class="row justify-content-center">
                @if ($headlines->isEmpty())
                    <div class="col-12 text-center">
                        <p>Tidak ada berita utama saat ini.</p>
                    </div>
                @else
                    @foreach ($headlines as $index => $news)
                        <div class="col-lg-4 col-md-6 mb-3" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}" ontouchstart="this.classList.toggle('hover');">
                            <div class="container-news">
                                <div class="front" style="background-image: url({{ asset('uploads/' . $news->image) }})">
                                    <div class="inner">
                                        <p>{{ $news->title }}</p>
                                        <span></span>
                                    </div>
                                </div>
                                <div class="back">
                                    <div class="inner">
                                        <p>{{ Str::limit($news->content, 60) }}</p>
                                        <a href="{{ route('news.show', $news->slug) }}" class="more-link">
                                        {{-- <a href="#" class="more-link"> --}}
                                            Lihat Detail
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-center" data-aos="fade-left">
            <button id="berita-lain" class="learn-more">
                <span class="circle" aria-hidden="true">
                <span class="icon arrow"></span>
                </span>
                <span class="button-text">Berita Lainnya</span>
            </button>
            <script>
              const b = document.getElementById('berita-lain');
              b.addEventListener('click',()=> {
                window.location.href = '{{ route("berita") }}';
              })
            </script>
            </div>
        </div>        
    </div>
  </section>

  <!-- Gallery Section -->
  <section id="gallery" class="gallery section">
     <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "centeredSlides": true,
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 5,
                  "spaceBetween": 0
                },
                "768": {
                  "slidesPerView": 7,
                  "spaceBetween": 20
                },
                "1200": {
                  "slidesPerView": 9,
                  "spaceBetween": 15
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><a target="_blank" href="https://www.airnavindonesia.co.id/"><img src="{{ asset('assets_landing/img/mitra/logo-airnav.png') }}" class="img-fluid" alt="airnav"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.batikair.com/id/"><img src="assets_landing/img/mitra/logo-batik.png" class="img-fluid" alt="batik air"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.bmkg.go.id/"><img src="assets_landing/img/mitra/logo-BMKG.png" class="img-fluid" alt="BMKG"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.lionair.co.id/"><img src="assets_landing/img/mitra/logo-lion.png" class="img-fluid" alt="lion air"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://kaltimprov.go.id/"><img src="assets_landing/img/mitra/Logo-Pemprov.png" class="img-fluid" alt="pemprov"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://pertamina.com/"><img src="assets_landing/img/mitra/logo-pertamina.png" class="img-fluid" alt="pertamina"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.superairjet.com/id/"><img src="assets_landing/img/mitra/logo-SAJ.png" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://kkp.go.id/"><img src="assets_landing/img/mitra/logo_kkp.svg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.citilink.co.id/"><img src="assets_landing/img/mitra/logo-citilink.png" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://smartaviation.co.id/home"><img src="assets_landing/img/mitra/logo-smart.jpg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.airnavindonesia.co.id/"><img src="{{ asset('assets_landing/img/mitra/logo-airnav.png') }}" class="img-fluid" alt="airnav"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.batikair.com/id/"><img src="assets_landing/img/mitra/logo-batik.png" class="img-fluid" alt="batik air"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.bmkg.go.id/"><img src="assets_landing/img/mitra/logo-BMKG.png" class="img-fluid" alt="BMKG"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.lionair.co.id/"><img src="assets_landing/img/mitra/logo-lion.png" class="img-fluid" alt="lion air"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://kaltimprov.go.id/"><img src="assets_landing/img/mitra/Logo-Pemprov.png" class="img-fluid" alt="pemprov"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://pertamina.com/"><img src="assets_landing/img/mitra/logo-pertamina.png" class="img-fluid" alt="pertamina"></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.superairjet.com/id/"><img src="assets_landing/img/mitra/logo-SAJ.png" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://kkp.go.id/"><img src="assets_landing/img/mitra/logo_kkp.svg" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://www.citilink.co.id/"><img src="assets_landing/img/mitra/logo-citilink.png" class="img-fluid" alt=""></a></div>
            <div class="swiper-slide"><a target="_blank" href="https://smartaviation.co.id/home"><img src="assets_landing/img/mitra/logo-smart.jpg" class="img-fluid" alt=""></a></div>
          </div>
          <!-- <div class="swiper-pagination"></div> -->
        </div>

      </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="contact section light-background">
    <div class="container section-title" >
        <h2>Contact</h2>
        <p><span>Need Help?</span> <span class="description-title">Contact Us</span></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="mb-5" >
          <iframe style="width: 100%; height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31917.868178300705!2d117.2570112!3d-0.3735552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df5d983539c7c29%3A0x8d05ebe56ab63de2!2sBandar%20Udara%20Aji%20Pangeran%20Tumenggung%20Pranoto%20(AAP)!5e0!3m2!1sid!2sid!4v1747638418231!5m2!1sid!2sid" frameborder="0" allowfullscreen=""></iframe>
        </div><!-- End Google Maps -->

        <div class="row justify-content-center gy-4">

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center">
              <i class="icon bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Alamat</h3>
                <p>Jl. Poros Samarinda – Bontang, Kel. Sungai Siring, Samarinda – Kalimantan Timur 75119</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center" >
              <i class="icon bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Nomor Telepon</h3>
                <p>+62 811 551 944</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          <div class="col-md-6">
            <div class="info-item d-flex align-items-center">
              <i class="icon bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email</h3>
                <p>mail.aptpranotoairport@gmail.com</p>
              </div>
            </div>
          </div><!-- End Info Item -->

          

        </div>

        <form action="{{ route('contact.submit') }}" method="post" class="php-email-form">
          @csrf
          <div class="row gy-4">
            <div class="col-md-6">
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama" value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Nomor Telepon" value="{{ old('phone_number') }}" required>
              @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <div class="form-floating">
                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                  <option value="" {{ old('subject') == '' ? 'selected' : '' }}>Pilih Kategori</option>
                  <option value="Informasi" {{ old('subject') == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                  <option value="Keluhan" {{ old('subject') == 'Keluhan' ? 'selected' : '' }}>Keluhan</option>
                  <option value="Saran" {{ old('subject') == 'Saran' ? 'selected' : '' }}>Saran</option>
                  <option value="Apresiasi" {{ old('subject') == 'Apresiasi' ? 'selected' : '' }}>Apresiasi dan Ucapan Terima kasih</option>
                </select>
                <label for="subject">Kategori</label>
                @error('subject')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-12">
              <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="6" placeholder="Pesan" required>{{ old('message') }}</textarea>
              @error('message')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-12">
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
            </div>
            <div class="col-md-12 text-center">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Pesan Anda telah terkirim. Terima kasih!</div>
              <button type="submit">Kirim Pesan</button>
            </div>
          </div>
        </form>

      </div>
  </section>
@endsection
@push('page-scripts')
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
      function updateFlightStats() {
        fetch('/api/flight-stats')
        .then(response => response.json())
        .then(data => {
          console.log("arrivals", data);
          if (data.success) {
            const arrival = document.querySelectorAll(".arrivals-count")
            const departure = document.querySelectorAll(".departures-count")
                    
            arrival.forEach((el)=> {
              el.textContent = data.data.arrivals
            });
            departure.forEach((el)=> {
              el.textContent = data.data.departures
            });
            
            // document.getElementById('arrivals-count').textContent = data.data.arrivals;
            //           document.getElementById('departures-count').textContent = data.data.departures;
                  }
              });
      }
      updateFlightStats()
        // Update setiap 1 menit
      setInterval(updateFlightStats, 60000);
      
      // AJAX for send form contact us
      document.addEventListener('DOMContentLoaded', function () {
          const form = document.querySelector('.php-email-form');
          const loading = form.querySelector('.loading');
          const errorMessage = form.querySelector('.error-message');
          const sentMessage = form.querySelector('.sent-message');
          let isSubmitting = false;


          if (form) {
              form.addEventListener('submit', function (e) {
                  e.preventDefault();
                  if (isSubmitting) return;
                  isSubmitting = true;

                  const submitButton = form.querySelector('button[type="submit"]');
                  submitButton.disabled = true;
                  submitButton.textContent = 'Mengirim...';

                  loading.classList.add('show');
                  errorMessage.classList.remove('d-block');
                  sentMessage.classList.remove('show');

                  fetch(form.action, {
                      method: 'POST',
                      body: new FormData(form),
                      headers: {
                          'X-Requested-With': 'XMLHttpRequest',
                          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                      }
                  })
                  .then(response => {
                      if (!response.ok) {
                          return response.json().then(errorData => {
                              if (errorData.success === true) {
                                  // Jika server mengembalikan success: true dengan status non-200 (misalnya 422), tangani sebagai sukses
                                  return errorData;
                              }
                              throw new Error(errorData.errors?.join(' ') || 'Network response was not ok: ' + response.status);
                          });
                      }
                      return response.json();
                  })
                  .then(data => {
                      loading.classList.remove('show');
                      errorMessage.classList.remove('d-block'); // Pastikan pesan error disembunyikan
                      sentMessage.classList.remove('show'); // Pastikan pesan sukses disembunyikan
                      if (data.success) {
                        console.log(data);
                          
                        sentMessage.textContent = data.message || 'Pesan Anda telah terkirim. Terima kasih!2';
                          sentMessage.classList.add('show');
                          form.reset();
                          grecaptcha.reset();
                          setTimeout(() => {
                              sentMessage.classList.remove('show');
                          }, 5000);
                      } else {
                          errorMessage.innerHTML = data.errors.join('<br>') || 'Terjadi kesalahan yang tidak diketahui.';
                          errorMessage.classList.add('show');
                          setTimeout(() => {
                              errorMessage.classList.remove('show');
                          }, 5000);
                      }
                  })
                  .catch(error => {
                      loading.classList.remove('show');
                      errorMessage.classList.remove('show'); // Pastikan pesan error disembunyikan
                      sentMessage.classList.remove('show'); // Pastikan pesan sukses disembunyikan
                      errorMessage.innerHTML = error.message || 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
                      errorMessage.classList.add('show');
                      console.error('Form submission error:', error);
                      setTimeout(() => {
                          errorMessage.classList.remove('show');
                      }, 5000);
                  })
                  .finally(() => {
                      isSubmitting = false;
                      submitButton.disabled = false;
                      submitButton.textContent = 'Kirim Pesan';
                  });
              });
          }
      });
  </script>
@endpush
@push('page-styles')
  <link href="{{ asset('assets_landing/css/beranda.css') }}" rel="stylesheet">
  <link href="{{ asset('assets_landing/css/pesawat.css') }}" rel="stylesheet">
  <link href="{{ asset('assets_landing/css/button-more.css') }}" rel="stylesheet">
@endpush