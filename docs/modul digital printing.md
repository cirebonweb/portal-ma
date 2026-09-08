# Dokumen Fungsional — Modul Digital Printing (dp_*)

**Proyek Starter:** Aplikasi Penjualan Nota Digital Printing (berlangsung)
**Proyek Final:** Aplikasi Portal Mulyatama Abadi (pencapaian)
**Perusahaan:** Mulyatama Abadi Digital Printing Advertising, Cirebon
**Stack:** CodeIgniter 4 + MySQL, CI4 Shield (auth), CI4 Settings
**Status:** Migrasi dari `Excel VBA + .mde` → `CI4 + MySql`
**Cakupan dokumen ini:** Modul Digital Printing (`dp_*`) saja — divisi lain (advertising, umum) di luar cakupan dokumen ini.

---

## 1. Latar Belakang

Sistem lama berbasis Excel VBA + Access (.mde) mulai sulit di-maintain seiring bertambahnya data dan kebutuhan fitur baru. Aplikasi ini dibangun ulang dengan CI4 agar lebih terstruktur, mudah dikembangkan, dan berpotensi digunakan lintas divisi (di luar cakupan dokumen ini).

Prefiks tabel `dp_` (Digital Printing) sengaja dipisah dari tabel umum (`konsumen`, `kategori_konsumen`) agar modul divisi lain (misal `adv_*` untuk advertising) bisa dibangun terpisah tanpa mengubah struktur inti.

---

## 2. Modul & Fitur

| Modul | Fungsi Utama |
|---|---|
| **Data Konsumen** | CRUD konsumen, pengelompokan kategori konsumen (Retail/Corporate/Karyawan), filter berdasarkan divisi akses |
| **Data Mesin** | Master data mesin produksi (kategori: Outdoor, Indoor, Cutting, Copy Color) |
| **Data Bahan** | Master data bahan cetak |
| **Data Produk** | Master produk digital printing, terhubung ke mesin & bahan, harga dasar, promo, rumus perhitungan harga |
| **Harga Per Kategori** | Harga khusus berdasarkan kategori konsumen (Retail/Corporate/dst) |
| **Harga Khusus** | Harga khusus per konsumen individual (override kategori) |
| **Nota Penjualan** | Input transaksi penjualan, multi-item per nota, kalkulasi otomatis berdasarkan rumus produk |
| **Pembayaran Nota** | Pencatatan pembayaran (tunai/transfer), termasuk parsial/cicilan |
| **Laporan Harian** | Rekap harian pemasukan/pengeluaran CS, alur serah terima kas ke manajemen |

---

## 3. Role & Hak Akses (garis besar)

> Detail permission granular ditangani lewat CI4 Shield. Bagian ini mencatat peran fungsional, bukan konfigurasi teknis.

| Role | Akses Utama |
|---|---|
| **CS / Admin Digital Printing** | Input nota, input pembayaran, buat & serahkan laporan harian |
| **Admin Divisi Lain** (mis. Advertising) | Bisa jadi penerima titipan laporan/kas saat CS DP berhalangan (lihat §5.4) |
| **Manajer / Direktur / Pihak Ditunjuk** | Menerima & mengetahui laporan serah terima, akses laporan rekap |

Catatan: kolom `divisi` pada tabel `konsumen` (0:umum, 1:printing, 2:advertising) digunakan untuk membatasi konsumen yang tampil sesuai akses login user — misal user grup printing hanya melihat konsumen umum + printing.

---

## 4. Alur Bisnis Utama

```
Input Nota → Pembayaran (bisa cicil) → Rekap ke Laporan Harian → Serah Terima ke Manajemen
```

1. CS membuat nota penjualan untuk konsumen (baru atau existing).
2. Sistem menghitung harga otomatis berdasarkan prioritas: **harga khusus konsumen** → **harga kategori konsumen** → **harga promo** → **harga dasar**.
3. Pembayaran dicatat (bisa langsung lunas atau bertahap), status nota otomatis ter-update.
4. Di akhir hari, CS membuat Laporan Harian yang merangkum seluruh pemasukan/pengeluaran hari itu (baik dari nota maupun pengeluaran operasional).
5. Laporan dicetak dan diserahkan (uang + dokumen) ke pihak manajemen yang ditunjuk.

---

## 5. Aturan Bisnis Kunci

### 5.1 Rumus Perhitungan Harga Produk (`dp_produk.rumus`)
- `0` = **Perkalian Luas**: `lebar × panjang × harga × qty`
- `1` = **Perkalian Qty**: `harga × qty`

