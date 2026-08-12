# Sikurva — Sistem Pemantauan Tumbuh Kembang Anak

**Project Portfolio**

**Nama:** Ahita Bisma Adlula

**Role:** Software Engineer / Full-Stack Developer

> Platform digital yang membantu tenaga kesehatan dan orang tua memantau pertumbuhan anak berdasarkan standar WHO, menggantikan pencatatan manual dengan sistem terintegrasi yang menghasilkan grafik pertumbuhan otomatis dan laporan PDF.

---

## Project Overview

**Sikurva** adalah aplikasi web untuk pemantauan tumbuh kembang anak berbasis kurva pertumbuhan WHO. Sistem ini memungkinkan tenaga kesehatan (dokter anak, bidan, posyandu) dan orang tua untuk mencatat data antropometri anak — berat badan, tinggi badan, lingkar kepala — kemudian secara otomatis memplot data tersebut ke dalam grafik kurva pertumbuhan standar WHO dan menghasilkan interpretasi status gizi.

Project ini saya bangun sendiri (solo) dari nol hingga production-ready. Saya bertanggung jawab atas seluruh aspek teknis: arsitektur sistem, desain database, pengembangan backend dan frontend, integrasi pembayaran, konfigurasi deployment, dan pemeliharaan.

---

## Problem

Sebelum adanya sistem seperti Sikurva, proses pemantauan tumbuh kembang anak menghadapi beberapa masalah:

**Problem**

- **Pencatatan manual di buku KIA/KMS yang rentan hilang dan sulit dianalisis.** Data antropometri ditulis tangan, sehingga tidak ada rekam historis digital yang mudah diakses. Ketika buku hilang, seluruh riwayat pertumbuhan anak ikut hilang.

- **Plotting kurva pertumbuhan dilakukan secara manual di atas kertas.** Tenaga kesehatan harus menggambar titik-titik di grafik cetak, yang memakan waktu dan rawan kesalahan interpretasi. Tidak ada otomatisasi yang bisa langsung menunjukkan apakah anak berada di zona normal, gizi kurang, atau gizi lebih.

- **Kolaborasi antar tenaga kesehatan dan orang tua terbatas.** Data anak hanya tersimpan di satu tempat. Dokter spesialis yang menerima rujukan tidak memiliki akses ke riwayat pertumbuhan pasien sebelumnya, sehingga harus memulai pencatatan dari awal.

Konsekuensinya: deteksi dini masalah gizi terlambat, data tidak terstandarisasi, dan proses monitoring memakan waktu yang seharusnya bisa digunakan untuk konsultasi dengan pasien.

---

## Solution

Sikurva menyelesaikan masalah tersebut dengan pendekatan digital end-to-end:

**Solution**

- **Input data digital menggantikan pencatatan kertas.** Setiap pemeriksaan dicatat langsung ke sistem melalui form terstruktur. Data tersimpan di database relasional (MySQL) dengan riwayat lengkap dan dapat diakses kapan saja.

- **Plotting grafik otomatis berdasarkan standar WHO.** Begitu data antropometri masuk, sistem otomatis menghitung usia anak, menentukan status gizi, dan memplot data ke grafik pertumbuhan menggunakan Chart.js. Grafik interaktif menampilkan kurva referensi WHO (garis Z-score) sebagai pembanding.

- **Sistem sharing pasien antar pengguna.** Tenaga kesehatan dapat membagikan data pasien ke kolega atau orang tua melalui sistem notifikasi. Penerima dapat menerima atau menolak undangan berbagi data, dan pemilik data dapat menghentikan akses kapan saja.

**Alur utama sistem:**

```
User → Blade UI (Alpine.js + Chart.js) → Controller
       → Service Layer → Repository/Model → MySQL
       → Response → PDF (DomPDF) / WhatsApp (RuangWA) / Email (SMTP)
```

---

## My Contribution

Project ini saya kerjakan sendiri sepenuhnya. Berikut kontribusi saya berdasarkan komponen sistem:

**Backend**

