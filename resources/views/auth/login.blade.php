<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login & Register - Bandara APT Pranoto</title>
    <link rel="stylesheet" href="{{ asset('assets_login/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_login/bootstrap-icons/bootstrap-icons.css') }}">
    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
            <img src="{{ asset('assets_login/images/APT04948.JPG') }}" alt="photo" class="background-form">
            <div class="text">
                <span class="text-1">Bergabung untuk pengalaman terbaik</span>
                <span class="text-2">Daftar sekarang</span>
            </div>
        </div>
    </div>
    <div class="forms">
        <div class="form-content">
            <div class="login-form">
                <img src="{{ asset('assets_login/images/logo-apt.svg') }}" alt="Logo APT Pranoto" class="logo-text-left">
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
                <img src="{{ asset('assets_login/images/logo-apt.svg') }}" alt="Logo APT Pranoto" class="logo-text-right">
                <div class="title">Daftar</div>
                <form method="POST" action="{{ route('register') }}">
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
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="input-box">
                            <i class="bi bi-telephone"></i>
                            <input id="phone" type="tel" class="@error('phone') is-invalid @enderror" name="phone" pattern="[0-9]{10,13}" placeholder="Masukkan nomor telepon Anda" value="{{ old('phone') }}" required autocomplete="tel">
                        </div>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="input-box">
                            <i class="bi bi-geo-alt"></i>
                            <input id="address" type="text" class="@error('address') is-invalid @enderror" name="address" placeholder="Masukkan alamat Anda" value="{{ old('address') }}" required autocomplete="street-address">
                        </div>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="input-box">
                            <i class="bi bi-lock"></i>
                            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" placeholder="Masukkan kata sandi Anda" required autocomplete="new-password">
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="input-box">
                            <i class="bi bi-lock"></i>
                            <input id="password_confirmation" type="password" class="@error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Konfirmasi kata sandi Anda" required autocomplete="new-password">
                        </div>
                        @error('password_confirmation')
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

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk menampilkan SweetAlert
    const showAlert = (icon, title, text) => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonColor: '#f8b281',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-custom',
                title: 'swal2-custom-title',
                content: 'swal2-custom-content'
            }
        });
    };

    // Cek session untuk pesan registrasi sukses
    @if (session('unverified'))
        showAlert('success', 'Pendaftaran Berhasil', '{{ session('unverified') }}');
    @endif

    // Cek session untuk login sukses
    @if (session('success'))
        showAlert('success', 'Login Berhasil', '{{ session('success') }}');
    @endif

    // Cek error login (email/password salah atau akun belum diverifikasi)
    @if ($errors->has('credentials'))
        showAlert('error', 'Login Gagal', 'Email atau password salah.');
    @elseif ($errors->has('unverified'))
        showAlert('warning', 'Akun Belum Diverifikasi', '{{ $errors->first('unverified') }}');
    @endif

    // Cek error registrasi
    @if ($errors->has('name') || $errors->has('email') || $errors->has('phone') || $errors->has('address') || $errors->has('password') || $errors->has('password_confirmation'))
        showAlert('error', 'Registrasi Gagal', 'Silakan periksa kembali data yang Anda masukkan.');
    @endif
</script>

<style>
    /* Custom styling untuk SweetAlert2 */
    .swal2-custom {
        font-family: 'Poppins', sans-serif;
        border-radius: 10px;
    }
    .swal2-custom-title {
        color: #1b1b1b;
        font-size: 1.5rem;
    }
    .swal2-custom-content {
        color: #555;
        font-size: 1rem;
    }
    .swal2-confirm {
        border-radius: 5px !important;
        padding: 0.5rem 1.5rem !important;
        font-size: 1rem !important;
    }
</style>
</body>
</html>