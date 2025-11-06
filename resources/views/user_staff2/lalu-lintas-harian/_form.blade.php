@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" 
               value="{{ old('date', isset($traffic) ? $traffic->date->format('Y-m-d') : now()->format('Y-m-d')) }}" 
               required>
        @error('date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>

{{-- Data Pesawat --}}
<div class="row align-items-center mb-3">
    <div class="col-md-3">
        <h6 class="mb-0">1. Pesawat</h6>
    </div>
    <div class="col-md-4">
        <label for="aircraft_arrival" class="form-label">Kedatangan</label>
        <input type="number" class="form-control @error('aircraft_arrival') is-invalid @enderror" id="aircraft_arrival" name="aircraft_arrival" 
               value="{{ old('aircraft_arrival', $traffic->aircraft_arrival ?? 0) }}" min="0" required>
        @error('aircraft_arrival')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="aircraft_departure" class="form-label">Keberangkatan</label>
        <input type="number" class="form-control @error('aircraft_departure') is-invalid @enderror" id="aircraft_departure" name="aircraft_departure" 
               value="{{ old('aircraft_departure', $traffic->aircraft_departure ?? 0) }}" min="0" required>
        @error('aircraft_departure')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Data Penumpang --}}
<div class="row align-items-center mb-3">
    <div class="col-md-3">
        <h6 class="mb-0">2. Penumpang</h6>
    </div>
    <div class="col-md-4">
        <label for="passenger_arrival" class="form-label">Kedatangan</label>
        <input type="number" class="form-control @error('passenger_arrival') is-invalid @enderror" id="passenger_arrival" name="passenger_arrival" 
               value="{{ old('passenger_arrival', $traffic->passenger_arrival ?? 0) }}" min="0" required>
        @error('passenger_arrival')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="passenger_departure" class="form-label">Keberangkatan</label>
        <input type="number" class="form-control @error('passenger_departure') is-invalid @enderror" id="passenger_departure" name="passenger_departure" 
               value="{{ old('passenger_departure', $traffic->passenger_departure ?? 0) }}" min="0" required>
        @error('passenger_departure')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Data Bagasi --}}
<div class="row align-items-center mb-3">
    <div class="col-md-3">
        <h6 class="mb-0">3. Bagasi (Kg)</h6>
    </div>
    <div class="col-md-4">
        <label for="baggage_arrival" class="form-label">Kedatangan</label>
        <input type="number" class="form-control @error('baggage_arrival') is-invalid @enderror" id="baggage_arrival" name="baggage_arrival" 
               value="{{ old('baggage_arrival', $traffic->baggage_arrival ?? 0) }}" min="0" required>
        @error('baggage_arrival')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="baggage_departure" class="form-label">Keberangkatan</label>
        <input type="number" class="form-control @error('baggage_departure') is-invalid @enderror" id="baggage_departure" name="baggage_departure" 
               value="{{ old('baggage_departure', $traffic->baggage_departure ?? 0) }}" min="0" required>
        @error('baggage_departure')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Data Kargo --}}
<div class="row align-items-center mb-3">
    <div class="col-md-3">
        <h6 class="mb-0">4. Kargo (Kg)</h6>
    </div>
    <div class="col-md-4">
        <label for="cargo_arrival" class="form-label">Kedatangan</label>
        <input type="number" class="form-control @error('cargo_arrival') is-invalid @enderror" id="cargo_arrival" name="cargo_arrival" 
               value="{{ old('cargo_arrival', $traffic->cargo_arrival ?? 0) }}" min="0" required>
        @error('cargo_arrival')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="cargo_departure" class="form-label">Keberangkatan</label>
        <input type="number" class="form-control @error('cargo_departure') is-invalid @enderror" id="cargo_departure" name="cargo_departure" 
               value="{{ old('cargo_departure', $traffic->cargo_departure ?? 0) }}" min="0" required>
        @error('cargo_departure')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('staff.air-traffic.index') }}" class="btn btn-light-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan Data</button>
</div>
