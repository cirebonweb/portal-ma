# ARSITEKTUR DATABASE & SKEMA OPERASIONAL DIGITAL PRINTING

Dokumen ini berisi spesifikasi teknis basis data, batasan modul, serta alur data (workflow) untuk aplikasi ERP/Sistem Operasional Digital Printing.

---

## 1. KONSEP UTAMA: PEMISAHAN PERAN (DECOUPLING ARCHITECTURE)

Sistem memisahkan perspektif data menjadi **2 Layer Utama** untuk mencegah kesalahan input (*human error*) dan mempercepat transaksi:

Di antarmuka, menu `/bahan` disebut **Bahan Cetak** untuk membedakannya dari material pendukung.
Nama tabel `bahan` dan URL `/bahan` tetap dipertahankan; rumus tetap menjadi atribut `bahan_jenis`,
dan tidak disimpan pada tabel `bahan`.

1. **Front-Office (CS / Kasir):** 
   - CS hanya berhubungan dengan **`bahan_jenis`** (Gramasi/Tipe Bahan tanpa dimensi roll, contoh: *Flexy 280 gsm*).
   - CS **tidak perlu tahu** stok roll fisik berukuran berapa yang tersedia di gudang (`3.2x60m`, `2.2x70m`, dsb).
2. **Back-Office (Purchasing, Keuangan & Operator Mesin):**
   - Purchasing & Keuangan membeli barang menggunakan master **`bahan`** (Master spesifikasi roll rapi dengan opsi dropdown *Lebar x Panjang*, tanpa input manual).
   - Operator Mesin mencatat produksi dan memotong stok fisik riil per-roll pada **`bahan_stok`**.

### 1.1 Pemisah bahan dan material

`bahan_jenis` juga memisahkan **sifat** barang melalui kolom `kategori`. Pemisahan ini tidak menambah
tabel master baru, karena material tetap memakai `bahan_jenis` dan `bahan` yang sama.

| `kategori` | Nama | Sifat | Tabel stok | Sisa / limbah |
|---|---|---|---|---|
| `0` | Bahan | diproses mesin, dinamis | `bahan_stok` per roll/paket | ya |
| `1` | Material | pendukung bahan, statis | `material_stok` agregat | tidak |

- **Bahan** (Indoor/Outdoor, Copy Colour A3+, Cutting, Kaos, Mug) dihitung per luas atau per qty, dan
  menghasilkan `bahan_sisa` serta `bahan_limbah`.
- **Material** (rangka X-Banner/Y-Banner/Roll-Banner, dsb) tidak melewati mesin, tidak menghasilkan
  sisa maupun limbah, dan stoknya bertambah/berkurang secara agregat.

---

## 2. STRUKTUR MIGRASI DATABASE (CodeIgniter 4)

Struktur tabel dibawah ini adalah potongan kolom tabel yang hanya memiliki keterangan.

### A. Master Data Layer

```php
// TABEL mesin_tipe
$this->forge->addField([
    'nama' => ['type' => 'varchar', 'constraint' => 100, 'unique' => true], // e.g., Outdoor, Indoor, Cutting, Copy Colour A3+
]);
$this->forge->createTable('mesin_tipe');

// TABEL bahan_jenis (Digunakan oleh CS & Master Produk)
$this->forge->addField([
    'mesin_tipe_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // NULL untuk material
    'kategori'      => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Bahan Cetak (diproses mesin), 1:Material (pendukung)
    'kode'          => ['type' => 'varchar', 'constraint' => 20], // e.g., FLX280, XBN-60x160
    'nama'          => ['type' => 'varchar', 'constraint' => 100], // e.g., Flexy 280 gsm
    'rumus'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Perkalian luas, 1:Perkalian qty
]);
$this->forge->createTable('bahan_jenis');

// TABEL bahan (Detail Paket Fisik - Digunakan oleh Purchasing PO & Operator)
$this->forge->addField([
    'bahan_jenis_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // FK ke bahan_jenis
    'kode'           => ['type' => 'varchar', 'constraint' => 30], // e.g., FLX280-3260
    'nama'           => ['type' => 'varchar', 'constraint' => 100], // e.g., Flexy 280 gsm (3.2 x 60m)
    'lebar'          => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // 3.2 m
    'panjang'        => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // 60 m
    'isi_paket'      => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // 3.2 x 60 = 192 m2
    'satuan_1'       => ['type' => 'varchar', 'constraint' => 10, 'default' => 'm²'],   // satuan isi stok (kecil)
    'satuan_2'       => ['type' => 'varchar', 'constraint' => 10, 'default' => 'roll'], // satuan paket pembelian (besar)
]);
$this->forge->createTable('bahan');

// TABEL produk (Katalog Item Penjualan CS)
$this->forge->addField([
    'bahan_jenis_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // FK ke bahan_jenis (bahan cetak)
    'material_jenis_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // FK ke bahan_jenis (material, jenis = 1)
    'kategori'          => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Internal, 1:Eksternal, 2:Jasa
    'lebar'             => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // ukuran nominal pesanan
    'panjang'           => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // ukuran nominal pesanan
    'rumus'             => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Perkalian luas, 1:Perkalian qty
    'status'            => ['type' => 'tinyint', 'constraint' => 1, 'default' => 1], // 0:Nonaktif, 1:Aktif
]);
$this->forge->createTable('produk');
```

