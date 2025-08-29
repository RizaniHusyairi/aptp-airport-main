<div class="row">
    <div class="col-12 mb-3">
        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $slide->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Deskripsi Singkat <span class="text-danger">*</span></label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" required>{{ old('description', $slide->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="image" class="form-label">Gambar <span class="text-danger">*</span></label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" @empty($slide) required @endempty>
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @isset($slide)<small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>@endisset
    </div>
    <div class="col-12 mb-3">
        <label for="link_url" class="form-label">Tautan (Opsional)</label>
        <input type="url" class="form-control @error('link_url') is-invalid @enderror" name="link_url" value="{{ old('link_url', $slide->link_url ?? '') }}" placeholder="https://...">
        @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_visible" value="1" id="is_visible" @checked(old('is_visible', $slide->is_visible ?? true))>
            <label class="form-check-label" for="is_visible">Tampilkan di Halaman Utama</label>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('admin.info-slides.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
