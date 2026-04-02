@extends('layouts-V2.master-layouts-v2')
@section('title', 'Tambah Logbook untuk ' . $inventory->name)

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Catatan Logbook</h3>
                <p class="text-subtitle text-muted">Untuk alat: <strong>{{ $inventory->name }}</strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label' => 'Dashboard', 'url' => route('staff.dashboard.index')],
                    ['label' => 'Inventaris', 'url' => route('staff.inventories.index')],
                    ['label' => 'Detail', 'url' => route('staff.inventories.show', $inventory->id)],
                    ['label' => 'Tambah Logbook', 'active' => true]
                ]" />
            </div>
        </div>
    </div>
</div>
<section class="section">
    <div class="card">
        <div class="card-header"><h5 class="card-title">Formulir Logbook</h5></div>
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
            
            <form action="{{ route('staff.inventories.storeLogbook', $inventory->id) }}" method="POST" enctype="multipart/form-data" id="logbook-form">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="log_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('log_date') is-invalid @enderror" id="log_date" name="log_date" value="{{ old('log_date', now()->format('Y-m-d')) }}" required>
                        @error('log_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="schedule_time" class="form-label">Jadwal (Jam) <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('schedule_time') is-invalid @enderror" id="schedule_time" name="schedule_time" value="{{ old('schedule_time', now()->format('H:i')) }}" required>
                        @error('schedule_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Catatan / Tindakan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" required>{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label for="documentation" class="form-label">Dokumentasi (Foto)</label>
                        <input class="form-control @error('documentation.*') is-invalid @enderror" type="file" id="documentation" name="documentation[]" multiple accept="image/*" capture="environment">
                        <small class="text-muted">Di perangkat yang mendukung, form ini bisa langsung membuka kamera belakang. Foto akan dikompres otomatis sebelum upload agar tetap di bawah 2MB per file.</small>
                        <div id="documentation-status" class="form-text"></div>
                        <div id="documentation-preview" class="row g-3 mt-1"></div>
                        @error('documentation.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('staff.inventories.show', $inventory->id) }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Logbook</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts_admin')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('documentation');
    const form = document.getElementById('logbook-form');
    const submitButton = form?.querySelector('button[type="submit"]');
    const statusText = document.getElementById('documentation-status');
    const previewContainer = document.getElementById('documentation-preview');
    const maxFileSize = 2 * 1024 * 1024;
    const targetFileSize = 1.8 * 1024 * 1024;
    const maxDimension = 1600;

    if (!fileInput || !form || !submitButton || !statusText || !previewContainer) {
        return;
    }

    let compressionPromise = Promise.resolve();
    let isCompressing = false;

    const setStatus = (message, type = 'muted') => {
        statusText.className = type ? `form-text text-${type}` : 'form-text';
        statusText.textContent = message;
    };

    const readFileAsDataUrl = (file) => new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });

    const renderPreviews = (files) => {
        previewContainer.innerHTML = '';

        files.forEach((file, index) => {
            const column = document.createElement('div');
            column.className = 'col-6 col-md-4 col-lg-3';

            const card = document.createElement('div');
            card.className = 'card shadow-sm h-100 mb-0';

            const image = document.createElement('img');
            image.className = 'card-img-top';
            image.alt = `Preview dokumentasi ${index + 1}`;
            image.style.height = '180px';
            image.style.objectFit = 'cover';
            image.src = URL.createObjectURL(file);
            image.onload = () => URL.revokeObjectURL(image.src);

            const body = document.createElement('div');
            body.className = 'card-body p-2';

            const sizeInfo = document.createElement('small');
            sizeInfo.className = 'text-muted d-block';
            sizeInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;

            body.appendChild(sizeInfo);
            card.appendChild(image);
            card.appendChild(body);
            column.appendChild(card);
            previewContainer.appendChild(column);
        });
    };

    const loadImage = (src) => new Promise((resolve, reject) => {
        const image = new Image();
        image.onload = () => resolve(image);
        image.onerror = reject;
        image.src = src;
    });

    const canvasToBlob = (canvas, quality) => new Promise((resolve, reject) => {
        canvas.toBlob((blob) => {
            if (blob) {
                resolve(blob);
            } else {
                reject(new Error('Gagal membuat file hasil kompresi.'));
            }
        }, 'image/jpeg', quality);
    });

    const compressImage = async (file) => {
        if (!file.type.startsWith('image/')) {
            return file;
        }

        const dataUrl = await readFileAsDataUrl(file);
        const image = await loadImage(dataUrl);
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        let width = image.width;
        let height = image.height;

        if (width > height && width > maxDimension) {
            height = Math.round((height * maxDimension) / width);
            width = maxDimension;
        } else if (height >= width && height > maxDimension) {
            width = Math.round((width * maxDimension) / height);
            height = maxDimension;
        }

        canvas.width = width;
        canvas.height = height;
        context.drawImage(image, 0, 0, width, height);

        let quality = 0.85;
        let blob = await canvasToBlob(canvas, quality);

        while (blob.size > targetFileSize && quality > 0.4) {
            quality -= 0.1;
            blob = await canvasToBlob(canvas, quality);
        }

        if (blob.size > maxFileSize) {
            let scaledWidth = width;
            let scaledHeight = height;

            while (blob.size > maxFileSize && scaledWidth > 800 && scaledHeight > 800) {
                scaledWidth = Math.round(scaledWidth * 0.85);
                scaledHeight = Math.round(scaledHeight * 0.85);
                canvas.width = scaledWidth;
                canvas.height = scaledHeight;
                context.drawImage(image, 0, 0, scaledWidth, scaledHeight);
                blob = await canvasToBlob(canvas, Math.max(quality, 0.4));
            }
        }

        const fileName = file.name.replace(/\.[^.]+$/, '') + '.jpg';
        return new File([blob], fileName, { type: 'image/jpeg', lastModified: Date.now() });
    };

    fileInput.addEventListener('change', function () {
        const selectedFiles = Array.from(fileInput.files || []);

        if (!selectedFiles.length) {
            setStatus('', '');
            previewContainer.innerHTML = '';
            return;
        }

        isCompressing = true;
        submitButton.disabled = true;
        setStatus('Menyiapkan dan mengompres foto...', 'muted');

        compressionPromise = Promise.all(selectedFiles.map(async (file) => {
            try {
                return await compressImage(file);
            } catch (error) {
                return file;
            }
        })).then((compressedFiles) => {
            const dataTransfer = new DataTransfer();
            compressedFiles.forEach((file) => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;

            const oversized = compressedFiles.filter((file) => file.size > maxFileSize);
            if (oversized.length > 0) {
                setStatus('Sebagian foto masih di atas 2MB. Coba ambil ulang foto dengan resolusi lebih rendah.', 'danger');
            } else {
                setStatus(`${compressedFiles.length} foto siap diupload dan sudah dikompres otomatis.`, 'success');
            }

            renderPreviews(compressedFiles);
        }).catch(() => {
            setStatus('Kompresi foto gagal dijalankan. File asli tetap akan digunakan.', 'warning');
            renderPreviews(selectedFiles);
        }).finally(() => {
            isCompressing = false;
            submitButton.disabled = false;
        });
    });

    form.addEventListener('submit', async function (event) {
        if (!fileInput.files.length || !isCompressing) {
            return;
        }

        event.preventDefault();
        submitButton.disabled = true;
        setStatus('Memastikan kompresi foto selesai sebelum upload...', 'muted');

        try {
            await compressionPromise;
            form.submit();
        } finally {
            submitButton.disabled = false;
        }
    });
});
</script>
@endsection

