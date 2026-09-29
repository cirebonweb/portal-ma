# Konsep Alur Nota, Produksi, dan Pengambilan Barang

> **Status: draft untuk audit alur.** Dokumen ini mencatat usulan konsep
> berdasarkan simulasi transaksi, bukan spesifikasi implementasi atau perubahan
> aturan kanonis. Dokumen ini belum menggantikan aturan lama pada
> [Alur Aplikasi](./03-alur-aplikasi.md), [Nota dan POS](./modul/nota.md),
> atau [Skema Bahan](./06-skema-bahan.md). Setujui alur dan keputusan terbuka
> terlebih dahulu sebelum rancangan backend atau migrasi dibuat.

## Tujuan

Menetapkan alur frontend dan tanggung jawab layar untuk tiga pekerjaan yang
saling berhubungan:

1. mencatat pesanan pelanggan;
2. menjadwalkan dan menjalankan produksi secara internal atau melalui vendor;
3. mencatat penyerahan barang kepada pelanggan, termasuk pengambilan sebagian.

Fokus audit adalah menjaga agar satu pesanan dapat dibagi ke beberapa pekerjaan
produksi, beberapa pesanan dapat digabung ke satu pekerjaan/file cetak, dan
hasil dari pekerjaan berbeda tetap dapat diserahkan secara bertahap.

## Prinsip konsep

- Produk memiliki jenis **Cetak** atau **Jasa**. Internal dan vendor bukan jenis
  produk; keduanya adalah jalur pelaksanaan pekerjaan.
- Satu baris `nota_isi` menyatakan pesanan dan jumlah yang dijual kepada
  pelanggan. Ia bukan satu-satunya pekerjaan produksi.
- Pekerjaan produksi dapat menggabungkan beberapa baris pesanan dan satu baris
  pesanan dapat dialokasikan ke beberapa pekerjaan.
- Status Antrian, Pending, Proses, Selesai, dan Batal menggambarkan pekerjaan
  produksi/file cetak, bukan seluruh jumlah pada baris pesanan.
- Pengambilan hanya berlaku untuk barang fisik hasil produk Cetak. Item Jasa
  tidak masuk daftar barang siap diambil.
- Data bahan, sisa, dan limbah hanya dicatat untuk produksi internal yang
  menggunakan stok bahan aplikasi. Pekerjaan vendor tidak otomatis memotong
  stok internal.
- Jumlah untuk pesanan, alokasi produksi, hasil selesai, dan jumlah diserahkan
  menggunakan satuan kuantitas produk yang sama. Luas cetak atau panjang
  tarikan mesin adalah ukuran produksi/bahan tersendiri, bukan jumlah barang
  yang diserahkan.

## Simulasi pesanan

### Nota 12345 — Ahmad

| Item pesanan | Ukuran/varian | Jumlah | Catatan pelaksanaan |
|---|---:|---:|---|
| Spanduk Flexy 280 | 2 × 1 m, outdoor | 10 pcs | 6 pcs direncanakan internal dan 4 pcs vendor |
| Kartu nama | Copy Colour A3+ | 3 box | Cetak |
| Jasa desain | — | sesuai satuan jasa | Jasa, bukan barang untuk diambil |
| Jasa pemasangan spanduk | — | sesuai satuan jasa | Jasa, bukan barang untuk diambil |

### Nota 12346 — Budi

| Item pesanan | Ukuran/varian | Jumlah | Catatan pelaksanaan |
|---|---:|---:|---|
| Spanduk Flexy 280 | 3 × 1 m, outdoor | 5 pcs | Dibagi ke dua pekerjaan: 2 pcs dan 3 pcs |
| Stiker | 3 × 2 m, indoor | 1 pcs | Cetak |
| Mug | Press mug | 10 pcs | Cetak; gunakan mesin/alat yang sesuai |
| Kaos | Sablon kaos | 5 pcs | Cetak; metode dan mesin sesuai kebutuhan |

