@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Role')
@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsv2/compiled/css/table-datatable-jquery.css') }}">
    {{-- 1. CSS Choices.js --}}
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
        <div class="card-header"><h4>Tambah Role</h4></div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST" id="createRoleForm">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="roleName" class="form-label">Nama Role</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="roleName" name="name" value="{{ old('name') }}" required>
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
                                                   {{ is_array(old('permissions')) && in_array($permission->id, old('permissions')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission{{ $permission->id }}">
                                                {{ $permission->permission_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- === AREA KATEGORI (Untuk Program Kerja & Inventaris) === --}}
                        <div id="workProgramOptions" class="mt-4 p-4 border rounded" style="display: none; border-left: 5px solid #435ebe !important;">
                            <h6 class="text-primary d-flex align-items-center">
                                <i class="bi bi-diagram-3-fill me-2"></i> Konfigurasi Kategori Tugas
                            </h6>
                            <p class="text-muted small mb-3" id="categoryHelpText">
                                Role ini memiliki akses pengelolaan (Program Kerja/Inventaris). Silakan tentukan kategori lingkup kerjanya:
                            </p>
                            
                            <div class="form-group mb-3">
                                <label for="work_categories" class="form-label fw-bold">Pilih Kategori</label>
                                <select class="choices form-select" name="work_categories[]" id="work_categories" multiple>
                                    @foreach($workCategories as $category)
                                        <option value="{{ $category }}" 
                                            {{ (collect(old('work_categories'))->contains($category)) ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="category-error" class="text-danger small mt-1" style="display:none;">Harap pilih setidaknya satu kategori.</div>
                            </div>

                            {{-- Wrapper untuk Opsi Verifikator (Hanya muncul jika Program Kerja dicentang) --}}
                            <div id="verifierOptionContainer" style="display: none;">
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="assign_verifier_permission" id="assignVerifierPermission" value="1"
                                        {{ old('assign_verifier_permission') ? 'checked' : '' }}>
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
                        <button type="submit" class="btn btn-primary" id="submitButton">Simpan Role</button>
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

            // 5. Logika Tampilkan/Sembunyikan
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

                // Tampilkan Dropdown Kategori jika salah satu dicentang
                if (isProkerChecked || isInventarisChecked) {
                    optionsContainer.slideDown();
                } else {
                    optionsContainer.slideUp();
                }

                // Tampilkan Opsi Verifikasi HANYA jika Proker dicentang
                if (isProkerChecked) {
                    verifierContainer.slideDown();
                } else {
                    verifierContainer.slideUp();
                    $('#assignVerifierPermission').prop('checked', false);
                }
            }

            $(document).on('change', '.permission-checkbox', toggleOptions);
            toggleOptions();

            // 6. Validasi Manual
            $('#createRoleForm').on('submit', function(e) {
                const roleName = $('#roleName').val().trim();
                const permissionsChecked = $('input[name="permissions[]"]:checked').length;
                let isValid = true;
                
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback-custom').remove();
                $('#category-error').hide();

                if (!roleName) {
                    $('#roleName').addClass('is-invalid').after('<div class="invalid-feedback invalid-feedback-custom">Nama role wajib diisi.</div>');
                    isValid = false;
                }

                if (permissionsChecked === 0) {
                    $('.form-group:has(.permission-checkbox)').first().before('<div class="alert alert-danger invalid-feedback-custom">Harap pilih setidaknya satu permission.</div>');
                    isValid = false;
                }

                // Validasi Kategori
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
                        $('#category-error').show();
                        isValid = false;
                        $('html, body').animate({ scrollTop: $("#workProgramOptions").offset().top - 100 }, 500);
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    if (!$('#category-error').is(':visible')) {
                        $('html, body').animate({ scrollTop: $(".is-invalid, .alert-danger").first().offset().top - 100 }, 500);
                    }
                } else {
                    $('#submitButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                }
            });
        });
    </script>
@endsection