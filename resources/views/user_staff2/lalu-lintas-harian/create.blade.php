@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Data LLAU Harian')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Data LLAU Harian</h3>
                <p class="text-subtitle text-muted">Buat satu entri baru untuk data LLAU per tanggal.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Data LLAU', 'url' => route('staff.air-traffic.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Data Harian</h5></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('staff.air-traffic.store') }}" method="POST">
                @include('user_staff2.lalu-lintas-harian._form')
            </form>
        </div>
    </div>
</section>
@endsection