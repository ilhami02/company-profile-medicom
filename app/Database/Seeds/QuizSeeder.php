<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Seed Quiz Questions
        $questions = [
            [
                'question_text' => 'Bagaimana Anda mendefinisikan "kepemimpinan yang transformasional" dalam konteks organisasi non-profit?',
                'sort_order'    => 1,
            ],
            [
                'question_text' => 'Apa peran utama Divisi Humas dalam sebuah UKM Jurnalistik dan Multimedia?',
                'sort_order'    => 2,
            ],
            [
                'question_text' => 'Dalam desain grafis, apa fungsi dari prinsip "Hierarchy" (Hierarki)?',
                'sort_order'    => 3,
            ],
            [
                'question_text' => 'Proses apa yang harus dilakukan sebelum memulai pengeditan video yang efektif?',
                'sort_order'    => 4,
            ],
            [
                'question_text' => 'Jika Anda menemukan bug pada sistem website UKM, apa tindakan pertama yang harus dilakukan?',
                'sort_order'    => 5,
            ],
        ];

        $this->db->table('cms_quiz_questions')->insertBatch($questions);

        // Seed Quiz Options
        $options = [
            // Question 1 options
            ['question_id' => 1, 'option_text' => 'A. Fokus pada aturan dan prosedur'],
            ['question_id' => 1, 'option_text' => 'B. Memotivasi anggota untuk mencapai visi bersama'],
            ['question_id' => 1, 'option_text' => 'C. Menghindari risiko konflik internal'],
            ['question_id' => 1, 'option_text' => 'D. Mengambil keputusan secara otoriter'],
            // Question 2 options
            ['question_id' => 2, 'option_text' => 'A. Menulis dan menerbitkan berita harian'],
            ['question_id' => 2, 'option_text' => 'B. Mengelola citra publik dan komunikasi eksternal'],
            ['question_id' => 2, 'option_text' => 'C. Bertanggung jawab penuh atas anggaran UKM'],
            ['question_id' => 2, 'option_text' => 'D. Melakukan pelatihan teknis videografi'],
            // Question 3 options
            ['question_id' => 3, 'option_text' => 'A. Membuat semua elemen memiliki ukuran yang sama'],
            ['question_id' => 3, 'option_text' => 'B. Menarik perhatian ke elemen paling penting melalui ukuran dan kontras'],
            ['question_id' => 3, 'option_text' => 'C. Menggunakan hanya dua jenis font'],
            ['question_id' => 3, 'option_text' => 'D. Memastikan semua warna adalah gradien'],
            // Question 4 options
            ['question_id' => 4, 'option_text' => 'A. Langsung menambahkan musik latar yang populer'],
            ['question_id' => 4, 'option_text' => 'B. Membuat log footage dan menyusun storyboard awal'],
            ['question_id' => 4, 'option_text' => 'C. Menghapus semua file asli untuk menghemat ruang disk'],
            ['question_id' => 4, 'option_text' => 'D. Mengubah semua warna menjadi hitam putih'],
            // Question 5 options
            ['question_id' => 5, 'option_text' => 'A. Menginformasikan kepada semua pengguna melalui media sosial'],
            ['question_id' => 5, 'option_text' => 'B. Mencari bantuan di forum online tanpa menjelaskan detail'],
            ['question_id' => 5, 'option_text' => 'C. Melaporkan, mereplikasi, dan mendokumentasikan bug tersebut'],
            ['question_id' => 5, 'option_text' => 'D. Mengganti seluruh sistem dengan yang baru'],
        ];

        $this->db->table('cms_quiz_options')->insertBatch($options);

        // Seed Quiz Recommendations
        $recommendations = [
            ['division_name' => 'DIVISI PUBLIKASI',     'description' => 'Kelola konten dan jadwal posting media sosial.'],
            ['division_name' => 'DIVISI FOTOGRAFI',     'description' => 'Ambil dan edit foto kegiatan.'],
            ['division_name' => 'DIVISI VIDEOGRAFI',    'description' => 'Rekam video dan atur teknis pengambilan gambar.'],
            ['division_name' => 'DIVISI VIDEOEDITING',  'description' => 'Edit video siap unggah untuk semua platform.'],
            ['division_name' => 'DIVISI DESAIN GRAFIS', 'description' => 'Buat desain visual poster dan feed.'],
            ['division_name' => 'DIVISI JURNALISTIK',   'description' => 'Tulis berita dan caption kegiatan.'],
            ['division_name' => 'DIVISI PEMROGRAMAN',   'description' => 'Bangun dan rawat website atau sistem.'],
        ];

        $this->db->table('cms_quiz_recommendations')->insertBatch($recommendations);
    }
}
