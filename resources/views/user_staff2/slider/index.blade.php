@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengaturan Latar Belakang Hero')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengaturan Latar Belakang Hero</h3>
                <p class="text-subtitle text-muted">Atur tampilan latar belakang utama di halaman beranda.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pengaturan Hero', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Pengaturan</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.hero-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Tipe Latar Belakang <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="hero_type" id="typeImage" value="image"
                                   @checked(old('hero_type', $settings['hero_type'] ?? 'image') == 'image') required>
                            <label class="form-check-label" for="typeImage">Gambar Statis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="hero_type" id="typeVideo" value="video"
                                   @checked(old('hero_type', $settings['hero_type'] ?? '') == 'video') required>
                            <label class="form-check-label" for="typeVideo">Video</label>
                        </div>
                    </div>
                </div>

                {{-- Input Gambar --}}
                <div id="image-input-container" class="mb-4" style="display: {{ old('hero_type', $settings['hero_type'] ?? 'image') == 'image' ? 'block' : 'none' }};">
                    <label for="hero_image" class="form-label">Unggah Gambar Latar Belakang</label>
                    <input class="form-control @error('hero_image') is-invalid @enderror" type="file" id="hero_image" name="hero_image" accept="image/*">
                    <small class="form-text text-muted">Format: JPG, PNG, WEBP. Maksimal 5MB. Jika tidak diubah, gambar lama akan tetap digunakan.</small>
                    @error('hero_image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    @if(!empty($settings['hero_image_path']))
                        <div class="mt-3">
                            <p class="mb-1">Gambar saat ini:</p>
                            {{-- <img src="{{ Storage::url($settings['hero_image_path']) }}" alt="Hero Background" style="max-height: 200px; max-width: 100%; border-radius: 8px;"> --}}
                            <img src="{{ asset('uploads/' . $settings['hero_image_path']) }}" alt="Hero Background" style="max-height: 200px; max-width: 100%; border-radius: 8px;">
                        </div>
                    @endif
                </div>

                {{-- Input Video --}}
                <div id="video-input-container" class="mb-4" style="display: {{ old('hero_type', $settings['hero_type'] ?? '') == 'video' ? 'block' : 'none' }};">
                    <label for="hero_video" class="form-label">Unggah Video Latar Belakang</label>
                    <input class="form-control @error('hero_video') is-invalid @enderror" type="file" id="hero_video" name="hero_video" accept="video/mp4,video/webm">
                    <small class="form-text text-muted">Format: MP4, WEBM. Maksimal 20MB. Jika tidak diubah, video lama akan tetap digunakan.</small>
                    @error('hero_video')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    @if(!empty($settings['hero_video_path']))
                        <div class="mt-3">
                            <p class="mb-1">Video saat ini:</p>
                            <video controls muted loop playsinline style="max-height: 200px; max-width: 100%; border-radius: 8px;">
                                {{-- <source src="{{ Storage::url($settings['hero_video_path']) }}" type="video/{{ pathinfo($settings['hero_video_path'], PATHINFO_EXTENSION) }}"> --}}
                                <source src="{{ asset('uploads/' . $settings['hero_video_path']) }}" type="video/{{ pathinfo($settings['hero_video_path'], PATHINFO_EXTENSION) }}">
                                Browser Anda tidak mendukung tag video.
                            </video>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeRadios = document.querySelectorAll('input[name="hero_type"]');
        const imageContainer = document.getElementById('image-input-container');
        const videoContainer = document.getElementById('video-input-container');
        const imageInput = document.getElementById('hero_image');
        const videoInput = document.getElementById('hero_video');

        function toggleInputs() {
            const selectedType = document.querySelector('input[name="hero_type"]:checked').value;
            if (selectedType === 'image') {
                imageContainer.style.display = 'block';
                videoContainer.style.display = 'none';
                // Jika ingin membuat input wajib saat radio dipilih:
                // imageInput.required = true;
                // videoInput.required = false;
            } else {
                imageContainer.style.display = 'none';
                videoContainer.style.display = 'block';
                // imageInput.required = false;
                // videoInput.required = true;
            }
        }

        typeRadios.forEach(radio => radio.addEventListener('change', toggleInputs));

        // Panggil sekali saat load untuk tampilan awal
        // toggleInputs(); // Sepertinya tidak perlu karena sudah diatur inline style
    });
</script>
@endsection