### 5.2 Prioritas Sumber Harga
Urutan penentuan harga saat nota dibuat (dari paling spesifik ke paling umum):
1. `dp_harga_khusus` (per konsumen individual)
2. `dp_kategori_harga` (per kategori konsumen)
3. `dp_produk.promo` (jika dalam rentang `promo_awal`–`promo_akhir`)
4. `dp_produk.harga` (harga dasar)

### 5.3 Status Nota (`dp_nota.status_nota`)
- `0` Belum Bayar
- `1` Belum Lunas
- `2` Lunas
- `3` Hapus Nota (void, bukan hard delete)
- `4` Retur Nota

### 5.4 Alur Serah Terima Laporan Harian
- Wajib dilakukan setiap sore oleh CS Digital Printing.
- Penerima **selalu pihak internal perusahaan** — bisa admin divisi lain, manajer, direktur, atau pihak lain yang ditunjuk manajemen. Tidak pernah pihak ketiga/luar perusahaan.
- Kasus khusus: jika penerima resmi (misal admin DP shift berikutnya) belum masuk kerja, laporan & uang bisa dititipkan sementara ke admin divisi lain, lalu diteruskan esok harinya. Kejadian ini dianggap jarang terjadi dan diketahui manajemen — **tidak perlu field khusus di database**, cukup dicatat di kolom `keterangan`.
- Field pencatatan: `diserahkan_id`, `diterima_id`, `diketahui_id` — semua merujuk ke user internal (FK ke tabel `users`).
- `print_at` mencatat kapan laporan terakhir dicetak fisik — karena laporan bisa dicetak ulang tanpa berarti serah terima terjadi saat itu.

---

## 6. Struktur Data (ringkas)

> Referensi lengkap ada di file migration `2026-04-07-043304_CreateDigitalPrintingTables.php`. Bagian ini hanya peta relasi tingkat tinggi.

**Master data:**
`kategori_konsumen` → `konsumen`
`dp_mesin`, `dp_bahan` → `dp_produk`

**Aturan harga:**
`dp_kategori_harga` (kategori_konsumen ↔ produk)
`dp_harga_khusus` (konsumen ↔ produk)

**Transaksi:**
`dp_nota` → `dp_nota_isi` (multi-item per nota)
`dp_nota` → `dp_nota_bayar` (multi-pembayaran per nota)

**Laporan:**
`dp_laporan` → `dp_laporan_isi` (detail baris pemasukan/pengeluaran, opsional terhubung ke `dp_nota`)

**Semua tabel transaksi/master mendukung soft delete** (`deleted_at`), diaktifkan per-Model lewat `$useSoftDeletes`.

---

## 7. Keputusan Desain Penting (Log)

Bagian ini mencatat *kenapa* suatu keputusan diambil, supaya tidak terulang tanya/diskusi yang sama di kemudian hari.

- **`konsumen.nama` tidak unique** — nama pelanggan/perusahaan bisa duplikat secara wajar.
- **Soft delete + unique constraint** — validasi uniqueness (misal nama produk) dilakukan di level Model/aplikasi (`WHERE deleted_at IS NULL`), bukan di level constraint DB, karena constraint DB akan bentrok dengan baris yang sudah di-soft-delete.
- **`dp_laporan` snapshot nominal** — nilai `pemasukan`, `pengeluaran`, `tunai`, `transfer` disimpan sebagai snapshot saat laporan dibuat, bukan dihitung ulang on-the-fly, agar dokumen serah terima tidak berubah meski data nota di-edit belakangan.
- **`diserahkan/diterima/diketahui` = FK ke `users`**, bukan teks bebas — karena selalu pihak internal perusahaan, memungkinkan validasi data & query rekap tanpa masalah variasi penulisan nama.
- **Unique key `[user_id, tanggal]` di `dp_laporan`** — satu user hanya boleh punya satu laporan per hari, tapi beberapa laporan (dari user berbeda) bisa ada di tanggal yang sama.

---

## 8. Di Luar Cakupan Dokumen Ini

- Modul divisi lain (`adv_*`, `umum_*`, `adm_*`) — akan didokumentasikan terpisah saat mulai dikerjakan.
- Fitur roadmap masa depan (notifikasi Telegram, bot WhatsApp, dsb) — dicatat terpisah, tidak termasuk scope pengerjaan saat ini.
- Detail teknis konfigurasi CI4 Shield (role/permission granular).

---

*Dokumen ini adalah referensi kerja pribadi, bukan dokumen formal untuk approval pihak lain. Update sesuai perkembangan proyek.*
