@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Aktivitas Lalu lintas')
@section('styles_admin')

@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Aktivitas Lalu Lintas</h3>
                <p class="text-subtitle text-muted">Formulir untuk Mengubah Aktivitas Lalu Lintas</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                                ['label' => 'Menu', 'url' => route('profile')],
                                ['label' => 'Lalu lintas', 'url' => route('laluLintas.staffIndex')],
                                ['label' => 'Edit Lalu lintas', 'active' => true],
                            ]" />        
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            
            <h5 class="card-title">Formulir Edit Lalu Lintas</h5>
        </div>
        <div class="card-body">
            <form id="form-surat" method="POST" action="{{ route('laluLintas.update', $traffic) }}" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="date">Periode</label>
                        <input type="month" name="date" class="form-control" value="{{ old('date', $traffic->date ? \Carbon\Carbon::parse($traffic->date)->format('Y-m') : '') }}" required>
                        @if ($errors->has('date'))
                            <div class="text-danger">{{ $errors->first('date') }}</div>
                        @endif

                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type">Angkutan / Jenis</label>
                        <select name="type" id="type" class="form-control">
                        <option value="Pesawat" {{ old('type', $traffic->type) == 'Pesawat' ? 'selected' : '' }}>Pesawat</option>
                        <option value="Penumpang" {{ old('type', $traffic->type) == 'Penumpang' ? 'selected' : '' }}>Penumpang</option>
                        <option value="Penumpang Transit" {{ old('type', $traffic->type) == 'Penumpang Transit' ? 'selected' : '' }}>Penumpang Transit</option>
                        <option value="Bagasi" {{ old('type', $traffic->type) == 'Bagasi' ? 'selected' : '' }}>Bagasi</option>
                        <option value="Kargo" {{ old('type' , $traffic->type) == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                        <option value="Pos" {{ old('type', $traffic->type) == 'Pos' ? 'selected' : '' }}>Pos</option>
                        </select>
                        @if ($errors->has('type'))
                            <div class="text-danger">{{ $errors->first('type') }}</div>
                        @endif

                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="datang" class="form-label">Datang</label>
                        <input type="number" class="form-control" id="datang" name="arrival" value="{{ old('arrival', $traffic->arrival) }}" required>
                        @if ($errors->has('arrival'))
                            <div class="text-danger">{{ $errors->first('arrival') }}</div>
                        @endif
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="berangkat" class="form-label">Berangkat</label>
                        <input type="number" class="form-control" id="berangkat" name="departure" value="{{ old('departure', $traffic->departure) }}" required>
                        @if ($errors->has('departure'))
                            <div class="text-danger">{{ $errors->first('departure') }}</div>
                        @endif
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('laluLintas.staffIndex') }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')

@endsection
