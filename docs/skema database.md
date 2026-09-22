### Skema Menu

- Dashboard CS → Statistik penjualan produk digital printing
- Dashboard Printing → Statistik antrian proses cetak
- Dashboard Admin → Statistik penerimaan uang masuk harian baik tunai maupun transfer

Master
- Tipe Konsumen → /konsumen-tipe
- Tipe Mesin    → /mesin-tipe
- Tipe Harga    → /harga-tipe
- Harga Khusus  → /harga-khusus
- Finishing     → /finishing

Bahan
- Data Bahan   → /bahan
- Order Bahan  → /bahan-order
- Stok Bahan   → /bahan-stok
- Sisa Bahan   → /bahan-sisa
- Limbah Bahan → /bahan-limbah

Data
- Data Konsumen   → /konsumen
- Data Supplier   → /supplier
- Data Mesin      → /mesin
- Data Produk     → /produk
- Data Nota       → /nota
- Data Cetak      → /cetak
- Data Pembayaran → /pembayaran
- Data Laporan    → /laporan

Log
- Log Laporan → /laporan-log

---

### Skema Tabel (sesuai urutan migrasi up)

| Nama Tabel      | Relasi Tabel              | Role User  |
|-----------------|---------------------------|------------|
| konsumen_tipe   |                           | cs         | 
| konsumen        | konsumen_tipe, users      | cs         | 
| supplier        |                           | admin      | 
| mesin_tipe      |                           | printing   | 
| mesin           | mesin_tipe                | printing   | 
| bahan           | mesin_tipe                | printing   | 
| bahan_order     | bahan, supplier, users    | admin      | 
| bahan_order_isi | bahan_order, bahan        | admin      | 
| bahan_stok      | bahan, bahan_order        | >sistem    | 
| produk          | bahan                     | cs         | 
| harga_tipe      | konsumen_tipe, produk     | cs         | 
| harga_khusus    | konsumen, produk          | cs         | 
| finishing       |                           | cs         | 
| nota            | konsumen, users           | cs         | 
| nota_isi        | nota, produk, finishing   | cs         | 
| nota_bayar      | nota, users               | cs         |
| cetak           | mesin, bahan, users       | printing   | 
| cetak_isi       | cetak, nota_isi           | printing   | 
| bahan_sisa      | bahan, cetak              | >sistem    |
| bahan_limbah    | bahan, cetak, bahan_sisa  | cs         | 
| laporan         | users                     | cs         |
| laporan_isi     | laporan, nota, users      | cs         |
| laporan_log     | laporan, users            | >sistem    |

Keterangan: 
- tabel `users` bawaan dari codeigniter4 shield tanpa modifikasi.
- superadmin → akses penuh seluruh sistem.
- cs         → Customer Service.
- printing   → Operator Mesin & Kepala Produksi.
- admin      → Admin Keuangan.
- Bahan, Data, Master dan Log adalah struktur folder untuk Controllers dan Views sesuai dengan skema menu.
- untuk setiap tabel *_isi merupakan bagian dari tabel induk.

---

### Skema Order Bahan dan Stok Bahan

**Keterkaitan**
- Menu: Bahan → URL: /bahan → Tabel: bahan
- Menu: Stok Bahan → URL: /bahan-stok → Tabel: bahan_stok
- Menu: Order Bahan → URL: /bahan-order → Tabel: bahan_order

**Contoh Simulasi**
1. User melakukan input data bahan `bahan` :
```
| ID | Tipe Mesin                | Kode | Nama Bahan        | Gramasi | Lebar  | Panjang | Isi Paket          | Rumus          | Aksi       |
|----|---------------------------|------|-------------------|---------|--------|---------|--------------------|----------------|------------|
| 1  | Printing (Outdoor/Indoor) | FLX  | Flexy 280-3260    | 280 gsm | 3.2 m  | 60 m    | 1 roll = 192 m²    | Perkalian luas | Edit/Hapus |
| 2  | Printing (Outdoor/Indoor) | FLX  | Flexy 280-3270    | 280 gsm | 3.2 m  | 70 m    | 1 roll = 224 m²    | Perkalian luas | Edit/Hapus |
| 3  | Printing (Outdoor/Indoor) | STR  | Stiker Ritrama    | 0 gsm   | 1.27 m | 50 m    | 1 roll = 63.5 m²   | Perkalian luas | Edit/Hapus |
| 4  | Copy Colour A3+           | STQ  | Stiker Quantac    | 0 gsm   | 0.33 m | 0.48 m  | 1 rim = 100 lembar | Perkalian qty  | Edit/Hapus |
| 5  | Press Mug                 | MCL  | Mug Coating Lokal | 0 gsm   | 0 m    | 0 m     | 1 dus = 48 pcs     | Perkalian qty  | Edit/Hapus |
```
- ID : `bahan.id`
- Tipe Mesin : `bahan.mesin_tipe_id` → `mesin_tipe.nama`
- Kode : `bahan.kode`
- Nama Bahan : `bahan.nama`
- Gramasi : `bahan.gsm`
- Lebar : `bahan.lebar`
- Panjang : `bahan.panjang`
- Isi Paket : 1 `bahan.satuan_2` = `bahan.isi_paket` `bahan.satuan_1`
- Rumus : `bahan.rumus`

