@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Suku Cadang <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $sparePart->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $sparePart->stock ?? 0) }}" min="0" required>
        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="photo" class="form-label">Foto Suku Cadang</label>
        <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/*">
        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(isset($sparePart) && $sparePart->photo_path)
            <div class="mt-2">
                <small class="form-text text-muted">Foto saat ini:</small><br>
                <img src="{{ asset('storage/' . $sparePart->photo_path) }}" alt="{{ $sparePart->name }}" width="150" class="rounded mt-1">
            </div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.spare-parts.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
