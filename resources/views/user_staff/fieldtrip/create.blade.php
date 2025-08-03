@extends('layouts.master')

@section('title')
  Pengajuan Fieldtrip
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Field Trip @endslot
    @slot('title') Pengajuan Field Trip @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Syarat & Ketentuan Pengajuan Field Trip</h4>

          <div class="accordion" id="accordionTenant">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  Dokumen yang Diperlukan
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionTenant">
                <div class="accordion-body">
                  <ul>
                    <li>Nomor Induk Berusaha</li>
                    <li>Kartu Tanda Penduduk (KTP)</li>
                    <li>Akta Pendirian Perusahaan</li>
                    <li>NPWP</li>
                    <li>Sertifikat penjamah makanan (Khusus untuk food & beverage)</li>
                    <li>Bukti bayar pajak 3 bulan terakhir</li>
                    <li>Proposal usaha</li>
                    <li>Desain dan gambar teknis Booth/Tempat Usaha (Softdrawing Sipil, Elektrikal, Plumbing, Internal, dll)</li>
                    <li>Surat pernyataan sanggup mengikuti aturan yang berlaku (Bermaterai)</li>
                    <li>Laporan keuangan perusahaan</li>
                    <li>Service Level Agreement (Maskapai, Cargo)</li>
                  </ul>
                </div>
              </div>
            </div>

            

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Cara Pendaftaran
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionTenant">
                <div class="accordion-body">
                  <ul>
                    <li>Mendisposisikan surat permohonan kepada Kasi Pelayanan dan Kerjasama</li>
                    <li>Mendisposisikan surat permohonan kepada petugas pengembangan usaha untuk verifikasi</li>
                    <li>Melakukan verifikasi permohonan usaha sesuai inventaris usaha yang akan dikembangkan dan membuat draft surat undangan presentasi bisnis beserta nota dinas</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">

          <h4 class="card-title mb-4">Formulir Pengajuan Field Trip</h4>

          <form method="POST" action="{{ route('fieldtrip.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label for="fieldtrip_name" class="form-label">Nama Field Trip</label>
              <input type="text" class="form-control" id="fieldtrip_name" name="fieldtrip_name" value="{{ old('fieldtrip_name') }}" required>
              @error('fieldtrip_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="fieldtrip_type" class="form-label">Jenis Field Trip</label>
              <input type="text" class="form-control" id="fieldtrip_type" name="fieldtrip_type" value="{{ old('fieldtrip_type') }}" required>
              @error('fieldtrip_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Field Trip</label>
              <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="documents" class="form-label">Dokumen yang Diperlukan</label>
              <input type="file" class="form-control" id="documents" name="documents" required>
              @error('documents')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary waves-effect waves-light">Ajukan Sekarang</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    // Bootstrap form validation
    document.querySelector('form').addEventListener('submit', function (e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      this.classList.add('was-validated');
    });
  </script>
@endsection
