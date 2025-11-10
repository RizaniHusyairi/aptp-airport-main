@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Logbook untuk ' . $inventory->name)

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Catatan Logbook</h3>
                <p class="text-subtitle text-muted">Untuk alat: <strong>{{ $inventory->name }}</strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                    ['label' => 'Inventaris', 'url' => route('staff.inventories.index')],
                    ['label' => 'Detail', 'url' => route('staff.inventories.show', $inventory->id)],
                    ['label' => 'Tambah Logbook', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Logbook</h5></div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('staff.inventories.storeLogbook', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="log_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('log_date') is-invalid @enderror" id="log_date" name="log_date" value="{{ old('log_date', now()->format('Y-m-d')) }}" required>
                        @error('log_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="schedule_time" class="form-label">Jadwal (Jam) <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('schedule_time') is-invalid @enderror" id="schedule_time" name="schedule_time" value="{{ old('schedule_time', now()->format('H:i')) }}" required>
                        @error('schedule_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Catatan / Tindakan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" required>{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="documentation" class="form-label">Dokumentasi (Foto)</label>
                        <input class="form-control @error('documentation.*') is-invalid @enderror" type="file" id="documentation" name="documentation[]" multiple accept="image/*">
                        <small class="text-muted">Bisa pilih lebih dari satu foto (Maks. 2MB per file).</small>
                        @error('documentation.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('staff.inventories.show', $inventory->id) }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Logbook</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

