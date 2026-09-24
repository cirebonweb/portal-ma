# Status Proyek

## Informasi umum

- Nama proyek: Portal MA
- Tahap: Pengembangan
- Target: Aplikasi siap digunakan di production
- Pembaruan terakhir: 2026-09-24

Status ini adalah catatan kerja tingkat tinggi. Detail teknis tetap dicatat pada
dokumentasi arsitektur, alur aplikasi, dan skema database.

## Tahap proyek

- [x] Perancangan awal menu dan database
- [x] Setup CodeIgniter 4
- [x] Setup CodeIgniter Shield
- [ ] Menetapkan dan memverifikasi role serta permission
- [x] Menyelesaikan CRUD Master
- [ ] Menyelesaikan POS/nota penjualan
- [ ] Menyelesaikan manajemen bahan
- [ ] Menyelesaikan proses cetak
- [ ] Menyelesaikan pembayaran
- [ ] Menyelesaikan laporan keuangan
- [ ] Menyelesaikan statistik dan grafik dashboard
- [ ] Pengujian terintegrasi
- [ ] Audit validasi dan hak akses
- [ ] Persiapan deployment
- [ ] Deployment production

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

### 2026-09-24

- Membuat instruksi repository untuk Copilot.
- Membuat indeks dokumentasi.
- Membuat status awal proyek.
- Membuat dokumentasi arsitektur aplikasi awal.
- Membuat dokumentasi alur aplikasi tingkat tinggi.
- Menandai seluruh CRUD Master sebagai final berdasarkan konfirmasi pengguna.
- Membuat kerangka panduan pengguna untuk diisi bertahap setelah pengujian CRUD.
