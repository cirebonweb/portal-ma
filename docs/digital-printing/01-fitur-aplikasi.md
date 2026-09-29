# Fitur Aplikasi

> Rancangan konsep. Cakupan ini adalah pengelompokan awal fitur, bukan
> keputusan skema database atau batas implementasi final.

## Peta fitur

| Fitur Utama | Cakupan | Detail |
|---|---|---|
| Penjualan / POS | Mengelola konsumen, memilih produk Cetak atau Jasa, menyusun pesanan dan rincian, menentukan harga/diskon, serta mengaitkan pembayaran dengan transaksi | [Alur Operasional](./03-alur-operasional.md) |
| Produksi | Mengubah kuantitas pesanan Cetak menjadi pekerjaan/batch; menggabungkan item dari beberapa nota; membagi satu item ke pekerjaan Internal dan Vendor; mengelola alat, progres, hasil, pemeriksaan, dan pembatalan | [Alur Operasional](./03-alur-operasional.md) |
| Persediaan | Mencatat penerimaan bahan/material, stok masuk, pemakaian aktual, bahan sisa, limbah, pemakaian ulang sisa, dan koreksi stok | [Audit Sebelum Database](./04-audit-sebelum-database.md) |
| Pemenuhan Pesanan | Menentukan kuantitas hasil yang siap dan mencatat penyerahan seluruh atau sebagian barang per item; Jasa tidak masuk pengambilan barang | [Alur Operasional](./03-alur-operasional.md) |
| Keuangan | Memantau pembayaran nota, penerimaan tunai/transfer, koreksi/pengembalian, penutupan kas, laporan, dan riwayat perubahan keuangan | [Audit Sebelum Database](./04-audit-sebelum-database.md) |
| Fitur pendukung: Master Data | Menyediakan data konsumen, produk, mesin/peralatan, vendor, supplier, bahan/material, harga, finishing, pengguna, dan konfigurasi yang dipakai fitur operasional | [Konsep Frontend](./02-konsep-frontend.md) |
| Fitur pendukung: Dashboard dan Analitik | Menyajikan ringkasan penjualan, pembayaran, antrean produksi, stok, hasil siap diserahkan, dan laporan sesuai kebutuhan role | [Konsep Frontend](./02-konsep-frontend.md) |

## Hubungan fitur

```text
Master Data
  ├── Produk Cetak/Jasa ───────────────┐
  ├── Mesin, Vendor, Bahan/Material    │
  └── Konsumen dan harga               │
                                       v
Penjualan / POS -> Pesanan -> Produksi Internal atau Vendor
       │                              │
       │ pembayaran                  ├── Internal: pemakaian stok, sisa, limbah
       v                              └── Vendor: progres dan penerimaan hasil
Keuangan                                      │
                                              v
                                Hasil siap -> Pemenuhan/Pengambilan
                                              │
                                              v
                                  Keuangan dan Analitik
```

Pembayaran dapat dilakukan dari konteks detail nota agar transaksi tetap
terhubung ke pesanan yang tepat. Pemisahan sebagai fitur Keuangan menjelaskan
tanggung jawab pelaporan dan pengawasan, bukan kewajiban membuat halaman CRUD
pembayaran yang terpisah.

## Jalur perubahan persediaan

```text
Pembelian bahan/material
  -> penerimaan diverifikasi
  -> stok bertambah

Pesanan Cetak
  -> dialokasikan ke pekerjaan Internal
  -> pemakaian aktual dicatat
  -> stok yang dipakai berkurang
  -> sisa layak pakai dicatat sebagai sisa
  -> bagian tidak layak dicatat sebagai limbah

Bahan sisa dipakai ulang
  -> jumlah sisa tersedia berkurang
  -> penggunaannya ditautkan ke pekerjaan baru

Pekerjaan Vendor
  -> tidak mengurangi stok internal secara otomatis
  -> bila bahan milik usaha diberikan ke vendor, perpindahan itu dicatat

Pengambilan konsumen
  -> barang siap diserahkan berkurang
  -> stok bahan tidak berubah pada saat serah-terima
```

Kaidah konsepnya: **stok bertambah saat penerimaan**, berkurang saat bahan
atau material benar-benar dipakai, dan tidak berubah saat pelanggan
mengambil hasil jadi. Waktu pencatatan pemakaian, reservasi stok, dan koreksi
produksi parsial harus diputuskan sebelum skema dirancang.

## Batas fitur yang perlu dijaga

- POS menerima dan mengubah pesanan; ia tidak menyamakan satu baris pesanan
  dengan satu pekerjaan produksi.
- Produksi mengelola pekerjaan dan alokasi kuantitas; ia tidak mengubah
  riwayat pembayaran atau transaksi pengambilan.
- Persediaan mencatat perubahan stok yang dapat ditelusuri ke penerimaan atau
  pemakaian; nilai stok jangan diubah tanpa alasan/transaksi yang jelas.
- Pemenuhan mencatat serah-terima hasil jadi; pengambilan bukan pemakaian stok
  bahan.
- Dashboard dan laporan membaca hasil alur; keduanya tidak menggantikan
  pencatatan transaksi sumber.
