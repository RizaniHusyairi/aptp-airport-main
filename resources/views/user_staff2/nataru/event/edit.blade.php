@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Event Posko')

@section('content')
<div class="page-heading">
    <h3>Edit Event Posko</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('staff.nataru-events.update', $nataruEvent->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Event</label>
                        <input type="text" name="name" class="form-control" value="{{ $nataruEvent->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $nataruEvent->is_active ? 'selected' : '' }}>Aktif (Buka Input)</option>
                            <option value="0" {{ !$nataruEvent->is_active ? 'selected' : '' }}>Selesai (Tutup Input)</option>
                        </select>
                    </div>
                    
                    {{-- INPUT PEMBANDING (EDIT) --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Event Pembanding</label>
                        <select name="compare_event_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($events as $ev)
                                <option value="{{ $ev->id }}" {{ $nataruEvent->compare_event_id == $ev->id ? 'selected' : '' }}>
                                    {{ $ev->name }} ({{ $ev->start_date->format('Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $nataruEvent->start_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $nataruEvent->end_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $nataruEvent->description }}</textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('staff.nataru-events.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection