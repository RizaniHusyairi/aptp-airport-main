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
                    
                    {{-- INPUT DATA PEMBANDING BARU --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Pembanding (Tahun Lalu)</label>
                        <select name="compare_event_id" class="form-select">
                            <option value="">-- Pilih Event --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }} ({{ $event->start_date->format('Y') }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih event tahun lalu untuk menampilkan grafik perbandingan otomatis.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                    {{-- KONFIGURASI HARI H (PUNCAK ARUS) --}}
                    <div class="col-12 mt-2 border-top pt-3">
                        <h6 class="text-muted"><i class="bi bi-calendar-event"></i> Konfigurasi Hari Puncak & Range Grafik TV</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Hari H (Puncak) <span class="text-danger">*</span></label>
                        <input type="date" name="peak_date" class="form-control" required>
                        <small class="text-muted text-xs">Sistem akan menyesuaikan grafik dari <b>Tanggal Mulai</b> hingga <b>Tanggal Selesai</b> dengan titik nol pada Hari H.</small>
                    </div>

                    <div class="col-12 mb-3 mt-2 border-top pt-3">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
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