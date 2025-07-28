@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Destinasi Wisata')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Destinasi Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk menambahkan destinasi wisata.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pariwisata', 'url' => route('admin.tourism.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Destinasi Wisata</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tourism.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin2.pariwisata.parties._form')
            </form>
        </div>
    </div>
</section>
@endsection
