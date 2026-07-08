# REST API + Dynamic Quiz + Migrations untuk Company Profile MEDICOM

## Ringkasan

Mengubah project Company Profile MEDICOM (CI4) agar:
1. **Menyediakan REST API** — endpoint JSON yang bisa diakses oleh CI4 itu sendiri dan aplikasi Flutter
2. **Quiz menjadi dinamis** — pertanyaan, opsi, dan rekomendasi divisi dikelola dari database melalui dashboard admin
3. **Migration lengkap** — membuat migration file untuk semua tabel yang sudah ada di database + tabel baru quiz

> [!IMPORTANT]
> **Tampilan TIDAK akan diubah sama sekali.** Semua perubahan hanya pada backend (controller, model, database, routes, filter). View files yang ada tidak dimodifikasi tampilannya — hanya sumber datanya yang berubah dari hardcoded → database.

---

## User Review Required

> [!IMPORTANT]
> **Autentikasi API**: Saya akan menggunakan **JWT (JSON Web Token)** untuk autentikasi API. Flutter akan login via `POST /api/auth/login` dan mendapatkan token, lalu mengirim token di header `Authorization: Bearer <token>` untuk endpoint yang butuh proteksi (admin). Endpoint public (read-only) tidak perlu token. Apakah ini sesuai?

> [!WARNING]
> **CSRF pada API**: Route API akan di-exclude dari CSRF filter karena Flutter tidak bisa mengirim CSRF token. Endpoint API akan dilindungi oleh JWT sebagai gantinya.

> [!IMPORTANT]
> **Migrasi Quiz**: Data quiz yang saat ini hardcoded di `QuizController.php` akan dipindahkan ke tabel database baru. View file `quiz_pertanyaan_1.php` s/d `quiz_pertanyaan_5.php` akan **digantikan oleh satu view dinamis** `quiz_pertanyaan.php` yang bisa menampilkan soal berapa pun. Tampilan/CSS-nya akan **identik persis** — hanya sumber data dan routing yang berubah.

---

## Keputusan yang Sudah Ditetapkan

- **JWT Library**: Project belum punya JWT. Akan install `firebase/php-jwt` via `composer require firebase/php-jwt`.
- **Quiz Scoring**: Tetap **random** — tidak ada konsep jawaban benar. Kolom `is_correct` pada tabel `cms_quiz_options` dihapus.

---

## Proposed Changes

### Database Migrations — Semua Tabel

Project saat ini **belum punya migration sama sekali**. Semua migration dibuat di `app/Database/Migrations/` mengikuti konvensi CI4 (`YYYY-MM-DD-HHMMSS_NamaClass.php`).

#### Tabel Existing (11 migration files)

| # | File | Tabel | Keterangan |
|---|------|-------|------------|
| 1 | [NEW] `2025-01-01-000001_CreateCmsUsersTable.php` | `cms_users` | id, username, password, created_at |
| 2 | [NEW] `2025-01-01-000002_CreateCmsHeroTable.php` | `cms_hero` | id, image_path, updated_at |
| 3 | [NEW] `2025-01-01-000003_CreateCmsPagesTable.php` | `cms_pages` | page_slug (PK), hero_title, hero_description, main_title, main_content, video_url |
| 4 | [NEW] `2025-01-01-000004_CreateCmsPartnersTable.php` | `cms_partners` | id, name, image_path |
| 5 | [NEW] `2025-01-01-000005_CreateCmsProgramsTable.php` | `cms_programs` | id, title, description, image_path |
| 6 | [NEW] `2025-01-01-000006_CreateCmsAchievementsTable.php` | `cms_achievements` | id, name, year, level, image_path |
| 7 | [NEW] `2025-01-01-000007_CreateCmsDivisionsTable.php` | `cms_divisions` | id, name, description, image_path, color_class |
| 8 | [NEW] `2025-01-01-000008_CreateCmsMembersTable.php` | `cms_members` | id, division_id (FK→cms_divisions), name, position, image_path |
| 9 | [NEW] `2025-01-01-000009_CreateCmsGalleryTable.php` | `cms_gallery` | id, image_path |
| 10 | [NEW] `2025-01-01-000010_CreateCmsObjectivesTable.php` | `cms_objectives` | id, content |
| 11 | [NEW] `2025-01-01-000011_CreateCmsReportsTable.php` | `cms_reports` | id, year, month, url |