- Merancang dan mengimplementasikan arsitektur aplikasi Laravel dengan pola Repository-Service-Controller, memisahkan business logic dari data access dan request handling
- Membangun seluruh REST API dan web route dengan autentikasi berbasis session, role-based access control (Super Admin, Admin Nakes, Admin Non-Nakes), dan middleware kustom untuk otorisasi akses pasien
- Mengembangkan modul poin system: user mendapatkan poin saat daftar, menggunakan poin untuk setiap tindakan (tambah pasien, generate PDF, penilaian, import data)

**Frontend**

- Membangun UI dashboard admin menggunakan Tailwind CSS, DaisyUI, dan Alpine.js untuk interaktivitas tanpa full-page reload
- Mengimplementasikan visualisasi kurva pertumbuhan interaktif dengan Chart.js yang menampilkan data aktual pasien di atas kurva referensi WHO
- Membuat landing page publik dengan sistem CMS yang bisa dikelola Super Admin (banner, layanan, testimoni, profil, kontak)

**Database**

- Merancang 30+ tabel database MySQL dengan relasi yang tepat (users, patients, antro_patients, subscriptions, point_transactions, kurva_table_settings, dll.)
- Menambahkan indexing strategis pada kolom-kolom query berat (tabel antro_patients, patients, point_transactions) untuk optimasi performa
- Mengimplementasikan database transaction pada proses pembayaran Midtrans agar batch poin dan transaksi poin selalu konsisten

**Deployment / Infrastructure**

- Membuat multi-stage Dockerfile yang memisahkan build frontend (Node.js) dan runtime PHP, menghasilkan image yang efisien
- Mengkonfigurasi docker-compose dengan 3 service: app, queue worker, dan scheduler untuk background jobs
- Men-setup CI/CD pipeline menggunakan GitHub Actions yang otomatis deploy ke VPS setiap push ke branch main

**Integrasi Eksternal**

- Mengintegrasikan Midtrans payment gateway untuk subscription, termasuk webhook handler yang memvalidasi signature key dan memproses status pembayaran
- Mengimplementasikan pengiriman laporan PDF via WhatsApp menggunakan RuangWA API (WhatsApp gateway Indonesia) dan via email menggunakan Gmail SMTP
- Menambahkan Google 2FA untuk keamanan akun Super Admin

---

## Technology Stack

| Category | Technology | Usage |
| --- | --- | --- |
| Backend | Laravel 12 (PHP 8.2+) | Framework utama, MVC, routing, queue, scheduler |
| Frontend | Blade + Tailwind CSS 4 + Alpine.js 3 | Server-side rendering dengan komponen interaktif |
| Charts | Chart.js 4 | Visualisasi kurva pertumbuhan interaktif |
| Database | MySQL | Penyimpanan seluruh data aplikasi |
| Authentication | Laravel Breeze + Spatie Permission + Google 2FA | Session auth, role-permission, two-factor |
| Payment | Midtrans (Snap) | Payment gateway untuk subscription paket |
| PDF | DomPDF (barryvdh/laravel-dompdf) | Generate laporan kurva pertumbuhan |
| Excel | Laravel Excel (maatwebsite/excel) | Import/export data pasien dan antro |
| WhatsApp | RuangWA API | Kirim laporan PDF via WhatsApp |
| Queue | Laravel Queue (database driver) | Background jobs: kirim PDF email, kirim PDF WhatsApp |
| Scheduler | Laravel Task Scheduler | Cron job: cek akun expired, bersihkan PDF, update data |
| Testing | Pest PHP | Unit dan feature testing |
| Deployment | Docker + GitHub Actions | Multi-stage container + CI/CD ke VPS |
| CSS UI | DaisyUI 5 | Komponen UI di atas Tailwind |

---

## Main Features

### 1. Manajemen Data Pasien

Fungsi utama untuk mencatat data anak. Pengguna dapat menambah, mengedit, menghapus, mencari, dan mengimpor data pasien via Excel. Setiap pasien memiliki kode lokal (kode MR), data identitas, tinggi orang tua, dan kontak. Sistem membedakan terminologi: tenaga kesehatan melihat "Pasien", orang tua melihat "Anak".

