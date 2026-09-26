# Alur Aplikasi

Dokumen ini menjelaskan alur aplikasi tingkat tinggi. Detail yang sudah panjang
akan dipisahkan ke folder `alur/` tanpa menghapus dokumen lama.

## Gambaran umum

Portal MA memiliki lima alur utama:

```text
Nota penjualan/POS
  -> Pembayaran
  -> Proses cetak
  -> Pemakaian bahan
  -> Laporan
```

Di sisi pengadaan bahan:

```text
Master bahan
  -> Order bahan
  -> Stok bahan
  -> Bahan terpakai
  -> Bahan sisa atau limbah
```

## 1. Nota penjualan/POS

Alur dasar:

```text
Konsumen
  -> Produk dan harga
  -> Nota
  -> Detail nota
  -> Pembayaran
```

Hal yang perlu ditetapkan dan diverifikasi:

- pemilihan konsumen;
- pemilihan produk dan harga;
- penggunaan tipe harga atau harga khusus;
- perhitungan subtotal, biaya tambahan, diskon, dan total;
- status nota;
- perubahan nota setelah pembayaran;
- pembayaran sebagian, lunas, atau pembatalan;
- kapan nota diteruskan ke proses cetak.

Tabel utama yang sudah dirancang: `konsumen`, `produk`, `harga_tipe`,
`harga_khusus`, `nota`, `nota_isi`, dan `nota_bayar`.

### 1.1 Alur berdasarkan kategori produk

`produk.kategori` menentukan perlakuan setiap item nota:

| Kategori | Produksi | Masuk `/cetak` | Potong stok bahan | Sisa / limbah | Alur status `nota_isi` |
|---|---|---|---|---|---|
| `0` Internal | mesin sendiri | ya (`status IN (1,2)`) | ya | ya | `0 → 1 → 3 → 4` |
| `1` Eksternal | vendor / mitra luar | tidak | tidak | tidak | `0 → 3 → 4` |
| `2` Jasa/Layanan | tanpa produksi | tidak | tidak | tidak | `0 → 3 → 4` |

- Item kategori `0` baru masuk antrean cetak bila produknya memakai **bahan**
  (`bahan_jenis.jenis = 0`, diproses mesin).
- Kategori `1` dan `2` berhenti pada `3 (Proses)` lalu diselesaikan menjadi `4 (Selesai)`
  tanpa melalui halaman cetak.
- Biaya vendor dan jasa **diinput manual pada `produk.hpp`**; tidak ada tabel biaya vendor
  terpisah. Contoh: Flexy 280 internal HPP 10.000 / vendor HPP 15.000 dengan harga jual sama
  20.000; jasa desain HPP 5.000 (boleh 0) dengan harga 15.000.
- Halaman cetak memakai nama menu **Data Produksi** dengan URL tetap `/cetak`
  (judul "Rincian Data Produksi") agar dapat menampung kategori lain tanpa mengubah URL.
- Tombol pemindah item dari `0 (Draft)` pada `/nota/isi?edit=` bersifat otomatis per kategori:
  kategori `0` berbahan menjadi `1 (Antrian)`, sedangkan kategori lain menjadi `3 (Proses)`.
- Khusus jasa, urutan kerja fleksibel: jasa boleh diselesaikan lebih dulu baru dibuat nota,
  atau nota dibuat lebih dulu baru jasa dikerjakan.

### 1.2 Aturan perhitungan rincian nota

- `luas = lebar × panjang`, dibulatkan maksimal 2 angka di belakang koma.
  Contoh: `1,27 × 1,6 = 2,032 → 2,03`; `1,27 × 1,67 = 2,1209 → 2,12`; `0,62 × 0,45 = 0,279 → 0,28`.
- `jumlah = luas × qty × harga` untuk rumus perkalian luas, atau `qty × harga` untuk perkalian qty.
- `jumlah` dibulatkan **ke atas pada kelipatan 500**.
  Contoh: `123.001 → 123.500`; `123.501 → 124.000`; `123.500 → 123.500`.
- **Harga minimum** (toggle "Harga Minimum" pada form rincian) hanya berlaku untuk rumus perkalian
  luas: bila diaktifkan, `jumlah` tidak boleh lebih kecil daripada `harga` produk.
  Contoh: harga 20.000 dengan hasil hitung 15.000 → jumlah menjadi 20.000.
  Toggle selalu mulai dari **Tidak** pada form tambah maupun edit, tidak disimpan di database, dan
  hanya memengaruhi nilai `jumlah` ketika diklik oleh pengguna.
- Server belum menghitung ulang `luas` dan `jumlah`; nilai dari form dipakai apa adanya
  (`NotaIsi::dataSimpan()`), sehingga aturan di atas masih bergantung pada JavaScript.

## 2. Pembayaran nota

Pembayaran dicatat melalui menu **Pembayaran Nota** (`/nota/bayar`):

```text
Daftar nota -> tombol "bayar" pada baris nota -> /nota/bayar?edit=<id nota>
  -> input tanggal, metode, uang diterima, jumlah bayar
  -> trigger menghitung ulang bayar, sisa, dan status nota
```

- Tanpa parameter `edit`, halaman menampilkan seluruh pembayaran sebagai daftar.
- `kembalian = uang diterima − jumlah bayar`, tidak boleh negatif.
- Nota dengan status `3` (Hapus Nota) tidak dapat diubah pembayarannya.

## 3. Manajemen bahan

Alur dasar:

