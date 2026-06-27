-- ===========================================
-- LMS UNSIQ - Seed Data
-- ===========================================
-- Data demo untuk testing
-- Run: mysql -u root lms_unsiq < database/seeders/seed_data.sql

USE lms_unsiq;

-- ===========================================
-- USERS (password di-hash dengan PASSWORD_BCRYPT)
-- ===========================================
-- Password: admin123
INSERT INTO users (name, email, password, role, nim_nidn, phone, is_active) VALUES
('Administrator', 'admin@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'admin', NULL, '08123456789', 1);

-- Password: dosen123
INSERT INTO users (name, email, password, role, nim_nidn, phone, bio, is_active) VALUES
('Dr. Ahmad Fauzi, M.Kom', 'dosen1@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'dosen', '0601068901', '08134567890', 'Dosen Teknik Informatika UNSIQ. Bidang keahlian: Web Development, Artificial Intelligence.', 1),
('Siti Nurhaliza, M.Cs', 'dosen2@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'dosen', '0601069002', '08145678901', 'Dosen Teknik Informatika UNSIQ. Bidang keahlian: Database Systems, Data Science.', 1);

-- Password: mhs123
INSERT INTO users (name, email, password, role, nim_nidn, phone, is_active) VALUES
('Budi Santoso', 'mhs1@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'mahasiswa', '21010001', '08156789012', 1),
('Dewi Lestari', 'mhs2@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'mahasiswa', '21010002', '08167890123', 1),
('Rizki Pratama', 'mhs3@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'mahasiswa', '21010003', '08178901234', 1),
('Aisyah Putri', 'mhs4@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'mahasiswa', '21010004', '08189012345', 1),
('Fajar Ramadhan', 'mhs5@lms.unsiq.ac.id', '$2y$10$bkrU0vfun1S0k1hwBKpR4Olewh.YugW3jyPPKfqx9/f6pFWSAVGHy', 'mahasiswa', '21010005', '08190123456', 1);

-- ===========================================
-- CATEGORIES
-- ===========================================
INSERT INTO categories (name, slug, description) VALUES
('Pemrograman', 'pemrograman', 'Mata kuliah terkait pemrograman dan pengembangan software'),
('Jaringan', 'jaringan', 'Mata kuliah terkait jaringan komputer dan infrastruktur'),
('Basis Data', 'basis-data', 'Mata kuliah terkait manajemen dan desain database'),
('Kecerdasan Buatan', 'kecerdasan-buatan', 'Mata kuliah terkait AI, ML, dan Data Science'),
('Umum', 'umum', 'Mata kuliah umum dan interdisipliner');

-- ===========================================
-- COURSES
-- ===========================================
INSERT INTO courses (dosen_id, category_id, code, name, description, sks, semester, academic_year, status) VALUES
(2, 1, 'MBKP-07.03.204', 'Pemrograman Web', 'Mata kuliah yang mempelajari pengembangan aplikasi web menggunakan HTML, CSS, JavaScript, dan PHP. Mahasiswa akan belajar dari fundamental hingga arsitektur MVC.', 3, 'Genap', '2025/2026', 'published'),
(2, 1, 'MBKP-07.03.205', 'Pemrograman Berorientasi Objek', 'Mata kuliah yang mempelajari paradigma pemrograman berorientasi objek menggunakan Java. Meliputi class, inheritance, polymorphism, interface, dan design patterns.', 3, 'Ganjil', '2025/2026', 'published'),
(3, 3, 'MBKP-07.03.301', 'Basis Data Lanjut', 'Mata kuliah lanjutan tentang desain dan manajemen database. Meliputi normalisasi, indexing, stored procedure, trigger, dan optimasi query.', 3, 'Genap', '2025/2026', 'published'),
(3, 4, 'MBKP-07.04.401', 'Pengantar Kecerdasan Buatan', 'Mata kuliah yang memperkenalkan konsep dasar AI, machine learning, neural network, dan penerapannya dalam kehidupan nyata.', 3, 'Ganjil', '2025/2026', 'draft'),
(2, 1, 'MBKP-07.03.206', 'Pemrograman Mobile', 'Pengembangan aplikasi mobile native dan cross-platform menggunakan React Native dan Flutter.', 3, 'Genap', '2025/2026', 'published');

-- ===========================================
-- ENROLLMENTS
-- ===========================================
INSERT INTO enrollments (user_id, course_id, status, progress) VALUES
(4, 1, 'active', 45.00),
(4, 2, 'active', 30.00),
(4, 3, 'active', 60.00),
(5, 1, 'active', 70.00),
(5, 3, 'active', 25.00),
(6, 1, 'active', 55.00),
(6, 2, 'active', 80.00),
(6, 5, 'active', 10.00),
(7, 1, 'active', 90.00),
(7, 2, 'active', 65.00),
(7, 3, 'active', 40.00),
(8, 1, 'active', 35.00),
(8, 5, 'active', 50.00);

-- ===========================================
-- MATERIALS
-- ===========================================
INSERT INTO materials (course_id, title, content, section, sort_order, is_published) VALUES
(1, 'Pengenalan Web Development', '<p>Web development adalah proses pembuatan dan pemeliharaan website. Pada materi ini kita akan mempelajari dasar-dasar web development termasuk HTML, CSS, dan JavaScript.</p>', 'Bab 1: Pendahuluan', 1, 1),
(1, 'HTML5 Fundamental', '<p>HTML (HyperText Markup Language) adalah bahasa markup standar untuk membuat halaman web. HTML5 adalah versi terbaru yang mendukung multimedia dan API modern.</p>', 'Bab 1: Pendahuluan', 2, 1),
(1, 'CSS3 & Responsive Design', '<p>CSS (Cascading Style Sheets) digunakan untuk mendesain tampilan halaman web. CSS3 menambahkan fitur seperti flexbox, grid, animasi, dan media queries.</p>', 'Bab 2: Frontend', 3, 1),
(1, 'JavaScript Fundamental', '<p>JavaScript adalah bahasa pemrograman yang berjalan di browser. Digunakan untuk membuat halaman web menjadi interaktif dan dinamis.</p>', 'Bab 2: Frontend', 4, 1),
(1, 'PHP Dasar', '<p>PHP (PHP: Hypertext Preprocessor) adalah bahasa pemrograman server-side yang banyak digunakan untuk pengembangan web.</p>', 'Bab 3: Backend', 5, 1),
(1, 'OOP PHP', '<p>Object-Oriented Programming di PHP memungkinkan kita membuat kode yang terstruktur, reusable, dan maintainable.</p>', 'Bab 3: Backend', 6, 1),
(1, 'Database dengan PDO', '<p>PDO (PHP Data Objects) menyediakan interface yang konsisten untuk mengakses database di PHP.</p>', 'Bab 4: Database', 7, 1),
(1, 'Session & Authentication', '<p>Session dan cookie digunakan untuk mengelola state di aplikasi web. Authentication memastikan identitas pengguna.</p>', 'Bab 5: Security', 8, 1),
(1, 'Arsitektur MVC', '<p>Model-View-Controller adalah pola arsitektur yang memisahkan aplikasi menjadi tiga komponen utama.</p>', 'Bab 6: Architecture', 9, 1),
(1, 'Project MVC - LMS', '<p>Implementasi project Learning Management System menggunakan arsitektur MVC PHP Native.</p>', 'Bab 6: Architecture', 10, 0);

-- ===========================================
-- ASSIGNMENTS
-- ===========================================
INSERT INTO assignments (course_id, title, description, max_score, deadline, allow_late, is_published) VALUES
(1, 'Tugas 1: HTML Portfolio', 'Buat halaman portfolio pribadi menggunakan HTML5 semantik. Harus mencakup: header, navigation, about, projects, dan footer.', 100, '2026-07-10 23:59:59', 1, 1),
(1, 'Tugas 2: CSS Responsive Layout', 'Buat layout responsive menggunakan CSS Flexbox dan Grid. Website harus tampil baik di mobile, tablet, dan desktop.', 100, '2026-07-17 23:59:59', 1, 1),
(1, 'Tugas 3: JavaScript Interactive', 'Buat aplikasi to-do list interaktif menggunakan JavaScript DOM manipulation. Harus bisa tambah, hapus, dan tandai selesai.', 100, '2026-07-24 23:59:59', 0, 1),
(1, 'Tugas 4: CRUD PHP & MySQL', 'Implementasikan operasi CRUD (Create, Read, Update, Delete) untuk manajemen data produk menggunakan PHP dan MySQL.', 100, '2026-08-01 23:59:59', 1, 1);

-- ===========================================
-- QUIZZES
-- ===========================================
INSERT INTO quizzes (course_id, title, description, duration_minutes, max_attempts, shuffle_questions, is_published, start_time, end_time) VALUES
(1, 'Quiz 1: HTML & CSS Fundamental', 'Quiz tentang dasar-dasar HTML5 dan CSS3. Pilihan ganda, 20 soal.', 30, 2, 1, 1, '2026-07-01 08:00:00', '2026-07-07 23:59:59'),
(1, 'Quiz 2: JavaScript Basics', 'Quiz tentang dasar-dasar JavaScript: variabel, fungsi, DOM, dan event handling.', 45, 1, 1, 1, '2026-07-15 08:00:00', '2026-07-21 23:59:59'),
(1, 'Quiz 3: PHP & MySQL', 'Quiz tentang PHP OOP, PDO, dan operasi database.', 60, 1, 0, 0, NULL, NULL);

-- ===========================================
-- QUESTIONS (Quiz 1)
-- ===========================================
INSERT INTO questions (quiz_id, question_text, type, options, correct_answer, points, sort_order, explanation) VALUES
(1, 'Apa kepanjangan dari HTML?', 'multiple_choice', '["A. Hyper Text Markup Language", "B. High Tech Modern Language", "C. Hyper Transfer Markup Language", "D. Home Tool Markup Language"]', 'A', 5, 1, 'HTML adalah singkatan dari Hyper Text Markup Language.'),
(1, 'Tag HTML mana yang digunakan untuk membuat paragraf?', 'multiple_choice', '["A. <paragraph>", "B. <p>", "C. <para>", "D. <text>"]', 'B', 5, 2, 'Tag <p> digunakan untuk mendefinisikan paragraf di HTML.'),
(1, 'CSS property mana yang mengubah warna teks?', 'multiple_choice', '["A. text-color", "B. font-color", "C. color", "D. text-style"]', 'C', 5, 3, 'Property color di CSS digunakan untuk mengubah warna teks.'),
(1, 'Apa itu CSS Flexbox?', 'multiple_choice', '["A. Framework CSS", "B. Layout model satu dimensi", "C. Layout model dua dimensi", "D. Library JavaScript"]', 'B', 5, 4, 'Flexbox adalah layout model satu dimensi (baris atau kolom). Grid adalah dua dimensi.'),
(1, 'Selector CSS #header merujuk pada...', 'multiple_choice', '["A. Class bernama header", "B. ID bernama header", "C. Tag bernama header", "D. Attribute bernama header"]', 'B', 5, 5, 'Tanda # di CSS adalah selector untuk ID.');

INSERT INTO questions (quiz_id, question_text, type, options, correct_answer, points, sort_order) VALUES
(2, 'Jelaskan perbedaan antara let, const, dan var di JavaScript!', 'essay', NULL, NULL, 20, 1),
(2, 'Apa itu DOM (Document Object Model)?', 'multiple_choice', '["A. Library JavaScript", "B. Representasi struktur HTML sebagai objek", "C. Framework CSS", "D. Database"]', 'B', 10, 2),
(2, 'Method JavaScript untuk mengambil elemen berdasarkan ID?', 'multiple_choice', '["A. getElement()", "B. findById()", "C. getElementById()", "D. selectById()"]', 'C', 10, 3);

-- ===========================================
-- ANNOUNCEMENTS
-- ===========================================
INSERT INTO announcements (course_id, user_id, title, content, is_pinned) VALUES
(NULL, 1, 'Selamat Datang di LMS UNSIQ', 'Selamat datang di platform Learning Management System UNSIQ. Platform ini digunakan untuk mengelola kegiatan pembelajaran secara online. Silakan login dengan akun yang telah diberikan.', 1),
(1, 2, 'Jadwal UTS Pemrograman Web', 'UTS Pemrograman Web akan dilaksanakan pada tanggal 15 Juli 2026. Materi yang diujikan meliputi HTML, CSS, JavaScript, dan PHP dasar. Silakan persiapkan diri dengan baik.', 1),
(1, 2, 'Pengumpulan Tugas 1 Diperpanjang', 'Deadline Tugas 1 (HTML Portfolio) diperpanjang hingga 10 Juli 2026. Pastikan mengumpulkan sebelum batas waktu.', 0);

-- ===========================================
-- SETTINGS
-- ===========================================
INSERT INTO settings (key_name, value, group_name, description) VALUES
('site_name', 'LMS UNSIQ', 'general', 'Nama aplikasi'),
('site_description', 'Learning Management System - Universitas Sains Al-Quran', 'general', 'Deskripsi aplikasi'),
('site_logo', NULL, 'general', 'Path logo aplikasi'),
('academic_year', '2025/2026', 'academic', 'Tahun akademik aktif'),
('active_semester', 'Genap', 'academic', 'Semester aktif'),
('max_upload_size', '10', 'upload', 'Ukuran maksimal upload (MB)'),
('maintenance_mode', '0', 'general', 'Mode maintenance (0=off, 1=on)');
