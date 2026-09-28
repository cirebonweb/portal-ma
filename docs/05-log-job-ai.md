# Indeks Rujukan CRUD dan Domain

Dokumen ini memetakan CRUD, tabel, dan area aplikasi ke dokumentasi rujukan.
Dokumen modul menjelaskan kondisi serta aturan yang berlaku saat ini; dokumen
arsip hanya menyimpan konteks historis dan tidak menjadi sumber aturan.

## Pemetaan

| CRUD, tabel, atau area | Rujukan utama | Rujukan terkait |
|---|---|---|
| Nota, Nota Isi, Nota Bayar (`nota`, `nota_isi`, `nota_bayar`) | [Nota dan POS](./modul/nota.md) | [Alur aplikasi §1–2](./03-alur-aplikasi.md) |
| Produk dan harga (`produk`, `harga_tipe`, `harga_khusus`) | [Produk dan harga](./modul/produk.md) | [Alur aplikasi §1](./03-alur-aplikasi.md) |
| CRUD Master (`konsumen_tipe`, `mesin_tipe`, `harga_tipe`, `harga_khusus`, `finishing`) | [CRUD Master](./modul/master.md) | [Arsitektur aplikasi](./02-arsitektur-aplikasi.md), [Produk dan Harga](./modul/produk.md) |
| Bahan dan material (`bahan_jenis`, `bahan`, `bahan_order`, `bahan_stok`, `material_stok`, `bahan_sisa`, `bahan_limbah`) | [Bahan dan material](./modul/bahan.md) | [Skema bahan](./06-skema-bahan.md), [Alur aplikasi §3–4](./03-alur-aplikasi.md) |
| Data Konsumen, Supplier, dan Mesin | [Status proyek](./01-status-proyek.md) | [Arsitektur aplikasi](./02-arsitektur-aplikasi.md); belum ada aturan domain terpisah |
| Produksi, pembayaran umum, laporan, dan dashboard | [Alur aplikasi §4–6](./03-alur-aplikasi.md) | [Status proyek](./01-status-proyek.md) |
| Arsitektur, pola teknis, dan aturan lintas-modul | [Arsitektur aplikasi](./02-arsitektur-aplikasi.md) | [Alur aplikasi](./03-alur-aplikasi.md) |
| Status pengerjaan, pekerjaan berikutnya, dan hal yang belum diverifikasi | [Status proyek](./01-status-proyek.md) | — |
| Riwayat pekerjaan AI 2026 | [Arsip riwayat](./riwayat-ai/2026.md) | Buka hanya jika konteks historis diperlukan |

`konsumen_tipe`, `mesin_tipe`, dan `finishing` adalah kelompok Master yang
memiliki pola administrasi serupa. Master yang memiliki aturan domain khusus
tetap dirujuk juga dari dokumen domainnya: `bahan_jenis` pada Bahan dan Material,
serta `harga_tipe`/`harga_khusus` pada Produk dan Harga.

## Aturan pemeliharaan

- Kelompokkan dokumentasi berdasarkan domain dan keterkaitan alur, bukan jumlah
  baris atau satu file untuk setiap tabel.
- Saat mengubah perilaku atau aturan, perbarui dokumen rujukan utama domain
  terlebih dahulu. Perbarui indeks hanya jika pemetaan atau cakupannya berubah.
- Simpan aturan yang berlaku saat ini dan keputusan penting; hindari jurnal
  panjang atas setiap perubahan kecil.
- Pisahkan dokumen domain lebih lanjut hanya ketika tanggung jawab atau alurnya
  sudah cukup berbeda sehingga satu dokumen sulit dinavigasi.
- Konsultasikan arsip historis hanya untuk memahami alasan atau konteks perubahan
  terdahulu. Jika aturan historis masih berlaku, pindahkan atau rangkum ke dokumen
  rujukan utama agar tidak perlu membaca arsip.
