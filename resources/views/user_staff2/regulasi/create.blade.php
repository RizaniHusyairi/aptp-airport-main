@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Regulasi')
@section('styles_admin')

@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Surat</h3>
                <p class="text-subtitle text-muted">Isi Formulir untuk menambahkan surat</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                                ['label' => 'Menu', 'url' => route('profile')],
                                ['label' => 'Regulasi', 'url' => route('letters.staff.index')],
                                ['label' => 'Tambah Surat', 'active' => true],
                            ]" />        
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            
            <h5 class="card-title">Formulir Tambah surat</h5>
        </div>
        <div class="card-body">
            <form id="form-surat" method="POST" action="{{ route('letters.staff.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row">

                    <div class="col-md-6 mb-3">
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
                    
                    <div class="col-md-6 mb-3">
                        <label for="number" class="form-label">No Surat</label>
                        <input type="text" name="number" id="number" class="form-control @error('number') is-invalid @enderror"
                                value="{{ old('number') }}" required>
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="issue_date" class="form-label">Tanggal Terbit</label>
                        <input type="date" name="issue_date" id="issue_date" class="form-control @error('issue_date') is-invalid @enderror"
                                value="{{ old('issue_date') }}" required>
                        @error('issue_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="file" class="form-label">File Surat</label>
                        <input type="file" class="form-control" id="file" name="file" multiple accept=".pdf" required>
                        @error('file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        <small class="form-text text-muted">Unggah dokumen dalam format PDF.</small>
                        
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('letters.staff.index') }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')

@endsection
