@extends('layouts.master')

@section('title')
    Regulasi
@endsection


@push('styles')
    <style>
        .form-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .form-label {
            font-weight: 600;
        }
        .is-invalid + .invalid-feedback {
            display: block;
        }
    </style>
@endpush


@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Regulasi @endslot
    @slot('title') Tambah Surat @endslot
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

          <h4 class="card-title mb-4">Formulir Tambah Surat</h4>

          <form method="POST" action="{{ route('letters.staff.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf


            <div class="mb-3">
                <label for="type" class="form-label">Jenis Surat</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="">Pilih Jenis</option>
                    <option value="edaran" {{ old('type') == 'edaran' ? 'selected' : '' }}>Surat Edaran</option>
                    <option value="utusan" {{ old('type') == 'utusan' ? 'selected' : '' }}>Surat Utusan</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="number" class="form-label">No Surat</label>
                <input type="text" name="number" id="number" class="form-control @error('number') is-invalid @enderror"
                        value="{{ old('number') }}" required>
                @error('number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="issue_date" class="form-label">Tanggal Terbit</label>
                <input type="date" name="issue_date" id="issue_date" class="form-control @error('issue_date') is-invalid @enderror"
                        value="{{ old('issue_date') }}" required>
                @error('issue_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File PDF</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="mb-1 btn btn-primary waves-effect waves-light"> <i class='bx bx-save'></i> Simpan</button>
              <a href="{{ route('letters.staff.index') }}" class="btn btn-secondary">Batal</a>
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
