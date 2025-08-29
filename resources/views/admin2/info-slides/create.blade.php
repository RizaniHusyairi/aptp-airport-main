<!-- =================================================================== -->
<!-- 2. FORMULIR TAMBAH (resources/views/admin2/info-slides/create.blade.php) -->
<!-- =================================================================== -->
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Slide Informasi')
@section('content')
<div class="page-heading"><h3>Tambah Slide Informasi Baru</h3></div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Slide</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.info-slides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin2.info-slides._form')
            </form>
        </div>
    </div>
</section>
@endsection

