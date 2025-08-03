@extends('layouts-V2.master-layouts-v2')
@section('title', 'Manajemen Slider')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
@endsection
@section('content')
<div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Manajemen Slider</h3>
                                <p class="text-subtitle text-muted">Kelola slider untuk halaman utama.</p>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <x-breadcrumb2 :items="[
                                        ['label' => 'Menu', 'url' => route('profile')],
                                        ['label' => 'Slider', 'active' => true]
                                    ]" />
                                
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Daftar Slider</h5>
                                <a href="{{ route('slider.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Slider
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-slider">
                                        <thead>
                                            <tr>
                                                <th>Nama File Slider</th>
                                                <th>Gambar</th>
                                                <th>Tampilkan</th>
                                                <th>Dibuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($sliders as $index => $slider)
                                            
                                            <tr data-id="{{ $slider->id }}">
                                                <td>{{ $slider->documents ? preg_replace('/^\d+_/', '', basename($slider->documents)) : '-' }}</td>
                                                <td><img src="{{ asset('uploads/' . $slider->documents) }}" alt="preview gambar" class="img-fluid w-75 rounded"></td>
                                                <td>
                                                    <form action="{{ route('slider.toggleVisibilityHome', $slider->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-check form-switch">

                                                        <input class="form-check-input toggle-display" value="1" type="checkbox" id="display-1" name="is_visible_home" onchange="this.form.submit()" {{ $slider->is_visible_home ? 'checked' : '' }} >
                                                        <label class="form-check-label" for="display-1"></label>
                                                    </div>
                                                    </form>
                                                </td>
                                                <td>{{ $slider->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-slider" data-id="{{ $slider->id }}" data-bs-toggle="tooltip" title="Hapus" aria-label="Hapus slider"><i class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                            @empty
                                            
                                            @endforelse
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                @endsection
                @section('scripts_admin')
                    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
                    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script> 
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    <script src="{{ asset('assetsv2/compiled/js/staff-slider.js') }}"></script>
                @endsection