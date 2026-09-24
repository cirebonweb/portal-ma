# Arsitektur Aplikasi

## Stack utama

- PHP `^8.2`
- CodeIgniter 4 `^4.7`
- CodeIgniter Shield `^1.3`
- AdminLTE `3.2.0`
- Bootstrap `4.6.2`
- jQuery `3.6.0`
- DataTables `1.12.1`
- SweetAlert2 `11.14.5`
- Select2 `4.0.13`

Detail dependensi dan plugin dapat dilengkapi kembali pada dokumentasi arsitektur
ini apabila diperlukan.

## Struktur aplikasi

- `app/Config/` — konfigurasi aplikasi dan route.
- `app/Controllers/` — controller berdasarkan area atau modul.
- `app/Models/` — model dan akses data.
- `app/Database/Migrations/` — migration serta SQL pendukung.
- `app/Views/` — template, layout, dan halaman modul.
- `app/Views/layout/` — template utama dan navigasi.
- `public/page/` — JavaScript halaman atau modul.
- `public/vendor/` — asset dan helper internal.
- `docs/` — dokumentasi proyek.

## Pola modul

Modul umumnya menghubungkan:

```text
Route
  -> Controller
  -> Model
  -> View
  -> JavaScript halaman/helper
  -> Tabel database
```

Perubahan pada satu modul perlu diperiksa pada seluruh bagian tersebut agar route,
response, tampilan, validasi, dan data tetap konsisten.

## Area aplikasi

Area menu dan folder controller/view yang sudah dirancang:

- `Master` — tipe konsumen, tipe mesin, tipe harga, harga khusus, dan finishing.
- `Bahan` — bahan, order bahan, stok, sisa, dan limbah.
- `Data` — konsumen, supplier, mesin, produk, nota, cetak, pembayaran, dan laporan.
- `Log` — log laporan.

## Role pengguna

Role bisnis yang sudah dirancang:

- `superadmin` — akses penuh.
- `cs` — customer service dan transaksi penjualan.
- `printing` — operator mesin dan kepala produksi.
- `admin` — administrasi keuangan.

Detail permission per route dan per aksi belum dianggap final sebelum diverifikasi
dengan implementasi CodeIgniter Shield.

## Komponen internal

Proyek memiliki helper dan trait internal, antara lain:

- `CrudTrait`
- `helper_form.min.js`
- `helper_format.min.js`
- helper upload
- CSS custom AdminLTE, sidebar, dan tabel

Gunakan komponen tersebut sebelum menambahkan implementasi baru yang memiliki
tanggung jawab sama.

## Aturan perubahan

- Perubahan backend harus mempertimbangkan route, controller, model, migration,
  validasi, dan response yang terkait.
- Perubahan frontend harus mempertimbangkan view, JavaScript halaman, helper,
  notifikasi, dan DataTables jika digunakan.
- Jangan mengandalkan worktree lain sebagai source of truth.
- Dokumentasikan keputusan arsitektur yang memengaruhi alur data atau hak akses.
