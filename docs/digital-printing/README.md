# Paket Konsep Aplikasi Digital Printing

> **Status: bahan konsep dan audit sebelum rancangan database.**
> Paket ini disiapkan agar dapat dipindahkan ke proyek baru `digital-printing`
> sebagai dokumentasi awal yang berdiri sendiri. Isinya belum merupakan
> spesifikasi final dan tidak menjelaskan route atau skema yang sudah
> diimplementasikan pada proyek lain.

## Dokumen paket

1. [Fitur Aplikasi](./01-fitur-aplikasi.md) — batas dan cakupan fitur utama
   serta jalur umum perubahan stok.
2. [Konsep Frontend](./02-konsep-frontend.md) — kelompok menu, halaman, URL,
   pengguna, dan fungsi UI.
3. [Alur Operasional](./03-alur-operasional.md) — simulasi nota, produksi
   gabungan/parsial, bahan, vendor, dan pengambilan.
4. [Audit Sebelum Database](./04-audit-sebelum-database.md) — urutan bahasan,
   keputusan yang harus dibuat, dan kriteria untuk mulai merancang skema.

## Cara menggunakan paket

- Bahas dan putuskan alur bisnis lebih dahulu; tandai keputusan sebagai
  disepakati, ditolak, atau masih terbuka.
- Perbarui dokumen konsep ini setelah setiap keputusan penting agar semua
  topik merujuk pada satu sumber yang konsisten.
- Jangan menganggap contoh tabel atau nama URL sebagai kontrak backend.
- Mulai rancangan database setelah kriteria pada
  [audit sebelum database](./04-audit-sebelum-database.md) terpenuhi.
- Setelah proyek baru dibuat, salin seluruh folder paket ini dan lanjutkan
  dokumentasi arsitektur, skema, API, hak akses, pengujian, serta panduan
  pengguna di proyek baru.

## Batas terhadap dokumentasi Portal MA

Paket ini sengaja tidak mengimpor spesifikasi skema atau status implementasi
Portal MA sebagai aturan proyek baru. Portal MA tetap menjadi sumber contoh
dan pelajaran historis, tetapi bukan source of truth untuk aplikasi baru.
Dokumen yang secara khusus perlu ditinjau sebagai bahan pembelajaran adalah:

- konsep alur dan menu Portal MA, untuk mempertahankan simulasi serta
  keputusan frontend yang masih relevan;
- skema bahan/material Portal MA, hanya sebagai referensi pola stok dan
  pemakaian yang perlu diaudit ulang;
- catatan arsitektur serta status Portal MA, hanya untuk mengenali pekerjaan
  yang pernah tertunda, batasan, dan asumsi yang jangan diwarisi tanpa
  keputusan.

Jangan menyalin migration, route, controller, trigger, status produk, atau
aturan lama secara otomatis. Tetapkan ulang kebutuhan dan lifecycle di paket
ini sebelum memilih bagian yang layak digunakan kembali.

## Istilah sementara

| Istilah | Makna konsep |
|---|---|
| Pesanan Cetak | Baris pesanan yang hasilnya berupa barang cetak |
| Pesanan Jasa | Baris jasa yang tidak menjadi barang untuk diserahkan |
| Pekerjaan/batch | Satu unit kerja produksi yang dapat memuat beberapa item dan kuantitas |
| Jalur Internal | Pekerjaan dikerjakan sendiri menggunakan alat dan/atau stok internal |
| Jalur Vendor | Pekerjaan dikerjakan vendor; penerimaan hasil perlu dicatat |
| Siap diserahkan | Kuantitas hasil yang lolos penyelesaian/pemeriksaan dan dapat diambil konsumen |
| Pengambilan | Transaksi serah-terima yang dapat mencakup beberapa item dan terjadi bertahap |

Istilah, label layar, status, dan URL masih dapat berubah selama audit.