1. User melakukan pembelian bahan `bahan_order` dan `bahan_order_isi` :
```
- ID : 1 → `bahan_order.id`
- Nama Supplier : PT. ABCD → `bahan_order.supplier_id`
- Tanggal Order : 19-10-2026 → `bahan_order.tgl_order`
- No. PO : PO-12345 → `bahan_order.order`
- SubTotal : Rp 4.7853.400 → `bahan_order.subtotal`
- Ongkir : Rp 30.000 → `bahan_order.ongkir`
- Total : Rp 4.783.400 → `bahan_order.total`
- Tombol : Tambah Stok Bahan → `bahan_order.status`
↓ bahan_order_isi ↓
-----------------------------------------------------------------------------------------------------------------------
| ID | Nama Bahan        | Ukuran        | Harga Satuan     | Harga Paket        | Qty    | Jumlah       | Aksi       |
|----|-------------------|---------------|------------------|--------------------|--------|--------------|------------|
| 1  | Flexy 280-3260    | 3.2 x 60 m    | Rp 4.800 /m      | Rp 921.600 /roll   | 2 roll | Rp 1.843.200 | Edit/Hapus |
| 2  | Flexy 280-3270    | 3.2 x 70 m    | Rp 5.800 /m      | Rp 1.299.200 /roll | 1 roll | Rp 1.299.200 | Edit/Hapus |
| 3  | Stiker Ritrama    | 1.27 x 50 m   | Rp 14.000 /m     | Rp 889.000 /roll   | 1 roll | Rp 889.000   | Edit/Hapus |
| 4  | Stiker Quantac    | 0.33 x 0.48 m | Rp 2.900 /lembar | Rp 290.000 /rim    | 1 rim  | Rp 290.000   | Edit/Hapus |
| 5  | Mug Coating Lokal | 0 x 0 m       | Rp 9.000 /pcs    | Rp 432.000 /dus    | 1 dus  | Rp 432.000   | Edit/Hapus |
-----------------------------------------------------------------------------------------------------------------------
```
**Keterangan**
- User dapat melakukan perubahan pada bahan_order kapan saja;
- Saat `bahan_order.status_stok` = 0 maka tombol 'Tambah Stok Bahan' dan Aksi menjadi enabled;
- Saat klik tombol 'Tambah Stok Bahan' maka semua `bahan_order_isi` masuk ke `bahan_stok`;
- `bahan_order.status_stok` = 1 dimana tombol 'Tambah Stok Bahan' dan Aksi menjadi disabled.

2. Tampilan pada Stok Bahan `bahan_stok`
```
| ID | Kode Bahan | Nama Bahan        | Ukuran        | Stok Masuk | Stok Pakai | Stok Sisa  | Kondisi       | Status   | Keterangan  |
|----|------------|-------------------|---------------|------------|------------|------------|---------------|----------|-------------|
| 1  | FLX-1-001  | Flexy 280-3260    | 3.2 x 60 m    | 2.240 m²   | 0 m²       | 2.240 m²   | Kondisi Baik  | Aktif    |             |
| 2  | FLX-1-002  | Flexy 280-3260    | 3.2 x 60 m    | 2.240 m²   | 0 m²       | 2.240 m²   | Kondisi Baik  | Aktif    |             |
| 3  | FLX-2-003  | Flexy 280-3270    | 3.2 x 70 m    | 2.240 m²   | 0 m²       | 2.240 m²   | Kondisi Rusak | Nonaktif | Bahan rusak |
| 4  | STR-3-001  | Stiker Ritrama    | 1.27 x 50 m   | 317.5 m²   | 0 m²       | 317.5 m²   | Kondisi Baik  | Aktif    |             |
| 5  | STQ-4-001  | Stiker Quantac    | 0.33 x 0.48 m | 100 lembar | 0 lembar   | 100 lembar | Kondisi Baik  | Aktif    |             |
| 6  | MCL-5-001  | Mug Coating Lokal | 0 x 0 m       | 48 pcs     | 0 pcs      | 48 pcs     | Kondisi Baik  | Aktif    |             |
```
**Kode Bahan**
- 'FLX, STR, STQ, MCL' diambil dari `bahan.kode`
- '1, 2, 3, 4, 5' diambil dari `bahan_order_isi.id`
- '001, 002, 003' hasil generate nomor urut Controller

