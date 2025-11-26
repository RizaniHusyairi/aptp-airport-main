@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Program Kerja: ' . $workProgram->name)

@section('styles_admin')
    <style> 
        .progress { height: 1.25rem; font-size: 0.8rem; } 
        .task-item { border-bottom: 1px solid #eee; padding-bottom: 1rem; margin-bottom: 1rem; }
        .task-item:last-child { border-bottom: none; }
        .verification-notes { 
            background-color: #181818; 
            border-left: 3px solid #6c757d; 
            padding: 8px 12px; 
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ $workProgram->name }}</h3>
                <p class="text-subtitle text-muted">Detail dan progres penyelesaian tugas.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Program Kerja', 'url' => route('staff.work-programs.index')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
     @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="card-title mb-0">Progres Penyelesaian</h5>
             <a href="{{ route('staff.work-programs.edit', $workProgram->id) }}" class="btn btn-warning btn-sm">Edit Program & Tugas</a>
        </div>
        <div class="card-body">
             <div class="progress mb-3">
                 <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated @if($workProgram->progress_percentage == 100) bg-success @else bg-primary @endif"
                      role="progressbar" style="width: {{ $workProgram->progress_percentage }}%"
                      aria-valuenow="{{ $workProgram->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                      {{ $workProgram->progress_percentage }}% Diverifikasi
                 </div>
             </div>

             <h6 class="mt-4">Daftar Tugas</h6>
             <div id="task-list-detail">
                 @forelse ($workProgram->tasks as $task)
                     <div class="task-item">
                         <div class="d-flex justify-content-between align-items-start mb-2">
                             <p class="mb-0 flex-grow-1 me-3">{{ $task->description }}</p>
                             @php
                                $statusClass = match($task->status) {
                                    'Diverifikasi' => 'success',
                                    'Revisi Diperlukan' => 'warning',
                                    'Menunggu Verifikasi' => 'info',
                                    default => 'secondary', // Belum Selesai
                                };
                            @endphp
                             <span class="badge bg-light-{{$statusClass}}">{{ $task->status }}</span>
                         </div>

                         {{-- Tampilkan detail tambahan berdasarkan status --}}
                         @if($task->supporting_document_link)
                            <div class="mb-2">
                                <small class="text-muted">Data Dukung:</small> 
                                <a href="{{ $task->supporting_document_link }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 ms-1">Lihat Dokumen</a>
                            </div>
                         @endif
                         @if($task->verifier)
                            <div class="verification-notes">
                                <small class="text-muted">
                                    @if($task->status == 'Diverifikasi')
                                        Diverifikasi oleh: <strong>{{ $task->verifier->name }}</strong> pada {{ $task->updated_at?->translatedFormat('d M Y H:i') }}
                                    @elseif($task->status == 'Revisi Diperlukan')
                                        Revisi diminta oleh: <strong>{{ $task->verifier->name }}</strong> pada {{ $task->updated_at?->translatedFormat('d M Y H:i') }}
                                    @endif
                                </small>
                                @if($task->verification_notes)
                                    <p class="mb-0 mt-1"><em>"{{ $task->verification_notes }}"</em></p>
                                @endif
                            </div>
                         @endif

                         {{-- Form Aksi untuk Staf (Ajukan Verifikasi) --}}
                         @if(!Auth::user()->hasPermission('Verifikasi Program Kerja') && in_array($task->status, ['Belum Selesai', 'Revisi Diperlukan']))
                         
                            <form action="{{ route('staff.tasks.submitVerification', $task->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="url" name="supporting_document_link" class="form-control" placeholder="Masukkan link data dukung" required>
                                    <button class="btn btn-outline-primary" type="submit">Ajukan Verifikasi</button>
                                </div>
                                @error('supporting_document_link', $task->id . '_submit') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror 
                            </form>
                         @endif

                         {{-- Form Aksi untuk Kanit (Verifikasi/Revisi) --}}
                         {{-- Ganti 'Kanit' dengan role yang sesuai --}}
                         @if(Auth::user()->hasPermission('Verifikasi Program Kerja') && $task->status == 'Menunggu Verifikasi')
                            <form action="{{ route('staff.tasks.verify', $task->id) }}" method="POST" class="mt-3 border p-3 rounded bg-light">
                                @csrf
                                <h6 class="mb-3">Tindakan Verifikasi</h6>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="verification_status" id="verify-{{$task->id}}" value="Diverifikasi" required>
                                        <label class="form-check-label" for="verify-{{$task->id}}">Verifikasi (Setujui)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="verification_status" id="revise-{{$task->id}}" value="Revisi Diperlukan" required>
                                        <label class="form-check-label" for="revise-{{$task->id}}">Minta Revisi</label>
                                    </div>
                                </div>
                                <div class="mb-2" id="notes-container-{{$task->id}}" style="display: none;">
                                    <label for="verification_notes-{{$task->id}}" class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                                    <textarea name="verification_notes" id="verification_notes-{{$task->id}}" class="form-control form-control-sm" rows="2" placeholder="Jelaskan apa yang perlu direvisi..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Simpan Tindakan</button>
                            </form>
                         @endif

                     </div>
                 @empty
                     <p class="text-muted">Belum ada tugas untuk program kerja ini.</p>
                 @endforelse
             </div>
        </div>
         <div class="card-footer">
             <a href="{{ route('staff.work-programs.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
         </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Logika untuk menampilkan/menyembunyikan input catatan revisi
            $('input[name="verification_status"]').on('change', function() {
                const notesContainerId = '#notes-container-' + $(this).attr('id').split('-')[1]; // Dapatkan ID task
                const notesTextarea = $(notesContainerId).find('textarea');
                
                if ($(this).val() === 'Revisi Diperlukan') {
                    $(notesContainerId).slideDown();
                    notesTextarea.prop('required', true); // Wajibkan catatan jika revisi
                } else {
                    $(notesContainerId).slideUp();
                    notesTextarea.prop('required', false);
                }
            });
        });
    </script>
@endsection

