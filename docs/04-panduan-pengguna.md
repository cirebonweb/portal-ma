# Panduan Pengguna

Dokumen ini ditujukan untuk pengguna aplikasi Portal MA. Isinya akan dibuat
bertahap setelah setiap CRUD selesai diuji dan dinyatakan lancar oleh pengguna.

Panduan harus menjelaskan tindakan yang benar-benar tersedia di aplikasi final,
bukan rancangan yang belum diuji.

## Status penyusunan

| Modul | Status panduan | Keterangan |
|---|---|---|
| Master | Menunggu pengujian | CRUD sudah dinyatakan final oleh pengembang |
| Nota penjualan/POS | Belum dibuat | Menunggu alur dan CRUD final |
| Manajemen bahan | Belum dibuat | Menunggu pengujian order, stok, sisa, dan limbah |
| Proses cetak | Belum dibuat | Menunggu alur produksi final |
| Laporan keuangan | Belum dibuat | Menunggu alur pembayaran dan laporan final |
| Dashboard | Belum dibuat | Menunggu statistik dan grafik final |

## 1. Persiapan menggunakan aplikasi

Bagian ini akan menjelaskan:

- cara masuk ke aplikasi;
- role pengguna dan menu yang dapat diakses;
- cara keluar dari aplikasi;
- arti notifikasi, status, dan tombol aksi;
- aturan umum pengisian form.

## 2. Master

CRUD Master yang sudah dinyatakan final:

- Tipe Konsumen;
- Tipe Mesin;
- Tipe Harga;
- Harga Khusus;
- Finishing.

Setiap subbagian akan ditulis setelah pengujian pengguna selesai, dengan format:

### Nama menu

- Tujuan menu:
- Role yang dapat mengakses:
- Cara membuka menu:
- Cara menambah data:
- Cara melihat data:
- Cara mengubah data:
- Cara menghapus data:
- Validasi dan aturan khusus:
- Hasil pengujian:

## 3. Nota penjualan/POS

Bagian ini akan menjelaskan pembuatan nota, pengisian detail nota, pemilihan
harga, pembayaran, perubahan status, dan pengiriman nota ke proses cetak.

## 4. Manajemen bahan

Bagian ini akan menjelaskan data bahan, supplier, order bahan, penambahan stok,
stok aktif, bahan sisa, dan bahan limbah.

## 5. Proses cetak

Bagian ini akan menjelaskan antrean atau pencatatan cetak, pemilihan mesin,
pemakaian bahan, penyelesaian cetak, cetak ulang, dan pencatatan sisa atau limbah.

## 6. Laporan keuangan

Bagian ini akan menjelaskan pembayaran, rekap penerimaan, laporan, koreksi,
penguncian laporan, dan log perubahan.

## 7. Dashboard

Bagian ini akan menjelaskan statistik dan grafik yang benar-benar tersedia,
termasuk filter periode, arti setiap angka, serta role yang dapat melihatnya.

## 8. Catatan pengujian pengguna

Setiap modul yang sudah diuji dapat dicatat secara ringkas:

```markdown
### Nama modul — YYYY-MM-DD

- Penguji:
- Skenario:
- Hasil:
- Catatan:
```

## Aturan pemeliharaan

- Tulis panduan berdasarkan tampilan dan perilaku aplikasi yang sudah diuji.
- Perbarui bagian terkait jika nama menu, field, status, atau alur berubah.
- Jangan menganggap modul selesai hanya karena CRUD sudah dibuat; modul harus
  diuji dari sisi pengguna.
- Gunakan bahasa langkah demi langkah dan hindari istilah teknis jika tidak
  diperlukan oleh pengguna.
