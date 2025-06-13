
@extends('layouts_landing.landing_app')

@section('title', 'Layanan - Bandara APT Pranoto')

@section('content')
<!-- About Section -->
    <section id="about" class="about section pt-6">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Layanan<br></h2>
        <p><span>Pengajuan</span> <span class="description-title">
          @switch(request()->segment(2))
            
          @case('tenant')
          Tenant
          @break
        @case('sewa')
          Sewa
          @break
        @case('perijinan-usaha')
          Perijinan Usaha
          @break
        @case('pengiklanan')
          Pengiklanan
          @break
        @case('field-trip')
          Field Trip
          @break
        @case('lelang')
          Lelang/Beauty contest
          @break
        @case('slot')
          Slot Charter
          @break
        @default
          Pengajuan
      @endswitch

        </span></p>
      </div><!-- End Section Title -->

      <div class="container-fluid">
        <div class="container">
          <div class="wrapper">
            <div class="main-title">
                @switch(request()->segment(2))
        @case('tenant')
          Syarat & Ketentuan Pengajuan Tenant
          @break
        @case('sewa')
          Syarat & Ketentuan Sewa
          @break
        @case('perijinan-usaha')
          Syarat & Ketentuan Perijinan Usaha
          @break
        @case('pengiklanan')
          Syarat & Ketentuan Pengajuan Pengiklanan
          @break
        @case('field-trip')
          Syarat & Ketentuan Field Trip
          @break
        @case('lelang')
          Syarat & Ketentuan Lelang/Beauty contest
          @break
        @case('slot')
          Syarat & Ketentuan Pengajuan Slot Charter
          @break
        @default
          Syarat & Ketentuan Pengajuan
      @endswitch
            </div>
            <input type="radio" id="radioYour Account" name="accordion" checked="checked"/>
            <label class="item" for="radioYour Account">
              <div class="title">Dokumen yang Diperlukan</div>
              <div class="content">
                <ul class="list-group list-group-flush">
                  @if (in_array(request()->segment(2), ['field-trip', 'pengiklanan']))
                  <li class="list-group-item">Surat Permohonan</li>
                  @else
                    <li class="list-group-item">Nomor Induk Berusaha</li>
                    <li class="list-group-item">Kartu Tanda Penduduk (KTP)</li>
                    @if (request()->segment(2) == 'slot')
                    <li class="list-group-item">Surat Permohonan Slot Charter</li>
                    <li class="list-group-item">Sertifikat Kelaikan Udara Pesawat</li>
                    <li class="list-group-item">Proposal Oprasional Penerbangan</li>
                    <li class="list-group-item">Surat Izin Operasi Penerbangan (untuk Operator)</li>
                    @endif

                    @if(request()->segment(2) != 'slot')
                    <li class="list-group-item">Akta Pendirian Perusahaan</li>
                    <li class="list-group-item">NPWP</li>
                    <li class="list-group-item">Proposal Usaha</li>
                    <li class="list-group-item">Desain Teknis Booth/Tempat Usaha</li>
                    <li class="list-group-item">Surat Pernyataan Mengikuti Aturan (bermaterai)</li>
                    <li class="list-group-item">Laporan Keuangan</li>

                    @endif
                    @if(request()->segment(2) != 'perijinan-usaha')
                    <li class="list-group-item">Sertifikat Penjamah Makanan (jika F&B)</li>
                    @endif
                    <li class="list-group-item">Bukti Bayar Pajak 3 Bulan Terakhir</li>
                    @if(request()->segment(2) == 'perijinan-usaha')
                    <li class="list-group-item">Service Level Agreement (Kecuali untuk kargo)</li>
                    @else
                    <li class="list-group-item">Service Level Agreement (jika berlaku)</li>

                    @endif
                  @endif
                    
                </ul>
              </div>
            </label>
            @if (request()->segment(2) == 'tenant')
            <input type="radio" id="radioPayment &amp; Pricing" name="accordion"/>
            <label class="item" for="radioPayment &amp; Pricing">
              <div class="title">Kategori Tenant</div>
              <div class="content">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Terbuka tanpa AC: Rp. 31.000/m²</li>
                  <li class="list-group-item">Tertutup tanpa AC: Rp. 48.000/m²</li>
                  <li class="list-group-item">Terbuka dengan AC: Rp. 65.000/m²</li>
                  <li class="list-group-item">Tertutup dengan AC: Rp. 82.000/m²</li>
                </ul>
              </div>
            </label>
            @endif
            <input type="radio" id="radioReturns &amp; Refunds" name="accordion"/>
            <label class="item" for="radioReturns &amp; Refunds">
              <div class="title">Cara Pendaftaran</div>
              <div class="content">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama</li>
                  <li class="list-group-item">Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha</li>
                  <li class="list-group-item">Presentasi bisnis sesuai dengan bidang usaha yang diajukan</li>
                  <li class="list-group-item">Melengkapi administrasi dan kontrak jika disetujui</li>
                </ul>
              </div>
            </label>
            
          </div>
          @php
      $routeSegment = request()->segment(2);
      $pengajuanRoute = match($routeSegment) {
          'tenant' => 'dashboard/tenant',
          'sewa' => 'dashboard/sewa',
          'perijinan-usaha' => 'dashboard/perijinan',
          'pengiklanan' => 'dashboard/pengiklanan',
          'field-trip' => 'dashboard/fieldtrip',
          'lelang' => 'dashboard/lelang',
          'slot' => 'dashboard/slot',
          default => '#',
      };
    @endphp
    @if ($pengajuanRoute != '#')
        <div class="mx-auto text-center mt-4">
        <a href="{{ url($pengajuanRoute) }}" class="btn btn-outline-secondary">Ajukan Sekarang</a>

        </div>
    @endif
        </div>
      </div>

    </section><!-- /About Section -->
    <!-- /news Section -->

@endsection

@push('page-styles')
  <link href="{{ asset('assets_landing/css/layanan-acc.css') }}" rel="stylesheet">
@endpush