> [!NOTE]
> Setiap migration file memiliki method `up()` (create table) dan `down()` (drop table). Schema disesuaikan **persis** dengan struktur tabel yang sudah ada di `db_medicom.sql`.

#### Tabel Baru Quiz (3 migration files)

| # | File | Tabel | Keterangan |
|---|------|-------|------------|
| 12 | [NEW] `2025-01-01-000012_CreateCmsQuizQuestionsTable.php` | `cms_quiz_questions` | id, question_text, sort_order |
| 13 | [NEW] `2025-01-01-000013_CreateCmsQuizOptionsTable.php` | `cms_quiz_options` | id, question_id (FK→cms_quiz_questions), option_text |
| 14 | [NEW] `2025-01-01-000014_CreateCmsQuizRecommendationsTable.php` | `cms_quiz_recommendations` | id, division_name, description |

#### Seeder — Data Awal Quiz

#### [NEW] [QuizSeeder.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Database/Seeds/QuizSeeder.php)

Seeder untuk mengisi data quiz awal (5 soal + opsi + 7 rekomendasi divisi) dari data yang saat ini hardcoded di `QuizController.php`.

---

### Database — File SQL Tambahan (Supplementary)

#### [NEW] [quiz_tables.sql](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/dbms/quiz_tables.sql)

File SQL sebagai referensi/backup manual untuk 3 tabel quiz baru + seed data. Migration files adalah cara utama membuat tabel.

---

### Models — Model Baru Quiz

#### [NEW] [QuizQuestionModel.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Models/QuizQuestionModel.php)
Model untuk tabel `cms_quiz_questions`.

#### [NEW] [QuizOptionModel.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Models/QuizOptionModel.php)
Model untuk tabel `cms_quiz_options`.

#### [NEW] [QuizRecommendationModel.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Models/QuizRecommendationModel.php)
Model untuk tabel `cms_quiz_recommendations`.

---

### API Controller — REST API Endpoints

#### [NEW] [Api.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Controllers/Api.php)

Controller tunggal untuk semua REST API endpoint. Mengembalikan response JSON.

**Public Endpoints (tanpa auth):**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/hero` | Ambil data hero image |
| GET | `/api/partners` | Ambil semua partner |
| GET | `/api/programs` | Ambil semua program kerja |
| GET | `/api/achievements` | Ambil semua prestasi |
| GET | `/api/divisions` | Ambil semua divisi |
| GET | `/api/members` | Ambil semua pengurus |
| GET | `/api/gallery` | Ambil semua galeri |
| GET | `/api/objectives` | Ambil semua tujuan |
| GET | `/api/pages/(:segment)` | Ambil data halaman (about/prestasi) |
| GET | `/api/reports` | Ambil laporan (query: `?year=2024`) |
| GET | `/api/quiz/questions` | Ambil semua soal quiz + opsi |
| GET | `/api/quiz/recommendations` | Ambil semua rekomendasi divisi |

**Auth Endpoints:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/auth/login` | Login, return JWT token |

