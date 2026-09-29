# Konsep Frontend: Menu dan URL

> **Status: konsep untuk audit.** Daftar ini mengusulkan navigasi halaman
> proyek baru; belum menjadi route final atau permission. Detail alur bisnis
> ada pada [Alur Operasional](./03-alur-operasional.md).

`{id}` berarti ID data yang sedang dibuka. Kolom **Tabel/Domain** menjelaskan
data yang secara konseptual dibaca atau dikelola halaman; nama bertanda `*`
adalah kelompok yang belum ditetapkan sebagai nama tabel final.

## Konsep Struktur Menu

```text
Beranda

Transaksi & Laporan
  Nota Penjualan
  Laporan Harian
  Riwayat Pembayaran
  Riwayat Laporan

Produksi
  Data Produksi
  Pengambilan Barang                (opsional)

Persediaan
  Jenis Bahan
  Bahan & Material
  Order Bahan
  Stok Bahan
  Stok Material
  Sisa Bahan
  Limbah Bahan

Master Data
  Konsumen
  Supplier
  Vendor
  Mesin/Peralatan
  Produk
  Tipe Konsumen
  Tipe Mesin
  Tipe Harga
  Harga Khusus
  Finishing

Pengaturan
  Pengaturan Situs
  Pengguna
  Role dan Permission
  Profil
```

Pengelompokan menu mengikuti alur kerja. **Transaksi & Laporan** menyatukan
pesanan, pembayaran, dan laporan. **Produksi** mengelola pekerjaan cetak
internal/vendor dan (bila diperlukan) antrean serah-terima. Master Data
menyediakan pilihan untuk transaksi, produksi, dan persediaan.

## Daftar halaman dan URL

### Beranda

| Menu/halaman | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Beranda | Agregasi lintas fitur, belum ditetapkan | `/` | Ringkasan aktivitas sesuai role; metrik/widget menunggu definisi |

### Transaksi & Laporan

| Menu/halaman | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Nota Penjualan | `nota` | `/nota` | Daftar nota, pencarian/filter, buat nota, lihat ringkasan transaksi dan progres, buka detail |
| Detail Nota (non-menu) | `nota`, `nota_isi`, `nota_bayar`, produksi*, pemenuhan* | `/nota/detail/{id}` | Edit header dan item pesanan, nilai transaksi, CRUD pembayaran dalam konteks nota, progres produksi, jumlah siap/diambil, catat/lihat pengambilan, invoice |
| Laporan Harian | `laporan`, `laporan_isi` | `/laporan` | Lihat dan kelola laporan harian sesuai aturan keuangan |
| Riwayat Pembayaran | `nota_bayar` | `/nota/bayar` | Daftar pembayaran lintas nota; pencarian/filter; tautan ke detail nota; bukan halaman CRUD pembayaran |
| Riwayat Laporan | `laporan_log` atau audit* | `/log-laporan` | Telusuri perubahan laporan |

Pembayaran dibuat/diubah/dihapus dari detail nota. `/nota/bayar` hanya
menampilkan data transaksi pembayaran dari `nota_bayar`; memilih baris
membuka nota terkait. Ini bukan tabel riwayat baru dan bukan CRUD kedua.

### Produksi

| Menu/halaman | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Data Produksi | pekerjaan*, alokasi pekerjaan*, `nota_isi` | `/cetak` | Daftar batch/file, filter status/jalur/alat/vendor, buat pekerjaan dari kuantitas item nota |
| Detail Produksi (non-menu) | pekerjaan*, alokasi pekerjaan*, `nota_isi`, persediaan*, `vendor` | `/cetak/detail/{id}` | Kelola alokasi, jalur Internal/Vendor, mesin/vendor, progres dan hasil, pemakaian bahan internal, sisa/limbah, penerimaan dan pemeriksaan vendor |

Satu pekerjaan dapat berisi beberapa item/nota. Satu item nota dapat
dialokasikan ke beberapa pekerjaan dengan kuantitas berbeda.

### Pengambilan Barang

| Menu/halaman | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Form dan riwayat pengambilan di nota | pengambilan*, detail pengambilan*, `nota_isi`, hasil produksi* | `/nota/detail/{id}` | Catat satu atau beberapa item dan kuantitas; tampilkan jumlah siap, tersisa, serta riwayat |
| Antrean Pengambilan (opsional) | pengambilan*, `nota_isi`, hasil produksi*, `nota` | `/pengambilan` | Antrean lintas nota untuk petugas counter; filter barang siap dan buka detail nota |

Mulai dari detail nota cukup untuk konsep awal. Tambahkan `/pengambilan` hanya
jika petugas serah-terima membutuhkan satu antrean lintas nota.

### Persediaan

