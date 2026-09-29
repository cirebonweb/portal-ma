# Konsep Frontend: Menu dan URL

> **Status: draft konsep baru untuk dipertimbangkan.** Dokumen ini mengusulkan
> navigasi dan halaman frontend, bukan deskripsi route yang sudah
> diimplementasikan. Konsep ini melanjutkan simulasi dan alur pada
> [07-konsep-alur.md](./07-konsep-alur.md), tetapi tidak menggantikan menu,
> URL, atau aturan aplikasi yang berjalan saat ini.

## Tujuan

Menentukan struktur navigasi yang mudah dipahami pengguna digital printing
sebelum backend dirancang. Pengelompokan menu mengikuti pekerjaan pengguna:

1. menerima dan mengelola pesanan;
2. mengerjakan pesanan melalui produksi internal atau vendor;
3. menyerahkan hasil kepada konsumen;
4. mengelola persediaan dan data pendukung;
5. memantau pembayaran serta laporan.

URL dengan pola `/.../detail/{id}` adalah halaman detail non-menu dan
`{id}` diganti dengan ID data yang sedang dibuka. URL pada dokumen ini adalah
URL halaman frontend. Endpoint AJAX/API untuk
memuat, menyimpan, atau menghapus data belum ditentukan.

## Fitur Aplikasi

Rancangan awal membagi aplikasi menjadi lima fitur operasional utama dan dua
fitur pendukung. Kolom **Detail** akan diarahkan ke dokumen terpisah yang
menjelaskan alur, diagram, dan jalur keterkaitan secara lebih rinci setelah
pengelompokan fitur ini disepakati.

| Fitur Utama | Cakupan | Detail |
|---|---|---|
| Penjualan / POS | Mengelola konsumen, memilih produk Cetak atau Jasa, menyusun nota dan rincian pesanan, menentukan harga/diskon, serta memulai pencatatan pembayaran | Dokumen detail alur POS — akan dibuat |
| Produksi | Mengubah kuantitas pesanan Cetak menjadi pekerjaan/batch; menggabungkan item dari beberapa nota; membagi satu item ke pekerjaan Internal dan Vendor; mengelola mesin/vendor, progres, hasil, dan pembatalan | Dokumen detail alur Produksi — akan dibuat |
| Persediaan | Mencatat penerimaan bahan/material, stok masuk, pemakaian saat produksi, bahan sisa, limbah, pemakaian kembali sisa, dan koreksi stok | Dokumen detail alur Persediaan — akan dibuat |
| Pemenuhan Pesanan | Menentukan kuantitas hasil yang siap diserahkan dan mencatat pengambilan seluruh atau sebagian barang per item; Jasa tidak masuk pengambilan barang | Dokumen detail alur Pemenuhan — akan dibuat |
| Keuangan | Memantau pembayaran nota, penerimaan tunai/transfer, rekonsiliasi transaksi, laporan harian, dan riwayat perubahan keuangan | Dokumen detail alur Keuangan — akan dibuat |
| Fitur pendukung: Master Data | Menyediakan data konsumen, produk, mesin, vendor, supplier, bahan/material, harga, finishing, pengguna, serta konfigurasi yang dipakai fitur operasional | Dicakup dalam dokumen detail fitur terkait — akan dibuat |
| Fitur pendukung: Dashboard dan Analitik | Menyajikan ringkasan penjualan, pembayaran, antrean produksi, stok, barang siap diserahkan, dan laporan sesuai kebutuhan role | Dokumen detail Dashboard — akan dibuat |

Pembayaran dapat dimulai dan dikelola dari detail nota agar pengguna tetap
berada dalam konteks transaksi. Pengelompokan sebagai fitur **Keuangan**
menunjukkan tanggung jawab alur pelaporan dan pemantauan, bukan keharusan
membuat halaman CRUD pembayaran terpisah.

### Jalur perubahan persediaan

```text
Order bahan/material
  -> barang diterima
  -> stok bertambah

Pesanan Cetak
  -> pekerjaan produksi Internal
  -> bahan aktual dipakai
  -> stok bahan berkurang
  -> sisa layak pakai dicatat sebagai bahan_sisa
  -> material tidak layak dicatat sebagai bahan_limbah

Bahan sisa dipakai kembali
  -> bahan_sisa berkurang/dipakai
  -> pemakaian ditautkan ke pekerjaan produksi

Pekerjaan Vendor
  -> tidak memotong stok internal secara otomatis
  -> pengecualian bila bahan milik usaha memang diberikan ke vendor

Barang siap
  -> diserahkan kepada konsumen
  -> jumlah siap diserahkan berkurang
  -> stok bahan tidak berubah pada saat pengambilan
```

