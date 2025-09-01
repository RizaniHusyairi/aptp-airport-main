{{-- resources/views/user_staff2/informasi-setiap-saat/create.blade.php --}}
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Laporan Layanan Informasi')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Dokumen Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk laporan tahunan layanan informasi publik.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Laporan Layanan Informasi', 'url' => route('staff.information-service-reports.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Dokumen</h5></div>
        <div class="card-body">
            <form action="{{ route('staff.information-service-reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('user_staff2.laporan-layanan-informasi._form')
            </form>
        </div>
    </div>
</section>
@endsection