### B. Procurement & Inventory Layer

```php
// TABEL bahan_order (Header Transaksi PO / Beli Bahan)
$this->forge->addField([
    'status_stok' => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Belum Masuk Stok, 1:Sudah Masuk Stok
]);
$this->forge->createTable('bahan_order');

// TABEL bahan_order_isi (Detail Item PO Beli Bahan)
$this->forge->addField([
    'bahan_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // FK ke master bahan (spesifik)
    'qty'            => ['type' => 'smallint', 'constraint' => 6, 'default' => 0], // Banyaknya paket pembelian
]);
$this->forge->createTable('bahan_order_isi');

// TABEL bahan_stok (Penampung Fisik Roll Real per Barcode)
$this->forge->addField([
    'kode_bahan'     => ['type' => 'varchar', 'constraint' => 50, 'null' => true, 'unique' => true], // FLX280-202612-0001
    'stok_masuk'     => ['type' => 'decimal', 'constraint' => '7,2', 'default' => 0.00], // Luas total m2/Qty satu paket
    'stok_pakai'     => ['type' => 'decimal', 'constraint' => '7,2', 'default' => 0.00], // Total pemakaian m2
    'stok_sisa'      => ['type' => 'decimal', 'constraint' => '7,2', 'default' => 0.00], // stok_masuk - stok_pakai
    'kondisi'        => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Baik, 1:Rusak, 2:Cacat
    'status'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Aktif, 1:Nonaktif, 2:Habis
    'keterangan'     => ['type' => 'varchar', 'constraint' => 100, 'null' => true], 
]);
$this->forge->createTable('bahan_stok');

// TABEL material_stok (Penampung Stok Material - Agregat Per Bahan Jenis)
// Material bersifat statis: satu baris menampung akumulasi seluruh pembelian dan pemakaian.
$this->forge->addField([
    'bahan_jenis_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // FK ke bahan_jenis (jenis = 1)
    'stok_masuk'     => ['type' => 'decimal', 'constraint' => '9,2', 'default' => 0.00], // += qty beli (akumulasi)
    'stok_pakai'     => ['type' => 'decimal', 'constraint' => '9,2', 'default' => 0.00], // += pemakaian (akumulasi)
    'stok_sisa'      => ['type' => 'decimal', 'constraint' => '9,2', 'default' => 0.00], // stok_masuk - stok_pakai
    'harga_satuan'   => ['type' => 'int', 'constraint' => 11, 'default' => 0],           // harga beli terakhir
    'kondisi'        => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Baik, 1:Rusak, 2:Cacat
    'status'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Aktif, 1:Nonaktif, 2:Habis
    'keterangan'     => ['type' => 'varchar', 'constraint' => 100, 'null' => true],
]);
$this->forge->addUniqueKey('bahan_jenis_id'); // 1 spesifikasi material = 1 baris stok
$this->forge->createTable('material_stok');
```

