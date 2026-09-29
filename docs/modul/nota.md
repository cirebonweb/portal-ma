# Nota dan POS

## Cakupan

Dokumen ini menjadi rujukan domain untuk nota penjualan, rincian nota, dan
pembayaran: tabel `nota`, `nota_isi`, `nota_bayar` serta alur kerja POS terkait.

## Rujukan kanonis

- [Alur nota dan POS](../03-alur-aplikasi.md#1-nota-penjualanpos) menjelaskan
  kategori produk, status rincian, serta aturan perhitungan di §1.1–1.2.
- [Pembayaran nota](../03-alur-aplikasi.md#2-pembayaran-nota) menjelaskan
  pencatatan pembayaran dan aturan kembalian.
- [Status proyek](../01-status-proyek.md) mencatat status modul dan pekerjaan
  yang belum diverifikasi.

## Bentuk alur halaman

- Daftar nota berada pada `/nota`.
- Detail nota menjadi tempat kerja untuk header, rincian, status produksi, dan
  pembayaran satu nota pada `/nota/detail/<id>`.
- `/nota/bayar` menampilkan daftar seluruh pembayaran; pembayaran untuk nota
  tertentu dimulai dari halaman kerja nota.
- Halaman detail ditangani oleh `NotaDetail`; operasi header, rincian, dan
  pembayaran tetap menjadi tanggung jawab `Nota`, `NotaIsi`, dan `NotaBayar`.
  Endpoint rincian dan pembayaran berada di bawah `/nota/isi/*` dan
  `/nota/bayar/*`; keduanya bukan URL halaman detail.
- Header nota dapat diedit langsung dari halaman detail; penyimpanan tetap
  menggunakan endpoint `Nota` (`/nota/getid` dan `/nota/simpan`).

Aturan perhitungan, transisi status, dan kategori produk harus mengikuti alur
kanonis di atas. Perbarui bagian tersebut ketika perilaku berubah; jangan
membuat salinan rinci yang dapat berbeda dari alur aplikasi.

## Catatan implementasi penting

- `harga_min` pada rincian nota disimpan di `nota_isi` dan dipulihkan saat edit;
  item baru dimulai dengan pilihan Tidak. Toggle hanya berlaku untuk rumus
  perkalian luas.
- Periksa bagian pekerjaan belum diverifikasi pada status proyek sebelum
  mengubah kalkulasi, trigger, pembayaran, atau proses cetak.
