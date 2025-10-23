@extends('layouts-V2.master-layouts-v2')
@section('title', 'Buat Permintaan Suku Cadang')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Permintaan Suku Cadang Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk membuat permintaan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Permintaan Suku Cadang', 'url' => route('staff.spare-part-requests.index')],
                    ['label' => 'Buat Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Permintaan</h5></div>
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
            <form action="{{ route('staff.spare-part-requests.store') }}" method="POST">
                @include('user_staff2.permintaan-suku-cadang._form')
            </form>
        </div>
    </div>
</section>
@endsection
