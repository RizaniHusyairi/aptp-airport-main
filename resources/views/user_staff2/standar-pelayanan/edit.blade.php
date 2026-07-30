@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Dokumen Standar Pelayanan')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Dokumen Standar Pelayanan</h3>
                <p class="text-subtitle text-muted">Perbarui detail dokumen.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Standar Pelayanan', 'url' => route('staff.service-standards.index')],
                    ['label' => 'Edit Dokumen', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Dokumen</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.service-standards.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('user_staff2.standar-pelayanan._form')
            </form>
        </div>
    </div>
</section>
@endsection
