
@extends('layouts_landing.landing_app')

@section('title', 'Pengajuan Informasi Publik - Bandara APT Pranoto')

@section('content')
<section id="pengajuan-informasi" class="section pt-6 light-background">
    <div class="container section-title" data-aos="fade-up">
        <h2>Pengajuan<br></h2>
        <p><span>Informasi Publik</span> <span class="description-title">Bandara A.P.T. Pranoto Samarinda</span></p>
    </div>

    <div class="container">
        <!-- Persyaratan Section -->
        <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="col-12">
                <h3 class="mb-4">Persyaratan Pengajuan Informasi Publik</h3>
                <div class="card shadow p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Scan Kartu Tanda Penduduk (KTP) dalam format PDF atau gambar (JPG/PNG).
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Surat Pernyataan Pertanggung Jawaban Informasi Publik. 
                            <a href="{{ asset('assets_landing/docs/surat_pernyataan_pertanggungjawaban.pdf') }}" 
                               class="btn btn-sm btn-outline-primary ms-2" 
                               download>
                                <i class="bi bi-download me-1"></i> Unduh Template
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Mengisi formulir pengajuan informasi publik di bawah ini.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="col-12 php-email-form">
                <h3 class="mb-4 text-center">Formulir Pengajuan Informasi Publik</h3>
                <div id="surveyContainer"></div>
            </div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="toastNotification" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="toast-header">
                    <strong class="me-auto toast-title"></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('page-styles')
<link href="https://unpkg.com/survey-jquery@1.9.131/survey.css" rel="stylesheet">
<link href="{{ asset('assets_landing/css/pengajuan_informasi.css') }}" rel="stylesheet">
<style>
    .sv_main .sv_container {
        background-color: var(--surface-color);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        border-radius: 8px;
    }
    .sv_main .sv_page_title {
        color: var(--heading-color);
        font-family: var(--heading-font);
        font-size: 24px;
        margin-bottom: 20px;
    }
    .sv_main .sv_q_title {
        color: var(--default-color);
        font-weight: 500;
    }
    .sv_main .sv_q_description {
        color: color-mix(in srgb, var(--default-color), transparent 50%);
    }
    .sv_main input, .sv_main select, .sv_main textarea {
        border-color: color-mix(in srgb, var(--default-color), transparent 80%);
        border-radius: 4px;
        padding: 10px;
        background-color: color-mix(in srgb, var(--surface-color), transparent 50%);
    }
    .sv_main input:focus, .sv_main select:focus, .sv_main textarea:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 5px rgba(217, 158, 78, 0.5);
    }
    .sv_main .sv_q_file input[type="file"] {
        padding: 5px;
    }
    .sv_main .sv_next_btn, .sv_main .sv_complete_btn {
        background-color: var(--accent-color);
        color: var(--contrast-color);
        padding: 10px 30px;
        border-radius: 50px;
        border: none;
        transition: 0.3s;
    }
    .sv_main .sv_next_btn:hover, .sv_main .sv_complete_btn:hover {
        background-color: color-mix(in srgb, var(--accent-color), transparent 20%);
    }
    .sv_main .sv_prev_btn {
        background-color: color-mix(in srgb, var(--default-color), transparent 80%);
        color: var(--default-color);
        padding: 10px 30px;
        border-radius: 50px;
        border: none;
        transition: 0.3s;
    }
    .sv_main .sv_prev_btn:hover {
        background-color: color-mix(in srgb, var(--default-color), transparent 60%);
    }
    @media (max-width: 575px) {
        .sv_main .sv_container {
            padding: 20px;
        }
    }
</style>
@endpush


