@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Tenant')
@section('styles_admin')

@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pengajuan Tenant</h3>
                <p class="text-subtitle text-muted">Formulir untuk mengajukan tenant baru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                                ['label' => 'Menu', 'url' => route('profile')],
                                ['label' => 'Tenant', 'url' => route('tenant.index')],
                                ['label' => 'Pengajuan', 'active' => true],
                            ]" />        
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Syarat & Ketentuan Pengajuan Tenant</h5>
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
                                <li>Nomor Induk Berusaha</li>
                                <li>Kartu Tanda Penduduk (KTP)</li>
                                <li>Akta Pendirian Perusahaan</li>
                                <li>NPWP</li>
                                <li>Sertifikat Penjamah Makanan (Khusus untuk Food & Beverage)</li>
                                <li>Bukti Bayar Pajak 3 Bulan Terakhir</li>
                                <li>Proposal Usaha</li>
                                <li>Desain dan Gambar Teknis Booth/Tempat Usaha (Softdrawing Sipil, Elektrikal, Plumbing, Internal, dll)</li>
                                <li>Surat Pernyataan Sanggup Mengikuti Aturan yang Berlaku (Bermaterai)</li>
                                <li>Laporan Keuangan Perusahaan</li>
                                <li>Service Level Agreement (Maskapai, Cargo)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingKategori">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKategori" aria-expanded="false" aria-controls="collapseKategori">
                            Kategori Tenant
                        </button>
                    </h2>
                    <div id="collapseKategori" class="accordion-collapse collapse" aria-labelledby="headingKategori" data-bs-parent="#accordionSyaratKetentuan">
                        <div class="accordion-body">
                            <ul>
                                <li>Terbuka tanpa AC: Rp. 31.000,00/m²</li>
                                <li>Tertutup tanpa AC: Rp. 48.000,00/m²</li>
                                <li>Terbuka dengan AC: Rp. 65.000,00/m²</li>
                                <li>Tertutup dengan AC: Rp. 82.000,00/m²</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPendaftaran">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePendaftaran" aria-expanded="false" aria-controls="collapsePendaftaran">
                            Cara Pendaftaran
                        </button>
                    </h2>
                    <div id="collapsePendaftaran" class="accordion-collapse collapse" aria-labelledby="headingPendaftaran" data-bs-parent="#accordionSyaratKetentuan">
                        <div class="accordion-body">
                            <ol>
                                <li>Mendisposisikan surat permohonan kepada Kasi Pelayanan dan Kerjasama</li>
                                <li>Mendisposisikan surat permohonan kepada petugas pengembangan usaha untuk verifikasi</li>
                                <li>Melakukan verifikasi permohonan usaha sesuai inventaris usaha yang akan dikembangkan dan membuat draft surat undangan presentasi bisnis beserta nota dinas</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            
            <h5 class="card-title">Formulir Pengajuan Tenant</h5>
        </div>
        <div class="card-body">
            <form id="form-pengajuan-tenant" method="POST" action="{{ route('tenant.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="business_name" class="form-label">Nama Usaha</label>
                        <input type="text" class="form-control" id="business_name" name="business_name" required>
                        @error('business_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="business_type" class="form-label">Jenis Usaha</label>
                        <input type="text" class="form-control" id="business_type" name="business_type" required>
                        @error('business_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="rental_type" class="form-label">Jenis Tenant</label>
                        <select class="form-select" id="rental_type" name="rental_type" required>
                            <option value="" selected disabled>Pilih Jenis Tenant</option>
                            <option value="Ruangan di dalam terminal terbuka tanpa AC">Ruangan di dalam terminal terbuka tanpa AC</option>
                            <option value="Ruangan di dalam terminal tertutup tanpa AC">Ruangan di dalam terminal tertutup tanpa AC</option>
                            <option value="Ruangan di dalam terminal terbuka dengan AC">Ruangan di dalam terminal terbuka dengan AC</option>
                            <option value="Ruangan di dalam terminal tertutup dengan AC">Ruangan di dalam terminal tertutup dengan AC</option>
                            <option value="Ruangan di luar terminal terbuka tanpa AC">Ruangan di luar terminal terbuka tanpa AC</option>
                            <option value="Ruangan di luar terminal tertutup tanpa AC">Ruangan di luar terminal tertutup tanpa AC</option>
                            <option value="Ruangan di luar terminal terbuka dengan AC">Ruangan di luar terminal terbuka dengan AC</option>
                            <option value="ATM">ATM</option>
                            <option value="Tanah">Tanah</option>
                            <option value="Tiang pancang reklame">Tiang pancang reklame</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('rental_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3" style="display: none;">
                        <label for="rental_more" class="form-label" >Jenis Tenant lainnya</label>
                        <input type="text" class="form-control" id="rental_more" name="rental_more">
                        @error('rental_more')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">Deskripsi Usaha</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="documents" class="form-label">dokumen yang Diperlukan</label>
                        <input type="file" class="form-control" id="documents" name="documents" multiple accept=".pdf,.doc,.docx" required>
                        @error('documents')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        
                        <small class="form-text text-muted">Unggah dokumen dalam format PDF, DOC, atau DOCX. Maksimal 10 file.</small>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        <a href="ajukan-tenant.html" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('../assetsv2/compiled/js/tambah-pengajuan-tenant.js') }}"></script>
    <script>
        document.getElementById('rental_type').addEventListener('change', function() {
            const additionalDocs = document.getElementById('rental_more');
            if (this.value === 'Lainnya') {
                additionalDocs.parentElement.style.display = 'block';
                document.getElementById('rental_more').setAttribute('required', 'required');
            } else {
                additionalDocs.parentElement.style.display = 'none';
                document.getElementById('rental_more').removeAttribute('required');
            }
        });

        // Trigger change on page load to handle edit mode
        document.getElementById('rental_type').dispatchEvent(new Event('change'));
    </script>
@endsection