**Protected Endpoints (butuh JWT / session):**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/hero` | Update hero image |
| POST | `/api/partners` | Tambah partner |
| DELETE | `/api/partners/(:num)` | Hapus partner |
| POST | `/api/programs` | Tambah program |
| DELETE | `/api/programs/(:num)` | Hapus program |
| POST | `/api/achievements` | Tambah prestasi |
| DELETE | `/api/achievements/(:num)` | Hapus prestasi |
| POST | `/api/divisions` | Tambah divisi |
| DELETE | `/api/divisions/(:num)` | Hapus divisi |
| POST | `/api/gallery` | Tambah galeri |
| PUT | `/api/gallery/(:num)` | Update galeri |
| DELETE | `/api/gallery/(:num)` | Hapus galeri |
| POST | `/api/members` | Tambah pengurus |
| DELETE | `/api/members/(:num)` | Hapus pengurus |
| PUT | `/api/pages/(:segment)` | Update halaman |
| POST | `/api/objectives` | Tambah tujuan |
| DELETE | `/api/objectives/(:num)` | Hapus tujuan |
| PUT | `/api/reports` | Update laporan |
| POST | `/api/quiz/questions` | Tambah soal quiz |
| PUT | `/api/quiz/questions/(:num)` | Update soal quiz |
| DELETE | `/api/quiz/questions/(:num)` | Hapus soal quiz |
| POST | `/api/quiz/recommendations` | Tambah rekomendasi |
| PUT | `/api/quiz/recommendations/(:num)` | Update rekomendasi |
| DELETE | `/api/quiz/recommendations/(:num)` | Hapus rekomendasi |

---

### API Filter — JWT Authentication

#### [NEW] [ApiAuthFilter.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Filters/ApiAuthFilter.php)

Filter yang memeriksa header `Authorization: Bearer <JWT>`. Jika valid, request dilanjutkan. Jika tidak, return JSON 401.

---

### CORS Filter

#### [NEW] [CorsFilter.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Filters/CorsFilter.php)

Filter untuk menangani CORS preflight requests dari Flutter.

---

### Config Updates

#### [MODIFY] [Filters.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Config/Filters.php)
- Tambah alias `apiAuth` → `ApiAuthFilter::class`
- Tambah alias `corsFilter` → `CorsFilter::class`

#### [MODIFY] [Routes.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Config/Routes.php)
- Tambah route group `api/` untuk semua endpoint API
- Public API: tanpa filter
- Protected API: dengan filter `apiAuth`

---

### Quiz Controller — Dynamic Quiz

#### [MODIFY] [QuizController.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Controllers/QuizController.php)

- Hapus semua data hardcoded (pertanyaan, opsi, rekomendasi)
- Ganti dengan query ke `QuizQuestionModel`, `QuizOptionModel`, `QuizRecommendationModel`
- Method `tampilkanPertanyaan()` mengambil soal dari DB berdasarkan `sort_order`
- Method `hasilQuiz()` mengambil rekomendasi dari DB
- Gunakan satu view `quiz_pertanyaan.php` yang dinamis untuk semua soal

#### [NEW] [quiz_pertanyaan.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Views/users/quiz_pertanyaan.php)

View tunggal yang menggantikan `quiz_pertanyaan_1.php` s/d `quiz_pertanyaan_5.php`. Tampilan **100% identik** — menggunakan CSS/HTML yang sama persis dari view yang ada, tapi dengan data dari database. View ini mendeteksi apakah soal terakhir dan menampilkan tombol "Lihat Hasil" atau "Lanjut" sesuai kondisi.

> [!NOTE]
> File `quiz_pertanyaan_1.php` s/d `quiz_pertanyaan_5.php` yang lama TIDAK dihapus, hanya tidak digunakan lagi. Ini untuk backward compatibility.

---

### Admin Controller — Quiz Management

#### [MODIFY] [Admin.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Controllers/Admin.php)

Tambah method untuk mengelola quiz:
- `quizQuestions()` — Halaman kelola soal quiz
- `addQuizQuestion()` — Tambah soal + opsi
- `deleteQuizQuestion($id)` — Hapus soal
- `quizRecommendations()` — Halaman kelola rekomendasi
- `addQuizRecommendation()` — Tambah rekomendasi
- `deleteQuizRecommendation($id)` — Hapus rekomendasi

#### [NEW] [quiz_questions.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Views/admin/quiz_questions.php)

Halaman admin untuk mengelola soal quiz (CRUD). Mengikuti style admin yang sudah ada.

#### [NEW] [quiz_recommendations.php](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/app/Views/admin/quiz_recommendations.php)

Halaman admin untuk mengelola rekomendasi divisi. Mengikuti style admin yang sudah ada.

---

### Dokumentasi API

#### [NEW] [Document-API.md](file:///e:/Kuliah/Semester%203/Web%202/uas_1/company-profile-medicom/Document-API.md)

Dokumentasi lengkap REST API dalam format Markdown, mencakup:
- Base URL
- Authentication (JWT flow)
- Semua endpoint dengan request/response example
- Error codes

---

## Verification Plan

### Manual Verification
- Test semua API endpoint public menggunakan browser / Postman
- Test login API dan dapatkan JWT token
- Test protected endpoints dengan token
- Test quiz flow (mulai → jawab soal dari DB → lihat hasil dari DB)
- Verifikasi tampilan web **tidak berubah** sama sekali
- Test CRUD quiz di dashboard admin
