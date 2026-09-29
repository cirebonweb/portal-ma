# Dokumentasi Portal MA

Dokumentasi ini menjelaskan status, struktur teknis, alur aplikasi, modul, database,
dan proses deployment Portal MA.

## Dokumentasi utama

- [Status Proyek](./01-status-proyek.md)
- [Arsitektur Aplikasi](./02-arsitektur-aplikasi.md)
- [Alur Aplikasi](./03-alur-aplikasi.md)
- [Panduan Pengguna](./04-panduan-pengguna.md) — diisi bertahap setelah CRUD diuji
- [Indeks Rujukan CRUD dan Domain](./05-log-job-ai.md) — peta menuju dokumentasi relevan
- [X] [Skema Bahan dan Material](./06-skema-bahan.md) — pemisahan `bahan_jenis`/`bahan`, stok bahan vs material
- `07-modul-dan-fitur.md` — akan dibuat bertahap
- `08-deployment.md` — akan dibuat menjelang persiapan production

## Rujukan modul

- [Panduan dokumentasi modul](./modul/README.md)
- [Nota dan POS](./modul/nota.md)
- [Produk dan harga](./modul/produk.md)
- [CRUD Master](./modul/master.md)
- [Bahan dan material](./modul/bahan.md)

Riwayat pekerjaan AI yang lama disimpan terpisah di
[`riwayat-ai/`](./riwayat-ai/); arsip tersebut bukan sumber aturan terkini dan
tidak perlu dibaca untuk pekerjaan rutin.

## Alur aplikasi

Dokumen alur terpisah dapat ditambahkan di folder `alur/` apabila penjelasan salah
satu proses membutuhkan aturan bisnis yang mendetail. Rujukan domain disimpan di
`modul/` dan dapat menautkan bagian alur terkait.

## Bahan konsep proyek baru

- [Paket konsep Digital Printing](./digital-printing/README.md) — dokumentasi
  mandiri untuk audit fitur, frontend, alur operasional, dan keputusan sebelum
  rancangan database proyek baru. Paket ini bukan spesifikasi Portal MA dan
  dapat dipindahkan utuh ke proyek `digital-printing`.

## Aturan pemeliharaan dokumentasi

- Jangan menghapus dokumen lama tanpa memastikan informasinya sudah dipindahkan atau
  memang sudah tidak diperlukan.
- Perbarui status proyek setelah satu pekerjaan logis selesai.
- Catat keputusan penting dan asumsi yang dapat memengaruhi implementasi.
- Gunakan dokumentasi sebagai referensi, tetapi tetap prioritaskan perilaku source code
  yang sudah diverifikasi.
- Kelompokkan dokumentasi berdasarkan domain dan alur terkait, bukan berdasarkan
  jumlah baris. Jaga indeks tetap ringkas dan arahkan pembaca hanya ke rujukan yang
  relevan.
