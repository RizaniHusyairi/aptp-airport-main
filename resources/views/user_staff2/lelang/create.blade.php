@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Lelang/Beauty Contest')
@section('styles_admin')

@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pengajuan Lelang/Beauty Contest</h3>
                <p class="text-subtitle text-muted">Formulir untuk mengajukan Lelang/Beauty Contest baru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                                ['label' => 'Menu', 'url' => route('profile')],
                                ['label' => 'Lelang/Beauty Contest', 'url' => route('lelang.index')],
                                ['label' => 'Pengajuan', 'active' => true],
                            ]" />        
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Syarat & Ketentuan Pengajuan Lelang/Beauty Contest</h5>
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
                                <li>Service Level Agreement (jika berlaku)</li>
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
            
            <h5 class="card-title">Formulir Pengajuan Lelang/Beauty Contest</h5>
        </div>
        <div class="card-body">
            <form id="form-pengajuan-fieldTrip" method="POST" action="{{ route('lelang.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lelang</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lelang_type" class="form-label">Jenis Lelang</label>
                        <input type="text" class="form-control" id="lelang_type" name="lelang_type" required>
                    
                        @error('lelang_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Deskripsi Lelang</label>
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
                        <a href="{{ route('lelang.index') }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    
@endsection
