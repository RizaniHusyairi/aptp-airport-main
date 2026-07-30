@extends('layouts-V2.master-layouts-v2')
@section('title', 'Pengaturan Survei Kepuasan Masyarakat')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Pengaturan Survei Kepuasan Masyarakat</h3>
                <p class="text-subtitle text-muted">Kelola tautan survei SKM yang tampil di beranda, footer, dan halaman Standar Pelayanan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pengaturan SKM', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tautan Survei</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.skm-settings.update') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="skm_url" class="form-label fw-bold">URL Survei <span class="text-danger">*</span></label>
                    <input type="url" class="form-control @error('skm_url') is-invalid @enderror" id="skm_url" name="skm_url"
                        value="{{ old('skm_url', $settings['skm_url'] ?? '') }}" placeholder="https://skm.dephub.go.id/..." required>
                    @error('skm_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Alamat form survei eksternal. Tautan dibuka di tab baru.</small>
                </div>

                <div class="mb-4">
                    <label for="skm_label" class="form-label fw-bold">Label Tombol <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('skm_label') is-invalid @enderror" id="skm_label" name="skm_label"
                        value="{{ old('skm_label', $settings['skm_label'] ?? 'Isi Survei Kepuasan Masyarakat') }}" maxlength="100" required>
                    @error('skm_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Teks yang tampil pada tombol dan tautan di halaman publik.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold d-block">Status Tampil</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="skm_is_active" name="skm_is_active" value="1"
                            @checked(old('skm_is_active', $settings['skm_is_active'] ?? '0') == '1')>
                        <label class="form-check-label" for="skm_is_active">Tampilkan tautan survei di website</label>
                    </div>
                    <small class="text-muted">Matikan saat periode survei ditutup. Tautan akan hilang dari beranda, footer, dan halaman Standar Pelayanan.</small>
                </div>

                @if(!empty($settings['skm_url']))
                    <div class="alert alert-light-primary d-flex align-items-center" role="alert">
                        <i class="bi bi-box-arrow-up-right me-2"></i>
                        <div>
                            Pratinjau tautan tersimpan:
                            <a href="{{ $settings['skm_url'] }}" target="_blank" rel="noopener">{{ $settings['skm_url'] }}</a>
                        </div>
                    </div>
                @endif

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