### C. Production & Waste Layer

```php
// TABEL cetak (Eksekusi Printing oleh Operator)
$this->forge->addField([
    'bahan_stok_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // Pemakaian dari Roll Utuh
    'bahan_sisa_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true], // Pemakaian dari Potongan Sisa
    'lebar'         => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // Lebar tarikan mesin
    'panjang'       => ['type' => 'decimal', 'constraint' => '5,2', 'default' => 0.00], // Panjang tarikan mesin
    'luas'          => ['type' => 'decimal', 'constraint' => '10,2', 'default' => 0.00], // Luas total dipotong dari stok
    'lokasi'        => ['type' => 'varchar', 'constraint' => 255, 'null' => true], // sumber lokasi file cetak tanpa upload file
    'user_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true], // ID Operator
]);
$this->forge->createTable('cetak');

// TABEL cetak_isi (Jembatan Item Cetak dengan Item Nota Sales)
$this->forge->addField([
    'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
    'cetak_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
    'nota_isi_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
    'created_at'  => ['type' => 'timestamp', 'null' => true],
]);
$this->forge->createTable('cetak_isi');

// TABEL bahan_sisa (Pencatatan Potongan Bahan Layak Cetak)
$this->forge->addField([
    'status'     => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Tersedia, 1:Tercetak Kembali
    'keterangan' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
]);
$this->forge->createTable('bahan_sisa');

// FK sirkular: bahan_sisa baru ada setelah tabel cetak dibuat, sehingga
// fk_cetak_bahan_sisa ditambahkan lewat ALTER TABLE setelah bahan_sisa tersedia.
$this->db->query('ALTER TABLE cetak ADD CONSTRAINT fk_cetak_bahan_sisa FOREIGN KEY (bahan_sisa_id) REFERENCES bahan_sisa(id) ON DELETE SET NULL ON UPDATE CASCADE');

// TABEL bahan_limbah (Pencatatan Waste / Scrap Unusable)
$this->forge->addField([
    'jenis'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0], // 0:Operasional, 1:Lainnya, 2:Cacat Cetak, 3:Mesin Error
]);
$this->forge->createTable('bahan_limbah');
```

## 3. ALUR OPERASIONAL SISTEM (WORKFLOW)

**Phase 1: Pengadaan Bahan (PO Keuangan & Gudang)**

1. Keuangan / Purchasing membuka form PO (bahan_order).
2. Di baris item (bahan_order_isi), user memilih item dari dropdown master bahan (Pilihan spesifik rapi, misal: Flexy 280 gsm (3.2 x 60m)).
3. Saat status PO berubah menjadi Diterima, sistem otomatis me-generate baris di bahan_stok sesuai qty roll yang dibeli, lengkap dengan barcode/kode unik roll (kode_bahan).

**Phase 2: Penjualan / Input Order Customer (CS / Kasir)**

1. Customer datang memesan spanduk/banner.
2. CS memilih item dari tabel produk, yang mana terhubung ke bahan_jenis (Misal: Flexy 280 gsm).
3. CS menginput ukuran pesanan pelanggan (Misal: Panjang 3m, Lebar 1m, Qty 2 pcs).
4. Data tersimpan di nota & nota_isi. CS tidak perlu mendaftarkan/memilih ukuran roll fisik yang akan dipotong.
5. Untuk produk paket (mis. **X-Banner 60x160 Flexy 280**), produk menautkan `bahan_jenis_id`
   (bahan cetak) dan `material_jenis_id` (rangka) dengan satu harga paket.

**Phase 3: Eksekusi Cetak & Pemotongan Stok (Operator Mesin)**

1. Operator membuka layar antrean SPK (nota_isi).
2. Operator melihat roll fisik yang saat ini terpasang di mesin (Misal: Barcode FLX280-001 ukuran roll 3.2m).
3. Operator menginput data cetak riil pada tabel cetak:
- Memilih bahan_stok_id (Roll aktif yang dipotong).
- Memasukkan ukuran riil bahan terbuang/terpakai di mesin (Misal: Lebar 3.2m, Panjang 2.1m = Luas 6.72 m²).
4. System otomatis memotong stok_pakai di tabel bahan_stok sebesar 6.72 m².
5. Jika ada sisa potongan bersih yang cukup besar, dimasukkan ke bahan_sisa. Jika margin terbuang sobek/cacat, dicatat ke bahan_limbah.

