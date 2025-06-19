@extends('layouts_landing.landing_app')

@section('title', $service->name . ' - Bandara APT Pranoto')

@section('content')
<section id="about" class="about section pt-6">
    <div class="container section-title" data-aos="fade-up">
        <h2>Layanan<br></h2>
        <p><span>Pengajuan</span> <span class="description-title">{{ $service->name }}</span></p>
    </div><div class="container-fluid">
        <div class="container">
            <div class="wrapper">
                <div class="main-title">{{ $service->title }}</div>
                
                {{-- Dokumen yang Diperlukan --}}
                <input type="radio" id="radioDokumen" name="accordion" checked="checked"/>
                <label class="item" for="radioDokumen">
                    <div class="title">Dokumen yang Diperlukan</div>
                    <div class="content">
                        <ul class="list-group list-group-flush">
                            @foreach($service->requirements as $requirement)
                                <li class="list-group-item">{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </div>
                </label>

                {{-- Kategori / Info Harga (Jika ada) --}}
                @if($service->has_pricing)
                <input type="radio" id="radioPricing" name="accordion"/>
                <label class="item" for="radioPricing">
                    <div class="title">Kategori & Harga</div>
                    <div class="content">
                        <ul class="list-group list-group-flush">
                             @foreach($service->pricing_info as $pricing)
                                <li class="list-group-item">{{ $pricing['name'] }}: {{ $pricing['price'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </label>
                @endif
                
                {{-- Cara Pendaftaran --}}
                <input type="radio" id="radioCaraDaftar" name="accordion"/>
                <label class="item" for="radioCaraDaftar">
                    <div class="title">Cara Pendaftaran</div>
                    <div class="content">
                        <ul class="list-group list-group-flush">
                             @foreach($service->steps as $step)
                                <li class="list-group-item">{{ $step }}</li>
                            @endforeach
                        </ul>
                    </div>
                </label>
            </div>
            
            <div class="mx-auto text-center mt-4">
                <a href="{{ url($service->submission_url) }}" class="btn btn-outline-secondary">Ajukan Sekarang</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('page-styles')
  <link href="{{ asset('assets_landing/css/layanan-acc.css') }}" rel="stylesheet">
@endpush