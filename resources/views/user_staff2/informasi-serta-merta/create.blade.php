{{-- resources/views/user_staff2/informasi-serta-merta/create.blade.php --}}
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Informasi Serta Merta')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Informasi Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk menambahkan informasi serta merta.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Informasi Serta Merta', 'url' => route('staff.immediate-informations.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Informasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.immediate-informations.store') }}" method="POST">
                @csrf
                @include('user_staff2.informasi-serta-merta._form')
            </form>
        </div>
    </div>
</section>
@endsection