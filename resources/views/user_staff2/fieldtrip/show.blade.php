@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Field Trip')
@section('styles_admin')
    <style>
    .doc-preview-item {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin-right: 8px;
        margin-bottom: 8px;
        text-decoration: none;
        color: #435ebe;
        transition: all 0.2s;
    }
    .doc-preview-item:hover {
        border-color: #435ebe;
        color: #25396f;
    }
    .doc-preview-item i {
        font-size: 1.2rem;
        margin-right: 8px;
        margin-bottom: 17px;
    }
</style>
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Field Trip</h3>
                    <p class="text-subtitle text-muted">Detail informasi pengajuan Field Trip.</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('staff.dashboard.index')],
                    ['label' => 'Field Trip', 'url' => auth()->user()->is_staff ? route('fieldtrip.staffIndex') : route('fieldtrip.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            @if(session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-header">
                <h5 class="card-title">Informasi Pengaju</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 col-12 text-center mb-3 mb-md-0">
                        <div class="avatar avatar-xl me-3">
                            <img src="{{ $fieldtrip->users->first()?->avatar_url }}" alt="Foto Profil {{ $fieldtrip->users->first()?->name }}">
            
            
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12">
                                <h6>Nama</h6>
                                <p>{{ $fieldtrip->users->first()?->name ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Email</h6>
                                <p>{{ $fieldtrip->users->first()?->email ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Tanggal Pengajuan</h6>
                                <p>{{ $fieldtrip->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Field Trip</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <h6>Nama Field Trip</h6>
                        <p>{{ $fieldtrip->fieldtrip_name }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Jenis Field Trip</h6>
                        <p>{{ $fieldtrip->fieldtrip_type }}</p>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <h6>Deskripsi Field Trip</h6>
                        <p>{{ $fieldtrip->description }}</p>
                    </div>
                    @if ($fieldtrip->documents)
                        {{-- === BAGIAN MENAMPILKAN BANYAK DOKUMEN === --}}
                    <div class="col-12">
                        <h6 class="mb-3">Dokumen Terlampir</h6>
                        
                        @if ($fieldtrip->documents)
                            <div class="d-flex flex-wrap">
                                {{-- Cek apakah data berupa Array (Multiple Files) --}}
                                @if(is_array($fieldtrip->documents))
                                    @foreach($fieldtrip->documents as $doc)
                                        <a href="{{ asset('uploads/' . $doc) }}" target="_blank" class="doc-preview-item" data-bs-toggle="tooltip" title="Klik untuk melihat">
                                            @if(Str::endsWith($doc, '.pdf'))
                                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                                            @elseif(Str::endsWith($doc, ['.doc', '.docx']))
                                                <i class="bi bi-file-earmark-word text-primary"></i>
                                            @else
                                                <i class="bi bi-file-earmark-text"></i>
                                            @endif
                                            <span>{{ basename($doc) }}</span>
                                        </a>
                                    @endforeach

                                {{-- Fallback jika data masih string (Single File lama) --}}
                                @elseif(is_string($fieldtrip->documents))
                                    <a href="{{ asset('uploads/' . $fieldtrip->documents) }}" target="_blank" class="doc-preview-item">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <span>{{ basename($fieldtrip->documents) }}</span>
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="text-muted fst-italic">Tidak ada dokumen yang diunggah.</p>
                        @endif
                    </div>
                    @else
                        <div class="col-md-6">
                            <h6>Tidak Ada Dokumen</h6>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <h6>Status Pengajuan</h6>
                        @php
                        $status = $fieldtrip->submission_status;
                        $badgeClass = match($status) {
                            'Disetujui' => 'bg-success',
                            'Ditolak' => 'bg-danger',
                            'Revisi Diperlukan' => 'bg-warning',
                            default => 'bg-info',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @if($fieldtrip->submission_status != 'Diajukan')

        @notstaff
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($fieldtrip->submission_status == 'Disetujui' && $fieldtrip->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $fieldtrip->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($fieldtrip->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $fieldtrip->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $fieldtrip->submission_status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $fieldtrip->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
        @endnotstaff
        @endif
        @staff
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="detail-section">
                        <h6>Tindakan</h6>
                        <form action="{{ route('fieldtrip.updateStatus', $fieldtrip->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="status" class="form-label">Ubah Status</label>
                                <select name="submission_status" id="submission_status" class="form-select">
                                    <option value="Diajukan" @selected($fieldtrip->submission_status == 'Diajukan')>Diajukan</option>
                                    <option value="Disetujui" @selected($fieldtrip->submission_status == 'Disetujui')>Setujui</option>
                                    <option value="Ditolak" @selected($fieldtrip->submission_status == 'Ditolak')>Tolak</option>
                                    <option value="Revisi Diperlukan" @selected($fieldtrip->submission_status == 'Revisi Diperlukan')>Minta Revisi</option>
                                </select>
                            </div>

                            {{-- Input Catatan (muncul saat Ditolak/Revisi) --}}
                            <div class="form-group" id="staff-notes-container" style="display: none;">
                                <label for="staff_notes" class="form-label">Catatan Revisi / Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="staff_notes" id="staff_notes" class="form-control" rows="4" placeholder="Berikan catatan...">{{ $fieldtrip->staff_notes }}</textarea>
                                @error('staff_notes')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                            </div>

                            {{-- Input Surat Balasan (muncul saat Disetujui) --}}
                            <div class="form-group" id="reply-document-container" style="display: none;">
                                <label for="reply_document_path" class="form-label">Tautan Surat Balasan (Google Drive) <span class="text-danger">*</span></label>
                                <input type="url" name="reply_document_path" id="reply_document_path" class="form-control @error('reply_document_path') is-invalid @enderror" placeholder="https://..." value="{{ old('reply_document_path', $fieldtrip->reply_document_path) }}">
                                @error('reply_document_path')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Status</button>
                            </div>
                        </form>
                    </div>
                    </div>
                    <div class="col-md-6 col-12">
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($fieldtrip->submission_status == 'Disetujui' && $fieldtrip->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $fieldtrip->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($fieldtrip->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $fieldtrip->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $fieldtrip->status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $fieldtrip->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
        @endstaff
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ auth()->user()->is_staff ? route('fieldtrip.staffIndex') : route('fieldtrip.index') }}" class="btn btn-secondary">Kembali</a>
            
        </div>
    </section>
</div>
@endsection
@section('scripts_admin')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('submission_status');
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