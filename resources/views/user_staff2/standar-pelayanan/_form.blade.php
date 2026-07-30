@php
    // Mode sumber dokumen: turunkan dari data lama saat edit, default 'upload' saat create.
    $currentSource = old('source_type', isset($document) ? ($document->is_uploaded ? 'upload' : 'link') : 'upload');
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="type" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
            <option value="" disabled @selected(!isset($document))>Pilih Jenis...</option>
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('type', $document->type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="document_number" class="form-label">Nomor Dokumen</label>
        <input type="text" class="form-control @error('document_number') is-invalid @enderror" id="document_number" name="document_number" value="{{ old('document_number', $document->document_number ?? '') }}" placeholder="cth: SK.01/APTP/2026">
        @error('document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="title" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $document->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Deskripsi Singkat</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Keterangan tambahan yang akan tampil di halaman publik (opsional).">{{ old('description', $document->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="published_date" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('published_date') is-invalid @enderror" id="published_date" name="published_date" value="{{ old('published_date', isset($document) ? $document->published_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('published_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label d-block">Status Tampil</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                @checked(old('is_active', isset($document) ? $document->is_active : true))>
            <label class="form-check-label" for="is_active">Tampilkan di halaman publik</label>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <div class="col-12 mb-3">
        <label class="form-label">Sumber Dokumen <span class="text-danger">*</span></label>
        <div class="d-flex gap-4">
            <div class="form-check">
                <input class="form-check-input source-type-radio" type="radio" name="source_type" id="source_type_upload" value="upload" @checked($currentSource === 'upload')>
                <label class="form-check-label" for="source_type_upload">Unggah file PDF</label>
            </div>
            <div class="form-check">
                <input class="form-check-input source-type-radio" type="radio" name="source_type" id="source_type_link" value="link" @checked($currentSource === 'link')>
                <label class="form-check-label" for="source_type_link">Tautan eksternal</label>
            </div>
        </div>
        @error('source_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row" id="source-upload-wrapper">
    <div class="col-12 mb-3">
        <label for="file" class="form-label">File Dokumen (PDF)</label>
        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept="application/pdf">
        <div class="form-text">Format PDF, maksimal 10 MB.</div>
        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror

        @if (isset($document) && $document->is_uploaded)
            <div class="mt-2">
                <a href="{{ $document->document_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-light-primary">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Lihat dokumen saat ini
                </a>
                <div class="form-text">Biarkan kolom di atas kosong jika tidak ingin mengganti file.</div>
            </div>
        @endif
    </div>
</div>

<div class="row" id="source-link-wrapper">
    <div class="col-12 mb-3">
        <label for="document_link" class="form-label">Tautan Dokumen</label>
        <input type="url" class="form-control @error('document_link') is-invalid @enderror" id="document_link" name="document_link" value="{{ old('document_link', $document->document_link ?? '') }}" placeholder="https://docs.google.com/...">
        <div class="form-text">Gunakan tautan yang dapat diakses publik, misalnya Google Drive.</div>
        @error('document_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.service-standards.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.source-type-radio');
        const uploadWrapper = document.getElementById('source-upload-wrapper');
        const linkWrapper = document.getElementById('source-link-wrapper');

        function toggleSourceInputs() {
            const isUpload = document.getElementById('source_type_upload').checked;
            uploadWrapper.style.display = isUpload ? '' : 'none';
            linkWrapper.style.display = isUpload ? 'none' : '';
        }

        // Sesuaikan tampilan saat halaman dimuat, lalu setiap kali pilihan berubah.
        toggleSourceInputs();
        radios.forEach(radio => radio.addEventListener('change', toggleSourceInputs));
    });
</script>
