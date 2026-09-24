# Alur Aplikasi

Dokumen ini menjelaskan alur aplikasi tingkat tinggi. Detail yang sudah panjang
akan dipisahkan ke folder `alur/` tanpa menghapus dokumen lama.

## Gambaran umum

Portal MA memiliki empat alur utama:

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

## 2. Manajemen bahan

Alur dasar:

```text
Bahan
  -> Supplier
  -> Order bahan
  -> Detail order
  -> Tambah ke stok
  -> Stok aktif
  -> Pemakaian
  -> Sisa atau limbah
```

Aturan awal yang sudah dicatat:

- order bahan dapat diedit sebelum dimasukkan ke stok;
- setelah stok dibuat, order dan detailnya perlu dikunci sesuai aturan bisnis;
- stok memiliki status aktif atau nonaktif;
- satuan dan isi paket memengaruhi perhitungan stok;
- bahan yang dipakai harus dapat ditelusuri ke proses cetak;
- bahan sisa dan limbah harus dapat dibedakan.

Tabel utama yang sudah dirancang: `bahan`, `supplier`, `bahan_order`,
`bahan_order_isi`, `bahan_stok`, `bahan_sisa`, dan `bahan_limbah`.

Detail simulasi manajemen bahan akan dilengkapi kembali pada dokumentasi skema
database atau dokumen alur khusus setelah struktur tersebut diverifikasi.

## 3. Proses cetak

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

## 4. Laporan keuangan

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

## 5. Statistik dan grafik dashboard

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
