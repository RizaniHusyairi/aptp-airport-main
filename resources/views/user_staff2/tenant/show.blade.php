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
                            <img src="{{ $tenant->users->first()?->avatar_url }}" alt="Foto Profil {{ $tenant->users->first()?->name }}">
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12">
                                <h6>Nama</h6>
                                <p>{{ $tenant->users->first()?->name ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Email</h6>
                                <p>{{ $tenant->users->first()?->email ?? '-' }}</p>
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
                            'disetujui' => 'bg-success',
                            'ditolak' => 'bg-danger',
                            default => 'bg-info',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </div>
                    <div class="col-12">
                        <h6>Deskripsi Usaha</h6>
                        <p>{{ $tenant->description }}</p>
                    </div>
                    @if ($tenant->documents)
                        
                    
                        <div class="col-12">
                            <h6>Dokumen Terlampir</h6>
                            <a href="{{ asset('uploads/documents/tenant/' . basename($tenant->documents)) }}" class="btn btn-sm btn-primary" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen"><i class="bi bi-file-earmark-pdf"></i> {{ basename($tenant->documents) }}</a>
                        </div>
                    @else
                        <div class="col-12">
                            <h6>Tidak Ada Dokumen</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @notstaff
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($tenant->status == 'Disetujui' && $tenant->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $tenant->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($tenant->status, ['Ditolak', 'Revisi Diperlukan']) && $tenant->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $tenant->status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $tenant->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
        @endnotstaff
        @staff
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($tenant->status == 'Disetujui' && $tenant->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $tenant->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($tenant->status, ['Ditolak', 'Revisi Diperlukan']) && $tenant->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $tenant->status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
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
