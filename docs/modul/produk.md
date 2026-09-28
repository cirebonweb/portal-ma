# Produk dan Harga

## Cakupan

Rujukan domain produk dan penentuan harga: CRUD `produk`, `harga_tipe`, serta
`harga_khusus`. Tabel harga merupakan data Master, tetapi dikelompokkan di sini
karena penggunaannya terkait langsung dengan pemilihan produk dan transaksi.

## Rujukan kanonis

- [Alur Nota dan POS §1.1](../03-alur-aplikasi.md#11-alur-berdasarkan-kategori-produk)
  menjelaskan perlakuan produk berdasarkan kategori.
- [Aturan perhitungan rincian nota §1.2](../03-alur-aplikasi.md#12-aturan-perhitungan-rincian-nota)
  menjelaskan hubungan rumus produk dengan kalkulasi nota.
- [Skema Bahan dan Material](../06-skema-bahan.md) menjelaskan relasi produk
  dengan jenis bahan dan material.
- [Status proyek](../01-status-proyek.md) mencatat status pengerjaan dan hal
  yang belum diverifikasi.

## Aturan domain yang perlu dipertahankan

- Kategori produk menentukan apakah item perlu masuk antrean produksi; lihat
  tabel transisi status di alur Nota dan POS.
- Jika produk memakai `bahan_jenis_id`, rumus produk mengikuti rumus jenis
  bahan. Produk tanpa bahan dapat menentukan rumusnya sendiri.
- Produk paket dapat menautkan `material_jenis_id` selain `bahan_jenis_id`;
  aturan struktur lengkap berada pada dokumen skema bahan.
- Perubahan daftar harga atau perilaku harga khusus perlu diperiksa pada
  pemilihan harga di nota, bukan hanya pada form CRUD produk.
