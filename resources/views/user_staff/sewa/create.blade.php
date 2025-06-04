@extends('layouts.master')

@section('title')
  Pengajuan Sewa
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Sewa @endslot
    @slot('title') Pengajuan Sewa @endslot
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
          <h4 class="card-title mb-4">Syarat & Ketentuan Pengajuan Sewa</h4>

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
                    <li>Bukti bayar pajak 3 bulan terakhir</li>
                    <li>Proposal usaha</li>
                    <li>Desain dan gambar teknis Booth/Tempat Usaha (Softdrawing Sipil, Elektrikal, Plumbing, Internal, dll)</li>
                    <li>Surat pernyataan sanggup mengikuti aturan yang berlaku (Bermaterai)</li>
                    <li>Laporan keuangan perusahaan</li>
                    <li>Service Level Agreement (Jika Berlaku)</li>
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
          <h4 class="card-title mb-4">Formulir Pengajuan Sewa</h4>
          <form method="POST" action="{{ route('sewa.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
              <label for="rental_name" class="form-label">Nama Sewa</label>
              <input type="text" class="form-control" id="rental_name" name="rental_name" value="{{ old('rental_name') }}" required>
              @error('rental_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="rental_type" class="form-label">Jenis Sewa</label>
              <select name="rental_type" id="rental_type" class="form-control" required onchange="toggleFields()">
                  <option value="">Pilih Jenis Sewa</option>
                  @foreach ($rentalTypes as $type => $config)
                      <option value="{{ $type }}">{{ $config['name'] }}</option>
                  @endforeach
                  <option value="Lainnya">Lainnya</option>
              </select>
              @error('rental_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3" id="rental_more" style="display: none;">
                <label for="rental_more" class="form-label">jenis Sewa Lainnya</label>
                <input type="text" class="form-control" id="rental_more" name="rental_more" value="{{ old('rental_more') }}" placeholder="masukkan jenis sewa lainnya" >
                @error('rental_more')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Sewa</label>
              <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="documents" class="form-label">Dokumen yang Diperlukan (PDF)</label>
              <input type="file" name="documents" id="documents" class="form-control" accept=".pdf" required>
              @error('documents')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save"></i>Ajukan Sekarang</button>
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

    // Show/hide additional documents based on lelang_type
        document.getElementById('rental_type').addEventListener('change', function() {
            const additionalDocs = document.getElementById('rental_more');
            if (this.value === 'Lainnya') {
                additionalDocs.style.display = 'block';
                document.getElementById('rental_more').setAttribute('required', 'required');
            } else {
                additionalDocs.style.display = 'none';
                document.getElementById('rental_more').removeAttribute('required');
            }
        });

        // Trigger change on page load to handle edit mode
        document.getElementById('rental_type').dispatchEvent(new Event('change'));

    
  </script>
@endsection
