@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengajuan Izin Kerja')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Formulir Pengajuan Izin Kerja</h3>
                <p class="text-subtitle text-muted">Lengkapi semua detail untuk mendapatkan izin kerja di area bandara.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Izin Kerja', 'url' => route('kerja.index')],
                    ['label' => 'Pengajuan Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    {{-- BAGIAN BARU: Syarat & Ketentuan --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Informasi & Prosedur Pengajuan</h5>
        </div>
        <div class="card-body">
            <div class="accordion" id="accordionSyaratKetentuan">
                {{-- Item Accordion 1: Dokumen --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingDokumen">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDokumen" aria-expanded="true" aria-controls="collapseDokumen">
                            <i class="bi bi-file-earmark-text-fill me-2"></i> Dokumen yang Diperlukan
                        </button>
                    </h2>
                    <div id="collapseDokumen" class="accordion-collapse collapse show" aria-labelledby="headingDokumen" data-bs-parent="#accordionSyaratKetentuan">
                        <div class="accordion-body">
                            <p>Pastikan Anda telah menyiapkan dokumen-dokumen berikut dalam format digital (PDF/JPG/PNG) sebelum melanjutkan:</p>
                            <ul>
                                <li><strong>Surat Permohonan Resmi:</strong> Diterbitkan oleh perusahaan atau instansi Anda.</li>
                                <li><strong>Metode Kerja (Work Method Statement):</strong> Penjelasan rinci mengenai tahapan pekerjaan yang akan dilakukan.</li>
                                <li><strong>Analisis Keselamatan Kerja (Job Safety Analysis - JSA):</strong> Identifikasi potensi bahaya dan langkah-langkah mitigasinya.</li>
                                <li><strong>Daftar Pekerja & Identitas:</strong> Nama lengkap semua pekerja beserta salinan KTP/Identitas yang valid.</li>
                                <li><strong>Sertifikat Kompetensi:</strong> Wajib dilampirkan untuk pekerjaan khusus (misalnya, sertifikat untuk pekerjaan listrik atau bekerja di ketinggian).</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                {{-- Item Accordion 2: Cara Pendaftaran --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPendaftaran">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePendaftaran" aria-expanded="false" aria-controls="collapsePendaftaran">
                            <i class="bi bi-list-ol me-2"></i> Alur Proses Pendaftaran
                        </button>
                    </h2>
                    <div id="collapsePendaftaran" class="accordion-collapse collapse" aria-labelledby="headingPendaftaran" data-bs-parent="#accordionSyaratKetentuan">
                        <div class="accordion-body">
                            <ol>
                                <li><strong>Kirim Pengajuan:</strong> Isi dan kirim formulir di bawah ini dengan data yang lengkap dan benar.</li>
                                <li><strong>Verifikasi Dokumen:</strong> Tim Operasional & Keselamatan Bandara akan memeriksa kelengkapan dan validitas dokumen Anda.</li>
                                <li><strong>Penilaian Risiko:</strong> Pengajuan Anda akan dinilai berdasarkan tingkat risiko dan dampaknya terhadap operasional bandara.</li>
                                <li><strong>Persetujuan:</strong> Status pengajuan (Disetujui, Ditolak, atau Revisi Diperlukan) akan diinformasikan melalui dashboard Anda.</li>
                                <li><strong>Pelaksanaan:</strong> Pekerjaan hanya dapat dimulai setelah Anda menerima Izin Kerja (Work Permit) yang telah disetujui secara resmi.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulir Pengajuan --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Detail Pekerjaan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kerja.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="work_type" class="form-label">Jenis Pekerjaan <span class="text-danger">*</span></label>
                        <select class="form-select @error('work_type') is-invalid @enderror" id="work_type" name="work_type" required>
                            <option value="" selected disabled>Pilih Jenis Pekerjaan...</option>
                            <option value="Pekerjaan Panas (Hot Work)" @selected(old('work_type') == 'Pekerjaan Panas (Hot Work)')>Pekerjaan Panas (Hot Work)</option>
                            <option value="Pekerjaan di Ketinggian" @selected(old('work_type') == 'Pekerjaan di Ketinggian')>Pekerjaan di Ketinggian</option>
                            <option value="Pekerjaan Listrik" @selected(old('work_type') == 'Pekerjaan Listrik')>Pekerjaan Listrik</option>
                            <option value="Pekerjaan Galian" @selected(old('work_type') == 'Pekerjaan Galian')>Pekerjaan Galian</option>
                            <option value="Pekerjaan Umum" @selected(old('work_type') == 'Pekerjaan Umum')>Pekerjaan Umum</option>
                        </select>
                        @error('work_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Lokasi Spesifik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Area Apron Stand 3" required>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Jadwal Mulai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Jadwal Selesai <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Deskripsi Rinci Pekerjaan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-4">
                        <label for="docs" class="form-label">Dokumen Pendukung <span class="text-danger">*</span></label>
                        <input class="form-control @error('docs.*') is-invalid @enderror" type="file" id="docs" name="docs[]" multiple required accept=".pdf,.jpg,.png">
                        @error('docs.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Unggah JSA, Surat Tugas, atau dokumen relevan lainnya (PDF, maks 2MB).</small>
                    </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('kerja.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts_admin')
<script>
    // Mengubah textarea menjadi JSON sebelum form disubmit
    document.querySelector('form').addEventListener('submit', function(e) {
        const workersText = document.querySelector('textarea[name="workers_text"]').value.trim();
        const equipmentText = document.querySelector('textarea[name="equipment_text"]').value.trim();
        
        document.querySelector('input[name="workers"]').value = JSON.stringify(workersText.split('\n').filter(n => n));
        document.querySelector('input[name="equipment"]').value = JSON.stringify(equipmentText.split('\n').filter(n => n));
    });
</script>
@endpush
