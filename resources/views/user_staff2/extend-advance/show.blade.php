@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Extend Advance')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengajuan</h3>
                <p class="text-subtitle text-muted">Rincian lengkap dari pengajuan Extend / Advance Hour.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Extend Advance', 'url' => auth()->user()->is_staff ? route('staff.extend-advance.index') : route('extend-advance.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($submission->submission_status == 'Menunggu Dokumen Ditandatangani')
        <div class="alert alert-info">
            <h4 class="alert-heading">Langkah Selanjutnya</h4>
            <p>1. Silakan unduh dokumen permohonan Anda dengan menekan tombol <strong>"Ekspor ke PDF"</strong>.</p>
            <p>2. Cetak dan tandatangani dokumen tersebut.</p>
            <p class="mb-0">3. Unggah kembali dokumen yang sudah ditandatangani pada formulir di bawah ini untuk melanjutkan proses verifikasi.</p>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Rincian Permohonan</h5>
                    <a href="{{ route('extend-advance.export-pdf', $submission->id) }}" class="btn btn-secondary"><i class="bi bi-file-earmark-pdf-fill"></i> Ekspor ke PDF</a>
                
                </div>
                <div class="card-body">
                    <h6>I. Pesawat Udara</h6>
                    <table class="table table-bordered table-sm">
                        <tr><th style="width: 40%;">Operator (Pemilik/Penyewa)</th><td>{{ $submission->operator }}</td></tr>
                        <tr><th>Tipe Pesawat</th><td>{{ $submission->aircraft_type }}</td></tr>
                        <tr><th>Tanda Pendaftaran / No. Penerbangan</th><td>{{ $submission->registration_and_flight_number }}</td></tr>
                    </table>

                    <h6 class="mt-4">II. Penerbangan</h6>
                    <table class="table table-bordered table-sm">
                        <tr><th style="width: 40%;">Tanggal</th><td>{{ $submission->flight_date->format('d F Y') }}</td></tr>
                        <tr><th>Jam Keberangkatan (EOBT)</th><td>{{ $submission->eobt }}</td></tr>
                        <tr><th>Jam Kedatangan (AOBT)</th><td>{{ $submission->aobt }}</td></tr>
                        <tr><th>Rute</th><td>{{ $submission->route }}</td></tr>
                        <tr><th>Alternate Take Off</th><td>{{ $submission->take_off_alternate ?? '-' }}</td></tr>
                        <tr><th>Keperluan Terbang</th><td>{{ $submission->purpose_of_flight }}</td></tr>
                    </table>

                    <h6 class="mt-4">III. Pernyataan</h6>
                    <table class="table table-bordered table-sm">
                        <tr><th style="width: 40%;">Nama Pilot in Command (PIC)</th><td>{{ $submission->pic_name }}</td></tr>
                    </table>
                </div>
            </div>
            {{-- ========================================================== --}}
            {{-- ===            FORMULIR UNGGAH DOKUMEN               === --}}
            {{-- ========================================================== --}}
            @if($submission->submission_status == 'Menunggu Dokumen Ditandatangani')
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Unggah Dokumen Bertanda Tangan</h5></div>
                <div class="card-body">
                    <form action="{{ route('extend-advance.upload-signed-document', $submission->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="signed_document" class="form-label">Pilih File PDF <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="signed_document" name="signed_document" accept=".pdf" required>
                            <small class="form-text text-muted">Pastikan file yang diunggah adalah PDF yang sudah Anda tandatangani. Maksimal ukuran file 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Unggah dan Kirim</button>
                    </form>
                </div>
            </div>
            @endif

        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">Status & Tindakan</h5></div>
                <div class="card-body">
                    <h6>Informasi Pengaju</h6>
                    <p class="mb-0"><strong>Nama:</strong> {{ $submission->user->name }}</p>
                    <p><strong>Tanggal Pengajuan:</strong> {{ $submission->created_at->format('d M Y H:i') }}</p>

                    <h6>Status Saat Ini</h6>
                    @php
                        $statusClass = match($submission->submission_status) { 'Disetujui' => 'success', 'Ditolak' => 'danger', 'Revisi Diperlukan' => 'warning', default => 'info' };
                    @endphp
                    <span class="badge bg-light-{{$statusClass}} mb-3 fs-6">{{ $submission->submission_status }}</span>

                    {{-- Menampilkan respon dari staff untuk user --}}
                    @notstaff
                        @if($submission->submission_status == 'Disetujui' && $submission->reply_document_path)
                            <h6 class="mt-3">Surat Balasan</h6>
                            <a href="{{ $submission->reply_document_path }}" target="_blank" class="btn btn-success"><i class="bi bi-file-earmark-arrow-down"></i> Unduh Surat Persetujuan</a>
                        @endif
                        @if(in_array($submission->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $submission->staff_notes)
                            <h6 class="mt-3">Catatan dari Staff</h6>
                            <div class="alert alert-light-{{$statusClass}} mb-0"><p class="mb-0">{{ $submission->staff_notes }}</p></div>
                        @endif
                    @endnotstaff

                    {{-- Form tindakan untuk staff --}}
                    @staff
                    <form action="{{ route('staff.extend-advance.updateStatus', $submission->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="submission_status" class="form-label">Ubah Status</label>
                            <select name="submission_status" id="submission_status" class="form-select">
                                <option value="Disetujui" @selected($submission->submission_status == 'Disetujui')>Setujui</option>
                                <option value="Ditolak" @selected($submission->submission_status == 'Ditolak')>Tolak</option>
                                <option value="Revisi Diperlukan" @selected($submission->submission_status == 'Revisi Diperlukan')>Minta Revisi</option>
                            </select>
                        </div>
                        <div class="form-group" id="staff-notes-container" style="display: none;">
                            <label for="staff_notes" class="form-label">Catatan Revisi / Alasan Penolakan</label>
                            <textarea name="staff_notes" id="staff_notes" class="form-control" rows="3">{{ old('staff_notes', $submission->staff_notes) }}</textarea>
                        </div>
                        <div class="form-group" id="reply-document-container" style="display: none;">
                            <label for="reply_document_path" class="form-label">Tautan Surat Balasan (Google Drive)</label>
                            <input type="url" name="reply_document_path" id="reply_document_path" class="form-control" value="{{ old('reply_document_path', $submission->reply_document_path) }}">
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Status</button>
                        </div>
                    </form>
                    @endstaff
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @staff
        const statusSelect = document.getElementById('submission_status');
        if (statusSelect) {
            const notesContainer = document.getElementById('staff-notes-container');
            const replyContainer = document.getElementById('reply-document-container');
            
            const toggleInputs = () => {
                const selectedValue = statusSelect.value;
                notesContainer.style.display = (selectedValue === 'Ditolak' || selectedValue === 'Revisi Diperlukan') ? 'block' : 'none';
                replyContainer.style.display = (selectedValue === 'Disetujui') ? 'block' : 'none';
            };
            
            toggleInputs();
            statusSelect.addEventListener('change', toggleInputs);
        }
        @endstaff
    });
</script>
@endsection
