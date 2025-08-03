@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Berita')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Manajemen Berita</h3>
                <p class="text-subtitle text-muted">Kelola daftar berita untuk staff.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('profile')],
                        ['label' => 'Berita', 'active' => true]
                    ]" />
                
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Berita</h5>
                <a href="{{ route('berita.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Berita</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-berita">
                        <thead>
                            <tr>
                                <th>Judul Berita</th>
                                <th>Headline</th>
                                <th>Publikasi</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @staff
                            <tbody>
                                @forelse ($news as $index => $new)
                                <tr>
                                    <td>{{ $new->title }}</td>
                                    <td>
                                    <form action="{{ route('berita.toggleHeadline', $new->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_headline" value="0">
                                        <div class="form-check form-switch">
                                        <input 
                                            type="checkbox" 
                                            value="1"
                                            class="form-check-input" 
                                            name="is_headline" 
                                            {{ $new -> is_headline ? 'checked' : '' }} 
                                            onchange="this.form.submit()"
                                        >
                                        </div>
                                    </form>
                                    </td>
                                    <td>
                                    <form action="{{ route('berita.togglePublish', $new->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_published" value="0">
                                        <div class="form-check form-switch">
                                        <input 
                                            type="checkbox" 
                                            value="1"
                                            class="form-check-input" 
                                            name="is_published" 
                                            {{ $new -> is_published ? 'checked' : '' }} 
                                            onchange="this.form.submit()"
                                        >
                                        </div>
                                    </form>
                                    </td>
                                    <td class="w-25">{{ $new->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('berita.show', $new->slug) }}" class="btn btn-sm btn-warning me-1 text-white btn-tooltip" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <form class="col" action="{{ route('berita.destroy', $new->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm text-white btn-tooltip" data-bs-toggle="tooltip" title="Hapus Berita"><i class="bi bi-trash"></i></button>
                                            </form>
                                    </td>
                                </tr>
                                @empty
                                
                                @endforelse
                            </tbody>
                            @endstaff
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>    
    <script src="{{ asset('assetsv2/compiled/js/staff-berita.js') }}"></script>
@endsection
