@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Program Kerja')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Program Kerja</h3>
                <p class="text-subtitle text-muted">Perbarui nama program dan daftar tugasnya.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Program Kerja', 'url' => route('staff.work-programs.index')],
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
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form action="{{ route('staff.work-programs.update', $workProgram->id) }}" method="POST">
                @method('PUT')
                @include('user_staff2.program-kerja._form', ['workProgram' => $workProgram])
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/compiled/js/program-kerja-form.js') }}"></script>
@endsection
