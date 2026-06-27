# LMS UNSIQ (Enterprise Edition)

LMS UNSIQ adalah sistem manajemen pembelajaran (Learning Management System) kelas *enterprise* yang dirancang secara profesional dengan arsitektur **PHP Native MVC**, mengadopsi standar **PSR-4 Autoloading**, pola desain **Singleton Database (PDO)**, serta dilengkapi sistem autentikasi berlapis dan desain UI modern berbasis glassmorphism dan *dark theme*.

## 🚀 Fitur Utama

- **Sistem Arsitektur Modern**:
  - Routing dengan dukungan `GET` & `POST` beserta Parameter Dinamis (`/courses/{id}`).
  - Middleware System: `AuthMiddleware`, `RoleMiddleware`, `GuestMiddleware`, dan perlindungan `CSRFMiddleware`.
  - Base Model & Controller Abstract Class.
- **Autentikasi & Multi-Role**:
  - Akses berdasarkan Role: **Admin**, **Dosen**, **Mahasiswa**.
  - Dashboard khusus berdasarkan role masing-masing.
- **Manajemen Pembelajaran**:
  - **Mata Kuliah (Courses)**: Dosen dapat membuat mata kuliah dan mengelola bahan ajar.
  - **Materi (Materials)**: Dukungan modul, video *embed*, dan unggah *file attachment* (PDF, PPT, dll).
  - **Tugas & Pengumpulan**: Sistem *deadline*, opsi toleransi terlambat, dan pengumpulan (upload file) dengan proses *grading* oleh dosen.
  - **Kuis / Ujian Online**: Ujian interaktif, opsi acak soal, skor minimal (KKM), batas durasi dengan fitur auto-submit.
  - **Rekap Nilai (Grades)**: Mahasiswa dapat melihat histori nilai, dosen dapat merekap per kelas.
- **Fitur Tambahan (Eksklusif Terbaru)**:
  - **Pencarian Cerdas (Live Search)**: Kotak pencarian dinamis (AJAX/JS) untuk rekomendasi mata kuliah di Navbar dan pencarian mahasiswa di halaman penilaian Dosen.
  - **Live Chat Global**: Sistem pesan *real-time* dengan antarmuka *bubble chat* modern untuk mengirim pesan ke semua sivitas akademika (Mahasiswa, Dosen, Admin).
  - **Kalender Akademik Terintegrasi**: Menggabungkan agenda universitas (buatan Admin) dan tenggat waktu tugas kelas secara otomatis, dilengkapi fitur *filter view*.
  - **Gamification Mahasiswa (Bento Grid)**: Dashboard khusus Mahasiswa dengan layout Bento UI, metrik "waktu belajar", dan sistem poin XP untuk meningkatkan retensi belajar.
  - **Manajemen Lampiran (Dosen)**: Dosen memiliki kapabilitas mengunggah file lampiran (PDF/Docs) beserta soal untuk diunduh mahasiswa.
  - Profil Pengguna (Upload Foto).
  - Pengumuman (Sematkan/Pin Pengumuman per Mata Kuliah atau Umum).
  - Notifikasi *Real-Time*.
  - Pengaturan Sistem Dinamis (Dikelola Admin).

## 🛠️ Instalasi & Konfigurasi

1. **Persyaratan Sistem**:
   - PHP >= 8.1
   - MySQL/MariaDB
   - Apache/Nginx (dengan dukungan `.htaccess` / `mod_rewrite`)

2. **Langkah Instalasi**:
   - Clone repository / tempatkan *source code* di folder server (misal: `d:\xampp\htdocs\project_lms`).
   - Salin file `.env.example` menjadi `.env` dan konfigurasikan akses ke Database Anda.
   - Buat database `lms_unsiq` (atau sesuai nama pada `.env`).
   - Import skema database di `database/migrations/001_create_all_tables.sql`.
   - Jalankan seeder di `database/seeders/seed_data.sql` (opsional untuk data *dummy* awal).

3. **Autentikasi Default**:
   - Akun Admin: `admin@lms.unsiq.ac.id` | Password: `password`
   - Akun Dosen: `dosen1@lms.unsiq.ac.id` | Password: `password`
   - Akun Mahasiswa: `mhs1@lms.unsiq.ac.id` | Password: `password`

## 🎨 UI/UX Design & Responsiveness

LMS UNSIQ menggunakan sistem desain terpusat (CSS Variables) dengan *theme* bergaya modern *glassmorphism* dan *bento grids*.
Komponen didesain sepenuhnya **100% responsive cross-device** (Desktop, Tablet, Smartphone) dengan Media Queries yang beradaptasi dengan *fluid*. 
Aplikasi mengusung konsep mikro interaksi (*micro-interactions*), status *badges*, navigasi *dual-sidebar* dinamis, *smooth pagination*, *alerts*, dan *modals*.

---
Dikembangkan sebagai bagian dari sistem skala Enterprise untuk membuktikan keandalan PHP Native berstandar industri modern.
