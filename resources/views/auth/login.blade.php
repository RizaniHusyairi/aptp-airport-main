<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="id" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('assets_login/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_login/bootstrap-icons/bootstrap-icons.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="{{ asset('assets_login/images/APT_1682.JPG') }}" alt="photo" class="background-form">
        <div class="text">
          <span class="text-1">Selamat Datang di Bandara A.P.T Pranoto</span>
          <span class="text-2">Masuk untuk akses layanan kami</span>
        </div>
      </div>
      <div class="back">
        <img src="{{ asset('assets_login/images/APT04948.JPG') }}" alt="" class="background-form">
        <div class="text">
          <span class="text-1">Bergabung untuk pengalaman terbaik</span>
          <span class="text-2">Daftar sekarang</span>
        </div>
      </div>
    </div>
    <div class="forms">
      <div class="form-content">
        <div class="login-form">
            <img src="{{ asset('assets_login/images/logo-apt.svg') }}" alt="" class="logo-text-left">
            <div class="title">Login</div>
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-boxes">
              <div class="input-box">
                <i class="bi bi-envelope"></i>
                <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autocomplete="email" autofocus>
              </div>
              @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="input-box">
                <i class="bi bi-lock"></i>
                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" placeholder="Masukkan kata sandi Anda" required autocomplete="current-password">
              </div>
              @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="button input-box">
                <input type="submit" value="Masuk">
              </div>
              <div class="text sign-up-text">Belum punya akun? <label for="flip">Daftar sekarang</label></div>
            </div>
        </form>
      </div>
        <div class="signup-form">
          <img src="{{ asset('assets_login/images/logo-apt.svg') }}" alt="" class="logo-text-right">

          <div class="title">Daftar</div>
        <form method="POST" action="{{ route('register') }}" >
            @csrf
            <div class="input-boxes">
              <div class="input-box">
                  <i class="bi bi-person"></i>
                  <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required autocomplete="name" autofocus>
                </div>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
                <div class="input-box">
                  <i class="bi bi-envelope"></i>
                  <input id="email_new" type="email" class="@error('email_new') is-invalid @enderror" name="email_new" placeholder="Masukkan Email Anda" value="{{ old('email_new') }}" required autocomplete="email_new" autofocus>
                </div>
                @error('email_new')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="input-box">
                  <i class="bi bi-telephone"></i>
                  <input id="phone" type="tel" class="@error('phone') is-invalid @enderror" name="phone" pattern="[0-9]{10,13}" placeholder="Masukkan Nomor Telepon Anda" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                </div>
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="input-box">
                  <i class="bi bi-geo-alt"></i>
                  <input id="address" type="text" class="@error('address') is-invalid @enderror" name="address" placeholder="Masukkan Alamat Anda" value="{{ old('address') }}" required autocomplete="address" autofocus>
                </div>
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="input-box">
                  <i class="bi bi-lock"></i>
                  <input id="password_new" type="password" name="password_new" class="@error('password_new') is-invalid @enderror" placeholder="Masukkan Kata sandi Anda" required>
                </div>
                @error('password_new')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="input-box">
                  <i class="bi bi-lock"></i>
                  <input type="password" name="confirm_password_new" class="@error('confirm_password_new') is-invalid @enderror" placeholder="Konfirmasi kata sandi Anda" required>
                </div>
                @error('confirm_password_new')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              <div class="button input-box">
                <input type="submit" value="Daftar">
              </div>
              <div class="text sign-up-text">Sudah punya akun? <label for="flip">Masuk sekarang</label></div>
            </div>
      </form>
    </div>
    </div>
    </div>
  </div>
</body>
</html>
