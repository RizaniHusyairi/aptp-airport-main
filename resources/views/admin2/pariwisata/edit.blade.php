@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Destinasi Wisata')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Destinasi Wisata</h3>
                <p class="text-subtitle text-muted">Perbarui detail untuk destinasi: {{ $tourism->name }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Pariwisata', 'url' => route('admin.tourism.index')],
                    ['label' => 'Edit', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Edit Destinasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tourism.update', $tourism) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin2.pariwisata.parties._form')
            </form>
        </div>
    </div>
</section>
@endsection