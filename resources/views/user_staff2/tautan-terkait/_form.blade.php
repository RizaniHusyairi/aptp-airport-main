<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Nama Tautan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $link->name ?? '') }}" maxlength="100" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="group" class="form-label">Kelompok <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('group') is-invalid @enderror" id="group" name="group"
            list="group-options" value="{{ old('group', $link->group ?? '') }}" maxlength="100" required>
        <datalist id="group-options">
            @foreach ($groups as $g)
                <option value="{{ $g }}"></option>
            @endforeach
        </datalist>
        @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Pilih dari daftar atau tulis kelompok baru.</small>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
        <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url"
            value="{{ old('url', $link->url ?? '') }}" placeholder="https://..." maxlength="500" required>
        @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="description" class="form-label">Deskripsi Singkat</label>
        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description"
            value="{{ old('description', $link->description ?? '') }}" maxlength="255">
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Tampil di bawah nama pada kartu di halaman publik.</small>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="icon" class="form-label">Ikon</label>
        <div class="input-group">
            <span class="input-group-text"><i id="icon-preview" class="bi {{ old('icon', $link->icon ?? 'bi-box-arrow-up-right') }}"></i></span>
            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon"
                value="{{ old('icon', $link->icon ?? '') }}" placeholder="bi-megaphone-fill" maxlength="50">
            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <small class="text-muted">
            Kelas Bootstrap Icons. Lihat daftarnya di
            <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">icons.getbootstrap.com</a>.
            Dikosongkan berarti memakai ikon bawaan.
        </small>
    </div>

    <div class="col-md-3 mb-3">
        <label for="sort_order" class="form-label">Urutan</label>
        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order"
            value="{{ old('sort_order', $link->sort_order ?? 0) }}" min="0">
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Nomor kecil tampil lebih dulu. <strong>Beri nomor berurutan per kelompok</strong> (jangan diselang-seling antar kelompok), karena urutan kelompok mengikuti nomor terkecil di dalamnya.</small>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label d-block">Status Tampil</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                @checked(old('is_active', isset($link) ? $link->is_active : true))>
            <label class="form-check-label" for="is_active">Tampilkan di website</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="logo" class="form-label">Logo (Opsional)</label>
        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo"
            accept="image/png,image/jpeg,image/webp,image/svg+xml">
        <div class="form-text">PNG/JPG/WEBP/SVG, maksimal 1 MB. Bila diisi, logo menggantikan ikon pada kartu.</div>
        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror

        @if (isset($link) && $link->logo_path)
            <div class="mt-2 d-flex align-items-center gap-3">
                <img src="{{ $link->logo_url }}" alt="Logo {{ $link->name }}" style="max-height: 48px; width: auto;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                    <label class="form-check-label" for="remove_logo">Hapus logo, kembali memakai ikon</label>
                </div>
            </div>
            <div class="form-text">Biarkan kolom unggah kosong jika tidak ingin mengganti logo.</div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.external-links.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('icon');
        const preview = document.getElementById('icon-preview');

        // Pratinjau ikon mengikuti apa yang diketik admin.
        input.addEventListener('input', function () {
            const cls = input.value.trim() || 'bi-box-arrow-up-right';
            preview.className = 'bi ' + cls;
        });
    });
</script>
