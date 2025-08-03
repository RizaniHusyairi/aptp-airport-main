@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Slider')
@section('styles_admin')

@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Slider</h3>
                <p class="text-muted subtitle">Unggah gambar untuk slider halaman utama.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Slider', 'url' => route('slider.index')],
                    ['label' => 'Tambah Slider', 'active' => true],
                ]" />
                
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            
            @endif
            
            <div class="card-header">
                <h6 class="card-title">Formulir Tambah Slider</h6>
            </div>
            <div class="card-body">
                <form id="form-post-tambah-slider" action="{{ route('slider.store') }}" enctype="multipart/form-data" method="POST" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="documents" class="form-label">Gambar Slider</label>
                        <input type="file" id="documents" name="documents" class="form-control" required>
                        @error('documents')
                            
                            <div id="error-gambar" class="invalid-feedback text-sm text-muted mt-1">{{ $message }}</div>
                        @enderror
                        <div class="preview-container">
                            <p class="text-muted text-sm mt-2">Pratinjau Gambar:</p>
                            <img id="image-preview" src="#" alt="Pratinjau Gambar" class="img-fluid w-75 rounded">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-primary" aria-label="Simpan slider">Simpan</button>
                        <a href="{{ route('slider.index') }}" class="btn btn-sm btn" aria-secondary-label="Batalkan penambahan slider">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts_admin')
    <script src="{{ asset('../assetsv2/compiled/js/staff-tambah-slider.js') }}"></script>

@endsection