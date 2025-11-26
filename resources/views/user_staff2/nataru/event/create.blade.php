@extends('layouts-V2.master-layouts-v2')
@section('title', 'Buat Event Posko')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Event Posko Baru</h3>
                <p class="text-subtitle text-muted">Siapkan periode posko baru untuk pengumpulan data.</p>
            </div>
             <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                        ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                        ['label' => 'Event Posko', 'url' => route('staff.nataru-events.index')],
                        ['label' => 'Buat Baru', 'active' => true]
                    ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('staff.nataru-events.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Posko Nataru 2024/2025" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <input type="text" name="description" class="form-control" placeholder="Keterangan tambahan...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('staff.nataru-events.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection