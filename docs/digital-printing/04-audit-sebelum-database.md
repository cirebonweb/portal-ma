# Audit Sebelum Rancangan Database

> Gunakan daftar ini sebagai urutan pembahasan. Jangan mengubahnya menjadi
> tabel atau migrasi sebelum keputusan yang memengaruhi data dan lifecycle
> disepakati. Kolom keputusan dapat diisi selama sesi audit.

## Kriteria mulai merancang database

Rancangan skema dapat dimulai saat:

- istilah Pesanan, Pekerjaan/Batch, Hasil, Siap Diserahkan, Pengambilan,
  Pemakaian, Sisa, Limbah, Pembayaran, dan Koreksi memiliki makna konsisten;
- lifecycle normal dan jalur pengecualian utama sudah digambar dari awal
  sampai selesai;
- perilaku kuantitas parsial dan batas validasinya dipahami;
- pemicu setiap perubahan stok/uang sudah jelas;
- aktor yang boleh melakukan setiap aksi sudah dipetakan;
- daftar status dan kejadian yang harus punya jejak audit sudah ditetapkan;
- simulasi Ahmad dan Budi dapat dijalankan end-to-end tanpa status/tabel yang
  memiliki dua arti.

## Urutan audit dan daftar keputusan

### 1. Produk, variasi, dan satuan

- Apakah jenis katalog cukup Cetak dan Jasa?
- Apakah jasa dapat menghasilkan barang fisik, atau selalu non-pengambilan?
- Bagaimana produk dengan ukuran, warna, bahan, finishing, atau metode berbeda
  direpresentasikan di layar transaksi?
- Apakah opsi teknis adalah variasi produk tersimpan atau pilihan per item
  nota?
- Apa satuan kuantitas pesanan, misalnya pcs, box, meter persegi, set, atau
  jasa? Dapatkah satu pesanan mempunyai pecahan?
- Apakah satu produk dapat memakai lebih dari satu bahan/material atau
  mempunyai beberapa langkah produksi?

**Hasil yang harus disepakati:** jenis/variasi yang terlihat ke CS dan
satuan transaksi yang konsisten dari pesanan hingga serah-terima.

### 2. POS dan lifecycle pesanan

- Kapan nota dibuat: saat quotation, saat pesanan dikonfirmasi, atau keduanya?
- Apakah draft pesanan bernomor nota? Kapan nomor menjadi tetap?
- Informasi apa yang menjadi snapshot pada nota/item, sehingga perubahan
  master produk/harga tidak mengubah transaksi lama?
- Kapan CS boleh mengubah konsumen, produk, spesifikasi, kuantitas, harga,
  diskon, deadline, atau keterangan?
- Apa yang terkunci setelah pembayaran, alokasi produksi, pekerjaan dimulai,
  atau sebagian barang diserahkan?
- Bagaimana penambahan/pengurangan kuantitas setelah produksi dimulai
  diperlakukan?
- Apa status lifecycle order yang dilihat CS, terpisah dari status batch
  produksi dan status pembayaran?

**Hasil yang harus disepakati:** state pesanan dan matriks perubahan data
sebelum/sesudah konfirmasi, pembayaran, produksi, dan pengambilan.

### 3. Harga, estimasi biaya, dan margin

- Bagaimana harga jual dihitung untuk luas, kuantitas, ukuran tetap, box,
  finishing, jasa, atau kombinasi?
- Kapan harga dikunci pada transaksi? Apakah perubahan harga master hanya
  memengaruhi transaksi baru?
- Bagaimana diskon, biaya desain, pemasangan, kirim, dan biaya tambahan
  diberikan/dicatat?
- Apakah sistem menampilkan estimasi HPP saat penjualan, HPP aktual setelah
  pekerjaan internal, dan biaya vendor aktual?
- Jika pekerjaan dialihkan dari internal ke vendor setelah nota dibuat,
  siapa menyetujui perubahan biaya dan bagaimana margin ditampilkan?

**Hasil yang harus disepakati:** komponen harga transaksi dan aturan
estimasi-versus-aktual untuk biaya produksi.

### 4. Perencanaan dan pekerjaan produksi

- Siapa menentukan rencana jalur: CS saat transaksi, penjadwal, atau operator?
- Apa yang dimaksud satu batch/file cetak? Kapan item dari beberapa nota boleh
  digabung (mesin, bahan, lebar, warna, finishing, deadline, vendor)?
