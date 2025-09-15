@extends('layouts-V2.master-layouts-v2')
@section('title', 'Buat Surat Baru')

@section('styles_admin')
    {{-- CSS untuk Choices.js (multi-select dropdown) --}}
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/choices.js/public/assets/styles/choices.css') }}">
    <style>
        .choices__item[data-final-approver="true"] .choices__button {
            display: none !important;
        }
        .choices__list--multiple .choices__item{
            background-color: #02606d;
            border: 1px solid #01535e;
            color: #fff;
        }
    </style>
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Konsep Surat Baru</h3>
                <p class="text-subtitle text-muted">Lengkapi formulir untuk memulai alur persetujuan surat.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('root')],
                    ['label' => 'Persuratan', 'url' => route('persuratan.staffIndex')],
                    ['label' => 'Buat Baru', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Formulir Tambah Konsep Surat</h5>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan pada formulir:</strong>
                <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('persuratan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="letter_type" class="form-label">Tipe Surat <span class="text-danger">*</span></label>
                        <select class="form-select @error('letter_type') is-invalid @enderror" id="letter_type" name="letter_type" required>
                            <option value="" selected disabled>Pilih Tipe Surat...</option>
                            <option value="Nota Dinas" @selected(old('letter_type') == 'Nota Dinas')>Nota Dinas</option>
                            <option value="Surat Dinas" @selected(old('letter_type') == 'Surat Dinas')>Surat Dinas</option>
                        </select>
                        @error('letter_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="letter_date" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('letter_date') is-invalid @enderror" id="letter_date" name="letter_date" value="{{ old('letter_date', now()->format('Y-m-d')) }}" required>
                        @error('letter_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="recipient_address" class="form-label">Tujuan Alamat Surat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('recipient_address') is-invalid @enderror" id="recipient_address" name="recipient_address" value="{{ old('recipient_address') }}" required>
                        @error('recipient_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="subject" class="form-label">Perihal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="final_approver_id" class="form-label">Pejabat Final yang Menandatangani <span class="text-danger">*</span></label>
                        <select class="form-select @error('final_approver_id') is-invalid @enderror" id="final_approver_id" name="final_approver_id" required>
                            <option value="" selected disabled>- Pilih Jabatan -</option>
                            
                            {{-- Loop melalui Role, bukan User --}}
                            @foreach ($approverRoles as $role)
                                {{-- Pastikan role tersebut memiliki user yang aktif --}}
                                @if($role->users->isNotEmpty())
                                    @php
                                        // Ambil user pertama yang memiliki role tersebut
                                        $approverUser = $role->users->first();
                                    @endphp
                                    <option value="{{ $approverUser->id }}" @selected(old('final_approver_id') == $approverUser->id)>
                                        {{ $role->name }} ({{ $approverUser->name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('final_approver_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="verifiers" class="form-label">Pejabat yang Melakukan Verifikasi Surat (Opsional)</label>
                        <p class="text-muted text-sm">Pilih satu atau lebih pejabat untuk proses verifikasi tambahan sebelum surat diteruskan ke atasan Anda.</p>
                        <select class="choices form-select multiple-remove @error('verifiers') is-invalid @enderror" multiple="multiple" id="verifiers" name="verifiers[]">
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        @error('verifiers')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="collaborators" class="form-label">Konsep surat ini saya kerjakan bersama (Opsional)</label>
                        <select class="choices form-select multiple-remove @error('collaborators') is-invalid @enderror" multiple="multiple" id="collaborators" name="collaborators[]">
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        @error('collaborators')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label class="form-label">Tautan Dokumen (Google Drive/Docs) <span class="text-danger">*</span></label>

                        <div id="link-wrapper" class="d-flex flex-column gap-2">
                            @php
                                $oldLinks = old('attachments', []);
                                if (empty($oldLinks)) { $oldLinks = ['']; } // minimal 1 baris
                            @endphp

                            @foreach ($oldLinks as $idx => $url)
                                <div class="input-group link-row">
                                    <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                    <input type="url"
                                        name="attachments[]"
                                        class="form-control @error('attachments.'.$idx) is-invalid @enderror"
                                        placeholder="https://drive.google.com/..."
                                        value="{{ $url }}"
                                        required>
                                    <button type="button" class="btn btn-outline-danger btn-remove-link" title="Hapus baris">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @error('attachments.'.$idx)
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        @error('attachments')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="mt-2">
                            <button type="button" id="btn-add-link" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus"></i> Tambah baris link
                            </button>
                            <small class="text-muted d-block mt-1">
                                Masukkan satu tautan per baris. Pastikan pengaturan berbagi (sharing) memungkinkan verifikator/atasan mengakses.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('persuratan.staffIndex') }}" class="btn btn-light-secondary me-2">Batal dan Keluar</a>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
    {{-- JS untuk Choices.js (multi-select dropdown) --}}
    <script src="{{ asset('assetsv2/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Choices.js untuk verifikator dan kolaborator
            const verifiersElement = document.getElementById('verifiers');
            const collaboratorsElement = document.getElementById('collaborators');
            
            // Definisikan verifierChoices di scope yang lebih tinggi
            let verifierChoices = null;

            if (verifiersElement) {
                verifierChoices = new Choices(verifiersElement, { 
                    removeItemButton: true, 
                    placeholder: true,
                    placeholderValue: 'Pilih pejabat verifikasi...', 
                    searchPlaceholderValue: 'Cari pejabat...' 
                });
            }
            if (collaboratorsElement) {
                new Choices(collaboratorsElement, { 
                    removeItemButton: true, 
                    placeholder: true,
                    placeholderValue: 'Pilih staff...', 
                    searchPlaceholderValue: 'Cari staff...' 
                });
            }

            // ========================================================== //
            // ===          LOGIKA SINKRONISASI (FIXED)               === //
            // ========================================================== //
            const finalApproverSelect = document.getElementById('final_approver_id');

            // Pastikan kedua elemen dropdown dan instance Choices.js sudah siap
            if (finalApproverSelect && verifierChoices) {
                let previousFinalApproverId = finalApproverSelect.value || null;

                function syncApproverToVerifier() {
                    const newSelectedValue = finalApproverSelect.value;
                    
                    // 1. Hapus pejabat final sebelumnya dari daftar verifikator (jika ada)
                    if (previousFinalApproverId && previousFinalApproverId !== newSelectedValue) {
                        // Cek apakah item tersebut masih ada sebelum mencoba menghapusnya
                        const existingValues = verifierChoices.getValue(true);
                        if (existingValues.includes(previousFinalApproverId)) {
                            verifierChoices.removeActiveItemsByValue(previousFinalApproverId);
                        }
                    }

                    if (newSelectedValue) {
                        // 2. Tambahkan pejabat final yang baru ke daftar verifikator
                        verifierChoices.setChoiceByValue(newSelectedValue);

                        // 3. Cari elemennya di UI dan kunci (sembunyikan tombol hapus)
                        setTimeout(() => {
                            const newChoiceEl = verifierChoices.containerInner.element.querySelector(`.choices__item[data-value="${newSelectedValue}"]`);
                            if (newChoiceEl) {
                                newChoiceEl.setAttribute('data-final-approver', 'true');
                            }
                        }, 50); // Jeda singkat untuk memastikan DOM update
                    }

                    // 4. Simpan ID pejabat saat ini untuk siklus berikutnya
                    previousFinalApproverId = newSelectedValue;
                }

                // Panggil fungsi saat ada perubahan pada dropdown Pejabat Final
                finalApproverSelect.addEventListener('change', syncApproverToVerifier);

                // Panggil sekali saat halaman dimuat untuk menangani data dari old()
                if (previousFinalApproverId) {
                    syncApproverToVerifier();
                }
            }

            // ---- Logika untuk Dynamic Link Rows (tidak berubah) ----
            const linkWrapper = document.getElementById('link-wrapper');
            const btnAdd = document.getElementById('btn-add-link');

            function makeRow(value = '') {
                const div = document.createElement('div');
                div.className = 'input-group link-row';
                div.innerHTML = `
                    <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                    <input type="url" name="attachments[]" class="form-control" placeholder="https://drive.google.com/..." required value="${value.replace(/"/g,'&quot;')}">
                    <button type="button" class="btn btn-outline-danger btn-remove-link" title="Hapus baris">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                return div;
            }

            btnAdd?.addEventListener('click', () => {
                linkWrapper.appendChild(makeRow());
            });

            linkWrapper.addEventListener('click', (e) => {
                if (e.target.closest('.btn-remove-link')) {
                    const rows = linkWrapper.querySelectorAll('.link-row');
                    if (rows.length > 1) {
                        e.target.closest('.link-row').remove();
                    } else {
                        const input = linkWrapper.querySelector('input[name="attachments[]"]');
                        if (input) input.value = '';
                    }
                }
            });
        });
    </script>
@endsection
