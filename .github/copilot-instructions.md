# Instruksi Copilot

## Repository dan referensi

- Repository aktif adalah `D:\Data Aplikasi\Codeigniter\portal-ma`.
- Kerjakan perubahan normal hanya di repository aktif tersebut.
- Jangan menggunakan `portal-ma.worktrees` sebagai target perubahan atau referensi utama kecuali diminta secara eksplisit.
- Prioritaskan source code aktif, dokumentasi proyek, dan pola yang sudah digunakan di repository ini.

## Cara bekerja

- Baca struktur dan kode terkait sebelum mengubah file.
- Gunakan helper, trait, komponen, dan pola yang sudah tersedia jika masih sesuai.
- Cari implementasi serupa terlebih dahulu sebelum membuat logika baru.
- Jangan mengubah file yang tidak berkaitan dengan pekerjaan.
- Pertahankan perilaku yang sudah berjalan kecuali perubahan perilaku memang diminta.
- Jika permintaan ambigu dan terdapat lebih dari satu interpretasi yang berdampak pada desain atau perilaku, tanyakan terlebih dahulu.
- Jika terdapat pilihan teknis yang lebih baik daripada kebiasaan lama, gunakan pilihan yang lebih baik dan jelaskan alasannya secara ringkas.
- Utamakan kode yang ringkas, jelas, aman, dan mudah dirawat.
- Hindari duplikasi logika dan penggunaan cast yang tidak diperlukan.
- Jangan menyembunyikan error dengan fallback diam-diam atau catch yang terlalu luas.

## Prinsip desain dan stabilitas server

Pertimbangkan prinsip berikut saat membuat atau merevisi kode, dengan prioritas
utama pada stabilitas server:

1. **Stabilitas Server** — kurangi request, proses, query, dan beban server yang
   tidak perlu tanpa mengorbankan keamanan, konsistensi data, atau kejelasan kode.
2. **OOP (Object-Oriented Programming)** — gunakan abstraksi, enkapsulasi, dan
   tanggung jawab objek secara tepat sesuai pola CodeIgniter yang sudah ada.
3. **DRY (Don't Repeat Yourself)** — gunakan helper, trait, service, atau fungsi
   bersama untuk logika yang memang berulang.
4. **SRP (Single Responsibility Principle)** — satu class, method, atau modul
   sebaiknya memiliki satu tanggung jawab yang jelas.
5. **KISS (Keep It Simple)** — pilih solusi yang sederhana, mudah dipahami, dan
   tidak menambahkan kompleksitas tanpa manfaat nyata.
6. **YAGNI (You Aren't Gonna Need It)** — jangan membuat fitur, abstraksi, query,
   atau konfigurasi sebelum benar-benar diperlukan.
7. **SoC (Separation of Concerns)** — pisahkan tanggung jawab route, controller,
   model, view, JavaScript, validasi, dan proses bisnis.

### Strategi validasi dan request

- Gunakan validasi sisi klien untuk memberikan feedback cepat, mencegah request
  yang jelas tidak valid, dan membuat UI terasa interaktif.
- Validasi sisi klien tidak menggantikan validasi server. Data dari pengguna tetap
  harus divalidasi ulang di server sebelum diproses atau disimpan.
- Hindari request AJAX berulang, query database yang tidak diperlukan, pemuatan
  asset berlebihan, dan proses server yang dapat dicegah secara aman dari klien.
- Gunakan debounce, batching, cache, pagination, atau pemuatan bertahap jika
  sesuai dengan pola aplikasi dan benar-benar mengurangi beban.
- Jangan memindahkan logika keamanan, otorisasi, integritas transaksi, atau aturan
  bisnis penting hanya ke JavaScript.
- Jika optimasi stabilitas server berpotensi mengubah UX atau konsistensi data,
  jelaskan trade-off tersebut sebelum menerapkannya.

## Konvensi proyek

- Ikuti struktur CodeIgniter 4 yang sudah digunakan oleh proyek.
- Pertahankan pemisahan Controller, Model, View, migration, dan JavaScript.
- Ikuti pola `CrudTrait` dan helper JavaScript yang sudah tersedia jika relevan.
- Pertahankan pola layout, role, permission, validasi, response AJAX, dan notifikasi yang sudah digunakan.
- Perhatikan integrasi CodeIgniter Shield, AdminLTE, DataTables, dan helper internal.
- Gunakan penamaan file dan simbol yang konsisten dengan modul terkait.

## Validasi dan dokumentasi

- Setelah mengubah kode, jalankan validasi atau test yang paling relevan.
- Periksa diff agar perubahan tidak meluas ke area yang tidak diminta.
- Perbarui dokumentasi yang berkaitan langsung dengan perubahan.
- Catat asumsi atau pekerjaan yang belum diverifikasi jika diperlukan.
- Setelah setiap pekerjaan selesai, selalu catat ringkasannya pada
  `docs/05-log-job-ai.md`.
- Catatan log minimal memuat tanggal, ringkasan pekerjaan, file atau area yang
  berubah, dan hasil validasi jika ada.
- Gunakan catatan yang lebih lengkap jika perubahan besar, keputusan desain,
  atau pekerjaan yang belum selesai perlu dijelaskan.
- Perbarui status pada bagian `Tahap proyek CRUD` di
  `docs/01-status-proyek.md` jika pekerjaan mengubah status modul.

### Status tahap proyek CRUD

Gunakan penanda berikut secara konsisten:

- `[x] selesai` — pekerjaan selesai dan hasilnya dapat dijadikan referensi untuk
  membuat atau mengembangkan CRUD terkait.
- `[p] proses` — pekerjaan sedang dikerjakan saat ini.
- `[ ] antrian` — pekerjaan belum dikerjakan.

## Batasan perubahan

- Jangan menghapus dokumen lama hanya karena dokumentasi baru dibuat.
- Jangan memindahkan informasi dari dokumen lama tanpa memastikan informasi tersebut sudah tercatat dengan cukup pada dokumen baru.
- Jangan melakukan commit atau push tanpa diminta secara eksplisit.
