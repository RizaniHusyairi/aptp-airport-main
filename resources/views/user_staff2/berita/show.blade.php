@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Berita')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/quill/quill.snow.css') }}">
@endsection

@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Edit Berita</h3>
                                <p class="text-subtitle text-muted">Ubah form untuk mengedit berita.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Berita', 'url' => route('berita.staffIndex')],
                                        ['label' => 'Edit Berita', 'active' => true]
                                    ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Form Edit Berita</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('berita.update', $news->slug) }}" enctype="multipart/form-data" id="form-tambah-berita" data-parsley-validate >
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Gambar Berita</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                accept="image/jpeg,image/png"
                                                data-parsley-fileextension="jpg,png"
                                                data-parsley-maxfilesize="2">
                                                @if ($news->image)
                                                
                                                <div class="mt-2">
                                                    <img id="gambar-preview" src="{{ asset('uploads/' . $news->image) }}" alt="Pratinjau Gambar" class="img-fluid" style="max-height: 200px;">
                                                </div>
                                                @else
                                                <p class="text-muted">Tidak ada gambar</p>
                                                @endif
                                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Judul Berita</label>
                                                <input type="text" class="form-control" id="title" name="title"
                                                    placeholder="Masukkan judul berita" value="{{ old('title', $news?->title) }}" data-parsley-required="true"
                                                    data-parsley-maxlength="255">
                                                
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="content" class="form-label">Isi Berita</label>
                                                <div id="editor" style="min-height: 300px;"></div>
                                                <input type="hidden" id="content" name="content" value="{{ old('content', $news?->content) }}" data-parsley-required="true">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Pembuatan</label>
                                                <input type="text" class="form-control" value="{{ $news->created_at->format('d M Y - H:i') }} WIB" data-parsley-maxlength="255" disabled>
                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('berita.staffIndex') }}" class="btn btn-secondary me-2">Batal</a>
                                        <button type="submit" class="btn btn-primary">Update Berita</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('assetsv2/compiled/js/staff-tambah-berita.js') }}"></script>
@endsection