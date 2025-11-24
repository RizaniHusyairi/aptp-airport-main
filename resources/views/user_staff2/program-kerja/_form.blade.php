@csrf
<div class="row">
    <div class="col-12 mb-3">
        <label for="name" class="form-label">Nama Program Kerja <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $workProgram->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori Program <span class="text-danger">*</span></label>
        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="" selected disabled>Pilih Kategori...</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $workProgram->category ?? '') == $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Tugas-tugas yang Akan Dicapai <span class="text-danger">*</span></label>
        @error('tasks')<div class="invalid-feedback d-block mb-2">{{ $message }}</div>@enderror
        <div id="task-list">
            {{-- Loop untuk data lama (edit) atau data dari validasi gagal (create/edit) --}}
            @php $tasks = old('tasks', isset($workProgram) ? $workProgram->tasks->toArray() : [['description' => '']]); @endphp
            @foreach ($tasks as $index => $task)
                <div class="input-group mb-2 task-item">
                    <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task['id'] ?? '' }}">
                    <span class="input-group-text"><i class="bi bi-list-task"></i></span>
                    <input type="text" name="tasks[{{ $index }}][description]" class="form-control @error('tasks.'.$index) is-invalid @enderror" placeholder="Deskripsi tugas..." value="{{ $task['description'] ?? '' }}" required>
                    <button type="button" class="btn btn-outline-danger btn-remove-task"><i class="bi bi-trash"></i></button>
                    @error('tasks.'.$index) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-outline-success btn-sm mt-2" id="add-task-button"><i class="bi bi-plus"></i> Tambah Tugas</button>
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.work-programs.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan Program Kerja</button>
</div>

{{-- Template untuk baris tugas baru (hidden) --}}
<template id="task-template">
    <div class="input-group mb-2 task-item">
        <input type="hidden" name="tasks[INDEX][id]" value="">
        <span class="input-group-text"><i class="bi bi-list-task"></i></span>
        <input type="text" name="tasks[INDEX][description]" class="form-control" placeholder="Deskripsi tugas..." value="" required>
        <button type="button" class="btn btn-outline-danger btn-remove-task"><i class="bi bi-trash"></i></button>
    </div>
</template>
