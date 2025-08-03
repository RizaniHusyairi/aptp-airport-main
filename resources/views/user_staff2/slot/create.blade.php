@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Slot Charter')
@section('styles_admin')

@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Tambah Pengajuan Slot Charter</h3>
                                <p class="text-subtitle text-muted">Formulir untuk mengajukan slot charter baru</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                    ['label' => 'Menu', 'url' => route('profile')],
                                    ['label' => 'Slot Charter', 'url' => route('slot.index')],
                                    ['label' => 'Tambah Pengajuan', 'active' => true],
                                ]" />
                                
                            </div>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Syarat & Ketentuan Pengajuan Slot Charter</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordionSyaratKetentuan">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingDokumen">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDokumen" aria-expanded="true" aria-controls="collapseDokumen">
                                            Dokumen yang Diperlukan
                                        </button>
                                    </h2>
                                    <div id="collapseDokumen" class="accordion-collapse collapse show" aria-labelledby="headingDokumen" data-bs-parent="#accordionSyaratKetentuan">
                                        <div class="accordion-body">
                                            <ul>
                                                <li>Surat Permohonan Slot Charter</li>
                                                <li>Kartu Tanda Penduduk (KTP) Pemohon</li>
                                                <li>Nomor Induk Berusaha (NIB)</li>
                                                <li>Sertifikat Kelaikan Udara Pesawat</li>
                                                <li>Jadwal Penerbangan yang Diusulkan</li>
                                                <li>Proposal Operasional Penerbangan</li>
                                                <li>Surat Izin Operasi Penerbangan (untuk operator)</li>
                                                <li>Bukti Bayar Pajak Perusahaan 3 Bulan Terakhir</li>
                                                <li>Service Level Agreement (Khusus untuk Maskapai)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingJenis">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJenis" aria-expanded="false" aria-controls="collapseJenis">
                                            Jenis Penerbangan
                                        </button>
                                    </h2>
                                    <div id="collapseJenis" class="accordion-collapse collapse" aria-labelledby="headingJenis" data-bs-parent="#accordionSyaratKetentuan">
                                        <div class="accordion-body">
                                            <ul>
                                                <li>Penumpang: Penerbangan untuk transportasi penumpang</li>
                                                <li>Kargo: Penerbangan untuk pengangkutan barang</li>
                                                <li>Lainnya: Penerbangan khusus seperti medis, tur, atau pelatihan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingProsedur">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProsedur" aria-expanded="false" aria-controls="collapseProsedur">
                                            Prosedur Pengajuan
                                        </button>
                                    </h2>
                                    <div id="collapseProsedur" class="accordion-collapse collapse" aria-labelledby="headingProsedur" data-bs-parent="#accordionSyaratKetentuan">
                                        <div class="accordion-body">
                                            <ol>
                                                <li>Mengajukan surat permohonan kepada Kepala Seksi Operasional Bandara</li>
                                                <li>Verifikasi dokumen oleh petugas slot penerbangan</li>
                                                <li>Menghadiri rapat koordinasi untuk penentuan slot dan jadwal</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Formulir Pengajuan Slot Charter</h5>
                        </div>
                        <div class="card-body">
                            <form id="form-pengajuan-slot" method="POST" action="{{ route('slot.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nomorRegistrasi" class="form-label">Nomor Registrasi Pesawat</label>
                                        <input type="text" class="form-control" id="nomorRegistrasi" name="nomorRegistrasi" placeholder="Contoh: PK-ABC" required>
                                        @error('nomorRegistrasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tipePesawat" class="form-label">Tipe Pesawat</label>
                                        <input type="text" class="form-control" id="tipePesawat" name="tipePesawat" placeholder="Contoh: Airbus A320" required>
                                        @error('tipePesawat')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}

                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jadwalKeberangkatan" class="form-label">Jadwal Keberangkatan</label>
                                        <input type="datetime-local" class="form-control" id="jadwalKeberangkatan" name="jadwalKeberangkatan" required>
                                        @error('jadwalKeberangkatan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                            
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jadwalKedatangan" class="form-label">Jadwal Kedatangan</label>
                                        <input type="datetime-local" class="form-control" id="jadwalKedatangan" name="jadwalKedatangan" required>
                                        @error('jadwalKedatangan')
                                        <div class="invalid-feedback">
                                            {{ $message }}

                                        </div>
                                            
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bandaraAsal" class="form-label">Bandara Asal</label>
                                        <input type="text" class="form-control" id="bandaraAsal" name="bandaraAsal" placeholder="Contoh: CGK" required>
                                        @error('bandaraAsal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                            
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bandaraTujuan" class="form-label">Bandara Tujuan</label>
                                        <input type="text" class="form-control" id="bandaraTujuan" name="bandaraTujuan" placeholder="Contoh: DPS" required>
                                        @error('bandaraTujuan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                            
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jenisPenerbangan" class="form-label">Jenis Penerbangan</label>
                                        <select class="form-select" id="jenisPenerbangan" name="jenisPenerbangan" required>
                                            <option value="" disabled selected>Pilih jenis penerbangan</option>
                                            <option value="penumpang">Penumpang</option>
                                            <option value="kargo">Kargo</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                        @error('jenisPenerbangan')  
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3" id="jenisLainnya" style="display: none;">
                                        <label for="jenislainnya" class="form-label">Jenis Penerbangan lainnya</label>
                                        <input type="text" class="form-control" id="jenislainnya" name="jenislainnya" value="{{ old('jenislainnya') }}" placeholder="Masukkan Jenis Penerbangan Lainnya" >
                                        @error('jenislainnya')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="dokumen" class="form-label">Dokumen Pendukung</label>
                                        <input type="file" class="form-control" id="dokumen" name="dokumen" multiple accept=".pdf,.doc,.docx" required>
                                        @error('dokumen')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                            
                                        @enderror
                                        <small class="form-text text-muted">Unggah dokumen dalam format PDF, DOC, atau DOCX. Maksimal 10 file.</small>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                                        <a href="{{ route('slot.index') }}" class="btn btn-secondary ms-2">Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
@endsection
@section('scripts_admin')
    <script src="{{ asset('../assetsv2/compiled/js/tambah-pengajuan-slot.js') }}"></script>

@endsection
