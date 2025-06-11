@extends('layouts-V2.master-layouts-v2')
@section('title', 'Profile')
@section('styles_admin')

@endsection


@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Profil</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[['label' => 'Profile', 'active' => true]]" />
            </div>
        </div>
    </div>
</div>
<section id="content-types">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-content text-center pt-4 pb-0">
                    <img src="../assets/compiled/jpg/1.jpg" class="img-profil" alt="Profile photo of John Ducky">
                    <div class="card-body pb-1">
                        <div class="title-content">
                            <h5 class="card-title">{{ Auth::user()->name }}</h5>
                            <span class="card-subtite">{{ Auth::user()->email }} | {{ Auth::user()->phone }}</span>
                        </div>
                        <div class="card-text row mt-4">
                            <div class="col-lg-12 justify-content-center d-flex">
                                <span class="fa-fw select-all fas me-2"></span>
                                <p>{{ Auth::user()->address ?? '---' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex w-100 justify-content-between p-3">
                        <button class="btn btn-warning rounded-pill text-white" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Ubah Kata Sandi</button>
                        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profil</button>
                    </li>
                </ul>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                                Tugas
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <span class="badge bg-danger">Manajemen Berita</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-primary">Manajemen Tenant</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-success">Manajemen Pengiklanan</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-info">Manajemen Penyewaan</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-warning">Manajemen Perijinan Usaha</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-secondary">Manajemen Slider</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-success">Manajemen Ajuan Informasi Publik</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-primary">Manajemen Lalu Lintas Angkutan Udara</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-info">Manajemen Pengaduan</span>  
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="badge bg-secondary">Manajemen Laporan Keuangan</span>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade text-left" id="editProfileModal" tabindex="-1" role="dialog"
    aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <form id="editProfileForm" action="#" method="POST" enctype="multipart/form-data">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="editProfileModalLabel">Edit Profil</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Format: 123-456-7890" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address" name="address" class="form-control" rows="4" required>{{ old('address', auth()->user()->address) }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="profilePhoto" class="form-label">Foto Profil</label>
                        <input type="file" id="profilePhoto" name="profilePhoto" class="form-control" accept="image/jpeg,image/png">
                        <div class="mt-2">
                            <img id="imagePreview" src="{{ asset('uploads/' . $news->image) }}" alt="Profile photo preview" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Change Password Modal -->
<div class="modal fade text-left" id="changePasswordModal" tabindex="-1" role="dialog"
    aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title white" id="changePasswordModalLabel">Ubah Kata Sandi</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="changePasswordForm" action="#" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="currentPassword" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" id="currentPassword" name="currentPassword" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="newPassword" class="form-label">Kata Sandi Baru</label>
                        <input type="password" id="newPassword" name="newPassword" class="form-control" minlength="8" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirmPassword" class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" minlength="8" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-warning ms-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts_admin')
        <script src="../assets/extensions/feather-icons/feather.min.js"></script>
        <script>
        // Initialize Feather Icons
        feather.replace();
        
        window.previewImage = function(e) {
            console.log('previewImage function called');
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        };

        // Initialize event listener for file input
            const profilePhotoInput = document.getElementById('profilePhoto');
            profilePhotoInput.addEventListener('change', (e)=> {
                console.log('File input changed');
                previewImage(e);
            });
            

        

        // Basic form validation for Change Password
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const newPassword = $('#newPassword').val();
            const confirmPassword = $('#confirmPassword').val();
            if (newPassword !== confirmPassword) {
                alert('Kata sandi baru dan konfirmasi tidak cocok!');
                return;
            }
            if (form.checkValidity()) {
                // Simulate form submission (replace with actual AJAX call to backend)
                alert('Kata sandi berhasil diubah!');
                $('#changePasswordModal').modal('hide');
            } else {
                form.reportValidity();
            }
        });
    </script>

@endsection