| Menu/halaman | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Jenis Bahan | `bahan_jenis` | `/bahan-jenis` | Kelola karakter/jenis bahan atau material |
| Bahan & Material | `bahan`, `bahan_jenis` | `/bahan` | Kelola detail fisik/paket bahan dan material |
| Order Bahan | `bahan_order`, `supplier` | `/bahan-order` | Daftar order; tautan ke detail |
| Detail Order Bahan (non-menu) | `bahan_order`, `bahan_order_isi`, `bahan` | `/bahan-order/detail/{id}` | Rincian order dan penerimaan stok |
| Stok Bahan | `bahan_stok`, `bahan` | `/bahan-stok` | Lihat stok fisik bahan cetak |
| Stok Material | `material_stok`, `bahan_jenis` | `/material-stok` | Lihat stok material agregat atau bentuk stok yang disepakati |
| Sisa Bahan | `bahan_sisa`, pemakaian* | `/bahan-sisa` | Lihat dan telusuri sisa bahan yang masih dapat digunakan |
| Limbah Bahan | `bahan_limbah`, pemakaian* | `/bahan-limbah` | Lihat limbah dan penyebabnya |

**Bahan & Material** berarti detail yang dibeli/dikelola secara fisik atau
paket, misalnya roll Flexy atau material rangka banner. **Jenis Bahan**
menjelaskan karakter umum seperti Flexy 280 gsm. Keduanya berhubungan, tetapi
perilaku stok bahan cetak dan material belum tentu sama.

### Master Data

| Menu | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Konsumen | `konsumen`, tipe konsumen* | `/konsumen` | Kelola konsumen transaksi |
| Supplier | `supplier` | `/supplier` | Kelola pemasok bahan/material |
| Vendor | `vendor` (usulan) | `/vendor` | Kelola vendor produksi yang dipilih pada pekerjaan Vendor |
| Mesin/Peralatan | `mesin`, tipe mesin* | `/mesin` | Kelola mesin/peralatan produksi |
| Produk | `produk`, jenis bahan/material, harga* | `/produk` | Kelola produk berjenis Cetak/Jasa serta atribut teknis dan harga |
| Tipe Konsumen | tipe konsumen* | `/konsumen-tipe` | Kelola klasifikasi konsumen |
| Tipe Mesin | tipe mesin* | `/mesin-tipe` | Kelola klasifikasi/kompatibilitas mesin |
| Tipe Harga | `harga_tipe` | `/harga-tipe` | Kelola tipe harga |
| Harga Khusus | `harga_khusus`, `produk`, `konsumen` | `/harga-khusus` | Kelola aturan harga khusus |
| Finishing | `finishing` | `/finishing` | Kelola opsi finishing |

Vendor ditempatkan di Master Data sebagai referensi pekerjaan. Apakah vendor
dan supplier berbagi entitas kontak atau menjadi master terpisah diputuskan
setelah kebutuhan operasional diaudit.

### Pengaturan

| Menu/halaman | Tabel/Domain | URL | Fitur |
|---|---|---|---|
| Pengaturan Situs | Konfigurasi*, belum ditetapkan | `/setting` | Cakupan pengaturan aplikasi menunggu definisi |
| Pengguna | Pengguna/autentikasi | `/user` | Kelola akun pengguna |
| Role dan Permission | Role/permission | `/role` | Kelola hak akses |
| Profil | Pengguna/autentikasi | `/profil` | Kelola profil pengguna |

## Navigasi inti

```text
/nota
  -> /nota/detail/{id}
       -> kelola pesanan dan pembayaran
       -> lihat progres pekerjaan
       -> catat pengambilan item yang siap

/cetak
  -> pilih item/kuantitas dari satu atau beberapa nota
  -> /cetak/detail/{id}
       -> proses internal atau vendor
       -> catat hasil dan pemakaian persediaan yang relevan

/nota/bayar
  -> telusuri pembayaran lintas nota
  -> /nota/detail/{id}
```

## Perkiraan penggunaan per role

| Role konseptual | Area utama |
|---|---|
| CS/Kasir | Nota, detail nota, konsumen, produk; pembayaran dari detail nota |
| Penjadwal/Kepala Produksi | Daftar dan alokasi pekerjaan, mesin/vendor |
| Operator | Detail batch, hasil, pemakaian bahan, sisa/limbah |
| Petugas Vendor/Procurement | Vendor, order/kemajuan vendor, penerimaan hasil |
| Petugas Counter | Detail nota dan pengambilan; antrean khusus bila disepakati |
| Keuangan/Admin | Riwayat pembayaran, laporan, penutupan dan koreksi sesuai izin |
| Pengelola Master | Produk, harga, bahan, mesin, supplier, vendor, dan master terkait |

Ini bukan keputusan permission. Satu akun dapat memegang beberapa tanggung
jawab sesuai organisasi.
