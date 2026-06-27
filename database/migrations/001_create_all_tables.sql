-- ===========================================
-- LMS UNSIQ - Database Schema
-- ===========================================
-- Complete migration file untuk semua tabel
-- Run: mysql -u root lms_unsiq < database/migrations/001_create_all_tables.sql

-- Buat database
CREATE DATABASE IF NOT EXISTS lms_unsiq
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE lms_unsiq;

-- ===========================================
-- 1. USERS
-- ===========================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dosen', 'mahasiswa') NOT NULL DEFAULT 'mahasiswa',
    nim_nidn VARCHAR(30) NULL COMMENT 'NIM untuk mahasiswa, NIDN untuk dosen',
    phone VARCHAR(20) NULL,
    avatar VARCHAR(255) NULL,
    bio TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role),
    INDEX idx_users_email (email),
    INDEX idx_users_nim_nidn (nim_nidn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 2. CATEGORIES
-- ===========================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 3. COURSES (Mata Kuliah)
-- ===========================================
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dosen_id INT NOT NULL,
    category_id INT NULL,
    code VARCHAR(20) NOT NULL UNIQUE COMMENT 'Kode mata kuliah',
    name VARCHAR(200) NOT NULL,
    description TEXT NULL,
    thumbnail VARCHAR(255) NULL,
    sks INT NOT NULL DEFAULT 2,
    semester VARCHAR(20) NULL COMMENT 'Ganjil/Genap',
    academic_year VARCHAR(20) NULL COMMENT '2025/2026',
    status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    max_students INT NULL DEFAULT 40,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dosen_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_courses_status (status),
    INDEX idx_courses_dosen (dosen_id),
    INDEX idx_courses_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 4. ENROLLMENTS (Pendaftaran MK)
-- ===========================================
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    status ENUM('active', 'completed', 'dropped') NOT NULL DEFAULT 'active',
    progress DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Persentase materi selesai',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    UNIQUE KEY unique_enrollment (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_enrollments_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 5. MATERIALS (Materi Kuliah)
-- ===========================================
CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content LONGTEXT NULL COMMENT 'Konten teks/HTML materi',
    file_path VARCHAR(255) NULL,
    file_name VARCHAR(255) NULL,
    file_type VARCHAR(20) NULL COMMENT 'pdf, docx, pptx, video, etc.',
    file_size INT NULL COMMENT 'Ukuran file dalam bytes',
    video_url VARCHAR(500) NULL COMMENT 'URL embed video (YouTube, etc.)',
    section VARCHAR(100) NULL COMMENT 'Bab/Section grouping',
    sort_order INT NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    download_count INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_materials_course (course_id),
    INDEX idx_materials_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 6. ASSIGNMENTS (Tugas)
-- ===========================================
CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    max_score INT NOT NULL DEFAULT 100,
    deadline DATETIME NOT NULL,
    allow_late TINYINT(1) NOT NULL DEFAULT 0,
    late_penalty INT DEFAULT 0 COMMENT 'Pengurangan persen untuk keterlambatan',
    file_required TINYINT(1) NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_assignments_course (course_id),
    INDEX idx_assignments_deadline (deadline)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 7. SUBMISSIONS (Pengumpulan Tugas)
-- ===========================================
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NULL COMMENT 'Jawaban teks',
    file_path VARCHAR(255) NULL,
    file_name VARCHAR(255) NULL,
    score INT NULL,
    feedback TEXT NULL COMMENT 'Feedback dari dosen',
    status ENUM('submitted', 'graded', 'late', 'resubmitted') NOT NULL DEFAULT 'submitted',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    graded_at DATETIME NULL,
    graded_by INT NULL,
    UNIQUE KEY unique_submission (assignment_id, user_id),
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_submissions_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 8. QUIZZES (Kuis)
-- ===========================================
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    duration_minutes INT NOT NULL DEFAULT 30,
    max_attempts INT NOT NULL DEFAULT 1,
    shuffle_questions TINYINT(1) NOT NULL DEFAULT 0,
    show_result TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Tampilkan hasil setelah selesai',
    passing_score INT DEFAULT 60 COMMENT 'Nilai minimum lulus',
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    start_time DATETIME NULL COMMENT 'Waktu mulai kuis tersedia',
    end_time DATETIME NULL COMMENT 'Waktu kuis ditutup',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_quizzes_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 9. QUESTIONS (Soal Kuis)
-- ===========================================
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    type ENUM('multiple_choice', 'essay') NOT NULL DEFAULT 'multiple_choice',
    options JSON NULL COMMENT 'Array opsi untuk pilihan ganda: ["A. ...", "B. ...", ...]',
    correct_answer VARCHAR(500) NULL COMMENT 'Jawaban benar (huruf untuk MC, teks untuk essay)',
    points INT NOT NULL DEFAULT 10,
    sort_order INT NOT NULL DEFAULT 0,
    explanation TEXT NULL COMMENT 'Penjelasan jawaban benar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_questions_quiz (quiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 10. QUIZ ATTEMPTS (Percobaan Kuis)
-- ===========================================
CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    user_id INT NOT NULL,
    score INT NULL,
    total_points INT NULL,
    started_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME NULL,
    status ENUM('in_progress', 'completed', 'timed_out') NOT NULL DEFAULT 'in_progress',
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_attempts_quiz_user (quiz_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 11. ANSWERS (Jawaban Kuis)
-- ===========================================
CREATE TABLE IF NOT EXISTS answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_text TEXT NULL,
    is_correct TINYINT(1) NULL,
    points_earned INT NOT NULL DEFAULT 0,
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_answers_attempt (attempt_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 12. GRADES (Rekap Nilai)
-- ===========================================
CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    grade_type ENUM('assignment', 'quiz', 'midterm', 'final') NOT NULL,
    reference_id INT NULL COMMENT 'ID assignment/quiz terkait',
    score DECIMAL(5,2) NOT NULL,
    max_score DECIMAL(5,2) NOT NULL DEFAULT 100,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_grades_course_user (course_id, user_id),
    INDEX idx_grades_type (grade_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 13. ANNOUNCEMENTS (Pengumuman)
-- ===========================================
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NULL COMMENT 'NULL = pengumuman global',
    user_id INT NOT NULL COMMENT 'Pembuat pengumuman',
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    is_pinned TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_announcements_course (course_id),
    INDEX idx_announcements_pinned (is_pinned)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 14. NOTIFICATIONS
-- ===========================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(500) NULL,
    type VARCHAR(50) NULL COMMENT 'assignment, quiz, grade, announcement, system',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_notifications_user (user_id),
    INDEX idx_notifications_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 15. SETTINGS (Pengaturan Sistem)
-- ===========================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) NOT NULL UNIQUE,
    value TEXT NULL,
    group_name VARCHAR(50) NOT NULL DEFAULT 'general',
    description VARCHAR(255) NULL,
    INDEX idx_settings_group (group_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================
-- 16. MATERIAL COMPLETIONS (Tracking progress materi)
-- ===========================================
CREATE TABLE IF NOT EXISTS material_completions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    material_id INT NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_completion (user_id, material_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