@push('page-scripts')
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
<script src="https://unpkg.com/survey-jquery@1.10.6/survey.jquery.min.js"></script>
<script>
    $(document).ready(function () {
        Survey.StylesManager.applyTheme("defaultV2");

        // Konfigurasi lokalisasi untuk bahasa Indonesia
        if (Survey && Survey.surveyLocalization) {
            Survey.surveyLocalization.locales["id"] = {
                requiredError: "Kolom ini wajib diisi.",
                email: "Masukkan alamat email yang valid.",
                regex: "Masukkan nilai yang sesuai dengan format yang diizinkan.",
                file: "File wajib diunggah.",
                maxSize: "Ukuran file melebihi batas maksimum {0}.",
                accept: "Format file tidak diizinkan. Gunakan format yang diperbolehkan."
            };
            Survey.surveyLocalization.defaultLocale = "id";
            console.log('Lokalisasi bahasa Indonesia diterapkan.');
        } else {
            console.warn('SurveyJS lokalisasi tidak tersedia. Gunakan pesan default.');
        }

        // Fungsi untuk mengonversi base64 ke objek File
        function base64ToFile(base64String, fileName, mimeType) {
            const byteString = atob(base64String.split(',')[1]);
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new File([ab], fileName, { type: mimeType });
        }

        // Inisialisasi toast
        const toastElement = document.getElementById('toastNotification');
        const toast = new bootstrap.Toast(toastElement);

        var surveyJSON = {
            pages: [
                {
                    name: "page1",
                    elements: [
                        {
                            type: "file",
                            name: "ktp",
                            title: "Upload Scan KTP",
                            description: "Unggah file scan KTP dalam format PDF, JPG, atau PNG (maks. 2MB).",
                            isRequired: true,
                            accept: "image/jpeg,image/png,application/pdf",
                            maxSize: 2097152,
                            requiredErrorText: "Scan KTP wajib diunggah."
                        },
                        {
                            type: "file",
                            name: "surat_pertanggungjawaban",
                            title: "Upload Surat Pernyataan Pertanggung Jawaban",
                            description: "Unggah surat pernyataan yang telah diisi dan ditandatangani (PDF/JPG/PNG, maks. 2MB).",
                            isRequired: true,
                            accept: "image/jpeg,image/png,application/pdf",
                            maxSize: 2097152,
                            requiredErrorText: "Surat pernyataan wajib diunggah."
                        },
                        {
                            type: "text",
                            name: "surat_permintaan",
                            title: "Surat Permintaan Informasi",
                            isRequired: true,
                            description: "Masukkan nama instansi/organisasi pengaju.",
                            placeHolder: "Contoh: PT. XYZ / Individu",
                            requiredErrorText: "Surat permintaan wajib diisi."
                        }
                    ]
                },
                {
                    name: "page2",
                    elements: [
                        {
                            type: "text",
                            name: "nama",
                            title: "Nama Lengkap",
                            isRequired: true,
                            placeHolder: "Masukkan nama lengkap Anda",
                            requiredErrorText: "Nama lengkap wajib diisi."
                        },
                        {
                            type: "text",
                            name: "alamat",
                            title: "Alamat",
                            isRequired: true,
                            placeHolder: "Masukkan alamat lengkap Anda",
                            requiredErrorText: "Alamat wajib diisi."
                        },
                        {
                            type: "text",
                            name: "pekerjaan",
                            title: "Pekerjaan",
                            isRequired: true,
                            placeHolder: "Masukkan pekerjaan Anda",
                            requiredErrorText: "Pekerjaan wajib diisi."
                        },
                        {
                            type: "text",
                            name: "npwp",
                            title: "Nomor NPWP",
                            isRequired: true,
                            placeHolder: "Masukkan nomor NPWP Anda",
                            requiredErrorText: "Nomor NPWP wajib diisi."
                        },
                        {
                            type: "text",
                            name: "no_hp",
                            title: "Nomor HP/WA",
                            isRequired: true,
                            placeHolder: "Masukkan nomor HP/WA Anda",
                            validators: [
                                {
                                    type: "regex",
                                    text: "Masukkan nomor telepon yang valid",
                                    regex: "^\\+?\\d{10,13}$"
                                }
                            ],
                            requiredErrorText: "Nomor HP/WA wajib diisi."
                        },
                        {
                            type: "text",
                            name: "email",
                            title: "Email",
                            isRequired: true,
                            placeHolder: "Masukkan alamat email Anda",
                            validators: [
                                {
                                    type: "email",
                                    text: "Masukkan email yang valid"
                                }
                            ],
                            requiredErrorText: "Email wajib diisi."
                        },
                        {
                            type: "text",
                            name: "rincian_informasi",
                            title: "Rincian Informasi yang Dibutuhkan",
                            isRequired: true,
                            placeHolder: "Jelaskan informasi yang Anda butuhkan secara rinci",
                            requiredErrorText: "Rincian informasi wajib diisi."
                        },
                        {
                            type: "text",
                            name: "tujuan_informasi",
                            title: "Tujuan Penggunaan Informasi",
                            isRequired: true,
                            placeHolder: "Jelaskan tujuan penggunaan informasi",
                            requiredErrorText: "Tujuan informasi wajib diisi."
                        },
                        {
                            type: "checkbox",
                            name: "cara_memperoleh",
                            title: "Cara Memperoleh Informasi",
                            isRequired: true,
                            choices: [
                                "Melihat/Membaca/Mendengarkan/Mencatat",
                                "Mendapatkan Copy Salinan (Hard Copy)"
                            ],
                            colCount: 2,
                            requiredErrorText: "Cara memperoleh informasi wajib dipilih."
                        },
                        {
                            type: "checkbox",
                            name: "cara_salinan",
                            title: "Cara Mendapat Salinan Informasi",
                            isRequired: true,
                            choices: [
                                "Langsung",
                                "Kurir",
                                "Pos",
                                "Fax",
                                "Email",
                                "Whatsapp"
                            ],
                            colCount: 3,
                            requiredErrorText: "Cara mendapat salinan informasi wajib dipilih."
                        }
                    ]
                }
            ],
            showProgressBar: "top",
            showQuestionNumbers: "off",
            completeText: "Kirim Pengajuan",
            pageNextText: "Lanjut",
            pagePrevText: "Kembali",
            showCompletedPage: false,
            storeOthersAsComment: false,
            sendResultOnPageNext: false
        };

        var survey = new Survey.Model(surveyJSON);
        $("#surveyContainer").Survey({
            model: survey,
            onCompleting: function (sender, options) {
                options.allowComplete = false; // Cegah penyelesaian default
                var formData = new FormData();

                // Proses data survey
                for (var key in sender.data) {
                    if (key === "ktp" || key === "surat_pertanggungjawaban") {
                        if (sender.data[key] && sender.data[key][0]) {
                            const fileData = sender.data[key][0];
                            let file;
                            if (fileData.content && fileData.content.startsWith('data:')) {
                                const mimeType = fileData.type || 'application/octet-stream';
                                file = base64ToFile(fileData.content, fileData.name, mimeType);
                            } else {
                                file = fileData;
                            }
                            console.log(`Menambahkan file untuk ${key}:`, file);
                            formData.append(key, file);
                        } else {
                            console.log(`Tidak ada file untuk ${key}`);
                        }
                    } else if (Array.isArray(sender.data[key])) {
                        console.log(`Menambahkan array untuk ${key}:`, sender.data[key]);
                        formData.append(key, sender.data[key].join(","));
                    } else {
                        console.log(`Menambahkan teks untuk ${key}:`, sender.data[key]);
                        formData.append(key, sender.data[key] || '');
                    }
                }
                formData.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('storePengajuanInformasiPublik') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        console.log('Mengirim permintaan AJAX...');
                        $('#toastNotification').removeClass('bg-success bg-danger').addClass('bg-secondary');
                        $('.toast-title').text('Mengirim');
                        $('.toast-body').text('Pengajuan Anda sedang diproses...');
                        toast.show();
                    },
                    success: function (response) {
                        console.log('Respons sukses:', response);
                        $('#toastNotification').removeClass('bg-secondary bg-danger').addClass('bg-success');
                        $('.toast-title').text('Sukses');
                        $('.toast-body').text(response.message || 'Pengajuan Anda telah terkirim. Terima kasih!');
                        toast.show();
                        survey.clear();
                        setTimeout(() => {
                            toast.hide();
                        }, 5000);
                    },
                    error: function (xhr) {
                        console.error('Error server:', xhr.responseJSON);
                        $('#toastNotification').removeClass('bg-secondary bg-success').addClass('bg-danger');
                        $('.toast-title').text('Error');
                        $('.toast-body').html(xhr.responseJSON?.errors?.join('<br>') || 'Terjadi kesalahan. Silakan coba lagi.');
                        toast.show();
                        setTimeout(() => {
                            toast.hide();
                        }, 7000);
                    }
                });
            }
        });
    });
</script>
@endpush