Jalur ini menempatkan penambahan stok pada penerimaan, pengurangan pada
pemakaian aktual, dan pencatatan sisa/limbah pada proses produksi internal.
Waktu tepat pencatatan pemakaian dan cara mengoreksi hasil parsial masih harus
ditetapkan pada dokumen detail Persediaan dan Produksi.

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
  Pengambilan Barang             (opsional, perlu validasi alur kerja)

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
  Mesin
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

Kelompok **Transaksi & Laporan**, **Produksi**, dan **Persediaan** memisahkan
alur kerja harian dari data Master. Laporan Harian dan Riwayat Laporan
ditempatkan bersama transaksi karena keduanya berkaitan dengan pencatatan dan
peninjauan aktivitas transaksi. Penempatan menu dapat disesuaikan melalui uji
coba pengguna, tetapi satu fungsi sebaiknya tidak muncul sebagai dua menu
berbeda tanpa alasan operasional.

## Daftar halaman dan URL

### Beranda

| Menu | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Beranda | agregasi lintas modul (belum ditetapkan) | `/` | Ringkasan aktivitas yang relevan dengan role pengguna; metrik dan widget belum ditetapkan |

### Transaksi & Laporan

| Menu/halaman | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Nota Penjualan | `nota` | `/nota` | Daftar nota, pencarian dan filter, tambah nota, ringkasan status pembayaran dan progres item, tautan ke detail |
| Detail Nota — halaman non-menu | `nota`, `nota_isi`, `nota_bayar`, `cetak_*`, `nota_*` | `/nota/detail/{id}` | Edit header, kelola rincian pesanan, lihat nilai transaksi, CRUD pembayaran untuk nota ini, lihat progres produksi per item, lihat jumlah siap/diambil, catat pengambilan, lihat riwayat pengambilan, print invoice |
| Laporan Harian | `laporan`, `laporan_isi` | `/laporan` | Daftar dan pengelolaan laporan keuangan harian |
| Riwayat Pembayaran | `nota_bayar` | `/nota/bayar` | Daftar lintas nota dari tabel transaksi `nota_bayar`, pencarian/filter, tautan ke nota terkait; bukan tabel baru atau tempat CRUD pembayaran |
| Riwayat Laporan | `laporan_log` | `/log-laporan` | Pencarian riwayat perubahan laporan |

Detail nota menjadi pusat kerja untuk satu transaksi. Pembayaran dibuat,
dilihat, diubah, atau dihapus dari konteks nota tersebut agar pengguna dapat
memastikan pembayaran terhubung ke nota yang benar. Halaman `/nota/bayar`
hanya berfungsi sebagai riwayat/pencarian lintas nota atas data `nota_bayar`;
aksi pada baris riwayat membuka `/nota/detail/{id}`, bukan form CRUD pembayaran
terpisah. Dengan demikian, tidak ada halaman CRUD pembayaran kedua atau tabel
riwayat pembayaran baru. Pengambilan juga dimulai dari konteks nota agar item
dan jumlah yang siap dapat diverifikasi.

### Produksi

| Menu/halaman | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Data Produksi | `cetak`, `cetak_isi`, `nota_isi` | `/cetak` | Daftar pekerjaan/batch, filter status dan jalur, filter mesin/vendor, buat batch, tampilkan nota/item/kuantitas di tiap pekerjaan |
| Detail Produksi — halaman non-menu | `cetak`, `cetak_isi`, `nota_isi`, `bahan_stok`, `bahan_sisa`, `bahan_limbah`, `vendor` | `/cetak/detail/{id}` | Lihat dan kelola isi batch, jalur Internal/Vendor, mesin atau vendor, perubahan status, kuantitas hasil, pemakaian bahan internal, sisa/limbah, penerimaan dan pemeriksaan hasil vendor |

URL `/cetak` dipertahankan sebagai usulan nama route agar konsisten dengan
konsep alur yang sedang diaudit. Label menu **Data Produksi** menjelaskan
bahwa halaman berisi pekerjaan produksi, bukan master mesin.

Satu pekerjaan dapat memuat beberapa item dari beberapa nota; satu item nota
dapat muncul di beberapa pekerjaan dengan kuantitas berbeda. Karena itu,
daftar `/cetak` berpusat pada batch/pekerjaan. Detail `/cetak/detail/{id}`
menampilkan seluruh alokasi dan progres batch.

### Pengambilan Barang

| Menu/halaman | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Form/riwayat pengambilan pada nota | `nota_ambil*`, `nota_isi`, `cetak_isi` | `/nota/detail/{id}` | Catat satu atau beberapa item dan kuantitas yang diserahkan, tampilkan kuantitas siap dan sisa, lihat transaksi pengambilan sebelumnya |
| Antrean Pengambilan Barang — opsional | `nota_ambil*`, `nota_isi`, `cetak_isi`, `nota` | `/pengambilan` | Antrean lintas nota untuk petugas counter; filter nota/konsumen, waktu siap, dan status pengambilan; buka nota atau catat serah-terima |