Nama mesin, produk, ukuran, dan kuantitas di atas adalah contoh simulasi.
Ketersediaan mesin, vendor, bahan, rumus harga, serta HPP tetap mengikuti
konfigurasi dan aturan bisnis yang disepakati.

## Alur pengguna ujung ke ujung

```text
Produk Cetak/Jasa
  -> Nota dan rincian pesanan
  -> Rencana jalur kerja per kuantitas
  -> Pekerjaan produksi internal atau vendor
  -> Hasil selesai dan siap diserahkan
  -> Satu atau beberapa transaksi pengambilan
```

### 1. Produk dan pemilihan layanan

Pada `/produk`, pengguna menentukan apakah produk merupakan **Cetak** atau
**Jasa**.

- Produk Cetak dapat memiliki atribut teknis seperti jenis bahan, ukuran,
  rumus harga, dan mesin/tipe mesin yang kompatibel.
- Produk Jasa tidak masuk antrean pekerjaan cetak dan tidak memiliki jumlah
  barang siap diambil.
- Jenis produk tidak memaksa pekerjaan dikerjakan sendiri atau oleh vendor.
  Produk cetak yang sama dapat memiliki jumlah internal dan jumlah vendor pada
  nota yang sama.
- CS dapat memilih rencana awal internal atau vendor pada saat membuat nota
  jika keputusan tersebut sudah diketahui. Rencana itu dapat berubah oleh
  bagian produksi karena tenggat, kapasitas, atau kerusakan mesin; perubahan
  harus tercermin pada pembagian pekerjaan aktual.

Jika pencatatan rencana jalur pada saat transaksi dianggap perlu, UI harus
menjelaskan bahwa itu adalah **rencana**, bukan status/fakta produksi final.
Rincian perubahan dari rencana ke jalur aktual dan siapa yang mengubahnya
masih perlu diputuskan.

### 2. Nota dan rincian pesanan

Pada `/nota`, pengguna melihat daftar nota. Tombol detail membuka
`/nota/detail/{id}`.

Halaman detail nota menjadi tempat kerja transaksi untuk:

- melihat dan mengubah data header nota;
- CRUD item pesanan pada `nota_isi`;
- melihat harga, subtotal, diskon, pembayaran, net total, dan sisa;
- melihat ringkasan produksi per item (jumlah belum dialokasikan, sedang
  dikerjakan, selesai/siap, atau perlu penanganan);
- melihat jumlah yang telah diserahkan dan jumlah yang masih dapat diserahkan;
- memulai transaksi pengambilan atas item yang sudah siap;
- melihat riwayat pengambilan per item;
- menjalankan aksi cetak invoice dan aksi nota lain yang telah disepakati.

Status pada ringkasan item merupakan gambaran agregat. Bila pekerjaan untuk
item yang sama tersebar pada beberapa batch dengan status berbeda, pengguna
tetap dapat membuka rincian pekerjaan sumbernya, bukan menganggap item
memiliki satu status produksi yang menggantikan semuanya.

### 3. Membuat pekerjaan produksi

Menu **Data Produksi** berada di `/cetak`. Halaman daftar menampilkan pekerjaan
per batch/file, bukan satu baris untuk setiap `nota_isi`.

Saat membuat pekerjaan, operator atau penjadwal:

1. memilih jalur **Internal** atau **Vendor**;
2. memilih mesin untuk jalur internal atau vendor untuk jalur vendor;
3. memilih satu atau beberapa item Cetak yang belum seluruh kuantitasnya
   dialokasikan;
4. menentukan kuantitas dari setiap item yang masuk ke batch/file;
5. menyimpan pekerjaan sebagai Draft/Antrian untuk diproses.

Satu pekerjaan dapat memuat item dari beberapa nota jika mesin/jalur dan
kebutuhan produksinya sesuai. Satu item pada satu nota dapat dialokasikan ke
beberapa pekerjaan, termasuk sebagian internal dan sebagian vendor.

#### Contoh pembagian produksi

