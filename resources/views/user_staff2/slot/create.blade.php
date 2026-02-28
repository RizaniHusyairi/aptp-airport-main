@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Slot Charter')
@section('styles_admin')
<style>
    /* Styling Area Drag & Drop */
    .upload-area {
        border: 2px dashed #435ebe;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .upload-area:hover, .upload-area.active {
        background-color: #eef2ff;
        border-color: #25396f;
    }

    .upload-area i {
        font-size: 3rem;
        color: #435ebe;
        margin-bottom: 10px;
    }

    .upload-area p {
        margin-bottom: 0;
        color: #6c757d;
    }

    /* Styling List File Preview */
    .file-preview-list {
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #dee2e6;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }

    .file-icon {
        font-size: 1.5rem;
        color: #dc3545; /* Warna icon PDF */
        margin-bottom: 25px;
        margin-right: 10px;
    }

    .file-name {
        font-weight: 500;
        color: #435ebe;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .file-name:hover {
        text-decoration: underline;
    }

    .btn-remove-file {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.2s;
    }

    .btn-remove-file:hover {
        color: #a71d2a;
    }
</style>
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
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nomorRegistrasi" class="form-label">Nomor Registrasi Pesawat</label>
                                        <input type="text" class="form-control @error('nomorRegistrasi') is-invalid @enderror" id="nomorRegistrasi" name="nomorRegistrasi" value="{{ old('nomorRegistrasi') }}" placeholder="Contoh: PK-ABC" required>
                                        @error('nomorRegistrasi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tipePesawat" class="form-label">Tipe Pesawat</label>
                                        <input type="text" class="form-control @error('tipePesawat') is-invalid @enderror" id="tipePesawat" name="tipePesawat" value="{{ old('tipePesawat') }}" placeholder="Contoh: Airbus A320" required>
                                        @error('tipePesawat')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jadwalKeberangkatan" class="form-label">Jadwal Keberangkatan</label>
                                        <input type="datetime-local" class="form-control @error('jadwalKeberangkatan') is-invalid @enderror" id="jadwalKeberangkatan" name="jadwalKeberangkatan" value="{{ old('jadwalKeberangkatan') }}" required>
                                        @error('jadwalKeberangkatan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jadwalKedatangan" class="form-label">Jadwal Kedatangan</label>
                                        <input type="datetime-local" class="form-control @error('jadwalKedatangan') is-invalid @enderror" id="jadwalKedatangan" name="jadwalKedatangan" value="{{ old('jadwalKedatangan') }}" required>
                                        @error('jadwalKedatangan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bandaraAsal" class="form-label">Bandara Asal</label>
                                        <input type="text" class="form-control @error('bandaraAsal') is-invalid @enderror" id="bandaraAsal" name="bandaraAsal" value="{{ old('bandaraAsal') }}" placeholder="Contoh: CGK" required>
                                        @error('bandaraAsal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bandaraTujuan" class="form-label">Bandara Tujuan</label>
                                        <input type="text" class="form-control @error('bandaraTujuan') is-invalid @enderror" id="bandaraTujuan" name="bandaraTujuan" value="{{ old('bandaraTujuan') }}" placeholder="Contoh: DPS" required>
                                        @error('bandaraTujuan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jenisPenerbangan" class="form-label">Jenis Penerbangan</label>
                                        <select class="form-select @error('jenisPenerbangan') is-invalid @enderror" id="jenisPenerbangan" name="jenisPenerbangan" required>
                                            <option value="" disabled selected>Pilih jenis penerbangan</option>
                                            <option value="penumpang" @selected(old('jenisPenerbangan') == 'penumpang')>Penumpang</option>
                                            <option value="kargo" @selected(old('jenisPenerbangan') == 'kargo')>Kargo</option>
                                            <option value="lainnya" @selected(old('jenisPenerbangan') == 'lainnya')>Lainnya</option>
                                        </select>
                                        @error('jenisPenerbangan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3" id="jenisLainnya" style="display: none;">
                                        <label for="jenislainnya" class="form-label">Jenis Penerbangan lainnya</label>
                                        <input type="text" class="form-control @error('jenislainnya') is-invalid @enderror" id="jenislainnya" name="jenislainnya" value="{{ old('jenislainnya') }}" placeholder="Masukkan Jenis Penerbangan Lainnya" >
                                        @error('jenislainnya')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Dokumen Pendukung <span class="text-danger">*</span></label>
                    
                                        {{-- Input File Tersembunyi (Hidden) --}}
                                        <input type="file" class="d-none" id="documents" name="documents[]" multiple accept=".pdf">
                                        
                                        {{-- Area Drag & Drop Visual --}}
                                        <div class="upload-area" id="drop-zone">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                            <p class="fw-bold">Seret & Lepas file di sini</p>
                                            <p class="small">atau klik untuk menjelajahi komputer</p>
                                            <p class="text-muted small mt-2">(Format: PDF. Maks: 2MB per file)</p>
                                        </div>
                
                                        {{-- Pesan Error Validasi --}}
                                        <div class="text-danger small mt-1" id="file-error"></div>
                                        @error('documents')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                        @error('documents.*')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                
                                        {{-- Tempat Menampilkan Preview File --}}
                                        <div class="file-preview-list" id="file-list"></div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('documents');
            const fileListContainer = document.getElementById('file-list');
            const errorContainer = document.getElementById('file-error');
            
            // Menggunakan DataTransfer untuk menyimpan kumpulan file yang dipilih/di-drop
            let dataTransfer = new DataTransfer();

            if (!dropZone || !fileInput) return; // safeguard

            // 1. Klik Area -> Trigger Input File
            dropZone.addEventListener('click', () => fileInput.click());

            // 2. Handle File Selected (Dari Klik)
            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            // 3. Handle Drag & Drop Events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Efek Visual saat Dragging
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('active'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('active'), false);
            });

            // Handle File Dropped
            dropZone.addEventListener('drop', function(e) {
                let droppedFiles = e.dataTransfer.files;
                handleFiles(droppedFiles);
            });

            // ================= FUNGSI UTAMA =================

            function handleFiles(files) {
                errorContainer.innerText = ''; // Reset error text
                
                Array.from(files).forEach(file => {
                    // Validasi Tipe File (Optional Client Side)
                    const allowedTypes = ['application/pdf'];
                    if(!allowedTypes.includes(file.type)) {
                        errorContainer.innerText += `File ${file.name} dilewati karena format tidak valid.\n`;
                        return;
                    }

                    // Validasi Ukuran (2MB)
                    if(file.size > 2 * 1024 * 1024) {
                        errorContainer.innerText += `File ${file.name} dilewati karena ukuran melebihi 2MB.\n`;
                        return;
                    }

                    // Cek duplikasi file (berdasarkan nama dan ukuran)
                    let isDuplicate = false;
                    for(let i=0; i<dataTransfer.items.length; i++) {
                        if(dataTransfer.items[i].getAsFile().name === file.name && dataTransfer.items[i].getAsFile().size === file.size) {
                            isDuplicate = true;
                            break;
                        }
                    }

                    if(!isDuplicate) {
                        dataTransfer.items.add(file);
                        addFilePreview(file, dataTransfer.items.length - 1);
                    }
                });

                // Sync ke Input File Asli
                fileInput.files = dataTransfer.files;
            }

            function addFilePreview(file, index) {
                // Buat URL Object sementara untuk preview
                const fileURL = URL.createObjectURL(file);
                
                // Tentukan Icon
                let iconClass = 'bi-file-earmark-pdf text-danger'; // Only PDF allowed here

                const item = document.createElement('div');
                item.className = 'file-item';
                item.dataset.index = index; // simpan index referensi
                item.innerHTML = `
                    <div class="file-info">
                        <i class="bi ${iconClass} file-icon"></i>
                        <div>
                            <a href="${fileURL}" target="_blank" class="file-name" title="Klik untuk Pratinjau">${file.name}</a>
                            <div class="text-muted x-small">${(file.size / 1024).toFixed(1)} KB</div>
                        </div>
                    </div>
                    <button type="button" class="btn-remove-file" title="Hapus File">
                        <i class="bi bi-x-circle"></i>
                    </button>
                `;

                // Event Hapus File spesifik
                item.querySelector('.btn-remove-file').addEventListener('click', function(e) {
                    e.stopPropagation(); // Agar tidak men-trigger klik dropzone
                    
                    const fileNameToRemove = file.name;
                    const fileSizeToRemove = file.size;
                    
                    // Buat DataTransfer baru tanpa file yang dihapus
                    const newDataTransfer = new DataTransfer();
                    for(let i = 0; i < dataTransfer.items.length; i++) {
                        const currentFile = dataTransfer.items[i].getAsFile();
                        if(currentFile.name !== fileNameToRemove || currentFile.size !== fileSizeToRemove) {
                            newDataTransfer.items.add(currentFile);
                        }
                    }
                    
                    // Replace the old DataTransfer
                    dataTransfer = newDataTransfer;
                    fileInput.files = dataTransfer.files;
                    
                    // Hapus Tampilan
                    item.remove();
                });

                // Klik link preview agar tidak men-trigger dropzone
                item.querySelector('a').addEventListener('click', function(e) {
                    e.stopPropagation(); 
                });

                fileListContainer.appendChild(item);
            }
        });
    </script>
@endsection
