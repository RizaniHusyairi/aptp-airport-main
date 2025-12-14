<div class="row">
    <div class="col-12 mb-3">
        <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $report->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="publication_year" class="form-label">Tahun Publikasi <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('publication_year') is-invalid @enderror" id="publication_year" name="publication_year" value="{{ old('publication_year', $report->publication_year ?? date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}" required>
        @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="document_link" class="form-label">Link Dokumen (Google Drive) <span class="text-danger">*</span></label>
        <input type="url" class="form-control @error('document_link') is-invalid @enderror" id="document_link" name="document_link" value="{{ old('document_link', $report->document_link ?? '') }}" placeholder="https://..." required>
        @error('document_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.information-service-reports.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>