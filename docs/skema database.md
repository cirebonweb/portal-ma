# Dokumentasi & Referensi Skema Database

## Pendahuluan
Dokumen ini berfungsi sebagai peta jalan (*roadmap*) arsitektur database sekaligus panduan urutan pengerjaan modul MVC (*Model-View-Controller*). Urutan penomoran tabel disusun berdasarkan **tingkat dependensi data** (dimulai dari *master data* terisolasi hingga transaksi dan pelaporan yang kompleks). 

Dengan pendekatan ini, pengembangan MVC dapat dilakukan secara terukur tanpa memicu *error* akibat relasi data (*foreign key*) yang belum siap. Setiap poin menjelaskan fungsi bisnis tabel serta keterhubungannya dengan tabel lain untuk mempermudah pembuatan *Model*, *Migration*, dan *Seeder*.

---

### 1. Tabel `kategori_konsumen`
Bertujuan untuk mengelompokkan profil atau tipe konsumen secara umum, seperti *Retail/Umum*, *Corporate/Perusahaan*, *Reseller*, maupun *Internal Karyawan*. Pengelompokan ini sangat krusial karena menjadi fondasi utama dalam menentukan skema pelayanan, sistem penagihan, hingga batas limit piutang untuk tiap kelompok pelanggan.

Selain untuk administrasi pelanggan, data dari tabel ini juga dijadikan acuan utama untuk menghubungkan kelompok konsumen ke dalam sistem penjenjangan (*tiering*) harga produk cetak.

**Relasi:**
* **`konsumen`**: Satu kategori konsumen menaungi banyak data profil konsumen (*One-to-Many*).
* **`dp_kategori_harga`**: Menjadi acuan penentuan kelompok harga khusus yang berlaku untuk tipe konsumen tertentu.

---

### 2. Tabel `kategori_produk`
Bertujuan untuk mengelompokkan jenis produk secara umum di dalam sistem. Contoh kategori yang umum digunakan meliputi *Mesin Outdoor*, *Jasa dan Layanan*, hingga *Cetak Offset*. 

Pemisahan kategori ini memudahkan navigasi katalog saat kasir atau operator menginput pesanan, serta mempermudah penyusunan statistik penjualan per divisi produk dalam laporan manajemen.

**Relasi:**
* **`dp_produk`**: Satu kategori produk menaungi banyak varian produk *digital printing* (*One-to-Many*).

---

### 3. Tabel `dp_bahan`
Bertujuan untuk mencatat *master data* media atau bahan baku cetak yang digunakan dalam operasional *digital printing*. Contohnya seperti *Flexi 280g*, *Flexi Korchin*, *Albatross*, *Art Paper 150g*.

Tabel ini menyimpan informasi mendasar mengenai ketersediaan dan spesifikasi fisik bahan, yang nantinya digunakan untuk mengkalkulasi kebutuhan produksi, konversi ukuran fisik, dan penetapan harga dasar produk.

**Relasi:**
* **`dp_produk`**: Menjadi komponen utama dalam pendefinisian spesifikasi varian produk cetak (*Many-to-One*).

---

### 4. Tabel `dp_kategori_harga`
Bertujuan untuk mendefinisikan nama atau jenjang (*tiering*) kelompok harga khusus untuk transaksi *digital printing*. Contoh skema tiering (sistem atau metode yang mengelompokkan sesuatu ke dalam berbagai tingkatan 'tier' atau lapisan berdasarkan kriteria tertentu) yang sering dipakai adalah *Harga Standar*, *Harga Member VIP*, *Harga Agent*, atau *Harga Rekanan*.

Tabel ini bertindak sebagai penjelas konteks atau *header* sebelum matriks nominal harga spesifik diterapkan pada masing-masing produk atau bahan cetak.

**Relasi:**
* **`kategori_konsumen`**: Dihubungkan ke kelompok konsumen sebagai acuan *tier* harga default mereka.
* **`dp_harga_khusus`**: Menjadi *parent header* bagi daftar matriks variasi harga spesifik per produk/bahan (*One-to-Many*).

---

### 5. Tabel `konsumen`
Bertujuan untuk menyimpan data lengkap profil pelanggan yang bertransaksi, seperti nama pelanggan, kontak/WhatsApp, alamat, nama perusahaan, serta catatan limit kredit/piutang.

Tabel ini menjadi entitas utama dalam setiap transaksi pemesanan. Data di dalamnya dipanggil untuk mengidentifikasi siapa pemilik nota serta menetapkan skema harga secara otomatis berdasarkan kategori pelanggan yang melekat padanya.

**Relasi:**
* **`kategori_konsumen`**: Mengambil nilai kategori dari tabel `kategori_konsumen` (*Many-to-One*).
* **`dp_nota`**: Satu konsumen dapat memiliki banyak riwayat transaksi nota pemesanan (*One-to-Many*).

---

### 6. Tabel `dp_produk`
Bertujuan untuk mengelola katalog item layanan atau produk jadi cetak yang ditawarkan kepada pelanggan, seperti *Cetak Spanduk Flexi*, *Kartu Nama Box*, atau *Brosur A4*.

Di dalam tabel ini diatur rumus perhitungan harga dasar (seperti hitungan per meter persegi ($m^2$), per lembar, atau pcs) serta pengikatan bahan baku dasar yang digunakan oleh produk tersebut.

