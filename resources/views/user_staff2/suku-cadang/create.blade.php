@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Suku Cadang')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Suku Cadang Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk menambahkan suku cadang baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Suku Cadang', 'url' => route('staff.spare-parts.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Suku Cadang</h5></div>
        <div class="card-body">
            <form action="{{ route('staff.spare-parts.store') }}" method="POST" enctype="multipart/form-data">
                @include('user_staff2.suku-cadang._form')
            </form>
        </div>
    </div>
</section>
@endsection
