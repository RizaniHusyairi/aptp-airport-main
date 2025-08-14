@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Pengajuan Informasi Publik')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pengajuan Informasi Publik</h3>
                <p class="text-subtitle text-muted">Formulir untuk mengajukan permohonan informasi publik baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Informasi Publik', 'url' => route('informasiPublik.index')],
                    ['label' => 'Pengajuan', 'active' => true],
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Syarat & Ketentuan Pengajuan</h5>
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
                                <li>Scan Kartu Tanda Penduduk (KTP) dalam format PDF atau gambar (JPG/PNG).</li>
                                <li>Surat Pernyataan Pertanggung Jawaban Informasi Publik. 
                                    <a href="https://docs.google.com/document/d/1hdV1e_SkNHG5KNDiYxGXsX125EaGPZwN/edit?usp=sharing&ouid=116067769203631007023&rtpof=true&sd=true" 
                                       class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                        <i class="bi bi-download me-1"></i> Unduh Template
                                    </a>
                                </li>
                                <li>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Mengisi formulir pengajuan informasi publik di bawah ini.
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Pengajuan</h5>
        </div>
        <div class="card-body">
            <form id="form-pengajuan-informasi" method="POST" action="{{ route('informasiPublik.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Data Pengguna (Otomatis) -->
                    <div class="col-12 mb-4">
                        <h6>Informasi Pengaju</h6>
                        <p class="text-muted">Informasi berikut diambil dari data profil Anda.</p>
                        <ul>
                            <li><strong>Nama:</strong> {{ auth()->user()->name }}</li>
                            <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
                            <li><strong>No. HP/WA:</strong> {{ auth()->user()->phone ?? 'Tidak ada' }}</li>
                            <li><strong>Alamat:</strong> {{ auth()->user()->address ?? 'Tidak ada' }}</li>
                        </ul>
                    </div>
                    <hr>

                    <!-- Data Tambahan yang Wajib Diisi -->
                    <div class="col-md-6 mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" required>
                        @error('pekerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="npwp" class="form-label">Nomor NPWP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" value="{{ old('npwp') }}" required>
                        @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="surat_permintaan" class="form-label">Surat Permintaan Informasi dari <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('surat_permintaan') is-invalid @enderror" id="surat_permintaan" name="surat_permintaan" value="{{ old('surat_permintaan') }}" placeholder="Contoh: PT. XYZ / Individu" required>
                        @error('surat_permintaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="rincian_informasi" class="form-label">Rincian Informasi yang Dibutuhkan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('rincian_informasi') is-invalid @enderror" id="rincian_informasi" name="rincian_informasi" rows="4" required>{{ old('rincian_informasi') }}</textarea>
                        @error('rincian_informasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="tujuan_informasi" class="form-label">Tujuan Penggunaan Informasi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('tujuan_informasi') is-invalid @enderror" id="tujuan_informasi" name="tujuan_informasi" rows="4" required>{{ old('tujuan_informasi') }}</textarea>
                        @error('tujuan_informasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- File Uploads --}}
                    <div class="col-md-6 mb-3">
                        <label for="ktp" class="form-label">Upload Scan KTP <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('ktp') is-invalid @enderror" id="ktp" name="ktp" accept=".pdf,.jpg,.png" required>
                        @error('ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="surat_pertanggungjawaban" class="form-label">Upload Surat Pernyataan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('surat_pertanggungjawaban') is-invalid @enderror" id="surat_pertanggungjawaban" name="surat_pertanggungjawaban" accept=".pdf,.jpg,.png" required>
                        @error('surat_pertanggungjawaban')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- Pilihan Cara Memperoleh --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cara Memperoleh Informasi <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input @error('cara_memperoleh') is-invalid @enderror" type="radio" name="cara_memperoleh" id="memperoleh1" value="Melihat/Membaca/Mendengarkan/Mencatat" @checked(old('cara_memperoleh') == 'Melihat/Membaca/Mendengarkan/Mencatat')>
                            <label class="form-check-label" for="memperoleh1">Melihat/Membaca/Mendengarkan/Mencatat</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('cara_memperoleh') is-invalid @enderror" type="radio" name="cara_memperoleh" id="memperoleh2" value="Mendapatkan Copy Salinan (Hard Copy)" @checked(old('cara_memperoleh') == 'Mendapatkan Copy Salinan (Hard Copy)')>
                            <label class="form-check-label" for="memperoleh2">Mendapatkan Copy Salinan (Hard Copy)</label>
                        </div>
                        @error('cara_memperoleh')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Pilihan Cara Mendapat Salinan --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cara Mendapat Salinan <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input @error('cara_salinan') is-invalid @enderror" type="radio" name="cara_salinan" id="salinan1" value="Langsung" @checked(old('cara_salinan') == 'Langsung')>
                            <label class="form-check-label" for="salinan1">Langsung</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input @error('cara_salinan') is-invalid @enderror" type="radio" name="cara_salinan" id="salinan2" value="Email" @checked(old('cara_salinan') == 'Email')>
                            <label class="form-check-label" for="salinan2">Email</label>
                        </div>
                        {{-- Tambahkan opsi lain jika perlu --}}
                        @error('cara_salinan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        <a href="{{ route('informasiPublik.index') }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
