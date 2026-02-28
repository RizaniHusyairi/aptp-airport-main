@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Tenant')
@section('styles_admin')
    
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Tenant</h3>
                    <p class="text-subtitle text-muted">Detail informasi pengajuan tenant.</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Tenant', 'url' => auth()->user()->is_staff ? route('tenant.staffIndex') : route('tenant.index')],
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
                            <img src="{{ $tenant->user?->avatar_url }}" alt="Foto Profil {{ $tenant->user?->name }}">
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12">
                                <h6>Nama</h6>
                                <p>{{ $tenant->user?->name ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Email</h6>
                                <p>{{ $tenant->user?->email ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Tanggal Pengajuan</h6>
                                <p>{{ $tenant->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Tenant</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h6>Nama Usaha</h6>
                        <p>{{ $tenant->business_name }}</p>
                    </div>
                    <div class="col-md-6 col-12">
                        <h6>Jenis Usaha</h6>
                        <p>{{ $tenant->business_type }}</p>
                    </div>
                    <div class="col-md-6 col-12">
                        <h6>Jenis Tenant</h6>
                        <p>{{ $tenant->rental_type == 'Lainnya' ? $tenant->rental_more : $tenant->rental_type }}</p>
                    </div>
                    <div class="col-md-6 col-12">
                        <h6>Status Pengajuan</h6>
                        @php
                        $status = $tenant->submission_status;
                        $badgeClass = match($status) {
                            'Disetujui' => 'bg-success',
                            'Ditolak' => 'bg-danger',
                            'Revisi Diperlukan' => 'bg-warning',
                            default => 'bg-info',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </div>
                    <div class="col-12 mt-2">
                        <h6>Deskripsi Usaha</h6>
                        <p>{{ $tenant->description }}</p>
                    </div>
                    @if ($tenant->documents)
                        <div class="col-12 mt-3">
                            <h6>Dokumen Terlampir</h6>
                            <div class="d-flex flex-wrap gap-2">
                            @if(is_array($tenant->documents))
                                @foreach($tenant->documents as $docPath)
                                    @php
                                        $extension = pathinfo($docPath, PATHINFO_EXTENSION);
                                        $iconClass = 'bi-file-earmark-text';
                                        $btnClass = 'btn-primary';
                                        
                                        if(in_array(strtolower($extension), ['pdf'])) {
                                            $iconClass = 'bi-file-earmark-pdf';
                                            $btnClass = 'btn-danger';
                                        } elseif(in_array(strtolower($extension), ['doc', 'docx'])) {
                                            $iconClass = 'bi-file-earmark-word';
                                            $btnClass = 'btn-primary';
                                        }
                                    @endphp
                                    <a href="{{ asset('storage/' . $docPath) }}" class="btn btn-sm {{ $btnClass }}" target="_blank" data-bs-toggle="tooltip" title="Lihat Dokumen">
                                        <i class="bi {{ $iconClass }}"></i> {{ preg_replace('/^\d+_/', '', basename($docPath)) }}
                                    </a>
                                @endforeach
                            @elseif(is_string($tenant->documents))
                                {{-- Fallback if legacy data is just a string --}}
                                <a href="{{ asset('uploads/documents/tenant/' . basename($tenant->documents)) }}" class="btn btn-sm btn-primary" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen">
                                    <i class="bi bi-file-earmark-pdf"></i> {{ basename($tenant->documents) }}
                                </a>
                            @endif
                            </div>
                        </div>
                    @else
                        <div class="col-12 mt-3">
                            <h6>Tidak Ada Dokumen</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($tenant->submission_status != 'Diajukan')

        @notstaff
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($tenant->submission_status == 'Disetujui' && $tenant->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $tenant->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($tenant->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $tenant->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $tenant->submission_status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $tenant->staff_notes }}</p>
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
                        <form action="{{ route('tenant.updateStatus', $tenant->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="status" class="form-label">Ubah Status</label>
                                <select name="submission_status" id="submission_status" class="form-select">
                                    <option value="Diajukan" @selected($tenant->submission_status == 'Diajukan')>Diajukan</option>
                                    <option value="Disetujui" @selected($tenant->submission_status == 'Disetujui')>Setujui</option>
                                    <option value="Ditolak" @selected($tenant->submission_status == 'Ditolak')>Tolak</option>
                                    <option value="Revisi Diperlukan" @selected($tenant->submission_status == 'Revisi Diperlukan')>Minta Revisi</option>
                                </select>
                            </div>

                            {{-- Input Catatan (muncul saat Ditolak/Revisi) --}}
                            <div class="form-group" id="staff-notes-container" style="display: none;">
                                <label for="staff_notes" class="form-label">Catatan Revisi / Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="staff_notes" id="staff_notes" class="form-control" rows="4" placeholder="Berikan catatan...">{{ $tenant->staff_notes }}</textarea>
                                @error('staff_notes')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                            </div>

                            {{-- Input Surat Balasan (muncul saat Disetujui) --}}
                            <div class="form-group" id="reply-document-container" style="display: none;">
                                <label for="reply_document_path" class="form-label">Tautan Surat Balasan (Google Drive) <span class="text-danger">*</span></label>
                                <input type="url" name="reply_document_path" id="reply_document_path" class="form-control @error('reply_document_path') is-invalid @enderror" placeholder="https://..." value="{{ old('reply_document_path', $tenant->reply_document_path) }}">
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
                        @if($tenant->submission_status == 'Disetujui' && $tenant->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $tenant->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($tenant->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $tenant->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $tenant->submission_status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $tenant->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
        @endstaff
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ auth()->user()->is_staff ? route('tenant.staffIndex') : route('tenant.index') }}" class="btn btn-secondary">Kembali</a>
            
        </div>
    </section>
</div>
@endsection
@section('scripts_admin')
    <script src="{{ asset('../assetsv2/compiled/js/staff-tenant-detail.js') }}"></script>
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
