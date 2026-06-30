<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainContentSeeder extends Seeder
{
    public function run()
    {
        // 1. cms_users (Admin)
        $this->db->table('cms_users')->insert([
            'username'   => 'admin',
            'password'   => password_hash('admin123', PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 2. cms_hero
        $this->db->table('cms_hero')->insert([
            'image_path' => '/assets/images/hero_dummy.jpg',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 3. cms_pages
        $pages = [
            [
                'page_slug'        => 'about',
                'hero_title'       => 'Tentang MEDICOM',
                'hero_description' => 'Membangun Karakter Melalui Jurnalistik dan Multimedia',
                'main_title'       => 'Sejarah Singkat',
                'main_content'     => 'MEDICOM adalah UKM yang bergerak di bidang jurnalistik, fotografi, videografi, dan desain grafis yang berdiri untuk mewadahi minat dan bakat mahasiswa.',
                'video_url'        => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ],
            [
                'page_slug'        => 'prestasi',
                'hero_title'       => 'Prestasi Kami',
                'hero_description' => 'Deretan Prestasi Gemilang Anggota MEDICOM',
                'main_title'       => 'Penghargaan',
                'main_content'     => 'Berbagai penghargaan yang telah diraih oleh UKM MEDICOM di tingkat regional, nasional, hingga internasional.',
                'video_url'        => ''
            ]
        ];
        $this->db->table('cms_pages')->insertBatch($pages);

        // 4. cms_partners
        $partners = [
            ['name' => 'Partner A', 'image_path' => '/assets/images/partner1.png'],
            ['name' => 'Partner B', 'image_path' => '/assets/images/partner2.png'],
            ['name' => 'Partner C', 'image_path' => '/assets/images/partner3.png'],
        ];
        $this->db->table('cms_partners')->insertBatch($partners);

        // 5. cms_programs
        $programs = [
            ['title' => 'Pelatihan Jurnalistik Dasar', 'description' => 'Pelatihan dasar menulis berita, artikel, dan opini.', 'image_path' => '/assets/images/proker1.jpg'],
            ['title' => 'Workshop Fotografi', 'description' => 'Teknik memotret yang baik menggunakan kamera DSLR/Mirrorless.', 'image_path' => '/assets/images/proker2.jpg'],
        ];
        $this->db->table('cms_programs')->insertBatch($programs);

        // 6. cms_achievements
        $achievements = [
            ['name' => 'Juara 1 Lomba Fotografi Tingkat Nasional', 'year' => 2023, 'level' => 'Nasional', 'image_path' => '/assets/images/ach1.jpg'],
            ['name' => 'Juara 2 Lomba Short Movie', 'year' => 2024, 'level' => 'Provinsi', 'image_path' => '/assets/images/ach2.jpg'],
        ];
        $this->db->table('cms_achievements')->insertBatch($achievements);

        // 7. cms_divisions
        $divisions = [
            ['name' => 'Divisi Fotografi', 'description' => 'Fokus pada dokumentasi visual dan estetika gambar.', 'image_path' => '/assets/images/div_foto.png', 'color_class' => 'bg-blue-500'],
            ['name' => 'Divisi Jurnalistik', 'description' => 'Fokus pada penulisan berita dan peliputan acara.', 'image_path' => '/assets/images/div_jurnal.png', 'color_class' => 'bg-green-500'],
        ];
        $this->db->table('cms_divisions')->insertBatch($divisions);

        // 8. cms_members
        $members = [
            ['division_id' => 1, 'name' => 'Ahmad Fulan', 'position' => 'Koordinator', 'image_path' => '/assets/images/member1.jpg'],
            ['division_id' => 2, 'name' => 'Budi Santoso', 'position' => 'Anggota', 'image_path' => '/assets/images/member2.jpg'],
            ['division_id' => 1, 'name' => 'Siti Aisyah', 'position' => 'Anggota', 'image_path' => '/assets/images/member3.jpg'],
        ];
        $this->db->table('cms_members')->insertBatch($members);

        // 9. cms_gallery
        $gallery = [
            ['image_path' => '/assets/images/gal1.jpg'],
            ['image_path' => '/assets/images/gal2.jpg'],
            ['image_path' => '/assets/images/gal3.jpg'],
        ];
        $this->db->table('cms_gallery')->insertBatch($gallery);

        // 10. cms_objectives
        $objectives = [
            ['content' => 'Menjadi UKM terbaik di bidang jurnalistik dan multimedia.'],
            ['content' => 'Mewadahi kreativitas mahasiswa dalam berkarya.'],
            ['content' => 'Menghasilkan konten berkualitas dan informatif.'],
        ];
        $this->db->table('cms_objectives')->insertBatch($objectives);

        // 11. cms_reports
        $reports = [
            ['year' => 2024, 'month' => 1, 'url' => 'https://example.com/report_jan_2024.pdf'],
            ['year' => 2024, 'month' => 2, 'url' => 'https://example.com/report_feb_2024.pdf'],
        ];
        $this->db->table('cms_reports')->insertBatch($reports);
    }
}