- Apakah satu batch hanya memakai satu mesin/vendor dan satu jenis jalur?
- Bagaimana satu item dibagi ke beberapa batch atau dibagi internal/vendor?
- Apakah kuantitas Draft sudah dialokasikan, atau baru dialokasikan setelah
  Antrian?
- Status mana yang berlaku di batch dan mana yang memerlukan status per
  alokasi/item?
- Bagaimana pending, pembatalan, pemindahan kuantitas, cetak ulang, hasil
  parsial, kekurangan, cacat, dan kelebihan hasil dicatat?
- Apakah “pekerjaan vendor selesai” berbeda dari “hasil diterima dan lolos
  pemeriksaan”?

**Hasil yang harus disepakati:** lifecycle pekerjaan, aturan alokasi dan
penggabungan, serta definisi kuantitas dialokasikan/selesai/gagal.

### 5. Vendor dan pengerjaan pihak luar

- Apakah vendor mempunyai data master sendiri atau berbagi kontak dengan
  Supplier bahan?
- Informasi apa yang diperlukan untuk vendor cetak (kontak, layanan, tarif,
  termin, status aktif)?
- Bagaimana order vendor, harga aktual, bukti pengiriman, penerimaan, dan
  pemeriksaan dilakukan?
- Apakah vendor dapat menerima sebagian kuantitas atau mengirim hasil parsial?
- Bagaimana stok internal dicatat jika bahan dibawa/dikirim ke vendor?
- Siapa yang bertanggung jawab atas bahan, hasil cacat, cetak ulang, dan
  kekurangan vendor?

**Hasil yang harus disepakati:** tahapan vendor dan tanggung jawab biaya,
kuantitas, serta bahan.

### 6. Persediaan, pemakaian, sisa, dan limbah

- Objek apa yang menjadi stok: roll/paket, luas, pcs, atau agregat material?
- Apakah bahan perlu lot/barcode per roll dan lokasi penyimpanan?
- Apakah stok direservasi saat pekerjaan direncanakan atau baru dikurangi saat
  operator mencatat pemakaian aktual?
- Kapan event pemakaian diposting: mulai kerja, progres aktual, atau selesai?
- Bagaimana pemakaian satu pekerjaan yang menghasilkan banyak item dibagi ke
  item nota?
- Bagaimana sisa roll, potongan yang dapat dipakai ulang, bahan cacat, dan
  limbah diukur serta ditautkan ke sumber?
- Apakah material stoknya dikurangi saat dipakai untuk produksi, saat item
  selesai, atau melalui transaksi lain?
- Bagaimana koreksi stok, stock opname, retur ke supplier, kerusakan gudang,
  dan pembatalan pemakaian diaudit?
- Bagaimana mencegah stok negatif dan transaksi stok yang sama diposting dua
  kali?

**Hasil yang harus disepakati:** jenis transaksi yang menambah/mengurangi
stok, satuan, waktu posting, dan prosedur koreksi.

### 7. Pemeriksaan, hasil siap, dan pengambilan

- Apa syarat kuantitas dianggap siap diserahkan untuk produksi internal dan
  vendor?
- Bagaimana barang gagal QC, perbaikan, cetak ulang, atau hasil kurang
  memengaruhi kuantitas siap?
- Apakah barang dapat diambil sebagian sebelum seluruh item di nota selesai?
- Apakah satu serah-terima dapat memuat banyak nota atau hanya satu nota?
- Informasi apa yang perlu pada bukti serah-terima (waktu, petugas, penerima,
  tanda tangan/foto, catatan)?
- Bagaimana salah input/retur setelah barang diserahkan ditangani?
- Apakah perlu daftar antrean pengambilan lintas nota selain detail nota?

**Hasil yang harus disepakati:** aturan readiness dan lifecycle pengambilan
parsial yang dapat direkonsiliasi.

### 8. Pembayaran dan keuangan

- Kapan pembayaran dapat dicatat relatif terhadap konfirmasi pesanan,
  produksi, dan pengambilan?
- Apakah pembayaran parsial, deposit, kelebihan bayar, refund, pembatalan,
  piutang, dan pembayaran gabungan didukung?
- Apakah satu pembayaran hanya terkait satu nota?
- Bagaimana tunai, transfer, biaya administrasi, bukti transfer, dan
  verifikasi bank ditangani?
- Apa yang termasuk Laporan Harian: penerimaan, pembayaran vendor,
  pengeluaran kas, koreksi, atau semuanya?
- Bagaimana pergantian hari, tutup kas, selisih kas, dan transaksi backdate
  ditangani?
- Siapa yang dapat mengubah/menghapus pembayaran setelah direkonsiliasi?
- Apakah riwayat pembayaran adalah daftar transaksi sumber, sedangkan log
  audit perubahan merupakan kebutuhan terpisah?