### 2. Input & Plotting Data Antropometri

Setiap kali pemeriksaan, pengguna menginput berat badan, tinggi badan, dan lingkar kepala anak. Sistem otomatis menghitung usia anak dalam bulan dan hari, menghitung IMT, serta menghitung usia koreksi untuk bayi prematur (berdasarkan usia gestasi). Data langsung terplot ke grafik kurva pertumbuhan WHO.

### 3. Grafik Kurva Pertumbuhan Interaktif

Sistem memplot data antropometri pasien di atas 12+ jenis kurva referensi WHO menggunakan Chart.js. Grafik menampilkan garis Z-score (-3 SD hingga +3 SD) dan titik data pasien. Grafik dibedakan berdasarkan jenis kelamin dan usia — mendukung bayi cukup bulan (full-term) dan bayi prematur dengan usia koreksi. Pengguna dapat berinteraksi dengan grafik: zoom, hover tooltip, dan toggle visibility.

### 4. Generate & Kirim Laporan PDF

Pengguna dapat meng-generate laporan PDF berisi grafik kurva pertumbuhan, interpretasi status gizi, dan tabel data pemeriksaan. PDF dapat dikirim langsung ke email atau WhatsApp orang tua/wali pasien. Sistem mendukung pengiriman standar dan kustom (memilih kolom yang ingin dicetak). Background job queue digunakan agar proses tidak memblokir UI.

### 5. Sistem Poin & Subscription

Setiap tindakan (menambah pasien, generate PDF, import data, penilaian) mengonsumsi poin. Pengguna mendapatkan poin melalui pembelian paket subscription via Midtrans. Sistem mencatat seluruh transaksi poin dan batch poin, lengkap dengan masa aktif dan riwayat penggunaan.

### 6. Sharing & Kolaborasi Pasien

Tenaga kesehatan dapat membagikan akses data pasien ke kolega (collaborator) atau ke orang tua (share). Penerima mendapat notifikasi dan dapat menerima/menolak. Pemilik data dapat menghentikan akses. Sistem mencatat log aktivitas sharing untuk keperluan audit. Fitur ini memungkinkan rujukan pasien antar fasilitas kesehatan tanpa kehilangan riwayat data.

### 7. Interpretasi Status Gizi

Berdasarkan data antropometri terbaru, sistem menghasilkan interpretasi otomatis: status gizi berdasarkan BB/U (berat badan menurut umur), TB/U (tinggi badan menurut umur), BB/TB (berat badan menurut tinggi badan), dan IMT/U. Interpretasi menggunakan kategori WHO: gizi buruk, gizi kurang, gizi baik, berisiko gizi lebih, gizi lebih, obesitas.

---

## Technical Architecture

**Frontend** — Blade template engine dengan Tailwind CSS untuk styling dan Alpine.js untuk reaktivitas komponen. Chart.js menangani visualisasi grafik kurva. Tidak menggunakan SPA framework; pendekatan server-rendered dipilih karena sesuai dengan kebutuhan aplikasi yang tidak memerlukan real-time update dan lebih sederhana dalam deployment.

**Backend** — Laravel 12 monolith dengan pola Service-Repository-Controller. Controller menangani HTTP request/response, Service layer berisi business logic, Repository layer mengabstraksi akses data. Middleware digunakan untuk otorisasi berbasis role (admin, super-admin, is_nakes) dan kepemilikan data (patient owner, patient share).

**API** — REST API versi 1 dengan JWT authentication tersedia di `api/v1/*`. API endpoints mencakup auth (register, login, logout, refresh, me) dan captcha. Postman collection tersedia di repository.

**Database** — MySQL dengan 30+ tabel. Tabel utama: `users`, `patients`, `antro_patients`, `subscriptions`, `user_subscriptions`, `point_transactions`, `point_batches`, `instansis`, `kurva_table_settings` (12 tabel referensi WHO). Indexing diterapkan pada foreign key dan kolom yang sering di-query.

