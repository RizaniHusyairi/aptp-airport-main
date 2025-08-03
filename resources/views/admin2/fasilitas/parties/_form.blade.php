<!-- resources/views/admin2/facilities/_form.blade.php (Form Partial) -->
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Fasilitas</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $facility->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori</label>
        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="udara" @selected(old('category', $facility->category ?? '') == 'udara')>Sisi Udara</option>
            <option value="darat" @selected(old('category', $facility->category ?? '') == 'darat')>Sisi Darat</option>
            <option value="umum" @selected(old('category', $facility->category ?? '') == 'umum')>Umum</option>
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="details" class="form-label">Detail (Satu baris per poin)</label>
        <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="4" required>{{ old('details', isset($facility) ? implode("\n", $facility->details) : '') }}</textarea>
        @error('details')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="image" class="form-label">Gambar Fasilitas</label>
        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @isset($facility)
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
        @endisset
    </div>
</div>
<div class="d-flex justify-content-end">
    <a href="{{ route('admin.facilities.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
