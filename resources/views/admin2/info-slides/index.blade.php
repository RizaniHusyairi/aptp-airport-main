<!-- =================================================================== -->
<!--   1. DAFTAR SLIDE (resources/views/admin2/info-slides/index.blade.php) -->
<!-- =================================================================== -->
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Slide Informasi')
@section('content')
<div class="page-heading"><h3>Manajemen Slide Informasi</h3></div>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Slide</h5>
            <a href="{{ route('admin.info-slides.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Slide Baru</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Status Tampil</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($infoSlides as $slide)
                    <tr>
                        <td><img src="{{ asset('uploads/' . $slide->image_path) }}" alt="slide" width="150" class="rounded"></td>
                        <td>
                            {{-- ### PERBAIKAN: Mengubah status menjadi saklar interaktif ### --}}
                            <form action="{{ route('admin.info-slides.toggleVisibility', $slide) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_visible" value="1" id="is_visible_{{ $slide->id }}" onchange="this.form.submit()" @checked($slide->is_visible)>
                                    <label class="form-check-label" for="is_visible_{{ $slide->id }}"></label>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.info-slides.edit', $slide) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.info-slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus slide ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection




