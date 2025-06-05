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
                    <li>Surat Permohonan Slot Charter</li>
                    <li>Sertifikat Kelaikan Udara Pesawat</li>
                    <li>Proposal Oprasional Penerbangan</li>
                    <li>Surat Izin Operasi Penerbangan (untuk Operator)</li>
                    <li>Bukti Bayar Pajak Perusahaan 3 Bulan Terakhir</li>
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
                    <li>Mengajukan surat permohonan kepada Kepala Seksi Operasional Bandara</li>
                    <li>Verifikasi dokumen oleh petugas slot penerbangan</li>
                    <li>Menghadiri rapat koordinasi untuk penentuan slot dan jadwal</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Formulir Pengajuan Slot Charter</h4>

                    <form method="POST" action="{{ route('slot.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="nomorRegistrasi" class="form-label">Nomor Registrasi Pesawat</label>
                                <input type="text" class="form-control" placeholder="Contoh: PK-ABC" id="nomorRegistrasi" name="nomorRegistrasi" value="{{ old('nomorRegistrasi') }}" required>
                                @error('nomorRegistrasi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="tipePesawat" class="form-label">Tipe Pesawat</label>
                                <input type="text" class="form-control" placeholder="Contoh: Airbus A320" id="tipePesawat" name="tipePesawat" value="{{ old('tipePesawat') }}" required>
                                @error('tipePesawat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="jadwalKeberangkatan" class="form-label">Jadwal Keberangkatan</label>
                                <input type="datetime-local" name="jadwalKeberangkatan" id="jadwalKeberangkatan" class="form-control @error('jadwalKeberangkatan') is-invalid @enderror"
                                value="{{ old('jadwalKeberangkatan') }}" required>
                                @error('jadwalKeberangkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="jadwalKedatangan" class="form-label">Jadwal Kedatangan</label>
                                <input type="datetime-local" name="jadwalKedatangan" id="jadwalKedatangan" class="form-control @error('jadwalKedatangan') is-invalid @enderror"
                                value="{{ old('jadwalKedatangan') }}" required>
                                @error('jadwalKedatangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="bandaraAsal" class="form-label">Bandara Asal</label>
                                <input type="text" class="form-control" placeholder="Contoh: CGK" id="bandaraAsal" name="bandaraAsal" value="{{ old('bandaraAsal') }}" required>
                                @error('bandaraAsal')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="bandaraTujuan" class="form-label">Bandara Tujuan</label>
                                <input type="text" class="form-control" placeholder="Contoh: DPS" id="bandaraTujuan" name="bandaraTujuan" value="{{ old('bandaraTujuan') }}" required>
                                @error('bandaraTujuan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="mb-3">
                            <label for="jenisPenerbangan" class="form-label">Jenis Penerbangan</label>
                            <select name="jenisPenerbangan" id="jenisPenerbangan" class="form-control" required>
                                <option value="" disabled selected>Pilih jenis penerbangan</option>
                                <option value="penumpang">Penumpang</option>
                                <option value="kargo">Kargo</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            @error('jenisPenerbangan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3" id="jenisLainnya" style="display: none;">
                            <label for="jenislainnya" class="form-label">Jenis Penerbangan lainnya</label>
                            <input type="text" class="form-control" id="jenislainnya" name="jenislainnya" value="{{ old('jenislainnya') }}" placeholder="Masukkan Jenis Penerbangan Lainnya" >
                            @error('jenislainnya')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="documents" class="form-label">Dokumen Pendukung</label>
                            <input type="file" class="form-control" id="documents" name="documents" multiple accept=".pdf,.doc,.docx" required>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Unggah dokumen dalam format PDF, DOC, atau DOCX.</small>
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
        document.getElementById('jenisPenerbangan').addEventListener('change', function() {
            const additionalDocs = document.getElementById('jenislainnya');
            if (this.value === 'lainnya') {
                additionalDocs.style.display = 'block';
                document.getElementById('jenislainnya').setAttribute('required', 'required');
            } else {
                additionalDocs.style.display = 'none';
                document.getElementById('jenislainnya').removeAttribute('required');
            }
        });

        // Trigger change on page load to handle edit mode
        document.getElementById('jenisPenerbangan').dispatchEvent(new Event('change'));
    </script>
@endsection