**Phase 4: Pemakaian Material (Statis, Otomatis)**

1. Saat `nota_isi.status` berubah menjadi `4 (Selesai)`, sistem membaca `produk.material_jenis_id`.
2. Bila produk memakai material, sistem memotong `material_stok`: `stok_pakai += nota_isi.qty`.
3. Material tidak menghasilkan `bahan_sisa` maupun `bahan_limbah`.
4. Bila status dikembalikan dari `4` atau item nota dihapus, pemotongan dibatalkan.

## 4. Bahan Produksi
Bahan produksi adalah bahan yang di proses langsung melalui mesin digital printing yang dapat menghasilkan sisa bahan maupun limbah bahan.

**Indoor/Outdoor**
- Paket bahan 1 roll dihitung lebar x panjang.
- Rumus = Perkalian Luas.

| Nama | GSM | Ukuran | Satuan |
|-|-|-|-|
| Flexy | 260 gsm | 3.2 x 60 m | roll
| Flexy | 260 gsm | 3.2 x 70 m | roll
| Flexy | 260 gsm | 2.2 x 60 m | roll
| Flexy | 260 gsm | 2.2 x 70 m | roll
| Flexy | 280 gsm | 3.2 x 60 m | roll
| Flexy | 280 gsm | 3.2 x 70 m | roll
| Flexy | 280 gsm | 2.2 x 60 m | roll
| Flexy | 280 gsm | 2.2 x 70 m | roll
| Flexy | 300 gsm | 3.2 x 60 m | roll
| Flexy | 300 gsm | 3.2 x 70 m | roll
| Flexy | 300 gsm | 2.2 x 60 m | roll
| Flexy | 300 gsm | 2.2 x 70 m | roll
| Flexy | 340 gsm | 3.2 x 60 m | roll
| Flexy | 340 gsm | 3.2 x 70 m | roll
| Flexy | 340 gsm | 2.2 x 60 m | roll
| Flexy | 340 gsm | 2.2 x 70 m | roll

**Copy Colour A3+**
- Paket bahan 1 rim = 100 lembar.
- Rumus = Perkalian Qty

| Nama | GSM | Ukuran | Satuan |
|-|-|-|-|
| Bw Gca Lux (2 Muka) | 210 gsm | 0.79 x 1.09 m | rim
| Bw Gca Lux (2 Muka) | 230 gsm | 0.79 x 1.09 m | rim
| Bw Gca Lux (2 Muka) | 260 gsm | 0.79 x 1.09 m | rim
| Bw Gca Lux (2 Muka) | 310 gsm | 0.79 x 1.09 m | rim
| Linen Tebal Putih | 0 gsm | 0.79 x 1.09 m | rim
| Stiker Kromo Pindo | 0 gsm | 0.70 x 1.08 m | rim
| Stiker Kromo Bontax | 0 gsm | 0.70 x 1.08 m | rim
| Stiker Hvs Camel | 0 gsm | 0.70 x 1.08 m | rim
| Stiker Tranparant | 0 gsm | 0.48 x 0.79 m | rim
| Stiker Vinil | 0 gsm | 0.53 x 0.86 m | rim
| King Truk Gca | 150 gsm | 0.79 x 1.09 m | rim
| King Truk Gca | 120 gsm | 0.79 x 1.09 m | rim
| Stiker Vinil Quantac | 0 gsm | 0.32 x 0.45 m | rim
| Hvs Plano Rap	| 70 gsm | 0.65 x 1 m | rim
| Bc Karkutik Paper Plus | 200 gsm | 0. 79 x 1.09 m | rim

## 5. Material Produksi
Komponen atau material yang dapat dijual terpisah maupun dengan bahan lain (paket harga), misal:

**X-Banner 60 280 + Rangka**
- X-Banner = Rangka X ukuran 0.6 x 1.6 m
- 280 = Flexy 280 gsm
- Rumus = Perkalian Qty

