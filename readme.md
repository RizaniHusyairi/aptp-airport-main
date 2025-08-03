# Sistem Informasi dan Layanan Bandara APT Pranoto

## Persyaratan Server
- PHP 8.1 atau lebih tinggi
- Composer 2.x
- Node.js & NPM
- Database (MySQL)
- Ekstensi PHP: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML

## Panduan Instalasi

1.  **Clone Repositori**
    ```
    git clone [https://github.com/NAMA_USER_ANDA/NAMA_REPO_ANDA.git](https://github.com/NAMA_USER_ANDA/NAMA_REPO_ANDA.git)
    cd NAMA_REPO_ANDA
    ```

2.  **Konfigurasi Environment**
    - Salin file `.env.example` menjadi `.env`:
      ```
      cp .env.example .env
      ```
    - Buka file `.env` dan isi semua variabel yang kosong (seperti `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `GEMINI_API_KEY`, dll) sesuai dengan konfigurasi server.

3.  **Instalasi Dependensi**
    ```
    composer install --no-dev
    npm install
    npm run build
    ```

4.  **Setup Aplikasi Laravel**
    - Generate kunci aplikasi:
      ```
      php artisan key:generate
      ```
    - Jalankan migrasi database (ini akan membuat semua tabel):
      ```
      php artisan migrate --seed
      ```
    - Buat symlink untuk storage:
      ```
      php artisan storage:link
      ```

5.  **Optimasi untuk Produksi**
    ```
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

6.  **Setup Queue Worker**
    - Pastikan Supervisor (atau manajer proses lainnya) dikonfigurasi untuk menjalankan perintah berikut:
      ```
      php artisan queue:work --sleep=3 --tries=3
      ```

7.  **Jalankan Aplikasi**
    ```
    php artisan serve