**Hasil yang harus disepakati:** lifecycle uang dan aturan rekonsiliasi antara
nota, pembayaran, kas, vendor, dan laporan.

### 9. Peran, otorisasi, dan jejak audit

- Siapa membuat/mengonfirmasi nota dan menyetujui diskon?
- Siapa menentukan atau mengubah jalur internal/vendor?
- Siapa mengubah status pekerjaan, mencatat pemakaian, menerima hasil vendor,
  dan memposting stok?
- Siapa menyerahkan barang serta mengoreksi pengambilan?
- Siapa boleh membatalkan, mengembalikan uang, memperbaiki stok, atau mengubah
  data historis?
- Perubahan apa yang harus menyimpan pelaku, waktu, alasan, nilai sebelum dan
  sesudah?

**Hasil yang harus disepakati:** matriks peran-aksi dan daftar event yang
wajib dapat diaudit.

### 10. Laporan dan indikator operasional

- Apa definisi penjualan, omset, pembayaran diterima, piutang, dan margin?
- Apakah tanggal laporan berdasarkan waktu nota, pembayaran, produksi, atau
  pengambilan?
- Laporan apa yang diperlukan CS, produksi, gudang, counter, dan keuangan?
- Apakah diperlukan laporan pekerjaan terlambat, kuantitas belum dialokasikan,
  hasil siap belum diambil, pemakaian bahan, sisa, limbah, dan biaya vendor?
- Data historis mana yang harus tersedia untuk ekspor dan audit?

**Hasil yang harus disepakati:** metrik, filter, periodisasi, dan role yang
dapat mengakses masing-masing laporan.

## Skenario uji alur sebelum database

Setelah keputusan di atas dibahas, jalankan walkthrough tanpa merancang tabel
terlebih dahulu:

1. Ahmad membuat pesanan 10 spanduk, 3 box kartu nama, jasa desain, dan jasa
   pemasangan.
2. Bagi spanduk Ahmad menjadi 6 internal dan 4 vendor; catat kartu nama,
   biaya/hasil jasa, pembayaran, dan beberapa pengambilan.
3. Budi memesan spanduk 5 pcs, stiker, mug, dan kaos.
4. Gabungkan 6 pcs spanduk Ahmad dengan 2 pcs spanduk Budi dalam satu batch
   outdoor, lalu kerjakan sisa 3 pcs Budi pada batch lain.
5. Alihkan pekerjaan internal ke vendor karena mesin rusak setelah alokasi
   dibuat.
6. Selesaikan hasil secara parsial, catat cacat/cetak ulang, dan pastikan
   jumlah siap tidak melebihi hasil yang lolos.
7. Catat pemakaian roll, sisa yang dapat dipakai, dan limbah; gunakan sisa
   tersebut pada pekerjaan berikutnya.
8. Terima hasil vendor, serahkan sebagian kepada Ahmad, lalu sisanya pada
   transaksi pengambilan lain.
9. Batalkan pekerjaan setelah bahan dipakai dan lakukan koreksi tanpa
   kehilangan histori atau menggandakan pengurangan stok.
10. Rekonsiliasi pembayaran, pengembalian/koreksi yang relevan, dan laporan
    harian untuk periode yang sama.

Jika salah satu skenario tidak dapat dijelaskan dengan status, kuantitas,
aktor, dan event yang jelas, audit alur belum selesai dan skema database
sebaiknya belum dimulai.

## Gerbang mulai rancangan database

Sebelum mulai, isi dan setujui minimal:

- [ ] Glosarium istilah dan satuan.
- [ ] State diagram pesanan, batch internal, batch vendor, hasil, pengambilan,
  pembayaran, dan status stok.
- [ ] Diagram relasi bisnis/kuantitas: pesanan → alokasi → hasil → siap →
  diserahkan.
- [ ] Tabel pemicu stok masuk/keluar dan mekanisme koreksi.
- [ ] Matriks lifecycle yang menjelaskan kapan entitas terkunci atau dapat
  diubah.
- [ ] Matriks role dan aksi yang memerlukan persetujuan.
- [ ] Hasil walkthrough simulasi Ahmad/Budi serta skenario pengecualian.
- [ ] Daftar laporan dan kebutuhan audit yang dapat memengaruhi retensi data.

Database adalah konsekuensi model bisnis. Jika gerbang ini belum lolos,
lanjutkan audit proses dan frontend, bukan membuat migration dengan asumsi
sementara.
