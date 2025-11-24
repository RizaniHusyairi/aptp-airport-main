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
                <h3>Edit Role</h3>
                <p class="text-subtitle text-muted">Perbarui izin untuk role <strong>{{ $role->name }}</strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('root')],
                    ['label' => 'Roles', 'url' => route('roles.index')],
                    ['label' => 'Edit Role', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Edit Role: {{ $role->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="roleName" class="form-label">Nama Role</label>
                            
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="roleName" name="name" value="{{ $role->name }}"
                                   {{ $isCoreRole ? 'disabled' : '' }}>
                            
                            @if($isCoreRole)
                                <small class="form-text text-muted mt-1">Nama role inti sistem tidak dapat diubah.</small>
                            @endif

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
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                   id="permission{{ $permission->id }}"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   data-name="{{ $permission->permission_name }}"
                                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission{{ $permission->id }}">
                                                {{ $permission->permission_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- === AREA KATEGORI PROGRAM KERJA (Sama seperti Create) === --}}
                        <div id="workProgramOptions" class="mt-3 p-3 border rounded bg-light" style="display: none;">
                            <h6 class="text-primary"><i class="bi bi-gear"></i> Konfigurasi Program Kerja</h6>
                            <p class="text-muted small">Pilih kategori program kerja yang dapat diakses oleh role ini.</p>
                            
                            <div class="row">
                                @foreach($workCategories as $category)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_categories[]" 
                                               value="{{ $category }}" 
                                               id="cat_{{ Str::slug($category) }}"
                                               {{ in_array($category, $selectedCategories) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat_{{ Str::slug($category) }}">
                                            {{ $category }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <hr>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="can_verify" id="canVerify" value="1" {{ $canVerify ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="canVerify">Verifikator Program Kerja</label>
                                <small class="d-block text-muted">Jika diaktifkan, role ini berhak memverifikasi tugas dari kategori yang dipilih.</small>
                            </div>
                        </div>
                        {{-- === AKHIR AREA KATEGORI === --}}

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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

            function toggleWorkProgramOptions() {
                let isChecked = false;
                $('.permission-checkbox').each(function() {
                    // Pastikan string permission name sesuai persis dengan di database
                    if ($(this).data('name') === 'Manajemen Program Kerja' && $(this).is(':checked')) {
                        isChecked = true;
                    }
                });

                if (isChecked) {
                    $('#workProgramOptions').slideDown();
                } else {
                    $('#workProgramOptions').slideUp();
                }
            }

            // Jalankan saat checkbox berubah
            $('.permission-checkbox').on('change', toggleWorkProgramOptions);
            
            // Jalankan saat halaman dimuat untuk mengecek state awal
            toggleWorkProgramOptions();
        });
    </script>
@endsection