**Status**
- `bahan_order.status_stok`: 0 = pembelian belum dimasukkan ke stok, 1 = seluruh item pembelian sudah dimasukkan ke stok.
- `bahan_stok.status`: 0 = stok nonaktif/tidak layak dipakai, 1 = stok aktif/layak dipakai.

---

### Alogaritma Laporan
1. laporan.status
- 0 : Draft → saat laporan pertama kali dibuat oleh cs
- 1 : Dicetak → saat laporan di print oleh cs
- 2 : Dikunci → saat laporan di input oleh admin keuangan
- 3 : Dibuka → saat laporan ingin direvisi dan hanya dibuka oleh admin keuangan
- 4 : Direvisi → saat laporan direvisi oleh cs dan kembali lagi ke laporan.status = 1

2. laporan_log
- laporan.status 0 → Laporan dibuat oleh `users.username`
- laporan.status 1 → Laporan dicetak oleh `users.username`
- laporan.status 2 → Laporan dikunci oleh `users.username`
- laporan.status 3 → Laporan dibuka oleh `users.username`
- laporan.status 4 → Laporan direvisi oleh `users.username`

Untuk status Dikunci dan Dibuka tabel admin keuangan belum dibuat, karena ini nantinya fitur tambahan. Jadi sementara fitur Dikunci dan Dibuka bisa di-skip atau tetap dipertahankan persiapan tabel admin keuangan jika sudah dibuat. 

Log yang selalu ada saat laporan Dicetak, hanya saja tidak mempengaruhi `laporan.status`. 
Misal `laporan.status` = '2' maka jangan sampai menjadi '1'.

3. Gambaran alur saat dijalankan di CodeIgniter 4:
```ruby
Simulasi Penerapan pada Alur Kerja (MVC)
├── CS Membuat Laporan Baru (Draft):
│   ├── laporan: status = 0, keterangan = "Laporan Shift Pagi"
│   └── laporan_log: user_id = 5, aksi = "Dibuat", catatan = "Laporan draft dibuat oleh CS Budi"
│
├── CS Mencetak Laporan (Pertama Kali):
│   ├── laporan: status = 1
│   └── laporan_log: user_id = 5, aksi = "Dicetak", catatan = "Laporan dicetak oleh CS Budi"
│
├── Admin Keuangan Membuka Kembali Laporan (Fitur Masa Depan):
│   ├── laporan: status = 3, keterangan = "Tolong perbaiki nota No. 102"
│   └── laporan_log: user_id = 2, aksi = "Dibuka", catatan = "Laporan dibuka oleh Admin Keuangan (Siti) untuk revisi"
│
├── CS Mencetak Ulang Laporan yang Sudah Dikunci (status = 2):
│   ├──  laporan: status TETAP 2 (Tidak berubah karena di-check di Controller: if (status == 2) { // jangan ubah status }).
└── └──  laporan_log: Tetap bertambah 1 baris baru: user_id = 5, aksi = "Dicetak", catatan = "Laporan cetak ulang oleh CS Budi"
```
---

### Catatan Struktur Database
Untuk struktur tabel `konsumen`, `produk`, `nota`, `nota_isi`, `nota_bayar`, `laporan`, `laporan_isi` tabel tidak dapat dirubah karena menyesuaikan dengan aplikasi lama Excel VBA & .mde dimana database akan dipindahkan ke CodeIgniter 4 & MySql. Untuk tabel lainnya merupakan penambahan sendiri yang belum ada pada firur VBA.