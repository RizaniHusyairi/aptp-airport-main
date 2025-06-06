<!-- resources/views/user_staff/lelang/create.blade.php -->
@extends('layouts.master')

@section('title')
    Pengajuan Lelang/Beauty Contest
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Lelang/Beauty Contest @endslot
        @slot('title')  'Pengajuan Lelang/Beauty Contest' @endslot
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
          <h4 class="card-title mb-4">Syarat & Ketentuan Pengajuan Lelang</h4>

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
                    <h4 class="card-title mb-4">Formulir Pengajuan Lelang/Beauty Contest</h4>

                    <form method="POST" action="{{ route('lelang.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lelang</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lelang_type" class="form-label">Jenis Pengajuan</label>
                            <select name="lelang_type" id="lelang_type" class="form-control" required>
                                <option value="">Pilih Jenis Pengajuan</option>
                                @foreach ($lelang_type as $type)
                                    <option value="{{ $type }}">
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lelang_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Pengajuan</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Untuk Beauty Contest, sertakan ringkasan proposal bisnis.</small>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="documents" class="form-label">Dokumen yang Diperlukan</label>
                            <input type="file" class="form-control" id="documents" name="documents"  required accept=".pdf">
                            {{-- @if($lelang && $lelang->documents)
                                <small class="form-text text-muted">
                                    File saat ini: 
                                    <a href="{{ asset('storage/' . $lelang->documents) }}" target="_blank">
                                        {{ preg_replace('/^\d+_/', '', basename($lelang->documents)) }}
                                    </a>
                                    (Kosongkan jika tidak ingin mengganti)
                                </small>
                            @endif --}}
                            @error('documents')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="additional_documents" style="display: none;">
                            <label for="additional_documents" class="form-label">Dokumen Tambahan (Proposal Bisnis, Laporan Keuangan, dll.)</label>
                            <input type="file" class="form-control" id="additional_documents" name="additional_documents" accept=".pdf">
                            <small class="form-text text-muted">Wajib untuk Beauty Contest, opsional untuk Lelang Aset.</small>
                            {{-- @if($lelang && $lelang->additional_documents)
                                <small class="form-text text-muted">
                                    File saat ini: 
                                    <a href="{{ asset('storage/' . $lelang->additional_documents) }}" target="_blank">
                                        {{ preg_replace('/^\d+_additional_/', '', basename($lelang->additional_documents)) }}
                                    </a>
                                    (Kosongkan jika tidak ingin mengganti)
                                </small>
                            @endif --}}
                            @error('additional_documents')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Ajukan Sekarang
                            </button>
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
        document.getElementById('lelang_type').addEventListener('change', function() {
            const additionalDocs = document.getElementById('additional_documents');
            if (this.value === 'Beauty Contest') {
                additionalDocs.style.display = 'block';
                document.getElementById('additional_documents').setAttribute('required', 'required');
            } else {
                additionalDocs.style.display = 'none';
                document.getElementById('additional_documents').removeAttribute('required');
            }
        });

        // Trigger change on page load to handle edit mode
        document.getElementById('lelang_type').dispatchEvent(new Event('change'));
    </script>
@endsection