| Pekerjaan/file | Jalur dan alat | Item yang dialokasikan |
|---|---|---|
| Cetak 001 | Internal, mesin outdoor | Ahmad: spanduk Flexy 280 6 pcs; Budi: spanduk Flexy 280 2 pcs |
| Cetak 002 | Internal, mesin outdoor | Budi: spanduk Flexy 280 3 pcs |
| Cetak 003 | Internal, mesin indoor | Budi: stiker 1 pcs |
| Pekerjaan vendor | Vendor yang ditunjuk | Ahmad: sisa spanduk Flexy 280 4 pcs |
| Pekerjaan alat lain | Jalur dan alat belum ditentukan | Budi: mug 10 pcs, misalnya press mug bila dikerjakan internal |
| Pekerjaan alat lain | Jalur dan alat belum ditentukan | Budi: kaos 5 pcs, misalnya metode/alat sablon bila dikerjakan internal |

Contoh ini melengkapi simulasi awal dengan alokasi eksplisit untuk seluruh
jumlah item. Pekerjaan Ahmad 4 pcs vendor dapat digabung ke batch vendor lain
apabila vendor dan kebutuhan kerja sama; nomor pekerjaan di contoh bukan
keharusan desain.

### 4. Memproses dan menyelesaikan pekerjaan

Halaman `/cetak/detail/{id}` menampilkan satu batch beserta seluruh item dan
kuantitas alokasinya. Status yang terlihat pada batch:

| Nilai konsep | Status |
|---:|---|
| 0 | Draft, belum masuk antrean kerja |
| 1 | Antrian |
| 2 | Pending |
| 3 | Proses |
| 4 | Selesai |
| 5 | Batal |

Perubahan status berlaku pada batch. Pembatalan atau pemindahan kuantitas ke
pekerjaan lain harus mengembalikan kuantitas tersebut ke jumlah yang belum
dialokasikan; pekerjaan yang sudah mulai atau sudah memakai bahan mungkin
memerlukan alur koreksi, bukan sekadar pembatalan biasa.

Untuk pekerjaan internal, detail pekerjaan juga menyediakan pencatatan
pemakaian bahan, hasil bahan sisa, dan limbah. Penggunaan stok, sisa, dan
limbah ditautkan ke batch/item produksi yang menghasilkannya agar dapat
ditelusuri.

Untuk pekerjaan vendor, halaman mencatat vendor dan progres sampai barang
diterima di tempat usaha. Konsep yang disarankan adalah status Selesai yang
siap untuk pengambilan baru diberikan setelah barang diterima dan lolos
pemeriksaan, bukan hanya setelah vendor menyatakan proses cetaknya selesai.
Tahap vendor seperti Dipesan, Dikerjakan, Dikirim, dan Diterima masih perlu
disepakati.

### 5. Pengambilan barang

Pencatatan pengambilan dimulai dari `/nota/detail/{id}`. Pengguna memilih satu
atau beberapa item Cetak yang telah siap, memasukkan kuantitas yang diserahkan,
dan menyimpan transaksi dengan tanggal/waktu serta pengguna yang melayani.

#### Contoh pengambilan Ahmad

| Tanggal | Barang diserahkan |
|---|---|
| Senin, 1 Januari | Spanduk 2 × 1 m: 6 pcs; kartu nama: 3 box |
| Rabu, 3 Januari | Spanduk 2 × 1 m: 4 pcs |

#### Contoh pengambilan Budi

| Tanggal | Barang diserahkan |
|---|---|
| Senin, 1 Januari | Seluruh barang siap: spanduk, stiker, mug, dan kaos |

Satu transaksi pengambilan dapat berisi beberapa item dari nota yang sama.
Item yang sama dapat muncul pada beberapa transaksi berbeda. UI harus
memperlihatkan kuantitas siap, kuantitas yang akan diserahkan, dan kuantitas
tersisa sebelum transaksi dikonfirmasi. Jasa desain dan jasa pemasangan tidak
ditawarkan sebagai item barang untuk diambil.

