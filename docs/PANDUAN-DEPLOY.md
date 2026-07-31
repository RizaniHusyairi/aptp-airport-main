# Panduan Update ke Server — Website Bandara A.P.T. Pranoto

Dokumen ini adalah runbook untuk menerapkan pekerjaan terbaru ke server produksi.

> **Terakhir diperbarui:** 30 Juli 2026
> **Branch:** `main` · **Remote:** `github.com/RizaniHusyairi/aptp-airport-main`

---

## 1. Status pekerjaan saat ini

Pekerjaan terbagi dua tahap dengan status berbeda. **Periksa ini lebih dulu** sebelum menjalankan langkah apa pun.

### Tahap 1 — sudah di-commit dan ter-push ke GitHub

Commit `bc3018e`. Sudah ada di `origin/main`, tetapi **belum tentu sudah dijalankan di server** (migration dan seeder-nya belum tentu jalan).

| Fitur | Yang termasuk |
|---|---|
| **Standar Pelayanan** | Modul CRUD + halaman `/informasi-publik/standar-pelayanan` |
| **Tautan SKM** | Pengaturan SKM + tautan di beranda, footer, halaman Standar Pelayanan |
| **Tautan Terkait** | Modul CRUD + halaman `/tautan-terkait` + menu navbar (SIPPN, SP4N-LAPOR!, SIK, e-Kinerja) |
| **FAQ** | Modul CRUD + halaman `/faq` + section beranda + blok per layanan |

### Tahap 2 — belum di-commit (masih di komputer lokal)

| Fitur | Berkas |
|---|---|
| **Papan Jadwal** Keberangkatan & Kedatangan | 2 blade + 1 partial + `flight-board.js` + `flight-particles.js` + penyempurnaan `flight-board.css` |
| **Profil Bandara** | 1 blade + `profil-modern.css` |

Tahap 2 **tidak** memerlukan migration maupun seeder — hanya berkas tampilan.

### Ringkasan dampak teknis

- **3 migration** (tahap 1): `service_standards`, `external_links`, `faqs`
- **4 seeder** (tahap 1): semuanya `updateOrCreate`, aman dijalankan berulang
- **Tidak ada variabel `.env` baru** pada kedua tahap
- **Tidak perlu `npm run build`** — semua CSS/JS baru berada di `public/assets_landing/`, tidak disentuh Laravel Mix

---

## 2. Prasyarat: akses server ke repo privat

**Cukup dikerjakan sekali.** Lewati bagian ini bila server sudah bisa `git pull` tanpa diminta kata sandi.

Repo ini privat, sehingga server tidak dapat menariknya tanpa kredensial. Cara yang dipakai adalah **Deploy Key (SSH)**, dipilih karena:

- **Read-only** — server hanya bisa `pull`, tidak bisa `push`. Bila server diretas, isi repo tetap aman.
- **Terbatas pada satu repo** — berbeda dengan token akun pribadi yang bisa mengakses seluruh repo Anda.
- **Tidak terikat akun perorangan** — deploy tetap berjalan meski ada pergantian staf atau ganti perangkat.
- Tanpa masa berlaku yang harus diperpanjang berkala.

### 2.1 Buat kunci di server

Login sebagai user yang menjalankan deploy — **bukan root**.

```bash
ssh-keygen -t ed25519 -C "deploy-aptp-server" -f ~/.ssh/id_ed25519_aptp -N ""
chmod 700 ~/.ssh
chmod 600 ~/.ssh/id_ed25519_aptp
cat ~/.ssh/id_ed25519_aptp.pub
```

Salin seluruh keluaran perintah terakhir (diawali `ssh-ed25519 ...`).

### 2.2 Daftarkan di GitHub

Buka `github.com/RizaniHusyairi/aptp-airport-main` → **Settings** → **Deploy keys** → **Add deploy key**

| Kolom | Isi |
|---|---|
| Title | `Server Produksi APTP` |
| Key | tempel isi berkas `.pub` tadi |
| Allow write access | **jangan dicentang** |

### 2.3 Arahkan SSH ke kunci tersebut

Buat atau sunting `~/.ssh/config`:

```
Host github.com
  HostName github.com
  User git
  IdentityFile ~/.ssh/id_ed25519_aptp
  IdentitiesOnly yes
```

```bash
chmod 600 ~/.ssh/config
ssh-keyscan github.com >> ~/.ssh/known_hosts    # agar tidak muncul prompt konfirmasi
ssh -T git@github.com                           # harus menyebut nama repo ini
```

> Bila server yang sama juga menarik repo privat lain, ganti `Host github.com` menjadi alias seperti `Host github.com-aptp` (tetap dengan `HostName github.com`), lalu pakai alias itu pada URL remote di langkah berikut. Satu deploy key hanya berlaku untuk satu repo.

