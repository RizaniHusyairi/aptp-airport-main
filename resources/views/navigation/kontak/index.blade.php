@extends('layouts.laravel-default')

@section('title', 'Pejabat Bandara | APT PRANOTO')

@push('styles')
    <style>
        @media only screen and (max-width: 750px) {
            #responsible .image-responsible {
                width: 80%;
            }
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
                       <div class="address-text">
                            <span class="label">Email:</span>
                            <a class="text-decoration-none" href="mailto:humas@aptpranotoairport.id">humas@aptpranotoairport.id</a>
                       </div>
                   </div>
                   <div class="address-box d-flex align-items-center mb-3">
                       <div class="address-icon">
                        <i class='bx bxs-phone'></i>
                       </div>
                       <div class="address-text">
                           <span class="label">WA / Tlp:</span>
                           <a class="text-decoration-none" href="#">(0541) 2831593</a>
                       </div>
                   </div>
                   <div class="address-box d-flex align-items-center mb-3">
                       <div class="address-icon">
                        <i class='bx bxs-map'></i>
                       </div>
                       <div class="address-text">
                           <span class="label">Alamat:</span>
                           <div class="desc">Jl. Poros Samarinda – Bontang, Kel. Sungai Siring, Samarinda – Kalimantan Timur 75119</div>
                       </div>
                   </div>

                </div>
           </div>

        </div>
        <div class="col-lg-8">
            <h2 class="fw-bold fs-1">Kontak Kami</h2>
            <form action="{{ route('pengaduan.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <input class="form-control form-control-lg" type="text" placeholder="Nama Lengkap" aria-label=".form-control-lg example">

                    </div>
                    <div class="col">
                        <input class="form-control form-control-lg" type="text" placeholder="Email" aria-label=".form-control-lg example">

                    </div>
                </div>
                <div class="mb-3">
                    <input class="form-control form-control-lg" type="text" placeholder="Topik" aria-label=".form-control-lg example">                    
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"></textarea>
                        <label for="floatingTextarea2">Isi Pesan Anda</label>
                      </div>
                </div>
                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
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
