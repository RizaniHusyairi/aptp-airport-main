<!-- =================================================================== -->
<!-- 3. FORMULIR EDIT (resources/views/admin2/info-slides/edit.blade.php) -->
<!-- =================================================================== -->
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Slide Informasi')
@section('content')
<div class="page-heading"><h3>Edit Slide Informasi</h3></div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Edit Slide</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.info-slides.update', $infoSlide) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin2.info-slides._form', ['slide' => $infoSlide])
            </form>
        </div>
    </div>
</section>
@endsection