**Relasi:**
* **`kategori_produk`**: Terikat pada satu kategori produk umum (*Many-to-One*).
* **`dp_bahan`**: Menggunakan referensi bahan cetak utama yang sesuai (*Many-to-One*).
* **`dp_harga_khusus`**: Item produk yang didaftarkan ke matriks variasi harga *tiering*.
* **`dp_nota_isi`**: Dipilih sebagai item rincian pesanan di dalam nota transaksi (*One-to-Many*).

---

### 7. Tabel `dp_harga_khusus`
Bertujuan untuk menyimpan matriks atau daftar nominal harga khusus (*override*) berdasarkan kombinasi produk/bahan dengan kelompok harga konsumen tertentu.

Dengan tabel ini, sistem dapat mengakomodasi fleksibilitas bisnis di mana satu produk yang sama (misal: *Spanduk Flexi*) memiliki nominal harga per meter yang berbeda-beda tergantung tingkat kelompok harga pembelinya.

**Relasi:**
* **`dp_kategori_harga`**: Terikat pada skema/tier harga tertentu (*Many-to-One*).
* **`dp_produk`**: Menunjuk pada item produk cetak spesifik yang diberi penyesuaian harga (*Many-to-One*).

---

### 8. Tabel `dp_nota`
Bertujuan sebagai *header* utama pencatatan transaksi pemesanan/penjualan *digital printing*. Tabel ini menyimpan data akumulatif dari satu pesanan, seperti nomor nota/faktur, tanggal masuk, tanggal tenggat (*deadline*), total biaya, diskon, status pengerjaan, dan status pelunasan.

Tabel ini menjadi pusat koordinasi antara divisi kasir (pembayaran), divisi produksi (pengerjaan cetak), dan divisi pengambilan barang.

**Relasi:**
* **`konsumen`**: Mengacu pada pelanggan yang memesan transaksi ini (*Many-to-One*).
* **`dp_nota_isi`**: Memiliki banyak rincian item cetakan yang dipesan dalam satu nota (*One-to-Many*).
* **`dp_nota_bayar`**: Memiliki banyak riwayat pencatatan DP/pelunasan (*One-to-Many*).
* **`dp_laporan_isi`**: Dapat dirangkum ke dalam satu atau lebih laporan operasional.

---

### 9. Tabel `dp_nota_isi`
Bertujuan mencatat rincian tiap *line-item* barang/jasa yang dipesan di dalam satu nota transaksi. 

Tabel ini menampung variabel spesifik pengerjaan *digital printing*, seperti panjang, lebar, jumlah cetak (pcs), perhitungan luas ($m^2$), finishing tambahan, harga satuan, hingga total harga per item pesanan.

**Relasi:**
* **`dp_nota`**: Terikat pada satu *header* nota transaksi utama (*Many-to-One*).
* **`dp_produk`**: Mengacu pada item produk cetak yang dipilih (*Many-to-One*).

---

### 10. Tabel `dp_nota_bayar`
Bertujuan menyimpan riwayat log pembayaran transaksi nota secara terperinci. Tabel ini mendukung skema pembayaran fleksibel seperti uang muka (DP), pelunasan bertahap/cicilan, hingga pembayaran lunas di awal.

Informasi yang dicatat mencakup nominal bayar, tanggal bayar, metode pembayaran (*Cash*, *Transfer*, *QRIS*), serta catatan/bukti transaksi.

**Relasi:**
* **`dp_nota`**: Terikat pada transaksi nota yang sedang dibayar (*Many-to-One*).

---

### 11. Tabel `dp_laporan`
Bertujuan sebagai *header* rekapitulasi atau laporan pencatatan operasional dan keuangan berkala. Contohnya seperti *Laporan Kas Harian*, *Laporan Serah Terima Shift*, atau *Laporan Penutupan Harian Printing*.

Tabel ini digunakan untuk mengunci (*freeze*) akumulasi transaksi pada periode tertentu agar data keuangan tidak berubah pasca-penutupan buku.

**Relasi:**
* **`dp_laporan_isi`**: Menampung rincian daftar nota transaksi yang dimasukkan ke dalam laporan tersebut (*One-to-Many*).
* **`dp_laporan_log`**: Memiliki riwayat log aktivitas dan perubahannya (*One-to-Many*).

---

### 12. Tabel `dp_laporan_isi`
Bertujuan mencatat pemetaan atau daftar nota mana saja yang dimasukkan dan dihitung ke dalam satu dokumen rekap laporan tertentu.

Tabel *pivot* ini memastikan bahwa satu nota transaksi tercatat dengan jelas pada dokumen laporan penutupan yang mana.

**Relasi:**
* **`dp_laporan`**: Terikat pada *header* rekap laporan terkait (*Many-to-One*).
* **`dp_nota`**: Mengacu pada nota transaksi yang dimasukkan ke dalam rincian laporan (*Many-to-One*).

---

### 13. Tabel `dp_laporan_log`
Bertujuan mencatat *audit trail* atau jejak histori aktivitas perlakuan terhadap dokumen laporan (misal: catatan kapan laporan dibuat sebagai *Draft*, diajukan (*Submitted*), disetujui supervisor (*Approved*), hingga ditutup (*Closed*)).

Pencatatan ini penting untuk transparansi operasional dan mencegah kecurangan (*fraud*) dalam pelaporan keuangan.

**Relasi:**
* **`dp_laporan`**: Terikat pada dokumen *header* laporan yang dicatat riwayatnya (*Many-to-One*).