@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Alat <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $inventory->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="input_date" class="form-label">Tanggal Penginputan <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('input_date') is-invalid @enderror" id="input_date" name="input_date" value="{{ old('input_date', isset($inventory) ? $inventory->input_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('input_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="photo" class="form-label">Foto Alat @if(!isset($inventory))<span class="text-danger">*</span>@endif</label>
        <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/*">
        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if(isset($inventory) && $inventory->photo_path)
            <div class="mt-2">
                <small class="form-text text-muted">Foto saat ini:</small><br>
                <img src="{{ asset('storage/' . $inventory->photo_path) }}" alt="{{ $inventory->name }}" width="150" class="rounded mt-1">
            </div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.inventories.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
