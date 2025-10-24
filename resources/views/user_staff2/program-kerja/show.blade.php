@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Program Kerja: ' . $workProgram->name)

@section('styles_admin')
    <style> .progress { height: 1.25rem; font-size: 0.8rem; } </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Program Kerja</h3>
                <p class="text-subtitle text-muted">{{ $workProgram->name }}</p>
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
                      {{ $workProgram->progress_percentage }}%
                 </div>
             </div>

             <h6 class="mt-4">Daftar Tugas</h6>
             <ul class="list-group" id="task-list-detail">
                 @forelse ($workProgram->tasks as $task)
                     <li class="list-group-item d-flex align-items-center">
                         <div class="form-check form-switch flex-grow-1">
                             <input class="form-check-input task-checkbox" type="checkbox" role="switch"
                                    id="task-{{ $task->id }}"
                                    data-task-id="{{ $task->id }}"
                                    data-update-url="{{ route('staff.tasks.updateStatus', $task->id) }}"
                                    @checked($task->is_completed)>
                             <label class="form-check-label @if($task->is_completed) text-decoration-line-through text-muted @endif"
                                    for="task-{{ $task->id }}">
                                 {{ $task->description }}
                             </label>
                         </div>
                     </li>
                 @empty
                     <li class="list-group-item text-muted">Belum ada tugas untuk program kerja ini.</li>
                 @endforelse
             </ul>
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
            $('.task-checkbox').on('change', function() {
                const taskId = $(this).data('taskId');
                const updateUrl = $(this).data('updateUrl');
                const isChecked = $(this).is(':checked');
                // === PERUBAHAN DI SINI: Konversi boolean ke 1 atau 0 ===
                const isCompletedValue = isChecked ? 1 : 0; 
                const progressBar = $('#progress-bar');
                const label = $(this).next('label'); 

                // Optimistic UI update
                label.toggleClass('text-decoration-line-through text-muted', isChecked);

                $.ajax({
                    url: updateUrl,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_completed: isCompletedValue // Kirim 1 atau 0
                    },
                    success: function(response) {
                        if (response.success) {
                            const progress = response.progress;
                            progressBar.css('width', progress + '%').attr('aria-valuenow', progress).text(progress + '%');
                            progressBar.toggleClass('bg-success', progress === 100).toggleClass('bg-primary', progress < 100);
                            label.toggleClass('text-decoration-line-through text-muted', isChecked);
                        } else {
                            $(this).prop('checked', !isChecked); // Revert checkbox
                            label.toggleClass('text-decoration-line-through text-muted', !isChecked); // Revert label
                            alert('Gagal memperbarui status tugas.');
                        }
                    },
                    error: function() {
                        $(this).prop('checked', !isChecked); // Revert checkbox
                        label.toggleClass('text-decoration-line-through text-muted', !isChecked); // Revert label
                        alert('Terjadi kesalahan saat menghubungi server.');
                    }
                });
            });
        });
    </script>
@endsection