Konsep awal tidak mewajibkan menu `/pengambilan`: semua fungsi pengambilan
dapat dimulai dan ditinjau di detail nota. Tambahkan antrean terpisah hanya
jika petugas membutuhkan satu layar operasional untuk mencari barang siap
lintas nota.

### Persediaan

| Menu/halaman | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Jenis Bahan | `bahan_jenis` | `/bahan-jenis` | Kelola jenis bahan/material beserta karakteristiknya |
| Bahan & Material | `bahan`, `bahan_jenis` | `/bahan` | Kelola detail bahan fisik/paket pembelian dan material yang digunakan atau dijual |
| Order Bahan | `bahan_order`, `supplier` | `/bahan-order` | Daftar order; detail order dibuka dari baris terkait |
| Detail Order Bahan — halaman non-menu | `bahan_order`, `bahan_order_isi`, `bahan` | `/bahan-order/detail/{id}` | Kelola rincian order dan proses penerimaan ke stok |
| Stok Bahan | `bahan_stok`, `bahan` | `/bahan-stok` | Lihat stok bahan fisik dan detail stok |
| Stok Material | `material_stok`, `bahan_jenis` | `/material-stok` | Lihat stok material agregat |
| Sisa Bahan | `bahan_sisa`, `cetak` | `/bahan-sisa` | Daftar potongan bahan yang masih dapat digunakan |
| Limbah Bahan | `bahan_limbah`, `cetak` | `/bahan-limbah` | Daftar bahan yang menjadi limbah beserta penyebabnya |

Persediaan bahan cetak mengikuti pekerjaan produksi internal. Layar pekerjaan
cetak menjadi konteks untuk melihat pemakaian bahan, sementara menu stok,
sisa, dan limbah menyediakan pemantauan serta administrasi persediaan.

**Bahan & Material** adalah halaman untuk data bahan fisik yang dibeli dalam
bentuk paket (misalnya roll Flexy) dan data material (misalnya rangka banner)
yang dipakai atau dijual sebagai produk. Halaman **Jenis Bahan** mengelola
karakter/jenis umumnya; halaman **Bahan & Material** mengelola varian atau
detail fisik/paket. Keduanya menggunakan domain bahan yang sama, tetapi stok
bahan cetak dan stok material memiliki perilaku berbeda. Nama menu ini dapat
diganti menjadi **Data Bahan & Material** bila lebih mudah dipahami pengguna.

### Master Data

| Menu | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Konsumen | `konsumen`, `konsumen_tipe` | `/konsumen` | CRUD data konsumen dan informasi yang dipakai transaksi |
| Supplier | `supplier` | `/supplier` | CRUD pemasok bahan |
| Vendor | `vendor` (usulan) | `/vendor` | CRUD data vendor produksi, kontak, dan status aktif; dipilih dari pekerjaan jalur Vendor |
| Mesin | `mesin`, `mesin_tipe` | `/mesin` | CRUD mesin/peralatan produksi |
| Produk | `produk`, `bahan_jenis`, `harga_tipe`, `harga_khusus` | `/produk` | CRUD katalog produk dengan jenis Cetak/Jasa, harga dan atribut teknis |
| Tipe Konsumen | `konsumen_tipe` | `/konsumen-tipe` | CRUD klasifikasi konsumen |
| Tipe Mesin | `mesin_tipe` | `/mesin-tipe` | CRUD tipe/kelompok mesin |
| Tipe Harga | `harga_tipe` | `/harga-tipe` | CRUD tipe harga |
| Harga Khusus | `harga_khusus`, `produk`, `konsumen` | `/harga-khusus` | CRUD aturan harga khusus |
| Finishing | `finishing` | `/finishing` | CRUD pilihan finishing produk |

Vendor ditempatkan pada **Master Data** karena merupakan data referensi yang
digunakan saat pekerjaan produksi melalui vendor dibuat. Supplier bahan tetap
berbeda fungsi. Apakah kedua master tersebut berbagi identitas/kontak atau
disimpan sebagai entitas terpisah masih perlu dirancang di backend, tetapi
tidak mengubah penempatan Vendor pada navigasi frontend.

### Pengaturan

