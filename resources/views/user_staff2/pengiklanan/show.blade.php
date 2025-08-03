@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Pengiklanan')
@section('styles_admin')
    
@endsection
@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengiklanan</h3>
                    <p class="text-subtitle text-muted">Detail informasi pengajuan Pengiklanan.</p>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Menu', 'url' => route('profile')],
                    ['label' => 'Pengiklanan', 'url' => route('pengiklanan.staffindex')],
                    ['label' => 'Detail', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            @if(session('success'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card-header">
                <h5 class="card-title">Informasi Pengaju</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 col-12 text-center mb-3 mb-md-0">
                        <div class="avatar avatar-xl me-3">
                            <img src="{{ asset('../assetsv2/compiled/jpg/2.jpg') }}" alt="{{  $ad->users->first()?->name ?? '-'  }}" srcset="">
                        </div>
                    </div>
                    <div class="col-md-9 col-12">
                        <div class="row">
                            <div class="col-12">
                                <h6>Nama</h6>
                                <p>{{ $ad->users->first()?->name ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Email</h6>
                                <p>{{ $ad->users->first()?->email ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <h6>Tanggal Pengajuan</h6>
                                <p>{{ $ad->created_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Pengiklanan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 ">
                        <h6>Nama Pengiklanan</h6>
                        <p>{{ $ad->ad_name }}</p>
                    </div>
                    <div class="col-md-6 ">
                        <h6>Jenis Pengiklanan</h6>
                        <p>{{ $ad->ad_type }}</p>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <h6>Deskripsi Pengiklanan</h6>
                        <p>{{ $ad->description }}</p>
                    </div>
                    @if ($ad->documents)
                        <div class="col-md-6">
                            <h6>Dokumen Terlampir</h6>
                            <a href="{{ asset('uploads/documents/ads/' . basename($ad->documents)) }}" class="btn btn-sm btn-primary" id="lihat-dokumen" data-bs-toggle="tooltip" title="Lihat Dokumen"><i class="bi bi-file-earmark-pdf"></i> {{ basename($ad->documents) }}</a>
                        </div>
                    @else
                        <div class="col-md-6">
                            <h6>Tidak Ada Dokumen</h6>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <h6>Status Pengajuan</h6>
                        @php
                        $status = $ad->submission_status;
                        $badgeClass = match($status) {
                            'disetujui' => 'bg-success',
                            'ditolak' => 'bg-danger',
                            default => 'bg-info',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pengiklanan.index') }}" class="btn btn-secondary">Kembali</a>
            @if ($ad->submission_status === 'diajukan')
                <div class="">
                  <form action="{{ route('pengiklanan.approve', $ad->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success" id="setujui-pengajuan">Setujui Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('pengiklanan.reject', $ad->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" id="tolak-pengajuan">Tolak Pengajuan</button>
                  </form>
                </div>
              @endif
        </div>
    </section>
</div>
@endsection
@section('scripts_admin')

@endsection