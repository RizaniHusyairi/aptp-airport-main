@extends('layouts.master')

@section('title')
  Pengajuan Sewa
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Sewa @endslot
    @slot('title') Pengajuan Sewa @endslot
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
          <h4 class="card-title mb-4">Formulir Pengajuan Sewa</h4>
          <form method="POST" action="{{ route('sewa.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
              <label for="rental_name" class="form-label">Nama Sewa</label>
              <input type="text" class="form-control" id="rental_name" name="rental_name" value="{{ old('rental_name') }}" required>
              @error('rental_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="rental_type" class="form-label">Jenis Sewa</label>
              <select name="rental_type" id="rental_type" class="form-control" required onchange="toggleFields()">
                  <option value="">Pilih Jenis Sewa</option>
                  @foreach ($rentalTypes as $type => $config)
                      <option value="{{ $type }}">{{ $config['name'] }}</option>
                  @endforeach
              </select>
              @error('rental_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
          </div>
            
            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Sewa</label>
              <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3" id="area_field" style="display: none;">
              <label for="area" class="form-label">Luas (m²)</label>
              <input type="number" name="area" id="area" class="form-control" value="{{ old('area') }}">
          </div>
          <div class="mb-3" id="location_field" style="display: none;">
              <label for="location" class="form-label">Lokasi</label>
              <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
          </div>
          <div class="mb-3" id="quantity_field" style="display: none;">
              <label for="quantity" class="form-label">Jumlah</label>
              <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}">
          </div>
          <div class="mb-3" id="design_file_field" style="display: none;">
              <label for="design_file" class="form-label">File Desain (JPG/PNG)</label>
              <input type="file" name="design_file" id="design_file" class="form-control" accept="image/jpeg,image/png">
          </div>
          <div class="mb-3">
              <label for="documents" class="form-label">Dokumen yang Diperlukan (PDF)</label>
              <input type="file" name="documents" id="documents" class="form-control" accept=".pdf" required>
              @error('documents')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save"></i>Ajukan Sekarang</button>
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

    function toggleFields() {
            const rentalType = document.getElementById('rental_type').value;
            const areaField = document.getElementById('area_field');
            const locationField = document.getElementById('location_field');
            const quantityField = document.getElementById('quantity_field');
            const designFileField = document.getElementById('design_file_field');

            // Reset visibility
            areaField.style.display = 'none';
            locationField.style.display = 'none';
            quantityField.style.display = 'none';
            designFileField.style.display = 'none';

            // Show fields based on rental type
            if (rentalType === 'ruang' || rentalType === 'lahan') {
                areaField.style.display = 'block';
                locationField.style.display = 'block';
            } else if (rentalType === 'xray_kabin' || rentalType === 'xray_kargo' || rentalType === 'bus' || rentalType === 'workshop') {
                quantityField.style.display = 'block';
            } else if (rentalType === 'reklame') {
                designFileField.style.display = 'block';
            }
        }
  </script>
@endsection
