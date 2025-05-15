@extends('layouts.laravel-default')

@section('title', 'Pejabat Bandara | APT PRANOTO')

@push('styles')
    <style>
        @media only screen and (max-width: 750px) {
            #responsible .image-responsible {
                width: 80%;
            }
        }
        .contact-anim{
            transition: transform 0.3s ease;
        }
        .contact-anim:hover{
            transform: translateX(10px);
        }
        
    </style>
@endpush

@section('content')
<section class="container pb-5" id="responsible">
    <div class="row">
        <div class="col-lg-4">
            <div class="card contact-box p-4">
                <div class="card-body">
                    <div class="sec-title mb-45">
                        <span class="sub-text new-text white-color">Silahkan hubungi kami jika mendapat kendala melalui alamat yang tercantum dibawah ini.</span>
                        <h2 class="title white-color">Hubungi Kami</h2>
                    </div>
                    <div class="address-box d-flex align-items-center mb-3">
                       <div class="address-icon">
                            <i class='bx bxl-gmail'></i>
                       </div>
                       <div class="address-text row">
                            <span class="label">Email:</span>
                            <a class="text-decoration-none contact-anim" href="mailto:mail.aptpranotoairport@gmail.com">mail.aptpranotoairport@gmail.com</a>
                       </div>
                   </div>
                   <div class="address-box d-flex align-items-center mb-3">
                       <div class="address-icon">
                        <i class='bx bxs-phone'></i>
                       </div>
                       <div class="address-text row">
                           <span class="label">Nomor telepon:</span>
                           <a class="text-decoration-none contact-anim" href="https://wa.me/62811551944">+62 811 551 944</a>
                       </div>
                   </div>
                   <div class="address-box d-flex align-items-center mb-3">
                       <div class="address-icon">
                        <i class='bx bxs-map'></i>
                       </div>
                       <div class="address-text row">
                           <span class="label">Alamat:</span>
                           <div class="desc">Jl. Poros Samarinda – Bontang, Kel. Sungai Siring, Samarinda – Kalimantan Timur 75119</div>
                       </div>
                   </div>

                </div>
           </div>

        </div>
        <div class="col-lg-8">
            <h2 class="fw-bold fs-1">Kontak Kami</h2>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('pengaduan.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <label for="name">Nama Lengkap</label>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                            <label for="email">Email</label>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="tel" pattern="(\+628[0-9]{8,11}|08[0-9]{8,11})?" class="form-control" name="phone_number" id="phone_number" placeholder="Nomor Telepon" value="{{ old('phone_number') }}" required>
                        <label for="phone_number">Nomor Telepon</label>
                    </div>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{ old('subject') }}" required>
                        <label for="subject">Topik</label>
                    </div>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Isi Pesan Anda" name="message" id="message" required>{{ old('message') }}</textarea>
                        <label for="message">Isi Pesan Anda</label>
                    </div>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class=" contact-box btn">Kirim pesan</button>
                </div>
            </form>
        </div>

    </div>


</section>



@endsection
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