```text
Bahan jenis (karakter)
  -> Bahan (detail paket fisik)
  -> Supplier
  -> Order bahan
  -> Detail order
  -> Tambah ke stok
  -> Stok aktif
  -> Pemakaian
  -> Sisa atau limbah
```

Aturan awal yang sudah dicatat:

- `bahan_jenis` adalah layer karakter yang dipakai CS dan master produk; `bahan` adalah layer
  detail paket fisik yang dipakai purchasing dan operator mesin;
- `bahan_jenis.jenis` memisahkan bahan (`0`, diproses mesin) dan material (`1`, pendukung statis);
- order bahan dapat diedit sebelum dimasukkan ke stok;
- setelah stok dibuat, order dan detailnya perlu dikunci sesuai aturan bisnis;
- stok memiliki status aktif atau nonaktif;
- satuan dan isi paket memengaruhi perhitungan stok;
- bahan yang dipakai harus dapat ditelusuri ke proses cetak;
- bahan sisa dan limbah harus dapat dibedakan;
- material tidak menghasilkan sisa maupun limbah.

Tabel utama yang sudah dirancang: `bahan_jenis`, `bahan`, `supplier`, `bahan_order`,
`bahan_order_isi`, `bahan_stok`, `material_stok`, `bahan_sisa`, dan `bahan_limbah`.

### 3.1 Order bahan dan material

Alur order menerima dua jenis item sekaligus:

```text
bahan_order_isi.bahan_id
  -> bahan_jenis.jenis = 0 (Bahan)    -> generate bahan_stok per paket/roll
  -> bahan_jenis.jenis = 1 (Material) -> akumulasi ke material_stok
```

- Material masuk lewat `bahan_order` dan `bahan_order_isi` yang sama; tidak ada tabel order terpisah.
- Saat order ditandai masuk stok, bahan menghasilkan baris `bahan_stok` per paket, sedangkan
  material menambah `stok_masuk` pada satu baris `material_stok`.

### 3.2 Pemakaian material

Pemakaian material belum punya tabel mutasi. Pemotongan dilakukan otomatis saat
`nota_isi.status` berubah menjadi `4 (Selesai)` dan hanya berlaku untuk produk yang memiliki
`material_jenis_id`. Aturan lengkap ada pada
[Skema Bahan dan Material](./06-skema-bahan.md).

Detail simulasi manajemen bahan akan dilengkapi kembali pada dokumentasi skema
database atau dokumen alur khusus setelah struktur tersebut diverifikasi.

## 4. Proses cetak

Alur dasar:

```text
Nota siap diproses
  -> Penjadwalan atau pencatatan cetak
  -> Pemilihan mesin dan bahan
  -> Proses produksi
  -> Pemakaian stok
  -> Sisa bahan atau limbah
  -> Status selesai atau perlu tindakan
```

Hal yang perlu ditetapkan:

- kapan nota masuk antrean cetak;
- mesin yang digunakan;
- bahan yang digunakan;
- jumlah bahan yang dipakai;
- pencatatan operator;
- proses cetak ulang atau pembatalan;
- pencatatan bahan sisa dan limbah;
- hubungan status cetak dengan status nota.

Tabel utama yang sudah dirancang: `mesin_tipe`, `mesin`, `cetak`, dan `cetak_isi`,
serta tabel bahan yang terkait.

## 5. Laporan keuangan

Alur dasar:

```text
Nota
  -> Pembayaran
  -> Rekap penerimaan
  -> Laporan
  -> Detail laporan
  -> Log perubahan
```

Hal yang perlu ditetapkan:

- periode laporan;
- penerimaan tunai dan transfer;
- hubungan pembayaran dengan nota;
- koreksi data laporan;
- siapa yang dapat membuat, mengubah, dan mengunci laporan;
- pencatatan log perubahan.

Tabel utama yang sudah dirancang: `nota_bayar`, `laporan`, `laporan_isi`,
dan `laporan_log`.

## 6. Statistik dan grafik dashboard

Statistik dashboard adalah area tersendiri yang menggunakan data dari alur utama.
Implementasinya dapat dibuat pada halaman dashboard yang sama atau dipisahkan
menjadi komponen/halaman statistik tersendiri setelah kebutuhan metrik lebih jelas.

Kandidat statistik:

- penjualan per hari, minggu, atau bulan;
- jumlah nota berdasarkan status;
- penerimaan tunai dan transfer;
- antrean serta status proses cetak;
- penggunaan dan sisa stok bahan;
- bahan yang rusak atau menjadi limbah;
- ringkasan performa per role atau area kerja.

Metrik, sumber query, rentang waktu, filter, dan hak akses statistik belum final.
Jangan menganggap daftar kandidat ini sebagai spesifikasi implementasi.

## Ketergantungan alur

```text
Master data
  -> Nota penjualan
  -> Pembayaran
  -> Proses cetak
  -> Pemakaian bahan
  -> Laporan
  -> Statistik dashboard
```

Manajemen bahan berjalan paralel sebagai sumber persediaan untuk proses cetak.
Perubahan pada status nota, pembayaran, atau stok dapat memengaruhi alur berikutnya
dan harus diverifikasi secara menyeluruh.

## Pertanyaan desain yang belum diputuskan

- Apakah nota dapat diubah setelah sebagian pembayaran?
- Apakah nota dapat dicetak tanpa pembayaran atau uang muka?
- Bagaimana pembatalan nota memulihkan stok atau pembayaran?
- Apakah satu nota dapat memiliki beberapa proses cetak?
- Bagaimana cetak ulang dicatat?
- Kapan laporan keuangan dikunci?
- Statistik dashboard menggunakan data real-time atau data agregat?
