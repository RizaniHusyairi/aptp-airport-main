@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Logbook untuk ' . $inventory->name)

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assets_landing/vendor/glightbox/css/glightbox.min.css') }}">
    <style>
        .thumb-container { display: flex; gap: 10px; flex-wrap: wrap; }
        .thumb-wrapper { 
            position: relative; 
            width: 80px; 
            height: 80px;
        }
        .thumb-wrapper img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            border-radius: 5px; 
        }
        /* Tombol Hapus Foto */
        .delete-photo-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 30% !important;
            background-color: var(--bs-danger);
            color: white;
            border: none;
            font-size: 10px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            z-index: 2;
        }

        .delete-photo-btn i {
            height: 1.6rem !important;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Catatan Logbook</h3>
                <p class="text-subtitle text-muted">Untuk alat: <strong>{{ $inventory->name }}</strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                    ['label' => 'Inventaris', 'url' => route('staff.inventories.index')],
                    ['label' => 'Detail', 'url' => route('staff.inventories.show', $inventory->id)],
                    ['label' => 'Edit Logbook', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Edit Logbook</h5></div>
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
            
            <form action="{{ route('staff.inventories.updateLogbook', ['inventory' => $inventory->id, 'logbook' => $logbook->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- Kontainer tersembunyi untuk menyimpan path foto yang akan dihapus --}}
                <div id="deleted-photos-container"></div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="log_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('log_date') is-invalid @enderror" id="log_date" name="log_date" value="{{ old('log_date', $logbook->log_date->format('Y-m-d')) }}" required>
                        @error('log_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="schedule_time" class="form-label">Jadwal (Jam) <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('schedule_time') is-invalid @enderror" id="schedule_time" name="schedule_time" value="{{ old('schedule_time', \Carbon\Carbon::parse($logbook->schedule_time)->format('H:i')) }}" required>
                        @error('schedule_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Catatan / Tindakan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" required>{{ old('notes', $logbook->notes) }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="documentation" class="form-label">Tambah Dokumentasi (Foto)</label>
                        <input class="form-control @error('documentation.*') is-invalid @enderror" type="file" id="documentation" name="documentation[]" multiple accept="image/*">
                        <small class="text-muted">Unggah file baru untuk ditambahkan ke logbook. Maks. 2MB per file.</small>
                        @error('documentation.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    @if($logbook->documentation && count($logbook->documentation) > 0)
                    <div class="col-12 mb-3">
                        <label class="form-label">Dokumentasi Saat Ini</label>
                        <div class="thumb-container p-3 border rounded">
                            @foreach ($logbook->documentation as $photoPath)
                                <div class="thumb-wrapper" id="thumb-{{ $loop->index }}">
                                    <a href="{{ asset('uploads/' . $photoPath) }}" class="glightbox">
                                        <img src="{{ asset('uploads/' . $photoPath) }}" alt="Dokumentasi">
                                    </a>
                                    {{-- Tombol Hapus per Item --}}
                                    <button type="button" class="delete-photo-btn" data-path="{{ $photoPath }}" data-thumb-id="thumb-{{ $loop->index }}" title="Hapus foto ini">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                         <small class="text-muted">Klik tombol (x) untuk menghapus foto yang ada.</small>
                    </div>
                    @endif
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('staff.inventories.show', $inventory->id) }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assets_landing/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi GLightbox
            const lightbox = GLightbox({ selector: '.glightbox' });

            // Logika untuk Hapus Foto
            const deletedPhotosContainer = document.getElementById('deleted-photos-container');
            document.querySelectorAll('.delete-photo-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const photoPath = this.dataset.path;
                    const thumbId = this.dataset.thumbId;
                    
                    // 1. Tambahkan path ke hidden input untuk dikirim ke server
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'deleted_photos[]';
                    hiddenInput.value = photoPath;
                    deletedPhotosContainer.appendChild(hiddenInput);
                    
                    // 2. Sembunyikan thumbnail dari tampilan
                    const thumbWrapper = document.getElementById(thumbId);
                    if (thumbWrapper) {
                        thumbWrapper.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection

