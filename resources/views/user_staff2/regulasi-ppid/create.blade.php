@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Regulasi PPID')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Regulasi PPID Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk menambahkan regulasi baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Regulasi PPID', 'url' => route('staff.ppid-regulations.index')],
                    ['label' => 'Tambah Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Regulasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.ppid-regulations.store') }}" method="POST">
                @csrf
                {{-- Memuat form partial --}}
                @include('user_staff2.regulasi-ppid._form')
            </form>
        </div>
    </div>
</section>
@endsection
