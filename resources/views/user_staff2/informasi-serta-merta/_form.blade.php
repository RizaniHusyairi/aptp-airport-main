{{-- resources/views/user_staff2/informasi-serta-merta/_form.blade.php --}}
<div class="row">
    <div class="col-12 mb-3">
        <label for="uraian" class="form-label">Uraian <span class="text-danger">*</span></label>
        <textarea class="form-control @error('uraian') is-invalid @enderror" id="uraian" name="uraian" rows="3" required>{{ old('uraian', $information->uraian ?? '') }}</textarea>
        @error('uraian')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mb-3">
        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="5" required>{{ old('keterangan', $information->keterangan ?? '') }}</textarea>
        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8 mb-3">
        <label for="link_url" class="form-label">URL Tautan <span class="text-danger">*</span></label>
        <input type="url" class="form-control @error('link_url') is-invalid @enderror" id="link_url" name="link_url" value="{{ old('link_url', $information->link_url ?? '') }}" placeholder="https://contoh.com/info-penting" required>
        @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="link_text" class="form-label">Teks Tombol <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('link_text') is-invalid @enderror" id="link_text" name="link_text" value="{{ old('link_text', $information->link_text ?? 'Lihat Detail') }}" required>
        @error('link_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.immediate-informations.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>