### 2.4 Ubah remote dari HTTPS ke SSH

```bash
cd /path/ke/aptp-airport-main
git remote set-url origin git@github.com:RizaniHusyairi/aptp-airport-main.git
git remote -v
git pull origin main
```

### Alternatif bila hosting tidak mendukung kunci SSH

Untuk cPanel atau shared hosting, gunakan **fine-grained personal access token**:

1. GitHub → **Settings** akun → **Developer settings** → **Personal access tokens** → **Fine-grained tokens**
2. Repository access: **Only select repositories** → pilih repo ini saja
3. Permissions: **Contents → Read-only**
4. Tetapkan masa berlaku dan catat tanggal kedaluwarsanya

```bash
git config credential.helper store
git pull origin main      # isi username, lalu token sebagai password
```

> **Jangan pernah menempelkan token ke dalam URL remote** (`https://TOKEN@github.com/...`). Token akan tersimpan di `.git/config` dan ikut terbawa backup maupun keluaran `git remote -v`. Dengan `credential.helper store` pun token tersimpan sebagai teks polos di `~/.git-credentials`, jadi batasi izinnya seketat mungkin dan perbarui saat kedaluwarsa.

### Hal yang mudah terlewat

1. **`.env` tidak ikut ter-pull** — berkas itu masuk `.gitignore`. Di server, `.env` dibuat manual sekali dan tidak akan pernah tertimpa `git pull`. Pastikan `BANDARA_API_URL` dan `BANDARA_API_TIMEOUT` terisi; tanpa keduanya, beranda dan papan jadwal penerbangan akan error.
2. **`vendor/` juga tidak ikut** — itulah sebabnya `composer install` ada di langkah 5.
3. **Jalankan git sebagai user yang tepat.** Bila `git pull` dijalankan sebagai root, berkas hasilnya bisa dimiliki root dan tidak terbaca web server. Gunakan user deploy biasa, lalu pastikan izin folder sesuai langkah 9.

---

## 3. Commit & push Tahap 2 dari komputer lokal

Jalankan dari `c:\laragon\www\aptp-airport-main`:

```bash
git status                # pastikan tidak ada berkas asing
git add -A
git commit -m "feat: rombak halaman jadwal penerbangan dan profil bandara"
git push origin main
```

Peringatan `LF will be replaced by CRLF` dari Git **aman diabaikan** — itu hanya normalisasi akhir baris di Windows.

Bila alur kerja tim Anda memakai Pull Request, buat branch dulu:

```bash
git switch -c feat/jadwal-dan-profil
git add -A && git commit -m "feat: rombak halaman jadwal penerbangan dan profil bandara"
git push -u origin feat/jadwal-dan-profil
```

---

## 4. Backup database server — jangan dilewati

Wajib dilakukan sebelum migration apa pun.

```bash
mysqldump -u USER -p NAMA_DB > backup-sebelum-deploy-$(date +%F).sql
```

Ini satu-satunya jaring pengaman bila migration bermasalah.

---

## 5. Tarik kode di server

```bash
cd /path/ke/aptp-airport-main

php artisan down                                    # mode maintenance
git pull origin main
composer install --no-dev --optimize-autoloader
```

`composer install` diperlukan karena `app/helpers.php` terdaftar di autoload Composer — di sana ada dua fungsi baru, `skmSetting()` dan `externalLinks()`.

---

## 6. Jalankan migration

```bash
php artisan migrate --force
```

`--force` wajib di production; tanpa itu Laravel menolak berjalan. Hanya 3 migration baru yang akan dijalankan, sisanya dilewati.

> **Prasyarat:** tabel `faqs` memiliki foreign key ke `services`. Pastikan tabel `services` sudah ada dan terisi di server.

---

## 7. Jalankan seeder

```bash
php artisan db:seed --class=SkmSettingSeeder --force
php artisan db:seed --class=ExternalLinkSeeder --force
php artisan db:seed --class=FaqSeeder --force
```

Ketiganya memakai `updateOrCreate` — aman dijalankan berulang, tidak menghapus maupun menggandakan data yang sudah diisi admin.

### Seeder opsional

```bash
php artisan db:seed --class=ServiceStandardSeeder --force
```

Seeder ini hanya berisi **3 data contoh** dengan tautan Google Drive palsu (`example-...`). **Lewati saja** bila Anda tidak ingin data contoh tampil ke publik, lalu isi dokumen asli langsung dari dashboard.

> **Catatan:** versi awal seeder ini memakai `truncate()` yang akan menghapus dokumen yang sudah diunggah admin bila dijalankan dua kali. Sudah diperbaiki menjadi `updateOrCreate`. Pastikan Anda memakai versi terbaru dari `main`.

### Catatan FaqSeeder

