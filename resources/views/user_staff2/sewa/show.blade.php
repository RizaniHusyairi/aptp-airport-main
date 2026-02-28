@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail sewa')
@section('styles_admin')
    
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Sewa</h3>
                    <p class="text-subtitle text-muted">Detail informasi pengajuan sewa.</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Sewa', 'url' => auth()->user()->is_staff ? route('staffSewa.index') : route('sewa.index')],
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
                            <img src="{{ $rental->user?->avatar_url }}" alt="Foto Profil {{ $rental->user?->name }}">

                            
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12">
                                <h6>Nama</h6>
                                <p>{{ $rental->user?->name ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Email</h6>
                                <p>{{ $rental->user?->email ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Tanggal Pengajuan</h6>
                                <p>{{ $rental->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Sewa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <h6>Nama Sewa</h6>
                        <p>{{ $rental->rental_name }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Jenis Sewa</h6>
                        <p>{{ $rental->rental_type == 'Lainnya' ? $rental->rental_more : $rental->rental_type }}</p>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <h6>Deskripsi Sewa</h6>
                        <p>{{ $rental->description }}</p>
                    </div>
                    @if ($rental->documents)
                        <div class="col-md-12 mt-3">
                            <h6>Dokumen Terlampir</h6>
                            <div class="d-flex flex-wrap gap-2">
                            @if(is_array($rental->documents))
                                @foreach($rental->documents as $docPath)
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
                            @elseif(is_string($rental->documents))
                                {{-- Fallback if legacy data is just a string --}}
                                <a href="{{ asset('uploads/documents/rental/' . basename($rental->documents)) }}" class="btn btn-sm btn-primary" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen">
                                    <i class="bi bi-file-earmark-pdf"></i> {{ basename($rental->documents) }}
                                </a>
                            @endif
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 mt-3">
                            <h6>Tidak Ada Dokumen</h6>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <h6>Status Pengajuan</h6>
                        @php
                        $status = $rental->submission_status;
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
         @if($rental->submission_status != 'Diajukan')

        @notstaff
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        {{-- Tampilkan Surat Balasan jika Disetujui --}}
                        @if($rental->submission_status == 'Disetujui' && $rental->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $rental->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($rental->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $rental->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $rental->submission_status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $rental->staff_notes }}</p>
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
                        <form action="{{ route('staffSewa.updateStatus', $rental->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="status" class="form-label">Ubah Status</label>
                                <select name="submission_status" id="submission_status" class="form-select">
                                    <option value="Diajukan" @selected($rental->submission_status == 'Diajukan')>Diajukan</option>
                                    <option value="Disetujui" @selected($rental->submission_status == 'Disetujui')>Setujui</option>
                                    <option value="Ditolak" @selected($rental->submission_status == 'Ditolak')>Tolak</option>
                                    <option value="Revisi Diperlukan" @selected($rental->submission_status == 'Revisi Diperlukan')>Minta Revisi</option>
                                </select>
                            </div>

                            {{-- Input Catatan (muncul saat Ditolak/Revisi) --}}
                            <div class="form-group" id="staff-notes-container" style="display: none;">
                                <label for="staff_notes" class="form-label">Catatan Revisi / Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="staff_notes" id="staff_notes" class="form-control" rows="4" placeholder="Berikan catatan...">{{ $rental->staff_notes }}</textarea>
                                @error('staff_notes')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                            </div>

                            {{-- Input Surat Balasan (muncul saat Disetujui) --}}
                            <div class="form-group" id="reply-document-container" style="display: none;">
                                <label for="reply_document_path" class="form-label">Tautan Surat Balasan (Google Drive) <span class="text-danger">*</span></label>
                                <input type="url" name="reply_document_path" id="reply_document_path" class="form-control @error('reply_document_path') is-invalid @enderror" placeholder="https://..." value="{{ old('reply_document_path', $rental->reply_document_path) }}">
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
                        @if($rental->submission_status == 'Disetujui' && $rental->reply_document_path)
                        <div class="detail-section">
                            <h6>Surat Balasan:</h6>
                            <a href="{{ $rental->reply_document_path }}" target="_blank" class="document-link success">
                                <i class="bi bi-file-earmark-check-fill"></i>
                                <span>Unduh Surat Persetujuan</span>
                            </a>
                        </div>
                        @endif

                        {{-- Tampilkan Catatan Staff jika Ditolak/Revisi --}}
                        @if(in_array($rental->submission_status, ['Ditolak', 'Revisi Diperlukan']) && $rental->staff_notes)
                        <div class="detail-section">
                            <h6>Catatan dari Staff:</h6>
                            <div class="alert alert-light-{{ $rental->submission_status == 'Ditolak' ? 'danger' : 'warning' }} mb-0">
                                <p class="mb-0">{{ $rental->staff_notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
        @endstaff

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ auth()->user()->is_staff ? route('staffSewa.index') : route('sewa.index') }}" class="btn btn-secondary">Kembali</a>
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