<footer id="footer" class="footer dark-background">
  <div class="container">
    <div class="row gy-3">
      
      <div class="col-lg-3 col-md-6 d-flex">
        <i class="bi bi-geo-alt icon"></i>
        <div class="address">
          <h4>Alamat</h4>
          <p>Jl. Poros Samarinda – Bontang, </p>
          <p>Kel. Sungai Siring, Samarinda – Kalimantan Timur 75119</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 d-flex">
        <i class="bi bi-telephone icon"></i>
        <div>
          <h4>Info Kontak</h4>
          <p>
            <strong>Phone:</strong> <span>+62 811 551 944</span><br>
            <strong>Email:</strong> <span>mail.aptpranotoairport@gmail.com</span><br>
          </p>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <h4>Layanan Publik</h4>
        <ul class="list-unstyled">
          @if(skmSetting()['active'])
            <li class="mb-2">
              <a href="{{ skmSetting()['url'] }}" target="_blank" rel="noopener" class="text-reset">
                <i class="bi bi-clipboard2-check me-1"></i> {{ skmSetting()['label'] }}
              </a>
            </li>
          @endif
          {{-- Kebijakan Privasi sengaja tidak diulang di sini, sudah ada di blok copyright --}}
          <li class="mb-2"><a href="{{ route('standarPelayanan') }}" class="text-reset">Standar Pelayanan</a></li>
          <li class="mb-2"><a href="{{ route('faq') }}" class="text-reset">FAQ</a></li>

          {{--
            Tautan terkait dirender rata dari helper ter-cache.
            Bila daftar tumbuh melewati ~8 butir, ganti blok ini dengan satu
            tautan ke route('tautanTerkait') saja agar footer tidak sesak.
          --}}
          @foreach(externalLinks() as $ttLinks)
            @foreach($ttLinks as $ttLink)
              <li class="mb-2">
                <a href="{{ $ttLink['url'] }}" target="_blank" rel="noopener" class="text-reset">{{ $ttLink['name'] }}</a>
              </li>
            @endforeach
          @endforeach
        </ul>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="d-flex flex-column">
          <div class="mb-4">
            <h4>Ikuti Kami</h4>
            <div class="social-links d-flex">
              <a href="https://x.com/aptp_airport" target="_blank" class="twitter"><i class="bi bi-twitter-x"></i></a>
              <a href="https://www.youtube.com/@aptpranotoairport" target="_blank" class="youtube"><i class="bi bi-youtube"></i></a>
              <a href="https://www.facebook.com/share/1EyVSyu6Un/" target="_blank" class="facebook"><i class="bi bi-facebook"></i></a>
              <a href="https://www.instagram.com/aptpranotoairport" target="_blank" class="instagram"><i class="bi bi-instagram"></i></a>
              <a href="https://www.tiktok.com/@aptpranotoairport" target="_blank" class="tiktok"><i class="bi bi-tiktok"></i></a>
            </div>

          </div>
          <div class="logo-f">
            <div class="col-lg-3 logo-footer d-flex">
                <img src="{{ asset('assets_landing/img/logo/Logo-BLU-Speed.png') }}" class="img-fluid me-3" alt="Logo-BLU">
                <img src="{{ asset('assets_landing/img/logo/Logo_Kementerian_Perhubungan_Indonesia_(Kemenhub).png') }}" class="img-fluid" alt="Logo-BLU">
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  <div class="container copyright text-center mt-4">
    <p>© <span>Copyright</span><span> Kantor Unit Penyelenggara Bandara Udara Kelas I <strong class="px-1 sitename">A.P.T Pranoto Samarinda</strong></span></p>
    <p class="mt-2 mb-0"><a href="{{ route('kebijakan-privasi') }}" class="text-reset">Kebijakan Privasi</a></p>
  </div>
</footer>