<div class="row">
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="" disabled @selected(!isset($information))>Pilih Kategori...</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $information->category ?? '') == $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="published_date" class="form-label">Tanggal Publikasi</label>
        <input type="date" class="form-control @error('published_date') is-invalid @enderror" id="published_date" name="published_date" value="{{ old('published_date', isset($information) ? optional($information->published_date)->format('Y-m-d') : '') }}">
        @error('published_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="title" class="form-label">Judul / Uraian Informasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $information->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="document_link" class="form-label">Link Dokumen (Google Drive) <span class="text-danger">*</span></label>
        <input type="url" class="form-control @error('document_link') is-invalid @enderror" id="document_link" name="document_link" value="{{ old('document_link', $information->document_link ?? '') }}" placeholder="https://..." required>
        @error('document_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.evergreen-informations.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>