Pada rancangan awal frontend, fitur ini menjadi bagian dari detail nota,
bukan menu terpisah. Menu antrean Pengambilan Barang (`/pengambilan`) dapat
ditambahkan bila operasional membutuhkan daftar lintas nota untuk meja
serah-terima.

## Rancangan menu dan URL frontend

| Area/menu | URL | Halaman dan fitur utama |
|---|---|---|
| Nota Penjualan | `/nota` | Daftar, pencarian/filter nota, tambah nota, ringkasan status transaksi, tautan detail |
| Detail Nota (non-menu) | `/nota/detail/{id}` | Header nota, item pesanan, nilai transaksi, pembayaran, ringkasan/alokasi produksi, status siap diambil, catat pengambilan, riwayat pengambilan, invoice |
| Produk | `/produk` | CRUD katalog; jenis Cetak/Jasa serta atribut teknis dan harga |
| Data Produksi | `/cetak` | Daftar batch/file; filter status, jalur, mesin/vendor; buat pekerjaan dari kuantitas item nota; lihat antrean dan progres |
| Detail Produksi (non-menu) | `/cetak/detail/{id}` | Item/kuantitas dalam batch, status pekerjaan, mesin/vendor, pemakaian bahan internal, sisa/limbah, penerimaan hasil vendor |
| Vendor (usulan Master) | `/vendor` | CRUD vendor produksi, informasi kontak, status aktif; pilihan vendor saat pekerjaan vendor dibuat |
| Pengambilan Barang (opsional) | `/pengambilan` | Antrean lintas nota untuk barang siap/diambil sebagian; usulan menu hanya bila alur kasir membutuhkannya |

Menu yang disarankan untuk tahap awal: Nota Penjualan, Data Produksi, Vendor,
serta menu existing terkait Master dan persediaan. Detail Nota dan Detail
Produksi adalah halaman non-menu yang dicapai dari tabel masing-masing.
Pembayaran tetap tersedia pada halaman detail nota dan daftar Pembayaran Nota
yang sudah ada, sampai kebutuhan daftar pembayaran lintas modul ditetapkan.

`/vendor` merupakan usulan tersendiri karena vendor jasa produksi berbeda
tanggung jawab dari Supplier bahan. Sebelum dibuat, perlu diputuskan apakah
master Vendor benar-benar terpisah atau identitas vendor dapat menggunakan
data Supplier yang sudah tersedia dengan penanda/peran tambahan.

## Ringkasan data yang perlu didukung backend

Bagian ini menunjukkan kebutuhan yang ditimbulkan alur, bukan keputusan final
nama tabel atau kolom.

```text
nota
  └── nota_isi                         pesanan Cetak/Jasa dan kuantitas
        ├── alokasi pekerjaan produksi kuantitas item per batch
        │     └── pekerjaan produksi   jalur Internal/Vendor dan status batch
        └── detail transaksi ambil      kuantitas item per transaksi
              └── transaksi ambil       tanggal/waktu dan petugas
```

- Model data harus mewakili relasi many-to-many antara item nota dan pekerjaan
  produksi, dengan kuantitas pada setiap alokasi.
- Pekerjaan internal perlu menunjuk mesin dan mengaitkan pemakaian bahan,
  bahan sisa, serta limbah ke pelaksanaan yang tepat.
- Pekerjaan vendor perlu menunjuk vendor; pembagian kuantitas item ke
  pekerjaan internal dan vendor harus sama-sama dapat ditelusuri.
- Transaksi pengambilan perlu merekam kuantitas per item dan waktu kejadian,
  bukan hanya satu nilai status/tanggal ringkasan.
- Jumlah siap diambil tidak boleh melebihi kuantitas pekerjaan yang selesai
  dan diterima. Kuantitas yang sudah diserahkan tidak boleh diserahkan kembali.
