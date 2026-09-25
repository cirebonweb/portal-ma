# Dokumentasi Portal MA

Dokumentasi ini menjelaskan status, struktur teknis, alur aplikasi, modul, database,
dan proses deployment Portal MA.

## Dokumentasi utama

- [Status Proyek](./01-status-proyek.md)
- [Arsitektur Aplikasi](./02-arsitektur-aplikasi.md)
- [Alur Aplikasi](./03-alur-aplikasi.md)
- [Panduan Pengguna](./04-panduan-pengguna.md) — diisi bertahap setelah CRUD diuji
- [Log Pekerjaan AI](./05-log-job-ai.md)
- `06-skema-database.md` — akan dibuat setelah struktur database diverifikasi
- `07-modul-dan-fitur.md` — akan dibuat bertahap
- `08-deployment.md` — akan dibuat menjelang persiapan production

## Alur aplikasi

Dokumen alur terpisah dapat ditambahkan di folder `alur/` apabila penjelasan salah
satu proses sudah terlalu panjang atau membutuhkan aturan bisnis yang mendetail.

## Aturan pemeliharaan dokumentasi

- Jangan menghapus dokumen lama tanpa memastikan informasinya sudah dipindahkan atau
  memang sudah tidak diperlukan.
- Perbarui status proyek setelah satu pekerjaan logis selesai.
- Catat keputusan penting dan asumsi yang dapat memengaruhi implementasi.
- Gunakan dokumentasi sebagai referensi, tetapi tetap prioritaskan perilaku source code
  yang sudah diverifikasi.