**Authentication** — Session-based auth menggunakan Laravel Breeze. Super Admin memiliki opsi Google 2FA. Role-based access control menggunakan Spatie Permission (role: super-admin, admin). Atribut `is_nakes` pada user membedakan tenaga kesehatan dan orang tua.

**External Services** — Midtrans Snap (payment gateway), RuangWA (WhatsApp API), Gmail SMTP (email).

**Deployment** — Docker multi-container (app + queue worker + scheduler). CI/CD via GitHub Actions, auto-deploy setiap push ke main. External Docker network untuk integrasi dengan service lain di VPS yang sama.

---

## Challenging Technical Problem

### Challenge 1: Plotting Kurva WHO untuk Bayi Prematur

**Challenge**

Standar kurva pertumbuhan WHO memiliki dua set data: untuk bayi cukup bulan (full-term, usia 0–60 bulan) dan bayi prematur (usia koreksi, usia gestasi <37 minggu). Sistem harus bisa menentukan kurva mana yang digunakan berdasarkan data pasien dan menghitung usia koreksi secara akurat.

**Investigation**

Saya membaca spesifikasi WHO Anthro dan memahami bahwa usia koreksi dihitung dengan mengurangi usia kronologis dengan selisih 40 minggu dan usia gestasi. Namun, perhitungan ini hanya berlaku hingga anak mencapai usia koreksi tertentu (biasanya 24 bulan). Saya mempelajari bahwa dibutuhkan `usia_koreksi_bulan` dan `usia_koreksi_total_hari` sebagai field terpisah di database.

**Solution**

Saya menambahkan field `usia_koreksi_bulan`, `usia_koreksi_total_hari`, `usia_gestasi_minggu`, dan `usia_gestasi_total_hari` pada tabel `antro_patients`. Di service layer, saya mengimplementasikan logika branching: jika `usia_kehamilan_minggu` pasien >= 37, gunakan tabel referensi full-term (Tabel 1-8); jika <37, gunakan tabel prematur (Tabel 9-12) dengan usia koreksi. Method `processChartData()` di `AntroService` menangani seluruh pemrosesan ini dan mengembalikan dataset siap pakai untuk Chart.js.

**Result**

Sistem kini mendukung penuh pemantauan tumbuh kembang untuk bayi prematur dan cukup bulan. Grafik menampilkan kurva yang benar sesuai usia kronologis atau usia koreksi, memungkinkan interpretasi status gizi yang akurat untuk kedua kelompok.

### Challenge 2: Integrasi Pembayaran Midtrans dengan Atomicity Poin

**Challenge**

Ketika pengguna membeli paket subscription, sistem harus: (1) menerima notifikasi pembayaran dari Midtrans webhook, (2) mengubah status subscription menjadi paid, (3) membuat point batch dengan masa aktif, dan (4) mencatat transaksi poin. Jika salah satu langkah gagal, seluruh proses harus di-rollback untuk menjaga konsistensi data.

**Investigation**

Saya menganalisis flow pembayaran Midtrans dan menemukan bahwa webhook bisa dikirim beberapa kali untuk order yang sama. Saya juga perlu memvalidasi signature key untuk memastikan request berasal dari Midtrans, bukan dari pihak ketiga.

**Solution**

Saya mengimplementasikan webhook handler (`MidtransWebhookController`) yang:
1. Memvalidasi signature key menggunakan SHA-512 hash
2. Mencocokkan order_id dengan data di database
3. Membungkus seluruh proses pembayaran sukses dalam database transaction (`DB::beginTransaction()` / `DB::commit()` / `DB::rollBack()`)
4. Membuat point batch dengan `pointService->createBatch()` dan transaksi terkait dalam satu transaksi atomik
5. Menggunakan idempotency check untuk menghindari pemrosesan ganda

**Result**

Pembayaran diproses secara atomik — tidak ada kasus di mana subscription terbayar tetapi poin tidak bertambah, atau sebaliknya. Cache poin di-invalidasi setelah transaksi sukses untuk memastikan data selalu akurat.

---

## Security & Engineering Practices

