# Bahan dan Material

## Cakupan

Domain ini mencakup `bahan_jenis`, detail `bahan`, order bahan, stok bahan,
stok material, bahan sisa, dan bahan limbah.

## Rujukan kanonis

- [Skema Bahan dan Material](../06-skema-bahan.md) adalah rujukan struktur data,
  pemisahan jenis, serta perilaku stok.
- [Alur manajemen bahan §3–4](../03-alur-aplikasi.md#3-manajemen-bahan)
  menjelaskan alur order, pemakaian material, dan proses cetak.
- [Status proyek](../01-status-proyek.md) mencatat status implementasi serta
  pekerjaan bahan yang belum selesai atau belum diverifikasi.

## Aturan rujukan

- Bahan cetak dan material menggunakan domain `bahan_jenis`/`bahan`, tetapi
  perilaku stoknya berbeda. Jangan menganggap keduanya dapat diproses sama.
- Sebelum mengubah kategori, relasi bahan/material, order, atau pemakaian stok,
  baca skema dan bagian alur yang ditautkan di atas.
- `bahan_jenis` dikelompokkan sebagai Master pada indeks CRUD, tetapi aturan
  teknis dan bisnisnya tetap dirujuk dari dokumen domain ini dan skema.
