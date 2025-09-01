<div class="row">
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori Dokumen <span class="text-danger">*</span></label>
        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="" disabled @selected(!isset($document))>Pilih Kategori...</option>
            <option value="Laporan Kinerja" @selected(old('category', $document->category ?? '') == 'Laporan Kinerja')>Laporan Kinerja</option>
            <option value="Survei kepuasan" @selected(old('category', $document->category ?? '') == 'Survei kepuasan')>Survei kepuasan</option>
            <option value="LHKPN" @selected(old('category', $document->category ?? '') == 'LHKPN')>LHKPN</option>
            <option value="Rencana kerja tahunan" @selected(old('category', $document->category ?? '') == 'Rencana kerja tahunan')>Rencana kerja tahunan</option>
            <option value="Data statistik kepegawaian" @selected(old('category', $document->category ?? '') == 'Data statistik kepegawaian')>Data statistik kepegawaian</option>
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Input Nama Pejabat (Kondisional) --}}
    <div class="col-md-6 mb-3" id="pejabat-name-wrapper" style="display: none;">
        <label for="pejabat_name" class="form-label">Nama Pejabat <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('pejabat_name') is-invalid @enderror" id="pejabat_name" name="pejabat_name" value="{{ old('pejabat_name', $document->pejabat_name ?? '') }}">
        @error('pejabat_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    <div class="col-md-6 mb-3">
        <label for="published_date" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('published_date') is-invalid @enderror" id="published_date" name="published_date" value="{{ old('published_date', isset($document) ? $document->published_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('published_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        {{-- Input diubah dari file menjadi text untuk URL --}}
        <label for="document_path" class="form-label">Link Google Drive <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('document_path') is-invalid @enderror" id="document_path" name="document_path" value="{{ old('document_path', $document->document_path ?? '') }}" placeholder="https://docs.google.com/..." required>
        @error('document_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>


<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.periodic-documents.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>

