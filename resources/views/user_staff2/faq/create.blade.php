@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Pertanyaan FAQ')

@section('styles_admin')
    <style>
        .mce-notification { display: none !important; }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Pertanyaan FAQ</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk menambahkan pertanyaan baru.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'FAQ', 'url' => route('staff.faqs.index')],
                    ['label' => 'Tambah Pertanyaan', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Pertanyaan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.faqs.store') }}" method="POST">
                @csrf
                @include('user_staff2.faq._form')
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    @include('user_staff2.faq._tinymce')
@endsection
