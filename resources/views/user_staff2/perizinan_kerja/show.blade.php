@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Izin Kerja')

@push('styles_admin')
    <link href="{{ asset('assetsv2/compiled/css/perizinan-kerja.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengajuan Izin Kerja</h3>
                <p class="text-subtitle text-muted">Lihat detail pengajuan izin kerja Anda.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Izin Kerja', 'url' => auth()->user()->is_staff ? route('kerja.index') : route('kerja.userindex')],
                    ['label' => 'Detail Pengajuan', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <!-- Kolom Kiri: Detail Pengajuan -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pengajuan</h5>
                </div>
                <div class="card-body">
                    <!-- Info Pengaju -->
                    <div class="detail-section">
                        <h6>Diajukan oleh:</h6>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-3">
                                <img src="{{ $workPermit->user->avatar_url }}" alt="Foto Profil {{ $workPermit->user->name }}">
                                
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ $workPermit->user->name }}</p>
                                <p class="text-muted mb-0">{{ $workPermit->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pekerjaan -->
                    <div class="detail-section mt-4">
                        <h6>Detail Pekerjaan:</h6>
                        <ul class="detail-list">
                            <li><strong>Jenis Pekerjaan:</strong> {{ $workPermit->work_type }}</li>
                            <li><strong>Lokasi:</strong> {{ $workPermit->location }}</li>
                            <li><strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($workPermit->start_date)->translatedFormat('d M Y, H:i') }} s/d {{ \Carbon\Carbon::parse($workPermit->end_date)->translatedFormat('d M Y, H:i') }}</li>
                        </ul>
                        <p class="mt-3"><strong>Deskripsi:</strong><br>{{ $workPermit->description }}</p>
                    </div>
                    <div class="detail-section mt-4">
                        <h6>Status:</h6>
                        @php
                            $statusClass = match($workPermit->submission_status) {
                                'Disetujui' => 'bg-success',
                                'Ditolak' => 'bg-danger',
                                'Revisi Diperlukan' => 'bg-warning',
                                default => 'bg-info',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $workPermit->submission_status }}</span>
                    </div>

                    <!-- Daftar Pekerja & Peralatan -->
                    
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status & Dokumen -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status & Dokumen</h5>
                </div>
                <div class="card-body">
                    <!-- Dokumen Terlampir -->
                    <div class="detail-section">
                        <h6>Dokumen Pendukung:</h6>
                        @forelse($workPermit->documents as $docPath)
                        <a href="{{ asset('uploads/documents/work_permits/' . basename($docPath)) }} " target="_blank" class="btn btn-info w-100">
                                
                            <i class="bi bi-file-earmark-arrow-down-fill me-2"></i> Lihat Dokumen Pengajuan
                        </a>
                        @empty
                            <p class="text-muted">Tidak ada dokumen terlampir.</p>
                        @endforelse
                    </div>

                    {{-- Tampilkan catatan staff jika ada (untuk Pengaju) --}}
                    @notstaff
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($workPermit->submission_status == 'Disetujui' && $workPermit->reply_document_path)
                        <div class="detail-section mt-4">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $workPermit->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($workPermit->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $workPermit->staff_notes)
                        <div class="detail-section mt-4">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $workPermit->submission_status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $workPermit->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                    @endnotstaff

                    {{-- Form Tindakan hanya untuk Staff --}}
                    @staff
                    <div class="detail-section mt-4">
                        <h6>Tindakan</h6>
                        <form action="{{ route('kerja.updateStatus', $workPermit->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="status" class="form-label">Ubah Status</label>
                                <select name="submission_status" id="status" class="form-select">
                                    <option value="Diajukan" @selected($workPermit->submission_status == 'Diajukan')>Diajukan</option>
                                    <option value="Disetujui" @selected($workPermit->submission_status == 'Disetujui')>Setujui</option>
                                    <option value="Ditolak" @selected($workPermit->submission_status == 'Ditolak')>Tolak</option>
                                    <option value="Revisi Diperlukan" @selected($workPermit->submission_status == 'Revisi Diperlukan')>Minta Revisi</option>
                                </select>
                            </div>

                            {{-- Input Catatan (muncul saat Ditolak/Revisi) --}}
                            <div class="form-group" id="staff-notes-container" style="display: none;">
                                <label for="staff_notes" class="form-label">Catatan Revisi / Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="staff_notes" id="staff_notes" class="form-control" rows="4" placeholder="Berikan catatan...">{{ $workPermit->staff_notes }}</textarea>
                                @error('staff_notes')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                            </div>

                            {{-- Input Surat Balasan (muncul saat Disetujui) --}}
                            <div class="form-group" id="reply-document-container" style="display: none;">
                                <label for="reply_document_path" class="form-label">Tautan Surat Balasan (Google Drive) <span class="text-danger">*</span></label>
                                <input type="url" name="reply_document_path" id="reply_document_path" class="form-control @error('reply_document_path') is-invalid @enderror" placeholder="https://..." value="{{ old('reply_document_path', $workPermit->reply_document_path) }}">
                                @error('reply_document_path')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Status</button>
                            </div>
                        </form>
                    </div>
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
        const statusSelect = document.getElementById('status');
        const notesContainer = document.getElementById('staff-notes-container');
        const replyContainer = document.getElementById('reply-document-container');

        if (statusSelect) {
            const toggleInputsVisibility = () => {
                const selectedValue = statusSelect.value;
                
                // Tampilkan catatan jika Ditolak atau Minta Revisi
                notesContainer.style.display = (selectedValue === 'Ditolak' || selectedValue === 'Revisi Diperlukan') ? 'block' : 'none';
                
                // Tampilkan input file jika Disetujui
                replyContainer.style.display = (selectedValue === 'Disetujui') ? 'block' : 'none';
            };

            toggleInputsVisibility();
            statusSelect.addEventListener('change', toggleInputsVisibility);
        }
    });
</script>
@endsection
