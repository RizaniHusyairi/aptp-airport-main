<div class="row">
    <div class="col-12 mb-3">
        <label for="question" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question"
            value="{{ old('question', $faq->question ?? '') }}" maxlength="255" required>
        @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <label for="answer" class="form-label">Jawaban <span class="text-danger">*</span></label>
        <textarea class="form-control tinymce-editor @error('answer') is-invalid @enderror" id="answer" name="answer" rows="8">{{ old('answer', $faq->answer ?? '') }}</textarea>
        @error('answer')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <small class="text-muted">Boleh memuat tautan dan daftar. Arahkan ke halaman terkait bila relevan.</small>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category"
            list="faq-category-options" value="{{ old('category', $faq->category ?? '') }}" maxlength="100" required>
        <datalist id="faq-category-options">
            @foreach ($categories as $c)
                <option value="{{ $c }}"></option>
            @endforeach
        </datalist>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Pilih dari daftar atau tulis kategori baru.</small>
    </div>

    <div class="col-md-6 mb-3">
        <label for="service_id" class="form-label">Layanan Terkait</label>
        <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id">
            <option value="">— Tidak terkait layanan tertentu —</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected(old('service_id', $faq->service_id ?? '') == $service->id)>{{ $service->name }}</option>
            @endforeach
        </select>
        @error('service_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Bila diisi, pertanyaan ini juga tampil di halaman layanan tersebut.</small>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="sort_order" class="form-label">Urutan</label>
        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order"
            value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0">
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Nomor kecil tampil lebih dulu, dihitung di dalam kategorinya.</small>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label d-block">Tampil di Beranda</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1"
                @checked(old('is_featured', isset($faq) ? $faq->is_featured : false))>
            <label class="form-check-label" for="is_featured">Jadikan pertanyaan unggulan</label>
        </div>
        <small class="text-muted">Beranda menampilkan maksimal 6 pertanyaan unggulan.</small>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label d-block">Status Tampil</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                @checked(old('is_active', isset($faq) ? $faq->is_active : true))>
            <label class="form-check-label" for="is_active">Tampilkan di website</label>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.faqs.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
