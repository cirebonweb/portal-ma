# Alur Operasional

> Dokumen konsep untuk mengaudit hubungan Penjualan/POS, Produksi,
> Persediaan, Pemenuhan Pesanan, dan Keuangan. Simulasi bukan data produksi
> dan belum menentukan struktur tabel final.

## Prinsip alur

- Produk dibedakan sebagai **Cetak** atau **Jasa**. Internal/Vendor adalah
  jalur pengerjaan aktual, bukan tipe produk.
- Satu rincian pesanan menyatakan jumlah yang dijual ke konsumen.
- Jumlah satu rincian pesanan dapat dibagi ke beberapa pekerjaan; satu
  pekerjaan/file dapat menggabungkan rincian dari beberapa nota.
- Catat kuantitas pesanan, kuantitas dialokasikan, hasil selesai, dan
  kuantitas diserahkan secara terpisah.
- Jasa tidak masuk pekerjaan cetak atau pengambilan barang kecuali nanti
  disepakati bahwa suatu jenis jasa menghasilkan barang fisik yang diserahkan.
- Produksi Vendor tidak memotong stok internal secara otomatis.
- Pengambilan barang tidak mengubah stok bahan; stok bahan berubah ketika
  bahan/material diterima atau benar-benar dipakai.

## Simulasi Nota

### Ahmad — Nota 12345

| Item | Spesifikasi | Jumlah dipesan | Rencana |
|---|---|---:|---|
| Spanduk Flexy 280 | 2 × 1 m, outdoor | 10 pcs | 6 internal, 4 vendor |
| Kartu nama | Copy Colour A3+ | 3 box | Cetak |
| Jasa desain | — | sesuai satuan jasa | Jasa |
| Jasa pemasangan spanduk | — | sesuai satuan jasa | Jasa |

### Budi — Nota 12346

| Item | Spesifikasi | Jumlah dipesan | Rencana |
|---|---|---:|---|
| Spanduk Flexy 280 | 3 × 1 m, outdoor | 5 pcs | Dibagi ke pekerjaan 2 pcs dan 3 pcs |
| Stiker | 3 × 2 m, indoor | 1 pcs | Cetak |
| Mug | Press mug | 10 pcs | Jalur/alat perlu ditetapkan |
| Kaos | Sablon kaos | 5 pcs | Jalur/metode perlu ditetapkan |

Rencana internal/vendor pada tabel hanya memberi konteks simulasi. Keputusan
apakah CS menentukan rencana saat membuat nota atau penjadwal menentukan
jalur nanti masih terbuka.

## Diagram hubungan proses

```text
Master produk dan teknis
        │
        v
Konsumen -> Nota -> Rincian pesanan Cetak/Jasa -> Pembayaran
                    │
                    ├── Jasa -> penyelesaian layanan
                    │
                    └── Cetak -> alokasi kuantitas
                                  │
                    ┌─────────────┴─────────────┐
                    v                           v
              Pekerjaan Internal          Pekerjaan Vendor
              pilih mesin/alat             pilih vendor
              catat hasil                   catat progres/penerimaan
              catat bahan aktual            stok internal tak berubah*
              hasilkan sisa/limbah
                    └─────────────┬─────────────┘
                                  v
                        Kuantitas siap diserahkan
                                  │
                                  v
                  Satu/beberapa transaksi pengambilan
                                  │
                                  v
                     Sisa item siap dan keuangan
```

`*` Pengecualian: apabila bahan milik usaha diserahkan kepada vendor, aliran
keluar bahan tersebut harus dicatat eksplisit dan hasil/sisanya
dipertanggungjawabkan.

## Contoh alokasi pekerjaan

| Pekerjaan/file | Jalur dan alat | Alokasi item |
|---|---|---|
| Cetak 001 | Internal, mesin outdoor | Ahmad: spanduk 6 pcs; Budi: spanduk 2 pcs |
| Cetak 002 | Internal, mesin outdoor | Budi: spanduk 3 pcs |
| Cetak 003 | Internal, mesin indoor | Budi: stiker 1 pcs |
| Pekerjaan vendor | Vendor yang dipilih | Ahmad: sisa spanduk 4 pcs |
| Pekerjaan mug | Belum diputuskan | Budi: mug 10 pcs |
| Pekerjaan kaos | Belum diputuskan | Budi: kaos 5 pcs |