| Nama | Ukuran | Satuan |
|-|-|-|
| Mini X-Banner | 0.26 x 0.38 m | pcs |
| X-Banner | 0.6 x 1.6 m | pcs |
| X-Banner | 0.8 x 1.8 m | pcs |
| Y-Banner | 0.6 x 1.6 m | pcs |
| Y-Banner | 0.8 x 1.8 m | pcs |
| Roll-Banner | 0.6 x 1.6 m | pcs |
| Roll-Banner | 0.8 x 1.8 m | pcs |
| Roll-Banner | 0.8 x 2 m | pcs |
| Roll-Banner | 0.85 x 2 m | pcs |

**Keterangan Bahan**

Daftar bahan diatas belum termasuk list bahan produksi yang digunakan mesin digital printing lainnya seperti:
- Cutting/Plotter
- Kaos Sablon Digital
- Press Mug

Sehingga baik produk maupun bahan bersifat fleksibel untuk berbagai mesin produksi digital printing.

### Aturan skema material

Material **tidak menambah tabel master baru**. Material memakai `bahan_jenis` dan `bahan` yang sama,
hanya dibedakan oleh kolom `kategori`:

| Objek | Nilai untuk material |
|---|---|
| `bahan_jenis.kategori` | `1` |
| `bahan_jenis.mesin_tipe_id` | `NULL` (tidak terikat mesin) |
| `bahan_jenis.kode` / `bahan_jenis.nama` | identitas material, mis. `XBN-60x160` / `X-Banner 0.6 x 1.6 m` |
| `bahan_jenis.rumus` | `1` (perkalian qty) |
| `bahan.kode` / `bahan.nama` | unit yang dibeli, mis. `XBN-0616` / `X-Banner 0.6 x 1.6 m (pcs)` |
| `bahan.isi_paket` | `1` |
| `bahan.satuan_1` / `bahan.satuan_2` | `pcs` |

Karena bersifat statis, material **tidak** memakai `bahan_stok`, `cetak`, `bahan_sisa`, maupun
`bahan_limbah`. Stok material dicatat pada `material_stok`.

### Perbedaan perilaku stok

| Aspek | Bahan Cetak (`kategori = 0`) | Material (`kategori = 1`) |
|---|---|---|
| Tabel stok | `bahan_stok` | `material_stok` |
| Baris stok | 1 baris per roll/paket, `kode_bahan` unik | 1 baris per `bahan_jenis` (agregat) |
| Kunci stok | `bahan_id` + `bahan_order_id` | `bahan_jenis_id` (unik) |
| `stok_masuk` | di-set saat order diterima | diakumulasi (`+=` qty beli) |
| Pemicu `stok_pakai` | proses `cetak` | `nota_isi.status = 4 (Selesai)` |
| Sisa / limbah | ya | tidak |

Contoh akumulasi `material_stok` untuk `X-Banner 0.6 x 1.6 m`:

| id | bahan_jenis | stok_masuk | stok_pakai | stok_sisa |
|---|---|---|---|---|
| 1 | X-Banner 0.6 x 1.6 m | 10 pcs | 3 pcs | 7 pcs |
| 1 | X-Banner 0.6 x 1.6 m (setelah order 5 pcs) | 15 pcs | 3 pcs | 12 pcs |

### Aturan pemakaian (potong) stok material

- Pemotongan hanya terjadi saat `nota_isi.status` **berubah** menjadi `4 (Selesai)`.
- Rumus: `material_stok.stok_pakai += nota_isi.qty` (satu unit produk memakai satu material).
- Setelahnya: `material_stok.stok_sisa = stok_masuk - stok_pakai`, dan `status = 2 (Habis)` bila
  `stok_sisa <= 0`.
- Karena berbasis transisi status, menyimpan ulang form dengan status tetap `4` **tidak** memotong
  dua kali.
- Status dikembalikan dari `4` atau `nota_isi` dihapus → pemotongan dibatalkan (stok dikembalikan).
- Belum ada tabel mutasi. Bila nanti dibutuhkan rincian per material per item (mis. satu item memakai
  rangka dan mata ayam), tambahkan tabel `material_pakai` tanpa mengubah `material_stok`.