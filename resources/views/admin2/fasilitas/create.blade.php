@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Fasilitas')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Fasilitas Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk menambahkan fasilitas baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Fasilitas', 'url' => route('admin.facilities.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Fasilitas</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin2.fasilitas.parties._form')
            </form>
        </div>
    </div>
</section>
@endsection
