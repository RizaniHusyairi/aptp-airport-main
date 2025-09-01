
@extends('layouts-V2.master-layouts-v2')
@section('title', 'Edit Dokumen Berkala')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Dokumen Informasi Berkala</h3>
                <p class="text-subtitle text-muted">Perbarui detail dokumen.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                 <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Informasi Berkala', 'url' => route('staff.periodic-documents.index')],
                    ['label' => 'Edit Dokumen', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Dokumen</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.periodic-documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('user_staff2.informasi-berkala._form')
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category');
        const pejabatNameWrapper = document.getElementById('pejabat-name-wrapper');
        const pejabatNameInput = document.getElementById('pejabat_name');

        function togglePejabatInput() {
            if (categorySelect.value === 'LHKPN') {
                pejabatNameWrapper.style.display = 'block';
            } else {
                pejabatNameWrapper.style.display = 'none';
                pejabatNameInput.value = ''; // Kosongkan nilainya saat disembunyikan
            }
        }

        // Panggil fungsi saat halaman dimuat untuk memeriksa nilai awal
        togglePejabatInput();

        // Panggil fungsi setiap kali nilai dropdown berubah
        categorySelect.addEventListener('change', togglePejabatInput);
    });
</script>
@endsection
