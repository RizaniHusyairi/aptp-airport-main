@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengajuan Extend / Advance Hour')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengajuan Extend / Advance Hour</h3>
                <p class="text-subtitle text-muted">Formulir permohonan Slot Clearance di luar jam operasi bandara.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Extend Advance', 'url' => route('extend-advance.index')],
                    ['label' => 'Pengajuan Baru', 'active' => true],
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Surat Pernyataan dan Permohonan</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan pada input Anda:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('extend-advance.store') }}" novalidate>
                @csrf

                {{-- Bagian I: Pesawat Udara --}}
                <h6 class="mt-4">I. Pesawat Udara</h6>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="operator" class="form-label">Operator (Pemilik/Penyewa) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('operator') is-invalid @enderror" id="operator" name="operator" value="{{ old('operator') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="aircraft_type" class="form-label">Tipe Pesawat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('aircraft_type') is-invalid @enderror" id="aircraft_type" name="aircraft_type" value="{{ old('aircraft_type') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="registration_and_flight_number" class="form-label">Tanda Pendaftaran / No. Penerbangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('registration_and_flight_number') is-invalid @enderror" id="registration_and_flight_number" name="registration_and_flight_number" value="{{ old('registration_and_flight_number') }}" required>
                    </div>
                </div>

                {{-- Bagian II: Penerbangan --}}
                <h6 class="mt-4">II. Penerbangan</h6>
                <hr>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="flight_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('flight_date') is-invalid @enderror" id="flight_date" name="flight_date" value="{{ old('flight_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="eobt" class="form-label">Jam Keberangkatan (EOBT) <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('eobt') is-invalid @enderror" id="eobt" name="eobt" value="{{ old('eobt') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="aobt" class="form-label">Jam Kedatangan (AOBT) <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('aobt') is-invalid @enderror" id="aobt" name="aobt" value="{{ old('aobt') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="route" class="form-label">Rute <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('route') is-invalid @enderror" id="route" name="route" value="{{ old('route') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="take_off_alternate" class="form-label">Alternate Take Off</label>
                        <input type="text" class="form-control @error('take_off_alternate') is-invalid @enderror" id="take_off_alternate" name="take_off_alternate" value="{{ old('take_off_alternate') }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="purpose_of_flight" class="form-label">Keperluan Terbang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('purpose_of_flight') is-invalid @enderror" id="purpose_of_flight" name="purpose_of_flight" value="{{ old('purpose_of_flight') }}" required>
                    </div>
                </div>

                {{-- Bagian III: Pernyataan --}}
                <h6 class="mt-4">III. Pernyataan</h6>
                <hr>
                <div class="alert alert-light-warning" role="alert">
                    <h4 class="alert-heading">Pernyataan Tanggung Jawab</h4>
                    {{-- Menampilkan teks pernyataan dinamis dari controller --}}
                    <p>{{ $statementText }}</p>
                </div>
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="pic_name" class="form-label">Nama Pilot in Command (PIC) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pic_name') is-invalid @enderror" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" required>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-12 d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary me-1 mb-1">Kirim Pengajuan</button>
                    <a href="{{ route('extend-advance.index') }}" class="btn btn-light-secondary me-1 mb-1">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
