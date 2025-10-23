@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="subject" class="form-label">Perihal Permintaan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="spare_part_id" class="form-label">Peralatan / Suku Cadang <span class="text-danger">*</span></label>
        <select class="form-select @error('spare_part_id') is-invalid @enderror" id="spare_part_id" name="spare_part_id" required>
            <option value="" selected disabled>Pilih Suku Cadang...</option>
            @foreach ($spareParts as $part)
                <option value="{{ $part->id }}" @selected(old('spare_part_id') == $part->id)>{{ $part->name }} (Stok: {{ $part->stock }})</option>
            @endforeach
        </select>
        @error('spare_part_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="follow_up_notes" class="form-label">Tindak Lanjut</label>
        <textarea class="form-control @error('follow_up_notes') is-invalid @enderror" id="follow_up_notes" name="follow_up_notes" rows="3">{{ old('follow_up_notes') }}</textarea>
        @error('follow_up_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12 mb-3">
        <label for="memo_link" class="form-label">Link Nota Dinas (Google Drive) <span class="text-danger">*</span></label>
        <input type="url" class="form-control @error('memo_link') is-invalid @enderror" id="memo_link" name="memo_link" value="{{ old('memo_link') }}" placeholder="https://..." required>
        @error('memo_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="form-text text-muted">Pastikan link dapat diakses oleh pihak terkait.</small>
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.spare-part-requests.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Kirim Permintaan</button>
</div>
