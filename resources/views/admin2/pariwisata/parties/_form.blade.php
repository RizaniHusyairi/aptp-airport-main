<div class="row">
    <div class="col-md-8 mb-3">
        <label for="name" class="form-label">Nama Destinasi</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tourism->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="category" class="form-label">Kategori</label>
        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="Alam" @selected(old('category', $tourism->category ?? '') == 'Alam')>Alam</option>
            <option value="Budaya" @selected(old('category', $tourism->category ?? '') == 'Budaya')>Budaya</option>
            <option value="Religi" @selected(old('category', $tourism->category ?? '') == 'Religi')>Religi</option>
            <option value="Kuliner" @selected(old('category', $tourism->category ?? '') == 'Kuliner')>Kuliner</option>
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="short_desc" class="form-label">Deskripsi Singkat (maks 255 karakter)</label>
        <textarea class="form-control @error('short_desc') is-invalid @enderror" id="short_desc" name="short_desc" rows="2" required>{{ old('short_desc', $tourism->short_desc ?? '') }}</textarea>
        @error('short_desc')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
     <div class="col-12 mb-3">
        <label for="description" class="form-label">Deskripsi Lengkap</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $tourism->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="address" class="form-label">Alamat Lengkap</label>
        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', $tourism->address ?? '') }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
     <div class="col-12 mb-3">
        <label for="gmaps_url" class="form-label">URL Google Maps (Embed)</label>
        <input type="url" class="form-control @error('gmaps_url') is-invalid @enderror" id="gmaps_url" name="gmaps_url" value="{{ old('gmaps_url', $tourism->gmaps_url ?? '') }}" placeholder="https://www.google.com/maps/embed?pb=...">
        @error('gmaps_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="cover_image" class="form-label">Gambar Sampul</label>
        <input class="form-control @error('cover_image') is-invalid @enderror" type="file" id="cover_image" name="cover_image">
        @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @isset($tourism) <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small> @endisset
    </div>
    <div class="col-md-6 mb-3">
        <label for="gallery" class="form-label">Galeri Gambar (bisa pilih lebih dari satu)</label>
        <input class="form-control @error('gallery.*') is-invalid @enderror" type="file" id="gallery" name="gallery[]" multiple>
        @error('gallery.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
         @isset($tourism) <small class="form-text text-muted">Unggah baru akan menggantikan semua gambar galeri lama.</small> @endisset
    </div>
    <div class="col-12 mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="published" @selected(old('status', $tourism->status ?? 'published') == 'published')>Published</option>
            <option value="draft" @selected(old('status', $tourism->status ?? '') == 'draft')>Draft</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="d-flex justify-content-end">
    <a href="{{ route('admin.tourism.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
