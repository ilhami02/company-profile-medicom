# REST API Documentation - MEDICOM

Base URL: `http://localhost:8080/api` (Sesuaikan dengan base URL aplikasi)

## Autentikasi (JWT)
API menggunakan **JWT (JSON Web Token)**. Endpoint yang dilindungi membutuhkan header:
`Authorization: Bearer <TOKEN>`

### Login
Mendapatkan token JWT.
- **URL**: `/api/auth/login`
- **Method**: `POST`
- **Body** (JSON/Form Data):
  - `username` (string)
  - `password` (string)
- **Response Sukses** (200 OK):
  ```json
  {
      "status": 200,
      "message": "Login berhasil",
      "token": "eyJh..."
  }
  ```

---

## Endpoint Publik (Tanpa Autentikasi)

Semua endpoint GET berikut bisa diakses tanpa token. Mereka mengembalikan data dalam format JSON.

| Endpoint | Method | Deskripsi |
|---|---|---|
| `/api/hero` | GET | Data Hero Section (gambar utama) |
| `/api/partners` | GET | Daftar Partner (mitra) |
| `/api/programs` | GET | Daftar Program Kerja |
| `/api/achievements` | GET | Daftar Prestasi |
| `/api/divisions` | GET | Daftar Divisi |
| `/api/members` | GET | Daftar Pengurus |
| `/api/gallery` | GET | Daftar Foto Galeri |
| `/api/objectives` | GET | Daftar Tujuan (Visi/Misi) |
| `/api/pages/(:segment)` | GET | Data Halaman (contoh: `/api/pages/about`) |
| `/api/reports` | GET | Data Laporan (contoh: `/api/reports?year=2024`) |
| `/api/quiz/questions` | GET | Data Soal Quiz beserta Opsi Jawaban |
| `/api/quiz/recommendations` | GET | Data Rekomendasi Hasil Quiz |

---

## Endpoint Terlindungi (Membutuhkan JWT)

Endpoint-endpoint ini digunakan untuk operasi Create, Update, dan Delete (CUD). Membutuhkan header `Authorization: Bearer <TOKEN>`.

### Hero
- `POST /api/hero` : Update data hero

### Partners
- `POST /api/partners` : Tambah partner
- `DELETE /api/partners/(:num)` : Hapus partner

### Programs
- `POST /api/programs` : Tambah program
- `DELETE /api/programs/(:num)` : Hapus program

### Achievements
- `POST /api/achievements` : Tambah prestasi
- `DELETE /api/achievements/(:num)` : Hapus prestasi

### Divisions
- `POST /api/divisions` : Tambah divisi
- `DELETE /api/divisions/(:num)` : Hapus divisi

### Gallery
- `POST /api/gallery` : Tambah foto galeri
- `PUT /api/gallery/(:num)` : Update foto galeri
- `DELETE /api/gallery/(:num)` : Hapus foto galeri

### Members
- `POST /api/members` : Tambah pengurus
- `DELETE /api/members/(:num)` : Hapus pengurus

### Pages
- `PUT /api/pages/(:segment)` : Update data halaman

### Objectives
- `POST /api/objectives` : Tambah tujuan
- `DELETE /api/objectives/(:num)` : Hapus tujuan

### Reports
- `PUT /api/reports` : Update data laporan

### Quiz Questions
- `POST /api/quiz/questions` : Tambah soal
- `PUT /api/quiz/questions/(:num)` : Update soal
- `DELETE /api/quiz/questions/(:num)` : Hapus soal

### Quiz Recommendations
- `POST /api/quiz/recommendations` : Tambah rekomendasi
- `PUT /api/quiz/recommendations/(:num)` : Update rekomendasi
- `DELETE /api/quiz/recommendations/(:num)` : Hapus rekomendasi
