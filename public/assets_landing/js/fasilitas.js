document.addEventListener('DOMContentLoaded', function() {
    const facilityModal = document.getElementById('facilityDetailModal');

    if (facilityModal) {
        facilityModal.addEventListener('show.bs.modal', function (event) {
            // Tombol (kartu) yang memicu modal
            const button = event.relatedTarget;

            // Ekstrak informasi dari data-* attributes
            const name = button.getAttribute('data-name');
            const image = button.getAttribute('data-image');
            const detailsJson = button.getAttribute('data-details');
            // const details = JSON.parse(detailsJson);
            const details = JSON.parse(button.getAttribute('data-details') || '[]');

            // Perbarui konten modal
            const modalTitle = facilityModal.querySelector('.modal-title');
            const modalImage = facilityModal.querySelector('.modal-image');
            const modalDetailsList = facilityModal.querySelector('.modal-details-list');

            modalTitle.textContent = name;
            modalImage.src = image;
            modalImage.alt = 'Foto ' + name;

            // Kosongkan daftar detail sebelumnya dan isi dengan yang baru
            modalDetailsList.innerHTML = '';
            if (details && details.length > 0) {
                details.forEach(detailText => {
                    const li = document.createElement('li');
                    li.textContent = detailText;
                    modalDetailsList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'Tidak ada detail informasi tambahan.';
                modalDetailsList.appendChild(li);
            }
        });
    }
});
