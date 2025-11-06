@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Data LLAU Harian')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Data LLAU Tanggal {{ $traffic->date->translatedFormat('d F Y') }}</h3>
                <p class="text-subtitle text-muted">Perbarui data LLAU untuk tanggal yang dipilih.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Data LLAU', 'url' => route('staff.air-traffic.index')],
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
             @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('staff.air-traffic.update', $traffic->id) }}" method="POST">
                @method('PUT')
                @include('user_staff2.lalu-lintas-harian._form', ['traffic' => $traffic])
            </form>
        </div>
    </div>
</section>
@endsection