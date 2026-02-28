@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengaturan Profil Bandara')

@section('styles_admin')
    <style>
        .mce-notification { display: none !important; }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengaturan Profil Bandara</h3>
                <p class="text-subtitle text-muted">Ubah konten halaman profil publik Bandara A.P.T. Pranoto.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pengaturan Profil', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Konten Profil</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.profile-bandara-settings.update') }}" method="POST">
                @csrf
                
                {{-- Sejarah dan Letak Geografis --}}
                <div class="mb-4">
                    <label for="profile_sejarah" class="form-label fw-bold">Sejarah dan Letak Geografis</label>
                    <textarea class="form-control tinymce-editor" id="profile_sejarah" name="profile_sejarah" rows="5">{{ old('profile_sejarah', $settings['profile_sejarah'] ?? '') }}</textarea>
                </div>

                {{-- Status dan Penetapan --}}
                <div class="mb-4">
                    <label for="profile_status" class="form-label fw-bold">Status dan Penetapan</label>
                    <textarea class="form-control tinymce-editor" id="profile_status" name="profile_status" rows="5">{{ old('profile_status', $settings['profile_status'] ?? '') }}</textarea>
                </div>

                {{-- Rute Penerbangan --}}
                <div class="mb-4">
                    <label for="profile_rute" class="form-label fw-bold">Rute Penerbangan</label>
                    <textarea class="form-control tinymce-editor" id="profile_rute" name="profile_rute" rows="5">{{ old('profile_rute', $settings['profile_rute'] ?? '') }}</textarea>
                </div>

                {{-- Tugas --}}
                <div class="mb-4">
                    <label for="profile_tugas" class="form-label fw-bold">Tugas</label>
                    <textarea class="form-control tinymce-editor" id="profile_tugas" name="profile_tugas" rows="5">{{ old('profile_tugas', $settings['profile_tugas'] ?? '') }}</textarea>
                </div>
                
                {{-- Fungsi --}}
                <div class="mb-4">
                    <label for="profile_fungsi" class="form-label fw-bold">Fungsi</label>
                    <textarea class="form-control tinymce-editor" id="profile_fungsi" name="profile_fungsi" rows="8">{{ old('profile_fungsi', $settings['profile_fungsi'] ?? '') }}</textarea>
                    <small class="text-muted">Gunakan Bullet List di editor untuk membuat daftar point-point fungsi.</small>
                </div>

                {{-- Visi --}}
                <div class="mb-4">
                    <label for="profile_visi" class="form-label fw-bold">Visi</label>
                    <textarea class="form-control tinymce-editor" id="profile_visi" name="profile_visi" rows="5">{{ old('profile_visi', $settings['profile_visi'] ?? '') }}</textarea>
                </div>

                {{-- Misi --}}
                <div class="mb-4">
                    <label for="profile_misi" class="form-label fw-bold">Misi</label>
                    <textarea class="form-control tinymce-editor" id="profile_misi" name="profile_misi" rows="8">{{ old('profile_misi', $settings['profile_misi'] ?? '') }}</textarea>
                    <small class="text-muted">Gunakan Bullet List di editor untuk membuat daftar point-point misi.</small>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/tinymce/tinymce.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
             tinymce.init({
                selector: '.tinymce-editor', // Targetkan textarea dengan class tinymce-editor
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save(); // Penting: sinkronkan konten TinyMCE kembali ke textarea
                    });
                }
            });
        });
    </script>
@endsection