| Menu/halaman | Tabel | URL | Fitur frontend |
|---|---|---|---|
| Pengaturan Situs | tabel pengaturan (belum ditetapkan) | `/setting` | Pengaturan aplikasi; cakupan fitur belum ditetapkan |
| Pengguna | tabel autentikasi/pengguna Shield | `/user` | Kelola akun pengguna |
| Role dan Permission | tabel autentikasi/permission Shield | `/role` | Kelola role dan permission |
| Profil | tabel autentikasi/pengguna Shield | `/profil` | Kelola profil pengguna yang sedang masuk |

Kolom **Tabel** menunjukkan sumber data utama yang digunakan atau ditampilkan
halaman, bukan keputusan skema. Nama bertanda `*` (misalnya `nota_ambil*`,
`cetak_*`, `harga_*`) adalah notasi kelompok tabel konseptual, bukan nama tabel
final. Tabel relasi/audit dan tabel turunan dapat ditambahkan saat alur serta
backend dirancang. URL pada dokumen ini adalah usulan navigasi dan perlu
diselaraskan dengan struktur Shield serta modul yang akhirnya dipilih.

## Navigasi antarhalaman

```text
/nota
  └── klik Detail
       └── /nota/detail/{id}
            ├── tambah/edit item pesanan
            ├── CRUD pembayaran dalam konteks nota
            ├── lihat progres pekerjaan terkait
            ├── catat pengambilan sebagian/seluruh item siap
            └── lihat riwayat pengambilan

/nota/bayar
  └── cari/lihat pembayaran lintas nota
       └── buka nota terkait di /nota/detail/{id}

/cetak
  └── buat batch dari satu atau beberapa item nota
       └── /cetak/detail/{id}
            ├── atur alokasi kuantitas
            ├── pilih jalur internal + mesin atau vendor
            ├── perbarui status/progres
            ├── catat hasil dan kebutuhan bahan internal
            └── tandai hasil diterima/siap sesuai aturan yang disepakati

/vendor
  └── kelola data master vendor yang dipilih pada pekerjaan jalur vendor
```

Detail Nota dan Detail Produksi adalah halaman non-menu karena konteksnya
berasal dari baris nota atau batch tertentu. URL keduanya menggunakan ID
entitas yang sedang dibuka.

## Ringkasan fitur per peran

Pembagian berikut hanya peta navigasi awal; permission final belum ditetapkan.

| Peran | Halaman utama yang diperkirakan digunakan |
|---|---|
| CS | `/nota`, `/nota/detail/{id}`, `/nota/bayar` (riwayat), `/konsumen`, `/produk` |
| Printing/Produksi | `/cetak`, `/cetak/detail/{id}`, `/bahan-stok`, `/bahan-sisa`, `/bahan-limbah` |
| Counter/Petugas serah-terima | `/nota/detail/{id}` dan, bila diperlukan, `/pengambilan` |
| Admin | `/nota/bayar` (riwayat), `/nota/detail/{id}` untuk koreksi sesuai hak akses, `/laporan`, `/log-laporan` |
| Pengelola Master | `/produk`, `/vendor`, `/mesin`, `/supplier`, serta halaman Master lainnya |

Satu pengguna dapat memiliki lebih dari satu role. Daftar ini tidak menetapkan
bahwa halaman tertentu hanya boleh diakses satu role.

## Keputusan frontend yang perlu dikonfirmasi

1. Apakah **Pengambilan Barang** cukup berada di detail nota, atau perlu menu
   antrean lintas nota `/pengambilan`?
2. Apakah pembuatan pekerjaan produksi dilakukan melalui form di `/cetak` atau
   memiliki URL khusus seperti `/cetak/tambah`?
3. Apakah detail batch perlu menampilkan tab terpisah untuk item, pemakaian
   bahan, sisa/limbah, dan progres vendor?
4. Apakah header/detail nota atau pekerjaan produksi memiliki aksi print,
   unduh, atau ekspor yang perlu masuk rancangan navigasi?
5. Apakah label menu **Data Produksi** dan URL `/cetak` cukup jelas bagi
   operator, atau perlu penamaan URL baru sebelum production?
6. Halaman apa yang dibutuhkan Dashboard untuk tiap role, dan metrik mana yang
   boleh ditampilkan?

## Batas penggunaan dokumen

- Dokumen ini hanya mengusulkan menu, URL halaman, fitur UI, dan perpindahan
  antarhalaman.
- URL yang tercantum belum berarti route atau permission sudah tersedia.
- Endpoint backend, struktur tabel, aturan validasi, transaksi database, dan
  status kanonis harus dirancang setelah alur pada
  [07-konsep-alur.md](./07-konsep-alur.md) disetujui.
- Dokumen ini tidak meminta penghapusan atau penimpaan implementasi saat ini.
  Keputusan melanjutkan proyek atau memulai proyek baru dapat dibuat setelah
  audit alur serta estimasi dampak backend selesai.
