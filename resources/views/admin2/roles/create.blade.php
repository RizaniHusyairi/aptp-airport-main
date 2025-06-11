@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">

@endsection
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Role</h3>
                <p class="text-subtitle text-muted">Tambahkan role baru untuk sistem</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                        ['label' => 'Menu', 'url' => route('root')],
                        ['label' => 'Roles', 'url' => route('roles.index')],
                        ['label' => 'Tambah Role', 'active' => true]
                    ]" />
                
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Role</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
            @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="roleName" class="form-label">Nama Role</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="roleName" name="name" value="{{ old('name') }}"
                                placeholder="Contoh: Admin">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-4">
                            <label>Permissions</label>
                            @error('permissions')
                                <div class="text-danger mb-2">{{ $message }}</div>
                            @enderror
                            <div class="row mt-2">
                                @foreach ($permissions as $permission)
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="permission{{ $permission->id }}"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission{{ $permission->id }}">
                                                {{ $permission->permission_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="form-group mt-4 d-flex justify-content-end">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary" id="submitButton">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Simpan Role
                            </button>
                        </div>
                        {{-- <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Role</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#createRoleForm').on('submit', function (e) {
                const roleName = $('#roleName').val().trim();
                const permissionsChecked = $('input[name="permissions[]"]:checked').length;

                if (!roleName) {
                    e.preventDefault();
                    $('#roleName').addClass('is-invalid');
                    if (!$('#roleName').next('.invalid-feedback').length) {
                        $('#roleName').after('<div class="invalid-feedback">Nama role wajib diisi.</div>');
                    }
                }

                if (permissionsChecked === 0) {
                    e.preventDefault();
                    if (!$('.form-group .text-danger').length) {
                        $('.form-group .row.mt-2').before('<div class="text-danger mb-2">Pilih setidaknya satu izin.</div>');
                    }
                }

                if (roleName && permissionsChecked > 0) {
                    $('#submitButton').find('.spinner-border').removeClass('d-none');
                    $('#submitButton').prop('disabled', true);
                }
            });

            $('#roleName').on('input', function () {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });

            $('input[name="permissions[]"]').on('change', function () {
                $('.form-group .text-danger').remove();
            });
        });
    </script>
@endsection