Pekerjaan 001 memperlihatkan penggabungan dua nota. Spanduk Ahmad
memperlihatkan pembagian satu rincian pesanan ke pekerjaan internal dan vendor.
Pekerjaan vendor dapat digabung dengan pesanan lain jika vendor dan kebutuhan
kerja sesuai.

## Status pekerjaan dan hasil

Status batch sementara untuk diskusi:

| Nilai konsep | Status | Pertanyaan terkait |
|---:|---|---|
| 0 | Draft | Apakah item yang ada di Draft sudah dianggap dialokasikan? |
| 1 | Antrian | Kapan pekerjaan resmi masuk antrean? |
| 2 | Pending | Siapa yang dapat menunda dan apa alasan wajibnya? |
| 3 | Proses | Bagaimana mencatat hasil parsial dan pemakaian aktual? |
| 4 | Selesai | Kapan hasil internal/vendor dinyatakan siap diserahkan? |
| 5 | Batal | Bagaimana melepaskan alokasi dan mengoreksi bahan yang sudah dipakai? |

Status ini konsep awal dan belum menjadi daftar status final. Karena pekerjaan
dapat mempunyai hasil parsial, status batch saja mungkin tidak menjelaskan
berapa unit yang selesai, rusak, atau masih kurang. Tentukan kebutuhan hasil
kuantitatif sebelum database dirancang.

## Penggunaan stok pada alur

### Stok bertambah

```text
Order bahan/material
  -> barang diterima dan diverifikasi
  -> penerimaan diposting
  -> stok bertambah
```

Perlu diputuskan apakah penerimaan parsial didukung, kapan order dikunci,
bagaimana penolakan barang, dan apakah stok dapat diedit langsung atau hanya
melalui transaksi penerimaan/koreksi.

### Stok berkurang pada produksi internal

```text
Pekerjaan Internal
  -> operator pilih bahan/roll/sisa yang digunakan
  -> catat ukuran dan kuantitas aktual
  -> stok sumber berkurang
  -> catat sisa layak pakai dan limbah
```

Pemakaian harus ditautkan ke pekerjaan dan item sumber agar stok, sisa, dan
limbah dapat ditelusuri. Pengurangan berdasarkan rencana, saat mulai, saat
selesai, atau input aktual operator masih harus dipilih. Pembatalan/cetak
ulang tidak boleh membuat mutasi ganda atau menghapus jejak koreksi.

### Jalur Vendor

```text
Pekerjaan vendor dibuat
  -> vendor dan kuantitas dialokasikan
  -> progres vendor dipantau
  -> hasil diterima/diperiksa
  -> kuantitas lolos menjadi siap diserahkan
```

Jika vendor menggunakan bahan milik usaha, perlu transaksi pengiriman bahan
dan pencatatan pengembalian/sisa yang terpisah dari stok pemakaian internal.
Status “vendor selesai” dan “hasil diterima serta lolos pemeriksaan” mungkin
merupakan kejadian berbeda.

## Pengambilan bertahap

Ahmad mengambil spanduk 6 pcs dan kartu nama 3 box pada Senin, 1 Januari,
kemudian mengambil sisa spanduk 4 pcs pada Rabu, 3 Januari. Budi mengambil
semua barang yang sudah siap pada Senin, 1 Januari.

Setiap serah-terima harus menjelaskan item dan jumlahnya. UI menunjukkan
kuantitas pesanan, kuantitas hasil siap, jumlah yang sudah diambil, serta
jumlah tersisa. Satu transaksi pengambilan dapat mencakup beberapa item dan
satu item dapat muncul pada beberapa transaksi pengambilan.

## Jalur uang

Pembayaran dapat terjadi sebelum atau sesudah barang siap; titik ini perlu
diputuskan sebagai aturan operasional, bukan ditentukan oleh status cetak.
Secara frontend, CRUD pembayaran dimulai dari detail nota; halaman riwayat
lintas nota hanya membantu pencarian dan navigasi.

Laporan harian harus merekonsiliasi penerimaan dengan pembayaran dan metode
bayar yang benar. Definisi waktu transaksi, penutupan kas, pembatalan,
kelebihan bayar, refund, dan koreksi pembayaran masih menjadi topik audit.
