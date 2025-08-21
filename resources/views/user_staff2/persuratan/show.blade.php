@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Surat')
@section('content')
<div class="page-heading"><h3>Detail Surat: {{ $letter->title }}</h3></div>
<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Pratinjau Dokumen</h5></div>
                <div class="card-body">
                    <iframe src="{{ Storage::url($letter->attachments[0]) }}" width="100%" height="700px"></iframe>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                 <div class="card-header"><h5 class="card-title">Status & Riwayat</h5></div>
                 <div class="card-body">
                    {{-- Tampilkan timeline status dan riwayat verifikasi di sini --}}
                 </div>
            </div>
            <div class="card">
                <div class="card-header"><h5 class="card-title">Tindakan</h5></div>
                <div class="card-body">
                    {{-- Tampilkan form approve/reject di sini --}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection