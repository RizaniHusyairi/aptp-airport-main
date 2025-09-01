{{-- resources/views/user_staff2/informasi-setiap-saat/edit.blade.php --}}
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit laporan layanan informasi')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Dokumen</h3>
                <p class="text-subtitle text-muted">Perbarui detail dokumen.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Laporan Layanan Informasi', 'url' => route('staff.information-service-reports.index')],
                    ['label' => 'Edit', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Edit Dokumen</h5></div>
        <div class="card-body">
            <form action="{{ route('staff.information-service-reports.update', $information->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('user_staff2.laporan-layanan-informasi._form')
            </form>
        </div>
    </div>
</section>
@endsection