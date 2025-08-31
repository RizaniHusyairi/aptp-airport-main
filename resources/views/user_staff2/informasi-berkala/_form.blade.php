<div class="row">
    <div class="col-12 mb-3">
        <label for="category" class="form-label">Kategori Dokumen <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $document->category ?? '') }}" list="category-suggestions" required>
        <datalist id="category-suggestions">
            @foreach($categories as $category)
                <option value="{{ $category }}">
            @endforeach
        </datalist>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-3">
        <label for="title" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $document->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="published_date" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('published_date') is-invalid @enderror" id="published_date" name="published_date" value="{{ old('published_date', isset($document) ? $document->published_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('published_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="document" class="form-label">File Dokumen (PDF) @if(!isset($document))<span class="text-danger">*</span>@endif</label>
        <input class="form-control @error('document') is-invalid @enderror" type="file" id="document" name="document" accept=".pdf">
        @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(isset($document) && $document->document_path)
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah file. File saat ini: <a href="{{ Storage::url($document->document_path) }}" target="_blank">Lihat Dokumen</a></small>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.periodic-documents.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>