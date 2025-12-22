@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Field Trip')
@section('styles_admin')
<style>
    /* Styling Area Drag & Drop */
    .upload-area {
        border: 2px dashed #435ebe;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        background-color: #f8f9fa;
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
        background: #fff;
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
                <h3>Tambah Pengajuan Field Trip</h3>
                <p class="text-subtitle text-muted">Formulir untuk mengajukan Field Trip baru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                                ['label' => 'Menu', 'url' => route('staff.dashboard.index')],
                                ['label' => 'Field Trip', 'url' => route('fieldtrip.index')],
                                ['label' => 'Pengajuan', 'active' => true],
                            ]" />        
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Syarat & Ketentuan Pengajuan Field Trip</h5>
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
            
            <h5 class="card-title">Formulir Pengajuan Field Trip</h5>
        </div>
        <div class="card-body">
            <form id="form-pengajuan-fieldTrip" method="POST" action="{{ route('fieldtrip.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fieldtrip_name" class="form-label">Nama Field Trip</label>
                        <input type="text" class="form-control @error('fieldtrip_name') is-invalid @enderror" id="fieldtrip_name" name="fieldtrip_name" required>
                        @error('fieldtrip_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fieldtrip_type" class="form-label">Jenis Field Trip</label>
                        <input type="text" class="form-control @error('fieldtrip_type') is-invalid @enderror" id="fieldtrip_type" name="fieldtrip_type" required>
                    
                        @error('fieldtrip_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Deskripsi Field Trip</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required></textarea>
                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Dokumen yang Diperlukan <span class="text-danger">*</span></label>
    
                        {{-- Input File Tersembunyi (Hidden) --}}
                        <input type="file" class="d-none" id="documents" name="documents" accept=".pdf,.doc,.docx">
                        
                        {{-- Area Drag & Drop Visual --}}
                        <div class="upload-area" id="drop-zone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="fw-bold">Seret & Lepas file di sini</p>
                            <p class="small">atau klik untuk menjelajahi komputer</p>
                            <p class="text-muted small mt-2">(Format: PDF, DOC, DOCX. Maks: 2MB)</p>
                        </div>

                        {{-- Pesan Error Validasi --}}
                        @error('documents')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        {{-- Tempat Menampilkan Preview File --}}
                        <div class="file-preview-list" id="file-list"></div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        <a href="{{ route('fieldtrip.index') }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('../assetsv2/compiled/js/tambah-pengajuan-fieldTrip.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('documents');
        const fileListContainer = document.getElementById('file-list');
        
        // Variabel untuk menyimpan file yang dipilih (DataTransfer object)
        // Ini trik agar kita bisa memanipulasi isi input type="file"
        let dataTransfer = new DataTransfer();

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
            // Karena di controller kamu cuma terima 1 file (single upload),
            // kita reset dulu setiap kali ada file baru masuk.
            // Jika ingin multiple, hapus baris: dataTransfer = new DataTransfer();
            dataTransfer = new DataTransfer(); 
            fileListContainer.innerHTML = ''; // Kosongkan tampilan lama

            if (files.length > 0) {
                // Ambil file pertama saja (karena logic controller single file)
                // Jika ingin multiple, lakukan looping foreach
                let file = files[0]; 
                
                // Validasi Tipe File (Optional Client Side)
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if(!allowedTypes.includes(file.type)) {
                    alert('Hanya file PDF, DOC, dan DOCX yang diperbolehkan.');
                    return;
                }

                // Validasi Ukuran (2MB)
                if(file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar (Maksimal 2MB).');
                    return;
                }

                // Tambahkan ke DataTransfer
                dataTransfer.items.add(file);

                // Update Visual Preview
                addFilePreview(file);
            }

            // Sync ke Input File Asli
            fileInput.files = dataTransfer.files;
        }

        function addFilePreview(file) {
            // Buat URL Object sementara untuk preview
            const fileURL = URL.createObjectURL(file);
            
            // Tentukan Icon
            let iconClass = 'bi-file-earmark-text';
            if(file.type === 'application/pdf') iconClass = 'bi-file-earmark-pdf text-danger';
            else if(file.type.includes('word')) iconClass = 'bi-file-earmark-word text-primary';

            const item = document.createElement('div');
            item.className = 'file-item';
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

            // Event Hapus File
            item.querySelector('.btn-remove-file').addEventListener('click', function(e) {
                e.stopPropagation(); // Agar tidak men-trigger klik dropzone
                
                // Reset Input & DataTransfer
                dataTransfer = new DataTransfer(); 
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
