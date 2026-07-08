-- ============================================
-- Quiz Tables SQL
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_general_ci
-- ============================================

-- -------------------------------------------
-- Table: cms_quiz_questions
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `cms_quiz_questions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `question_text` TEXT NOT NULL,
    `sort_order` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -------------------------------------------
-- Table: cms_quiz_options
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `cms_quiz_options` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `question_id` INT(11) UNSIGNED NOT NULL,
    `option_text` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_quiz_options_question_id` (`question_id`),
    CONSTRAINT `fk_quiz_options_question_id` FOREIGN KEY (`question_id`) REFERENCES `cms_quiz_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -------------------------------------------
-- Table: cms_quiz_recommendations
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `cms_quiz_recommendations` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `division_name` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Seed Data
-- ============================================

-- -------------------------------------------
-- Seed: cms_quiz_questions
-- -------------------------------------------
INSERT INTO `cms_quiz_questions` (`question_text`, `sort_order`) VALUES
('Bagaimana Anda mendefinisikan "kepemimpinan yang transformasional" dalam konteks organisasi non-profit?', 1),
('Apa peran utama Divisi Humas dalam sebuah UKM Jurnalistik dan Multimedia?', 2),
('Dalam desain grafis, apa fungsi dari prinsip "Hierarchy" (Hierarki)?', 3),
('Proses apa yang harus dilakukan sebelum memulai pengeditan video yang efektif?', 4),
('Jika Anda menemukan bug pada sistem website UKM, apa tindakan pertama yang harus dilakukan?', 5);

-- -------------------------------------------
-- Seed: cms_quiz_options
-- -------------------------------------------
INSERT INTO `cms_quiz_options` (`question_id`, `option_text`) VALUES
(1, 'A. Fokus pada aturan dan prosedur'),
(1, 'B. Memotivasi anggota untuk mencapai visi bersama'),
(1, 'C. Menghindari risiko konflik internal'),
(1, 'D. Mengambil keputusan secara otoriter'),
(2, 'A. Menulis dan menerbitkan berita harian'),
(2, 'B. Mengelola citra publik dan komunikasi eksternal'),
(2, 'C. Bertanggung jawab penuh atas anggaran UKM'),
(2, 'D. Melakukan pelatihan teknis videografi'),
(3, 'A. Membuat semua elemen memiliki ukuran yang sama'),
(3, 'B. Menarik perhatian ke elemen paling penting melalui ukuran dan kontras'),
(3, 'C. Menggunakan hanya dua jenis font'),
(3, 'D. Memastikan semua warna adalah gradien'),
(4, 'A. Langsung menambahkan musik latar yang populer'),
(4, 'B. Membuat log footage dan menyusun storyboard awal'),
(4, 'C. Menghapus semua file asli untuk menghemat ruang disk'),
(4, 'D. Mengubah semua warna menjadi hitam putih'),
(5, 'A. Menginformasikan kepada semua pengguna melalui media sosial'),
(5, 'B. Mencari bantuan di forum online tanpa menjelaskan detail'),
(5, 'C. Melaporkan, mereplikasi, dan mendokumentasikan bug tersebut'),
(5, 'D. Mengganti seluruh sistem dengan yang baru');

-- -------------------------------------------
-- Seed: cms_quiz_recommendations
-- -------------------------------------------
INSERT INTO `cms_quiz_recommendations` (`division_name`, `description`) VALUES
('DIVISI PUBLIKASI', 'Kelola konten dan jadwal posting media sosial.'),
('DIVISI FOTOGRAFI', 'Ambil dan edit foto kegiatan.'),
('DIVISI VIDEOGRAFI', 'Rekam video dan atur teknis pengambilan gambar.'),
('DIVISI VIDEOEDITING', 'Edit video siap unggah untuk semua platform.'),
('DIVISI DESAIN GRAFIS', 'Buat desain visual poster dan feed.'),
('DIVISI JURNALISTIK', 'Tulis berita dan caption kegiatan.'),
('DIVISI PEMROGRAMAN', 'Bangun dan rawat website atau sistem.');
