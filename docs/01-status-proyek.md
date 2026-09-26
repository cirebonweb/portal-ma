# Status Proyek

## Informasi umum

- Nama proyek: Portal MA
- Tahap: Pengembangan
- Target: Aplikasi siap digunakan di production
- Pembaruan terakhir: 2026-09-25

Status ini adalah catatan kerja tingkat tinggi. Detail teknis tetap dicatat pada
dokumentasi arsitektur, alur aplikasi, dan skema database.

## Tahap proyek CRUD

- `[x] selesai` — dapat dijadikan referensi saat membuat CRUD karena status sudah selesai.
- `[r] review` — sudah dikerjakan namun belum dilakukan uji coba.
- `[p] proses` — sedang dikerjakan saat ini.
- `[ ] antrian` — belum dikerjakan.

Dashboard
- [ ] Dashboard CS → Statistik penjualan produk digital printing
- [ ] Dashboard Printing → Statistik antrian proses cetak
- [ ] Dashboard Admin → Statistik penerimaan uang masuk harian baik tunai maupun transfer

Data
- [r] Konsumen → /konsumen
- [r] Supplier → /supplier
- [r] Mesin → /mesin
- [r] Produk → /produk
- [p] Nota Penjualan → /nota → /nota/isi?edit={nota.id} → /nota/bayar?edit={nota.id}
- [ ] Cetak Produksi → /cetak — internal masuk antrean cetak, eksternal & jasa cukup ubah status
- [ ] Pembayaran → /pembayaran
- [ ] Laporan → /laporan

Bahan
- [r] Data Bahan → /bahan
- [r] Jenis Bahan → /bahan-jenis
- [r] Order Bahan → /bahan-order
- [r] Stok Bahan → /bahan-stok
- [r] Stok Material → /material-stok
- [ ] Sisa Bahan → /bahan-sisa
- [ ] Limbah Bahan → /bahan-limbah

Master
- [x] Tipe Konsumen → /konsumen-tipe
- [x] Tipe Mesin → /mesin-tipe
- [x] Tipe Harga → /harga-tipe
- [x] Harga Khusus → /harga-khusus
- [x] Finishing → /finishing

Log
- [ ] Riwayat Pembayaran → /log-pembayaran — tabel nota_bayar
- [ ] Riwayat Laporan → /log-laporan

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
| Nota penjualan/POS | Berjalan | Nota, tempat kerja detail nota (`/nota/isi`), dan pembayaran selesai; sisa bukti transfer, tombol print, dan pengujian pengguna |
| Manajemen bahan | Berjalan | Skema, migrasi, seeder, dan CRUD selesai; sisa pemotongan stok material saat nota selesai. Menunggu pengujian pengguna |
| Proses cetak | Belum selesai | Menunggu alur nota dan pemakaian bahan lebih jelas |
| Pembayaran | Belum selesai | Bergantung pada alur nota |
| Laporan keuangan | Belum mulai | Bergantung pada transaksi dan pembayaran |
| Dashboard | Belum mulai | Statistik dan grafik akan dirancang setelah data transaksi stabil |
| CodeIgniter Shield | Terpasang | Role dan permission perlu dipastikan terhadap setiap modul |

## Pekerjaan berikutnya

1. Menguji seluruh CRUD Master dari sisi pengguna.
2. Menerapkan pemotongan stok material saat `nota_isi.status` menjadi `4 (Selesai)` beserta
   pembatalannya, sesuai aturan pada `docs/06-skema-bahan.md`.
3. Menerapkan pemotongan stok bahan dan material pada proses cetak (`cetak`, `cetak_bahan`).
4. Menguji alur nota penjualan/POS dari sisi pengguna, termasuk pembayaran dan status Hapus Nota.
5. Memverifikasi hubungan nota, pembayaran, dan proses cetak.
6. Memverifikasi aturan stok bahan, bahan sisa, limbah, dan pemakaian material.
7. Menetapkan role dan permission setiap modul.
8. Menambahkan test atau validasi untuk alur yang sudah stabil.

## Catatan pekerjaan yang belum diverifikasi

