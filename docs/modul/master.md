# CRUD Master

## Cakupan

Dokumen ini mengelompokkan CRUD Master yang dipakai lintas alur aplikasi:
`konsumen_tipe`, `mesin_tipe`, dan `finishing`.

Master yang memiliki aturan domain khusus ditautkan ke rujukan domainnya:

- `bahan_jenis` → [Bahan dan Material](./bahan.md) dan
  [Skema Bahan](../06-skema-bahan.md).
- `harga_tipe` dan `harga_khusus` → [Produk dan Harga](./produk.md).

## Rujukan kanonis

- [Arsitektur aplikasi](../02-arsitektur-aplikasi.md) menjelaskan area Master,
  pola modul, komponen internal, dan ketentuan teknis lintas-modul.
- [Status proyek](../01-status-proyek.md) mencatat status tiap CRUD.

Dokumen ini bukan pengganti source code untuk perilaku CRUD tertentu. Saat
mengubah satu master, periksa implementasi lengkap route, controller, model,
view, JavaScript, validasi, dan relasi yang relevan. Tambahkan aturan khusus di
sini hanya jika benar-benar berlaku bersama untuk kelompok Master.