`FaqSeeder` mengaitkan sebagian pertanyaan ke layanan berdasarkan **slug** (`tenant`, `slot-charter`, `field-trip`, `pengiklanan`, `informasi-publik`). Layanan yang tidak ditemukan cukup dilewati tanpa error. Karena di server tabel `services` terisi, kaitan itu akan otomatis terbentuk.

---

## 8. Bersihkan cache — paling sering terlewat

```bash
php artisan cache:clear      # WAJIB
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Kemudian, bila server memakai cache untuk performa:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Mengapa `cache:clear` krusial:** helper `skmSetting()` dan `externalLinks()` memakai `Cache::rememberForever`. Bila cache lama tidak dibersihkan, footer dan navbar bisa menampilkan data usang tanpa batas waktu.

**Mengapa `route:clear` krusial:** ada 4 route publik baru — `/informasi-publik/standar-pelayanan`, `/tautan-terkait`, `/faq`, dan route API yang sudah ada tetap dipakai papan jadwal.

**Mengapa `view:clear` krusial:** seluruh halaman tampilan pada kedua tahap berubah.

---

## 9. Izin folder unggahan

```bash
ls -ld public/uploads
chmod -R 775 public/uploads storage bootstrap/cache
chown -R www-data:www-data public/uploads storage bootstrap/cache   # sesuaikan user web server
```

Subfolder `public/uploads/standar-pelayanan/` dan `public/uploads/tautan-terkait/` dibuat otomatis saat unggahan pertama — tidak perlu dibuat manual.

`public/uploads/*` sudah masuk `.gitignore`, jadi berkas unggahan di server **tidak akan tertimpa** oleh `git pull`.

```bash
php artisan up      # keluar dari mode maintenance
```

---

## 10. Verifikasi setelah deploy

Buka sebagai pengunjung anonim (mode incognito).

### Halaman publik

| Halaman | Yang harus terlihat |
|---|---|
| `/faq` | Akordeon, kotak pencarian, 4 pil kategori, CTA "Masih Ada Pertanyaan?" |
| `/tautan-terkait` | Kartu SIPPN, SP4N-LAPOR!, SIK, e-Kinerja dalam 2 kelompok |
| `/informasi-publik/standar-pelayanan` | Accordion per jenis dokumen + CTA SKM |
| `/informasi-publik/profil-bandara` | Hero partikel, navigasi menempel, 5 kartu bagian, panel Visi |
| `/keberangkatan` | Hero partikel, papan jadwal berisi data, kolom Gate/Konter |
| `/kedatangan` | Hero partikel, papan jadwal berisi data (tanpa kolom Gate) |
| `/` | Banner SKM, section Tautan Terkait, section FAQ |
| `/sitemap.xml` | Memuat `/faq`, `/tautan-terkait`, `/informasi-publik/standar-pelayanan` |

### Navigasi & footer

- **Navbar:** PPID → Standar Pelayanan · Informasi → FAQ · menu **Tautan Terkait**
- **Footer** (semua halaman): kolom "Layanan Publik" berisi SKM, Standar Pelayanan, FAQ, dan 4 portal

### Dashboard

Login, lalu pastikan 4 menu sidebar baru terbuka tanpa error:

- **Standar Pelayanan**
- **Tautan Terkait**
- **FAQ**
- **Pengaturan SKM**

### Yang paling perlu diperhatikan

1. **Grid footer di layar laptop dan tablet.** Footer berubah dari 3 menjadi 4 kolom — ini satu-satunya perubahan layout pada komponen yang tampil di semua halaman.
2. **Papan jadwal benar-benar terisi data.** Bila kosong atau muncul "Gagal memuat data", periksa `BANDARA_API_URL` dan `BANDARA_API_TIMEOUT` di `.env` server.
3. **Interaksi FAQ.** Gulir ke bawah tanpa melihat kartu terakhir, lalu cari kata yang hanya cocok dengan kartu itu — kartunya harus tampil penuh, tidak transparan.
4. **Animasi partikel** di tiga halaman (keberangkatan, kedatangan, profil) berjalan dan tidak membuat halaman berat.

---

## 11. Yang harus diisi admin setelah deploy

1. **4 pertanyaan FAQ bertanda `[PERIKSA]`** masih nonaktif karena jawabannya memerlukan data yang belum dipastikan. Buka **Dashboard → FAQ**, koreksi jawabannya, lalu nyalakan switch "Tampilkan di website":
   - Berapa lama sebelum keberangkatan harus tiba di bandara
   - Jam operasional terminal penumpang
   - Fasilitas untuk penyandang disabilitas
   - Tarif parkir kendaraan
2. **Dokumen Standar Pelayanan asli** — unggah PDF atau tempel tautan lewat **Dashboard → Standar Pelayanan**, dan hapus data contoh bila seeder-nya dijalankan.
3. **Verifikasi tautan SKM** di **Dashboard → Pengaturan SKM** sudah benar: `https://skm.dephub.go.id/ly/ApfkINxw`
4. **Empat kartu fakta di hero Profil Bandara** (kode IATA, tanggal operasi, pola pengelolaan, klasifikasi) ditulis langsung di blade, bukan dari database. Bila datanya berubah, sunting di bagian `.pf-facts` pada `resources/views/landing-menu/informasi-publik/profil-bandara/index.blade.php`.

---

## 12. Rollback bila terjadi masalah

```bash
php artisan down

git reset --hard <commit-sebelumnya>
php artisan migrate:rollback --step=3

# Bila data perlu dipulihkan
mysql -u USER -p NAMA_DB < backup-sebelum-deploy-YYYY-MM-DD.sql

php artisan cache:clear && php artisan config:clear
php artisan route:clear && php artisan view:clear
php artisan up
```

> **Peringatan:** `migrate:rollback` akan **menghapus** tabel `service_standards`, `external_links`, dan `faqs` beserta seluruh isinya. Karena itu backup di langkah 4 penting.

---

## 13. Lampiran — inventaris berkas

### Berkas baru

**Backend**

```
app/Models/{ServiceStandard,ExternalLink,Faq}.php
app/Http/Controllers/Admin/{ServiceStandard,ExternalLink,Faq,SkmSetting}Controller.php
database/migrations/2026_07_30_000000_create_service_standards_table.php
database/migrations/2026_07_30_100000_create_external_links_table.php
database/migrations/2026_07_30_200000_create_faqs_table.php
database/seeders/{ServiceStandard,SkmSetting,ExternalLink,Faq}Seeder.php
```

**Tampilan**

```
resources/views/admin2/skm-settings/index.blade.php
resources/views/user_staff2/{standar-pelayanan,tautan-terkait,faq}/
resources/views/landing-menu/{faq,tautan-terkait}/index.blade.php
resources/views/landing-menu/informasi-publik/standar-pelayanan/index.blade.php
resources/views/landing-menu/partials/{faq-accordion,tautan-terkait-groups}.blade.php
resources/views/landing-menu/beranda/partials/flight-board.blade.php
```

**Aset**

```
public/assets_landing/css/{skm,tautan-terkait,faq,flight-board,profil-modern}.css
public/assets_landing/js/{faq,flight-board,flight-particles}.js
```

### Berkas yang diubah

```
app/helpers.php                                          (2 helper ter-cache)
routes/web.php                                           (3 resource + 3 route publik)
app/Http/Controllers/LandingPageController.php           (3 method + data FAQ)
app/Http/Controllers/SitemapController.php               (3 URL)
app/Providers/ViewServiceProvider.php                    (menu navbar)
resources/views/layouts_landing/header.blade.php         (rel="noopener")
resources/views/layouts_landing/footer.blade.php         (kolom Layanan Publik)
resources/views/layouts-V2/sidebars/admin.blade.php      (4 item menu)
resources/views/landing-menu/beranda/index.blade.php     (3 section baru)
resources/views/landing-menu/layanan/index.blade.php     (blok FAQ)
resources/views/landing-menu/beranda/keberangkatan.blade.php
resources/views/landing-menu/beranda/kedatangan.blade.php
resources/views/landing-menu/informasi-publik/profil-bandara/index.blade.php
```

### Berkas lama yang kini tidak terpakai

Boleh dihapus kapan saja, tidak lagi dirujuk berkas blade mana pun:

```
public/assets_landing/css/{keberangkatan,kedatangan}.css
public/assets_landing/js/{keberangkatan,kedatangan}.js
```

---

## 14. Catatan yang belum terverifikasi

Hal-hal berikut **belum** diuji dan sebaiknya diperiksa langsung setelah deploy:

- **Login dashboard lewat sesi nyata.** Tabel `users` pada database pengembangan lokal kosong, sehingga halaman admin hanya diverifikasi pada tingkat render, bukan melalui browser dengan login.
- **Hasil visual seluruh halaman baru.** Markup, kontrak data, dan logika sudah diverifikasi; tampilan sesungguhnya di browser belum.
- **Perilaku interaktif** — pencarian dan filter FAQ, navigasi menempel di Profil Bandara, serta animasi partikel di tiga halaman.
- **Tampilan di perangkat mobile** untuk semua halaman baru.

---

## 15. Perintah ringkas (setelah backup)

Untuk deploy rutin, seluruh langkah dapat dijalankan berurutan:

```bash
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=SkmSettingSeeder --force
php artisan db:seed --class=ExternalLinkSeeder --force
php artisan db:seed --class=FaqSeeder --force
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan up
```
