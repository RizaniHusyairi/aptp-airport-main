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
        <div class="card-header"><h4>Tambah Role</h4></div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST" id="createRoleForm">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="roleName">Nama Role</label>
                            <input type="text" class="form-control" id="roleName" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group mt-4">
                            <label>Permissions</label>
                            <div class="row mt-2">
                                @foreach ($permissions as $permission)
                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                   id="permission{{ $permission->id }}"
                                                   name="permissions[]"
                                                   value="{{ $permission->id }}"
                                                   data-name="{{ $permission->permission_name }}">
                                            <label class="form-check-label" for="permission{{ $permission->id }}">
                                                {{ $permission->permission_name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- === AREA KATEGORI PROGRAM KERJA (Hidden by Default) === --}}
                        <div id="workProgramOptions" class="mt-4 p-4 border rounded bg-light" style="display: none; border-left: 5px solid #435ebe !important;">
                            <h6 class="text-primary d-flex align-items-center">
                                <i class="bi bi-diagram-3-fill me-2"></i> Konfigurasi Program Kerja
                            </h6>
                            <p class="text-muted small mb-3">
                                Karena role ini memiliki akses <strong>Manajemen Program Kerja</strong>, silakan tentukan cakupan kerjanya:
                            </p>
                            
                            {{-- 1. Dropdown Multi-select untuk Kategori --}}
                            <div class="form-group mb-3">
                                <label for="work_categories" class="form-label fw-bold">Pilih Kategori Tugas</label>
                                <select class="form-select" name="work_categories[]" id="work_categories" multiple style="height: 150px;">
                                    @foreach($workCategories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    * Tahan tombol <strong>CTRL</strong> (Windows) atau <strong>Command</strong> (Mac) untuk memilih lebih dari satu kategori.
                                </small>
                            </div>

                            <hr>

                            {{-- 2. Opsi Verifikator (Ini akan mengontrol permission 'Verifikasi Program Kerja') --}}
                            <div class="form-check form-switch">
                                {{-- Name kita buat 'assign_verifier_permission' agar bisa ditangkap controller secara spesifik --}}
                                <input class="form-check-input" type="checkbox" name="assign_verifier_permission" id="assignVerifierPermission" value="1">
                                <label class="form-check-label fw-bold text-dark" for="assignVerifierPermission">
                                    Izinkan Verifikasi Program Kerja?
                                </label>
                                <small class="d-block text-muted mt-1">
                                    Jika diaktifkan, role ini akan mendapatkan hak akses untuk <strong>memverifikasi (menyetujui/menolak)</strong> tugas yang dikerjakan staff lain dalam kategori yang dipilih di atas.
                                </small>
                            </div>
                        </div>
                        {{-- === AKHIR AREA KATEGORI === --}}

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Role</button>
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
            // 1. Logika Tampilkan/Sembunyikan Opsi Program Kerja
            function toggleWorkProgramOptions() {
                // Cari checkbox permission dengan nama spesifik 'Manajemen Program Kerja'
                // Kita menggunakan atribut data-name yang sudah kamu buat di loop permissions
                const programKerjaCheckbox = $('.permission-checkbox[data-name="Manajemen Program Kerja"]');
                const optionsContainer = $('#workProgramOptions');

                if (programKerjaCheckbox.is(':checked')) {
                    optionsContainer.slideDown();
                    // Tambahkan atribut required pada select jika ditampilkan
                    $('#work_categories').prop('required', true);
                } else {
                    optionsContainer.slideUp();
                    // Hapus value dan required jika disembunyikan
                    $('#work_categories').val([]).trigger('change');
                    $('#work_categories').prop('required', false);
                    $('#assignVerifierPermission').prop('checked', false);
                }
            }

            // Jalankan fungsi saat checkbox permission berubah
            $(document).on('change', '.permission-checkbox', function() {
                // Cek apakah yang diklik adalah Manajemen Program Kerja
                if($(this).data('name') === 'Manajemen Program Kerja') {
                    toggleWorkProgramOptions();
                }
            });
            
            // Jalankan saat halaman dimuat (untuk menangani validasi error/old input)
            toggleWorkProgramOptions();

            // 2. Validasi Form Sebelum Submit
            $('#createRoleForm').on('submit', function (e) {
                const roleName = $('#roleName').val().trim();
                const permissionsChecked = $('input[name="permissions[]"]:checked').length;
                let isValid = true;

                // Reset error states
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback-custom').remove();

                if (!roleName) {
                    $('#roleName').addClass('is-invalid').after('<div class="invalid-feedback invalid-feedback-custom">Nama role wajib diisi.</div>');
                    isValid = false;
                }

                if (permissionsChecked === 0) {
                    $('.form-group:has(.permission-checkbox)').first().before('<div class="alert alert-danger invalid-feedback-custom">Harap pilih setidaknya satu permission.</div>');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    // Scroll ke error pertama
                    $('html, body').animate({
                        scrollTop: $(".is-invalid, .alert-danger").first().offset().top - 100
                    }, 500);
                } else {
                    // Tampilkan loading state
                    $(this).find('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                }
            });
        });
    </script>
@endsection