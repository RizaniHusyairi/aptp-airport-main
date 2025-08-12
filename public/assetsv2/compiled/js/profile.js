$(document).ready(function () {
    // Logika untuk pratinjau gambar di modal
    $('#avatar').on('change', function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('.img-profil-modal').attr('src', e.target.result);
        }
        reader.readAsDataURL(event.target.files[0]);
    });

    // Fungsi untuk membersihkan galat sebelumnya
    function clearFormErrors(form) {
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');
    }

    // Fungsi untuk menampilkan galat
    function displayFormErrors(form, errors) {
        for (const field in errors) {
            const input = form.find(`[name="${field}"]`);
            const errorContainer = form.find(`.invalid-feedback[data-field="${field}"]`);
            
            input.addClass('is-invalid');
            if (errorContainer.length) {
                errorContainer.text(errors[field][0]);
            }
        }
    }

    // Handle Edit Profile Form Submission
    $('#editProfileForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = $('#saveProfile');

        clearFormErrors(form);
        submitButton.find('.spinner-border').removeClass('d-none');
        submitButton.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $('.img-profil').attr('src', response.data.avatar_url);
                    $('.img-profil-modal').attr('src', response.data.avatar_url);
                    $('.card-title').text(response.data.name);
                    $('.card-subtite').text(response.data.email + ' | ' + (response.data.phone || '---'));
                    $('.card-text p').text(response.data.address || '---');
                    
                    $('#editProfileModal').modal('hide');
                    
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    displayFormErrors(form, xhr.responseJSON.errors);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan saat memperbarui profil.' });
                }
            },
            complete: function () {
                submitButton.find('.spinner-border').addClass('d-none');
                submitButton.prop('disabled', false);
            }
        });
    });

    // Handle Change Password Form Submission
    $('#changePasswordForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = $('#savePassword');

        clearFormErrors(form);
        submitButton.find('.spinner-border').removeClass('d-none');
        submitButton.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    $('#changePasswordModal').modal('hide');
                    form[0].reset();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    displayFormErrors(form, xhr.responseJSON.errors);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan saat memperbarui kata sandi.' });
                }
            },
            complete: function () {
                submitButton.find('.spinner-border').addClass('d-none');
                submitButton.prop('disabled', false);
            }
        });
    });
});
