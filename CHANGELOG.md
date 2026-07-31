# Changelog — aptpairport.id

Perubahan penting pada situs **Bandara APT Pranoto Samarinda** (aptpairport.id).

Formatnya mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
penomorannya mengikuti [Semantic Versioning](https://semver.org/lang/id/).

---

## ⚠️ Angka yang BUKAN versi produk

Repositori ini memuat dua angka yang mudah tertukar dengan versi produk:

| Tempat | Nilai | Sebenarnya apa |
|---|---|---|
| `package.json` → `"version"` | `3.3.1` | Versi **template admin Skote** dari vendor. Jangan dinaikkan, dibaca, atau dikutip sebagai versi situs. |
| `resources/views/layouts-V2/` | — | Nama **keluarga layout Blade internal** (perombakan tampilan dari `layouts/`). Tidak ada hubungannya dengan versi produk. |

Versi produk situs ini hidup di tepat tiga tempat:
`.env` (`APP_VERSION`), berkas ini, dan tag git.

---

## Hubungan dengan AIAIS

Situs ini adalah **generasi pertama** portal Bandara APT Pranoto. Penggantinya,
**AIAIS** (repositori terpisah, Next.js + Laravel), memakai seri **2.x** dan
akan menggantikan situs ini setelah mencapai `2.0.0` final.

---

## [Unreleased]

Belum ada perubahan setelah v1.0.0.

---

## [1.0.0] - 2026-08-01

Rilis pertama yang diberi nomor versi.

Tag ini **retroaktif**: ia menandai kondisi situs produksi pada tanggal
tersebut. Riwayat commit sebelum titik ini tidak pernah diberi versi dan
sengaja tidak dirinci di sini — merangkai ulang riwayat rilis yang tidak pernah
ada justru akan menyesatkan.

Nilainya sebagai titik acuan: nama untuk melakukan rollback, batas agar
perbaikan berikutnya bisa diberi nomor `1.0.1` / `1.1.0`, dan jangkar konkret
bagi pernyataan "AIAIS 2.0.0 menggantikan versi ini".

### Added

- `APP_VERSION` pada `.env` dan `config('app.version')` sebagai sumber versi produk.
- Versi produk ditampilkan di footer halaman publik.
- Berkas CHANGELOG ini.
