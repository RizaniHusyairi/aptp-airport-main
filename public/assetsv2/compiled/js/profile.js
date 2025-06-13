$(document).ready(function () {
    // Handle Edit Profile Form Submission
    $('#editProfileForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = $('#saveProfile');
        var errorContainer = $('#profileErrors');

        // Show loading spinner and disable button
        submitButton.find('.spinner-border').removeClass('d-none');
        submitButton.prop('disabled', true);
        errorContainer.addClass('d-none').empty();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    // Update UI with new data
                    $('.card-title').text(response.data.name);
                    $('.card-subtite').text(response.data.email + ' | ' + (response.data.phone || '---'));
                    $('.card-text p').text(response.data.address || '---');
                    // Close modal
                    $('#editProfileModal').modal('hide');
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Profil berhasil diperbarui.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Show error message with SweetAlert2
                    let errorMessage = response.message || 'Terjadi kesalahan.';
                    if (response.errors) {
                        errorMessage += '<ul>' + response.errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    errorContainer.removeClass('d-none').html(errorMessage);
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memperbarui profil.',
                    confirmButtonText: 'OK'
                });
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
        var errorContainer = $('#passwordErrors');

        // Show loading spinner and disable button
        submitButton.find('.spinner-border').removeClass('d-none');
        submitButton.prop('disabled', true);
        errorContainer.addClass('d-none').empty();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    // Close modal
                    $('#changePasswordModal').modal('hide');
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kata sandi berhasil diperbarui.',
                        confirmButtonText: 'OK'
                    });
                    // Reset form
                    form[0].reset();
                } else {
                    // Show error message with SweetAlert2
                    let errorMessage = response.message || 'Terjadi kesalahan.';
                    if (response.errors) {
                        errorMessage += '<ul>' + response.errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                    errorContainer.removeClass('d-none').html(errorMessage);
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memperbarui kata sandi.',
                    confirmButtonText: 'OK'
                });
            },
            complete: function () {
                submitButton.find('.spinner-border').addClass('d-none');
                submitButton.prop('disabled', false);
            }
        });
    });
});