Berdasarkan implementasi di repository:

- **Authentication:** Session-based dengan email verification. Super Admin diwajibkan setup Google 2FA.
- **Authorization:** Middleware bertingkat — `AdminMiddleware`, `SuperAdminMiddleware`, `IsNakesMiddleware`, `PatientOwnerMiddleware`, `PatientShareMiddleware`. Setiap akses ke data pasien diverifikasi kepemilikannya.
- **Input Validation:** Form request validation di setiap controller (`$request->validate()` dengan rules lengkap). Validasi nomor WhatsApp menggunakan `propaganistas/laravel-phone` untuk format Indonesia.
- **CSRF Protection:** Default Laravel CSRF token di semua form POST/PUT/DELETE.
- **Payment Security:** Webhook Midtrans divalidasi dengan signature key SHA-512. Midtrans keys disimpan sebagai konfigurasi database (bukan hardcode), dapat di-update oleh Super Admin.
- **Error Handling:** Try-catch di operasi kritis (pembayaran, import data). Logging error menggunakan Laravel Log facade.
- **Database Transaction:** Digunakan pada proses pembayaran Midtrans untuk menjaga atomicity data poin dan subscription.
- **Environment Configuration:** `.env` file untuk semua kredensial. `.env.example` disediakan untuk referensi setup.
- **CAPTCHA:** Mews CAPTCHA diimplementasikan pada form registrasi dan login publik.

---

## Performance & Scalability

Berdasarkan analisis source code:

- **Database Indexing:** Migration terpisah (`add_indexes_to_antro_patients_table`, `add_indexes_to_patients_table`, `add_indexes_to_point_transactions_table`) menunjukkan perhatian pada optimasi query di tabel dengan pertumbuhan data tinggi.
- **Caching:** Landing page data (banner, profile, layanan, testimoni, paket, helps) di-cache menggunakan Laravel Cache dengan TTL 30 menit hingga 7 hari. Setting poin di-cache "forever" hingga ada perubahan. Cache di-invalidasi saat data berubah.
- **Pagination:** Diimplementasikan di halaman daftar pasien, aktivitas, dan dashboard (25 item per halaman).
- **Queue/Background Jobs:** Laravel Queue (database driver) digunakan untuk: mengirim PDF via email (`SendPdfEmail`), mengirim PDF via WhatsApp (`SendPdfWhatsapp`), dan notifikasi poin rendah (`SendEmailPointLessThan100`). Queue worker berjalan sebagai container terpisah dalam docker-compose.
- **Data Export:** Export data menggunakan Laravel Excel dengan batasan ukuran file (maksimal 2MB) dan limit waktu eksekusi yang ditingkatkan (300 detik).

Aspek seperti Redis caching, horizontal scaling, dan load balancing belum menjadi fokus project karena project ini dirancang untuk deployment skala klinik/posyandu, bukan sistem nasional.

---

## Result / Impact

Project telah mencapai status production-ready dengan:

- **Full-stack application** mencakup landing page publik, dashboard admin, dan dashboard super admin
- **Fungsionalitas lengkap** dari input data hingga output laporan PDF/WhatsApp
- **Sistem pembayaran terintegrasi** dengan Midtrans untuk monetisasi
- **CI/CD pipeline** yang memungkinkan deployment otomatis setiap perubahan kode
- **Scheduler otomatis** untuk maintenance tasks (cek akun expired, bersihkan file PDF temporary)

Secara kualitatif, sistem ini:

- Menghilangkan kebutuhan plotting grafik manual oleh tenaga kesehatan
- Menyediakan rekam historis pertumbuhan digital yang tidak bisa hilang
- Memungkinkan kolaborasi antar fasilitas kesehatan melalui fitur sharing pasien
- Mengotomatisasi pembuatan laporan yang sebelumnya memakan waktu signifikan

---

## Demo

**[MASUKKAN URL DEMO]**

---

## Source Code

**GitHub:** https://github.com/bisma/sikurva

Repository berisi source code lengkap aplikasi, termasuk backend (Laravel), frontend (Blade + Tailwind + Alpine.js), database migrations, Docker configuration, CI/CD workflow, dan Postman API collection.

