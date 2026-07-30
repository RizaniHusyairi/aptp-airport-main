@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Pertanyaan FAQ')

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
                <h3>Edit Pertanyaan FAQ</h3>
                <p class="text-subtitle text-muted">Perbarui pertanyaan dan jawabannya.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'FAQ', 'url' => route('staff.faqs.index')],
                    ['label' => 'Edit Pertanyaan', 'active' => true]
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
            <form action="{{ route('staff.faqs.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('user_staff2.faq._form')
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    @include('user_staff2.faq._tinymce')
@endsection
