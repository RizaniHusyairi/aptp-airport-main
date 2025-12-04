@extends('layouts-V2.master-layouts-v2')
@section('title', 'Dashboard')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    {{-- 1. Tambahkan CSS Choices.js --}}
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/choices.js/public/assets/styles/choices.css') }}">
    <style>
        .choices__inner {
            background-color: #fff;
            border-radius: .25rem;
            min-height: 44px;
        }
        .choices__list--multiple .choices__item {
            background-color: #435ebe;
            border-color: #435ebe;
        }
    </style>
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
            <form action="{{ route('roles.update', $role->id) }}" method="POST" id="editRoleForm">
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

                        {{-- === AREA KATEGORI (Unified untuk Proker & Inventaris) === --}}
                        <div id="workProgramOptions" class="mt-4 p-4 border rounded" style="display: none; border-left: 5px solid #ffc107 !important;">
                            <h6 class="text-primary d-flex align-items-center">
                                <i class="bi bi-diagram-3-fill me-2"></i> Konfigurasi Kategori Tugas
                            </h6>
                            <p class="small mb-3">
                                Atur cakupan kerja (Program Kerja / Inventaris) untuk role <strong>{{ $role->name }}</strong>:
                            </p>
                            
                            {{-- Dropdown Multi-select --}}
                            <div class="form-group mb-3">
                                <label for="work_categories" class="form-label fw-bold">Pilih Kategori</label>
                                <select class="choices form-select" name="work_categories[]" id="work_categories" multiple>
                                    @foreach($workCategories as $category)
                                        <option value="{{ $category }}" 
                                            {{ in_array($category, $selectedCategories) ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="category-error" class="text-danger small mt-1" style="display:none;">Harap pilih setidaknya satu kategori.</div>
                                <small class="text-muted">
                                    * Klik pada kotak untuk memilih/mencari kategori.
                                </small>
                            </div>

                            {{-- Wrapper Opsi Verifikator --}}
                            <div id="verifierOptionContainer" style="display: none;">
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="assign_verifier_permission" id="assignVerifierPermission" value="1" 
                                        {{ $canVerify ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="assignVerifierPermission">
                                        Izinkan Verifikasi Program Kerja?
                                    </label>
                                    <small class="d-block mt-1">
                                        Aktifkan jika role ini bertugas memverifikasi pekerjaan staf lain (Khusus Program Kerja).
                                    </small>
                                </div>
                            </div>
                        </div>
                        {{-- === AKHIR AREA KATEGORI === --}}

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">Kembali</a>
                        <button type="submit" class="btn btn-primary" id="submitButton">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>

    <script>
        $(document).ready(function () {
            let choicesInstance;
            const element = document.querySelector('#work_categories');
            if (element) {
                choicesInstance = new Choices(element, {
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Pilih Kategori...',
                    searchPlaceholderValue: 'Cari kategori...',
                    shouldSort: false,
                });
            }

            // Logika Tampilkan/Sembunyikan
            function toggleOptions() {
                let isProkerChecked = false;
                let isInventarisChecked = false;

                $('.permission-checkbox').each(function() {
                    const name = $(this).data('name');
                    if (name === 'Manajemen Program Kerja' && $(this).is(':checked')) {
                        isProkerChecked = true;
                    }
                    if (name === 'Manajemen Inventaris' && $(this).is(':checked')) {
                        isInventarisChecked = true;
                    }
                });

                const optionsContainer = $('#workProgramOptions');
                const verifierContainer = $('#verifierOptionContainer');

                if (isProkerChecked || isInventarisChecked) {
                    optionsContainer.slideDown();
                } else {
                    optionsContainer.slideUp();
                }

                // Verifikasi hanya relevan untuk Program Kerja
                if (isProkerChecked) {
                    verifierContainer.slideDown();
                } else {
                    verifierContainer.slideUp();
                    // Opsional: uncheck jika disembunyikan agar tidak tersimpan tidak sengaja
                    // $('#assignVerifierPermission').prop('checked', false); 
                }
            }

            $(document).on('change', '.permission-checkbox', toggleOptions);
            toggleOptions();

            // Validasi Manual
            $('#editRoleForm').on('submit', function(e) {
                let isValid = true;
                $('#category-error').hide();
                
                let needCategory = false;
                $('.permission-checkbox').each(function() {
                    const name = $(this).data('name');
                    if ((name === 'Manajemen Program Kerja' || name === 'Manajemen Inventaris') && $(this).is(':checked')) {
                        needCategory = true;
                    }
                });

                if (needCategory) {
                    const selectedValues = choicesInstance.getValue(true);
                    if (selectedValues.length === 0) {
                        e.preventDefault();
                        $('#category-error').show();
                        isValid = false;
                        $('html, body').animate({
                            scrollTop: $("#workProgramOptions").offset().top - 100
                        }, 500);
                    }
                }
                
                if(isValid) {
                    $('#submitButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                }
            });
        });
    </script>
@endsection