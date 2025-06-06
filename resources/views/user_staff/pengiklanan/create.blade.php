@extends('layouts.master')

@section('title')
  Pengajuan Pengiklanan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Pengiklanan @endslot
    @slot('title') Pengajuan Pengiklanan @endslot
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
          <h4 class="card-title mb-4">Syarat & Ketentuan Pengajuan Pengiklanan</h4>

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
                    <li>Surat Permohonan</li>
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
                    <li>Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama</li>
                    <li>Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha</li>
                    <li>Presentasi bisnis sesuai dengan bidang usaha yang diajukan</li>
                    <li>Melengkapi administrasi dan kontrak jika disetujui</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">

          <h4 class="card-title mb-4">Formulir Pengajuan Pengiklanan</h4>

          <form method="POST" action="{{ route('pengiklanan.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label for="ad_name" class="form-label">Nama Pengiklanan</label>
              <input type="text" class="form-control" id="ad_name" name="ad_name" value="{{ old('ad_name') }}" required>
              @error('ad_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="ad_type" class="form-label">Jenis Pengiklanan</label>
              <input type="text" class="form-control" id="ad_type" name="ad_type" value="{{ old('ad_type') }}" required>
              @error('ad_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Pengiklanan</label>
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
