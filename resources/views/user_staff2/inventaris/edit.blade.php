@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Inventaris')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Item Inventaris</h3>
                <p class="text-subtitle text-muted">Perbarui detail untuk: {{ $inventory->name }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                    ['label' => 'Inventaris', 'url' => route('staff.inventories.index')],
                    ['label' => 'Edit', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Edit</h5></div>
        <div class="card-body">
            <form action="{{ route('staff.inventories.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('user_staff2.inventaris._form')
            </form>
        </div>
    </div>
</section>
@endsection