---

## Screenshots

### Screenshot 1 — Landing Page

[SCREENSHOT DIPERLUKAN]

Halaman landing page publik yang menampilkan informasi layanan, paket subscription, dan testimoni pengguna. Konten halaman dapat dikelola melalui panel Super Admin.

### Screenshot 2 — Dashboard Admin (Daftar Pasien)

[SCREENSHOT DIPERLUKAN]

Halaman utama setelah login. Menampilkan daftar pasien dengan fitur pencarian, filter, dan aksi (lihat detail, edit, hapus). Dashboard menampilkan metrik aktivitas pengguna.

### Screenshot 3 — Grafik Kurva Pertumbuhan

[SCREENSHOT DIPERLUKAN]

Halaman preview pasien yang menampilkan grafik kurva pertumbuhan WHO interaktif. Titik data antropometri pasien terplot di atas kurva referensi standar WHO. Grafik mendukung interaksi zoom dan tooltip.

### Screenshot 4 — Input Data Antropometri

[SCREENSHOT DIPERLUKAN]

Form input data pemeriksaan antropometri: tanggal periksa, usia, berat badan, tinggi badan, dan lingkar kepala. Sistem otomatis menghitung IMT dan total usia dalam hari.

### Screenshot 5 — Halaman Subscription & Pembayaran

[SCREENSHOT DIPERLUKAN]

Halaman pemilihan paket subscription dengan integrasi pembayaran Midtrans. Menampilkan detail paket, harga, dan poin yang didapatkan.

---

## Why This Is My Best Project

Sikurva adalah project paling komprehensif yang pernah saya bangun seorang diri, dan inilah alasannya:

1. **End-to-end ownership** — Saya membangun semuanya dari nol: arsitektur database, backend API, frontend UI, integrasi third-party, deployment, dan CI/CD. Tidak ada bagian yang saya serahkan ke orang lain. Ini membuktikan kemampuan saya sebagai full-stack developer yang bisa deliver produk lengkap.

2. **Domain complexity** — Pemantauan tumbuh kembang anak memiliki aturan bisnis yang tidak trivial: referensi kurva WHO yang berbeda berdasarkan jenis kelamin, usia, dan status prematur; perhitungan usia koreksi; interpretasi status gizi multi-indikator. Saya berhasil menerjemahkan kompleksitas domain medis ke dalam kode yang terstruktur dan teruji.

3. **Production-ready engineering** — Project ini bukan prototype. Ada database indexing, caching strategy, queue system, scheduler, database transaction, input validation, error handling, logging, dan CI/CD pipeline. Semua praktik yang saya terapkan menunjukkan saya memahami apa yang dibutuhkan software untuk berjalan di production.

4. **Business logic implementation** — Sistem poin, subscription, dan pembayaran Midtrans bukan sekadar CRUD. Saya mengimplementasikan atomic transaction untuk memastikan integritas data finansial, idempotency handling untuk webhook, dan cache invalidation yang tepat.

5. **Real-world problem solving** — Project ini lahir dari masalah nyata di lapangan: pencatatan tumbuh kembang anak yang masih manual, tidak terstandarisasi, dan rentan kehilangan data. Saya tidak hanya membangun software; saya membangun solusi untuk masalah yang ada.

Project ini mendemonstrasikan kemampuan saya dalam menganalisis masalah, merancang arsitektur, memilih teknologi yang tepat, mengimplementasikan fitur kompleks, dan mendeploy aplikasi ke production — semua dilakukan secara mandiri.

---

## Checklist

- [ ] GitHub sudah benar — https://github.com/bisma/sikurva
- [ ] Demo sudah benar — **[MASUKKAN URL DEMO]**
- [ ] Screenshot sudah tersedia — **[SCREENSHOT DIPERLUKAN]**
- [ ] Semua klaim teknis sudah diverifikasi dari source code
- [ ] Tidak ada data yang dibuat-buat
- [ ] Dokumen siap diexport menjadi PDF