- Jasa tidak dialokasikan ke pekerjaan cetak atau transaksi pengambilan barang.
- Ringkasan status nota/item di UI diturunkan dari pekerjaan serta transaksi
  pengambilan. Status ringkasan tidak menjadi pengganti catatan kuantitas.

## Hal yang perlu diaudit sebelum rancangan backend

1. **Tipe produk:** apakah hanya dua jenis Cetak/Jasa, dan bagaimana produk
   paket yang mengandung barang sekaligus jasa diperlakukan?
2. **Satuan kuantitas:** satuan yang dicatat di pesanan, pekerjaan, dan
   pengambilan harus jelas untuk pcs, box, luas, jasa, dan produk lain.
3. **Rencana vs jalur aktual:** apakah CS memilih rencana Internal/Vendor di
   nota, atau jalur baru dipilih penjadwal? Siapa yang dapat mengubahnya dan
   apakah perubahan perlu riwayat?
4. **Ketersediaan mesin:** apakah satu batch/file hanya memakai satu mesin?
   Bagaimana mesin press mug dan sablon kaos direpresentasikan dalam master
   mesin dan kelompok kompatibilitas produk?
5. **Batas penggabungan batch:** kapan item dari beberapa nota boleh disatukan
   dalam satu file—mesin, bahan, warna, ukuran, deadline, vendor, atau keputusan
   operator?
6. **Arti status Selesai:** untuk internal, kapan hasil dianggap selesai dan
   siap diserahkan? Untuk vendor, apakah perlu status diterima/inspeksi
   tersendiri sebelum siap diambil?
7. **Hasil parsial dan cetak ulang:** bagaimana hasil sebagian, barang rusak,
   cetak ulang, kekurangan hasil vendor, atau pembatalan setelah bahan
   terpakai direkam?
8. **Pemakaian bahan:** pada status/peristiwa apa stok internal dipotong?
   Bagaimana pembatalan, cetak ulang, sisa layak pakai, dan limbah dikoreksi
   tanpa mutasi ganda?
9. **Biaya vendor dan HPP aktual:** bagaimana biaya vendor aktual dicatat dan
   memengaruhi HPP/laporan ketika jalur berubah setelah nota dibuat?
10. **Vendor vs Supplier:** apakah entitas dan menu Vendor terpisah dari
    Supplier bahan, atau berbagi master kontak?
11. **Antrean pengambilan:** apakah entry point di detail nota cukup, atau
    perlu menu lintas nota `/pengambilan` untuk petugas counter?
12. **Pembayaran dan perubahan pesanan:** apakah item/kuantitas dan jalur kerja
    boleh diubah setelah pembayaran sebagian, pekerjaan dibuat, atau hasil
    mulai diproduksi?
13. **Hak akses:** siapa membuat/mengubah pesanan, mengalokasikan pekerjaan,
    mengubah vendor/mesin, mencatat bahan, dan menyerahkan barang?

## Hubungan dengan dokumentasi dan implementasi saat ini

Dokumen ini sengaja belum mengubah implementasi maupun dokumen aturan lama.
Beberapa konsep di [Alur Aplikasi](./03-alur-aplikasi.md),
[Nota dan POS](./modul/nota.md), dan [Skema Bahan](./06-skema-bahan.md)
masih mengasumsikan kategori produk Internal/Eksternal/Jasa serta status
produksi pada `nota_isi`. Perbedaan itu adalah bahan audit, bukan aturan yang
diam-diam dianggap sudah diganti.

Setelah alur disetujui:

1. tetapkan keputusan terbuka dan istilah UI;
2. perbarui dokumentasi domain Nota, Produk, Produksi, dan Bahan agar
   menjelaskan aturan terkini;
3. putuskan apakah skema/migrasi dan route saat ini diteruskan atau proyek
   dimulai ulang;
4. baru susun rancangan backend, validasi, transisi status, dan migrasi;
5. implementasikan frontend berdasarkan alur yang disepakati.

Tidak ada migrasi, perubahan route, atau revisi aturan kanonis yang diusulkan
oleh dokumen draft ini.
