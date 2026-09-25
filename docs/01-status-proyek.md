# Status Proyek

## Informasi umum

- Nama proyek: Portal MA
- Tahap: Pengembangan
- Target: Aplikasi siap digunakan di production
- Pembaruan terakhir: 2026-09-24

Status ini adalah catatan kerja tingkat tinggi. Detail teknis tetap dicatat pada
dokumentasi arsitektur, alur aplikasi, dan skema database.

## Tahap proyek CRUD

- `[x] selesai` — dapat dijadikan referensi saat membuat CRUD karena status sudah selesai.
- `[p] proses` — sedang dikerjakan saat ini.
- `[ ] antrian` — belum dikerjakan.

Dashboard
- [ ] Dashboard CS → Statistik penjualan produk digital printing
- [ ] Dashboard Printing → Statistik antrian proses cetak
- [ ] Dashboard Admin → Statistik penerimaan uang masuk harian baik tunai maupun transfer

Data
- [x] Data Konsumen → /konsumen
- [x] Data Supplier → /supplier
- [x] Data Mesin → /mesin
- [x] Data Produk → /produk
- [p] Data Nota → /nota
- [ ] Data Cetak → /cetak
- [ ] Data Pembayaran → /pembayaran
- [ ] Data Laporan → /laporan

Bahan
- [x] Data Bahan → /bahan
- [x] Order Bahan → /bahan-order
- [x] Stok Bahan → /bahan-stok
- [ ] Sisa Bahan → /bahan-sisa
- [ ] Limbah Bahan → /bahan-limbah

Master
- [x] Tipe Konsumen → /konsumen-tipe
- [x] Tipe Mesin → /mesin-tipe
- [x] Tipe Harga → /harga-tipe
- [x] Harga Khusus → /harga-khusus
- [x] Finishing → /finishing

Log
- [ ] Log Laporan → /laporan-log

Setting
- [ ] Setting Situs
- [ ] Setting User
- [ ] Role User
- [ ] Profil

## Modul dan kondisi saat ini

| Area | Status | Catatan |
|---|---|---|
| CRUD Master | Final | Seluruh CRUD pada folder Master selesai; menunggu pengujian pengguna |
| Konsumen | Berjalan | CRUD dan integrasi terkait masih perlu diverifikasi |
| Produk dan harga | Berjalan | Produk, tipe harga, dan harga khusus sedang dikembangkan |
| Nota penjualan/POS | Berjalan | Controller, view, dan JavaScript nota sudah mulai dibuat |
| Manajemen bahan | Berjalan | Order bahan dan stok menjadi bagian penting untuk diverifikasi |
| Proses cetak | Belum selesai | Menunggu alur nota dan pemakaian bahan lebih jelas |
| Pembayaran | Belum selesai | Bergantung pada alur nota |
| Laporan keuangan | Belum mulai | Bergantung pada transaksi dan pembayaran |
| Dashboard | Belum mulai | Statistik dan grafik akan dirancang setelah data transaksi stabil |
| CodeIgniter Shield | Terpasang | Role dan permission perlu dipastikan terhadap setiap modul |

## Pekerjaan berikutnya

1. Menguji seluruh CRUD Master dari sisi pengguna.
2. Menetapkan alur nota penjualan/POS.
3. Memverifikasi hubungan nota, pembayaran, dan proses cetak.
4. Memverifikasi aturan stok bahan, bahan sisa, dan limbah.
5. Menetapkan role dan permission setiap modul.
6. Menambahkan test atau validasi untuk alur yang sudah stabil.

## Catatan pekerjaan yang belum diverifikasi

- Perubahan lokal yang sedang berjalan perlu ditinjau dan diuji sebelum dianggap selesai.
- Panduan pengguna belum lengkap karena setiap modul perlu ditulis setelah CRUD
  dinyatakan lancar melalui pengujian pengguna.
- Statistik dashboard belum memiliki spesifikasi metrik, sumber data, atau periode laporan.

## Log pekerjaan AI

Log lengkap pekerjaan Copilot dicatat pada
[05-log-job-ai.md](./05-log-job-ai.md).