- Perubahan lokal yang sedang berjalan perlu ditinjau dan diuji sebelum dianggap selesai.
- `docs/06-skema-bahan.md` sudah diterapkan ke migrasi, seeder, dan CRUD.
- Pemotongan stok material saat `nota_isi` selesai belum dikerjakan sehingga `material_stok`
  saat ini hanya bertambah dari order bahan.
- Trigger pada `sql/trg_nota.sql`, `sql/trg_nota_bayar.sql`, `sql/trg_nota_isi.sql`, dan
  `sql/trg_bahan_order_isi.sql` sudah dipasang manual dan lolos pengujian (35/35 pemeriksaan).
  Migrasi hanya berisi `DROP TRIGGER` pada `down()` tanpa pembuatan ulang, sehingga setelah
  `php spark migrate --all` trigger harus dipasang kembali secara manual.
- Kalkulasi trigger membiarkan nilai negatif: `nettotal` menjadi negatif bila `diskon_nominal`
  diisi sebelum nota memiliki item, dan `sisa` menjadi negatif saat nota dibayar melebihi
  `nettotal` (berfungsi sebagai kelebihan bayar). Belum ada keputusan pembulatan/validasi.
- Route `/nota/bayar*` kini hanya daftar seluruh pembayaran; `?edit=` dialihkan ke
  `/nota/isi?edit=`. Pembayaran satu nota memakai controller `Data\NotaBayar` pada halaman nota.
- Aturan perhitungan rincian nota (luas maksimal 2 desimal, jumlah dibulatkan ke atas kelipatan 500,
  dan toggle harga minimum) dicatat pada `docs/03-alur-aplikasi.md` §1.2.
- Toggle "Harga Minimum" pada form rincian nota tidak memiliki kolom di database dan selalu mulai
  dari Tidak pada form tambah maupun edit.
- Alur per kategori produk dicatat pada `docs/03-alur-aplikasi.md` §1.1: internal berurutan
  (`0 → 1 → 3 → 4`), eksternal & jasa langsung `0 → 3 → 4` tanpa potong stok bahan.
- Biaya vendor dan jasa diinput manual pada `produk.hpp`; tidak ada tabel biaya vendor terpisah.
- Halaman `/nota/isi?edit=` sudah menjadi tempat kerja nota: data header nota, ringkasan nilai,
  tombol pemindah status produksi, dan pembayaran nota (dipindah dari `/nota/bayar?edit=`).
  `/nota/bayar` kini hanya menampilkan daftar seluruh pembayaran.
- Tombol "Mulai Produksi" pada `/nota/isi` memindahkan item `0 (Draft)` menjadi `1 (Antrian)` untuk
  kategori internal berbahan, dan `3 (Proses)` untuk kategori lain.
- Tombol print nota (format invoice) belum dibuat.
- Aturan tabel DataTables (pencarian, filter, dan pengurutan hanya untuk tabel banyak data) dicatat
  pada `docs/02-arsitektur-aplikasi.md`. Rincian Nota dan Pembayaran Nota kini tanpa pencarian,
  filter, maupun pengurutan, dan urutannya ditetapkan dari sisi server.
- Bukti transfer (`nota_bayar.file`) belum diimplementasikan; belum ada pola upload di proyek.
- Halaman nota dan pembayaran nota belum diuji dari sisi pengguna. Render view tidak dapat diuji
  dari CLI karena CSRF memerlukan `IncomingRequest`, sedangkan CLI memakai `CLIRequest`.
- `NotaIsi::dataSimpan()` masih mempercayai nilai `jumlah` dan `luas` dari klien, sehingga aturan
  pembulatan pada §1.2 hanya dijalankan di sisi JavaScript; perhitungan ulang di server belum dibuat.
- Route `/pembayaran` (daftar pembayaran lintas modul) belum dibuat; menu sidebar untuk pembayaran
  nota sudah tersedia di `/nota/bayar`.
- Modul bahan, produk, order bahan, dan stok belum diuji dari sisi pengguna setelah perubahan skema.
- Panduan pengguna belum lengkap karena setiap modul perlu ditulis setelah CRUD
  dinyatakan lancar melalui pengujian pengguna.
- Statistik dashboard belum memiliki spesifikasi metrik, sumber data, atau periode laporan.

## Log pekerjaan AI

Log lengkap pekerjaan Copilot dicatat pada
[05-log-job-ai.md](./05-log-job-ai.md).
