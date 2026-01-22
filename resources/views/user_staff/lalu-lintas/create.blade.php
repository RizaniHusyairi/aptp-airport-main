@extends('layouts.master')

@section('title')
Tambah Aktivitas Lalu Lintas Angkutan Udara
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Aktivitas Bandara @endslot
    @slot('title')Tambah Aktivitas Lalu Lintas Angkutan Udara @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">

          <h4 class="card-title mb-4">Tambah Aktivitas Lalu Lintas Angkutan Udara</h4>

          <form method="POST" action="{{ route('laluLintas.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="form-group mb-3">
              <label for="date">Periode</label>
              <input type="month" name="date" class="form-control" value="{{ old('date') }}" required>
              @if ($errors->has('date'))
                <div class="text-danger">{{ $errors->first('date') }}</div>
              @endif

            </div>

            <div class="form-group mb-3">
            <label for="type">Angkutan / Jenis</label>
            <select name="type" id="type" class="form-control">
              <option value="Pesawat" {{ old('type') == 'Pesawat' ? 'selected' : '' }}>Pesawat</option>
              <option value="Penumpang" {{ old('type') == 'Penumpang' ? 'selected' : '' }}>Penumpang</option>
              <option value="Penumpang Transit" {{ old('type') == 'Penumpang Transit' ? 'selected' : '' }}>Penumpang Transit</option>
              <option value="Bagasi" {{ old('type') == 'Bagasi' ? 'selected' : '' }}>Bagasi</option>
              <option value="Kargo" {{ old('type') == 'Kargo' ? 'selected' : '' }}>Kargo</option>
              <option value="Pos" {{ old('type') == 'Pos' ? 'selected' : '' }}>Pos</option>
            </select>
              @if ($errors->has('type'))
                <div class="text-danger">{{ $errors->first('type') }}</div>
              @endif

            </div>

            <div class="mb-3">
              <label for="datang" class="form-label">Datang</label>
              <input type="number" class="form-control" id="datang" name="arrival" value="{{ old('arrival') }}" required>
              @if ($errors->has('arrival'))
                <div class="text-danger">{{ $errors->first('arrival') }}</div>
              @endif
            </div>
            
            <div class="mb-3">
              <label for="berangkat" class="form-label">Berangkat</label>
              <input type="number" class="form-control" id="berangkat" name="departure" value="{{ old('departure') }}" required>
              @if ($errors->has('departure'))
                <div class="text-danger">{{ $errors->first('departure') }}</div>
              @endif
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary waves-effect waves-light">Tambah Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    // Bootstrap form validation
    document.querySelector('form').addEventListener('submit', function (e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      this.classList.add('was-validated');
    });
  </script>
@endsection
