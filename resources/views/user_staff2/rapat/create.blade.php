@extends('layouts-V2.master-layouts-v2')
@section('title', 'Buat Rapat Baru')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Agenda Rapat Baru</h3>
                <p class="text-subtitle text-muted">Siapkan ruang absensi digital untuk kegiatan rapat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Rapat', 'url' => route('staff.meetings.index')],
                    ['label' => 'Buat Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Rapat</h5></div>
        <div class="card-body">
            <form action="{{ route('staff.meetings.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="title" class="form-label">Judul / Agenda Rapat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Rapat Koordinasi Bulanan" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', '09:00') }}" required>
                        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Lokasi Ruangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Ruang Rapat Lantai 2" required>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="organizer" class="form-label">Pimpinan Rapat / Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('organizer') is-invalid @enderror" id="organizer" name="organizer" value="{{ old('organizer') }}" placeholder="Contoh: Kepala Bandara" required>
                        @error('organizer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('staff.meetings.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Buat & Dapatkan Link</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection