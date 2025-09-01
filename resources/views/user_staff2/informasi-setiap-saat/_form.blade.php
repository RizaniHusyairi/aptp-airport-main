{{-- resources/views/user_staff2/informasi-setiap-saat/_form.blade.php --}}
{{-- resources/views/user_staff2/informasi-setiap-saat/_form.blade.php --}}
<div class="row">
    <div class="col-12 mb-3">
        <label for="title" class="form-label">Judul / Uraian Informasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $information->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-3">
        <label for="document_path" class="form-label">Link Google Drive Dokumen <span class="text-danger">*</span></label>
        <input type="url" class="form-control @error('document_path') is-invalid @enderror" id="document_path" name="document_path" value="{{ old('document_path', $information->document_path ?? '') }}" placeholder="https://docs.google.com/..." required>
        @error('document_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="published_date" class="form-label">Tanggal Publikasi <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('published_date') is-invalid @enderror" id="published_date" name="published_date" value="{{ old('published_date', isset($information) ? $information->published_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('published_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.evergreen-informations.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>