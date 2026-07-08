# Dokumentasi REST API MEDICOM

Dokumen ini menjelaskan API untuk website MEDICOM berbasis CodeIgniter 4 agar bisa dikonsumsi oleh aplikasi Flutter, baik dari server production maupun dari server lokal saat pengembangan.

## Base URL

Gunakan salah satu base URL berikut sesuai target aplikasi:

| Target Flutter | Base URL API |
|---|---|
| Production / hosting | `https://medicom.skyibe.my.id/api` |
| Flutter Web / Desktop lokal | `http://localhost:8080/api` |
| Android Emulator lokal | `http://10.0.2.2:8080/api` |
| iOS Simulator lokal | `http://localhost:8080/api` |
| HP fisik satu WiFi dengan laptop | `http://IP_LAPTOP:8080/api` |

Catatan untuk HP fisik: jalankan backend lokal agar bisa diakses dari jaringan, misalnya dengan host `0.0.0.0`, lalu ganti `IP_LAPTOP` dengan IP laptop, contoh `http://192.168.1.10:8080/api`.

## Header Umum

Untuk request JSON:

```http
Content-Type: application/json
Accept: application/json
```

Untuk upload gambar gunakan `multipart/form-data`.

Endpoint protected membutuhkan header:

```http
Authorization: Bearer <TOKEN>
```

## Format Respons

### Respons sukses

Semua respons sukses memakai format:

```json
{
  "status": "success",
  "data": {}
}
```

Untuk endpoint list, `data` biasanya berupa array:

```json
{
  "status": "success",
  "data": []
}
```

### Respons error

```json
{
  "status": "error",
  "message": "Pesan error"
}
```

Untuk token yang tidak valid/expired, respons dapat memiliki tambahan `detail`.

## Autentikasi JWT

Token didapat dari endpoint login dan berlaku selama 24 jam atau `86400` detik.

### Login

`POST /auth/login`

Body dapat dikirim sebagai JSON atau form data.

```json
{
  "username": "admin",
  "password": "password"
}
```

Respons sukses:

```json
{
  "status": "success",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "username": "admin",
    "expires_in": 86400
  }
}
```

Respons gagal:

```json
{
  "status": "error",
  "message": "Username atau password salah."
}
```

## Endpoint Publik

Endpoint berikut dapat diakses tanpa token.

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/hero` | Mengambil gambar hero utama |
| GET | `/partners` | Mengambil daftar partner |
| GET | `/programs` | Mengambil daftar program kerja |
| GET | `/achievements` | Mengambil daftar prestasi, urut tahun terbaru |
| GET | `/divisions` | Mengambil daftar divisi |
| GET | `/members` | Mengambil daftar pengurus |
| GET | `/gallery` | Mengambil daftar gambar galeri |
| GET | `/objectives` | Mengambil daftar tujuan/visi misi |
| GET | `/pages/{slug}` | Mengambil data halaman berdasarkan slug |
| GET | `/reports?year={year}` | Mengambil laporan berdasarkan tahun |
| GET | `/quiz/questions` | Mengambil soal quiz beserta opsi |
| GET | `/quiz/recommendations` | Mengambil rekomendasi hasil quiz |

### GET `/hero`

Contoh respons:

```json
{
  "status": "success",
  "data": {
    "id": "1",
    "image_path": "/src/img/hero.jpg"
  }
}
```

### GET `/partners`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "name": "Nama Partner",
      "image_path": "/src/img/partners/logo.png"
    }
  ]
}
```

### GET `/programs`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "title": "Nama Program",
      "description": "Deskripsi program",
      "image_path": "/src/img/programs/program.jpg"
    }
  ]
}
```

### GET `/achievements`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "name": "Juara 1 Lomba",
      "year": "2025",
      "level": "Nasional",
      "image_path": "/src/img/prestasi/prestasi.jpg"
    }
  ]
}
```

### GET `/divisions`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "name": "Divisi Programming",
      "description": "Deskripsi divisi",
      "image_path": "/src/img/divisi/programming.jpg",
      "color_class": "bg-blue-500 text-white"
    }
  ]
}
```

### GET `/members`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "division_id": "1",
      "name": "Nama Pengurus",
      "position": "Ketua",
      "image_path": "/src/img/pengurus/foto.jpg"
    }
  ]
}
```

### GET `/gallery`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "image_path": "/src/img/galeri/foto.jpg"
    }
  ]
}
```

### GET `/objectives`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "content": "Meningkatkan kemampuan anggota di bidang teknologi."
    }
  ]
}
```

### GET `/pages/{slug}`

Contoh slug yang dapat digunakan mengikuti data pada tabel `cms_pages`, misalnya `about` atau slug halaman lain yang tersedia.

Contoh request:

```http
GET https://medicom.skyibe.my.id/api/pages/about
```

Contoh respons:

```json
{
  "status": "success",
  "data": {
    "page_slug": "about",
    "hero_title": "Tentang MEDICOM",
    "hero_description": "Deskripsi hero",
    "main_title": "Profil MEDICOM",
    "main_content": "Isi halaman",
    "video_url": "https://www.youtube.com/watch?v=..."
  }
}
```

Jika halaman tidak ditemukan:

```json
{
  "status": "error",
  "message": "Halaman tidak ditemukan."
}
```

### GET `/reports?year={year}`

Jika query `year` tidak dikirim, API memakai tahun saat ini.

Contoh request:

```http
GET https://medicom.skyibe.my.id/api/reports?year=2025
```

Contoh respons:

```json
{
  "status": "success",
  "data": {
    "year": 2025,
    "reports": [
      {
        "id": "1",
        "year": "2025",
        "month": "januari",
        "url": "https://drive.google.com/..."
      }
    ]
  }
}
```

### GET `/quiz/questions`

Soal diurutkan berdasarkan `sort_order`.

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "question_text": "Pertanyaan quiz?",
      "sort_order": "1",
      "options": [
        {
          "id": "1",
          "question_id": "1",
          "option_text": "Jawaban A"
        }
      ]
    }
  ]
}
```

### GET `/quiz/recommendations`

Contoh respons:

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "division_name": "Programming",
      "description": "Cocok untuk kamu yang suka coding."
    }
  ]
}
```

## Endpoint Protected

Endpoint berikut membutuhkan token JWT dari login.

### Hero

#### Update hero

`POST /hero`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | number | Tidak | Default `1` |
| `hero_image` | file | Ya | File gambar hero |

Respons sukses:

```json
{
  "status": "success",
  "data": {
    "message": "Hero berhasil diperbarui.",
    "image_path": "/src/img/nama-file.jpg"
  }
}
```

### Partners

#### Tambah partner

`POST /partners`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `name` | string | Ya |
| `partner_image` | file | Ya |

#### Hapus partner

`DELETE /partners/{id}`

### Programs

#### Tambah program

`POST /programs`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `title` | string | Ya |
| `description` | string | Ya |
| `program_image` | file | Ya |

#### Hapus program

`DELETE /programs/{id}`

### Achievements

#### Tambah prestasi

`POST /achievements`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `name` | string | Ya |
| `year` | number/string | Ya |
| `level` | string | Ya |
| `image` | file | Ya |

#### Hapus prestasi

`DELETE /achievements/{id}`

### Divisions

#### Tambah divisi

`POST /divisions`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `name` | string | Ya | Nama divisi |
| `description` | string | Tidak | Deskripsi divisi |
| `color` | string | Tidak | `blue`, `red`, atau kosong/default |
| `division_image` | file | Ya | File gambar divisi |

Nilai `color` akan dikonversi menjadi `color_class`:

| `color` | `color_class` |
|---|---|
| `blue` | `bg-blue-500 text-white` |
| `red` | `bg-red-500 text-white` |
| kosong/lainnya | `bg-white text-gray-800` |

#### Hapus divisi

`DELETE /divisions/{id}`

### Gallery

#### Tambah galeri

`POST /gallery`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `gallery_image` | file | Ya | Maksimal total galeri adalah 9 gambar |

#### Update galeri

`PUT /gallery/{id}`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `gallery_image` | file | Tidak |

Catatan: jika file baru dikirim, gambar lama akan diganti.

#### Hapus galeri

`DELETE /gallery/{id}`

### Members

#### Tambah pengurus

`POST /members`

Tipe body: `multipart/form-data`

| Field | Tipe | Wajib |
|---|---|---|
| `division_id` | number/string | Tidak |
| `name` | string | Ya |
| `position` | string | Tidak |
| `member_image` | file | Ya |

#### Hapus pengurus

`DELETE /members/{id}`

### Pages

#### Update halaman

`PUT /pages/{slug}`

Tipe body: JSON atau form data.

```json
{
  "hero_title": "Judul hero",
  "hero_description": "Deskripsi hero",
  "main_title": "Judul konten",
  "main_content": "Isi konten",
  "video_url": "https://www.youtube.com/watch?v=..."
}
```

Semua field bersifat opsional, tetapi field yang tidak dikirim dapat tersimpan sebagai `null` pada implementasi saat ini.

### Objectives

#### Tambah objective

`POST /objectives`

Tipe body: JSON atau form data.

```json
{
  "content": "Isi tujuan"
}
```

#### Hapus objective

`DELETE /objectives/{id}`

### Reports

#### Update laporan

`PUT /reports`

Tipe body: JSON atau form data.

```json
{
  "year": 2025,
  "urls": {
    "januari": "https://drive.google.com/...",
    "februari": "https://drive.google.com/...",
    "maret": ""
  }
}
```

Keterangan:

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `year` | number/string | Ya | Tahun laporan |
| `urls` | object | Ya | Key adalah nama bulan, value adalah URL laporan |

Jika value URL kosong dan laporan bulan tersebut sudah ada, data laporan bulan itu akan dihapus.

### Quiz Questions

#### Tambah soal quiz

`POST /quiz/questions`

Tipe body: JSON atau form data.

```json
{
  "question_text": "Pertanyaan quiz?",
  "sort_order": 1,
  "options": [
    "Pilihan A",
    "Pilihan B",
    "Pilihan C"
  ]
}
```

Respons sukses:

```json
{
  "status": "success",
  "data": {
    "message": "Soal quiz berhasil ditambahkan.",
    "id": 1
  }
}
```

#### Update soal quiz

`PUT /quiz/questions/{id}`

Tipe body: JSON atau form data.

```json
{
  "question_text": "Pertanyaan quiz baru?",
  "sort_order": 2,
  "options": [
    "Pilihan baru A",
    "Pilihan baru B"
  ]
}
```

Catatan: jika `options` dikirim, semua opsi lama untuk soal tersebut akan diganti.

#### Hapus soal quiz

`DELETE /quiz/questions/{id}`

### Quiz Recommendations

#### Tambah rekomendasi

`POST /quiz/recommendations`

Tipe body: JSON atau form data.

```json
{
  "division_name": "Programming",
  "description": "Cocok untuk kamu yang suka membuat aplikasi."
}
```

#### Update rekomendasi

`PUT /quiz/recommendations/{id}`

Tipe body: JSON atau form data.

```json
{
  "division_name": "Programming",
  "description": "Deskripsi rekomendasi terbaru."
}
```

#### Hapus rekomendasi

`DELETE /quiz/recommendations/{id}`

## Contoh Flutter

Tambahkan package HTTP di `pubspec.yaml`:

```yaml
dependencies:
  http: ^1.2.2
```

### Konfigurasi base URL

```dart
class ApiConfig {
  // Production
  static const String productionBaseUrl = 'https://medicom.skyibe.my.id/api';

  // Android emulator saat backend berjalan di laptop pada port 8080
  static const String androidEmulatorBaseUrl = 'http://10.0.2.2:8080/api';

  // iOS simulator, desktop, atau Flutter web lokal
  static const String localBaseUrl = 'http://localhost:8080/api';

  static const String baseUrl = productionBaseUrl;
}
```

### GET data publik

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

Future<List<dynamic>> getPrograms() async {
  final response = await http.get(
    Uri.parse('${ApiConfig.baseUrl}/programs'),
    headers: {
      'Accept': 'application/json',
    },
  );

  final body = jsonDecode(response.body);

  if (response.statusCode == 200 && body['status'] == 'success') {
    return body['data'] as List<dynamic>;
  }

  throw Exception(body['message'] ?? 'Gagal mengambil data program');
}
```

### Login dan simpan token

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

Future<String> login(String username, String password) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/auth/login'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: jsonEncode({
      'username': username,
      'password': password,
    }),
  );

  final body = jsonDecode(response.body);

  if (response.statusCode == 200 && body['status'] == 'success') {
    return body['data']['token'] as String;
  }

  throw Exception(body['message'] ?? 'Login gagal');
}
```

### Request protected dengan token

```dart
Future<void> addObjective(String token, String content) async {
  final response = await http.post(
    Uri.parse('${ApiConfig.baseUrl}/objectives'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode({
      'content': content,
    }),
  );

  final body = jsonDecode(response.body);

  if (response.statusCode != 201 || body['status'] != 'success') {
    throw Exception(body['message'] ?? 'Gagal menambah objective');
  }
}
```

### Upload gambar dari Flutter

Contoh upload partner:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> addPartner({
  required String token,
  required String name,
  required String imagePath,
}) async {
  final request = http.MultipartRequest(
    'POST',
    Uri.parse('${ApiConfig.baseUrl}/partners'),
  );

  request.headers.addAll({
    'Accept': 'application/json',
    'Authorization': 'Bearer $token',
  });

  request.fields['name'] = name;
  request.files.add(
    await http.MultipartFile.fromPath('partner_image', imagePath),
  );

  final streamedResponse = await request.send();
  final response = await http.Response.fromStream(streamedResponse);
  final body = jsonDecode(response.body);

  if (response.statusCode != 201 || body['status'] != 'success') {
    throw Exception(body['message'] ?? 'Gagal menambah partner');
  }
}
```

## Menampilkan Gambar di Flutter

Field gambar dari API berisi path relatif, misalnya:

```json
"/src/img/partners/logo.png"
```

Untuk menampilkannya, gabungkan domain utama tanpa `/api` dengan `image_path`.

```dart
String imageUrl(String? path) {
  if (path == null || path.isEmpty) return '';
  if (path.startsWith('http')) return path;

  const String host = 'https://medicom.skyibe.my.id';
  return '$host$path';
}
```

Contoh:

```dart
Image.network(imageUrl(program['image_path']))
```

Jika sedang memakai backend lokal:

```dart
const String host = 'http://10.0.2.2:8080'; // Android emulator
```

## Status Code yang Umum

| Status Code | Arti |
|---|---|
| 200 | Request berhasil |
| 201 | Data berhasil dibuat |
| 400 | Data request tidak lengkap/salah |
| 401 | Token tidak ada, salah, atau expired |
| 404 | Data tidak ditemukan |
| 500 | Error server atau upload gagal |

## Catatan CORS dan Local Development

API memakai filter CORS dan mengizinkan method:

```text
GET, POST, PUT, DELETE, OPTIONS
```

Header yang diizinkan:

```text
Content-Type, Authorization, X-Requested-With
```

Untuk Flutter Android emulator, jangan gunakan `localhost` jika backend berjalan di laptop. Gunakan `10.0.2.2` karena `localhost` di emulator menunjuk ke emulator itu sendiri, bukan ke laptop.

Untuk HP fisik, laptop dan HP harus berada di jaringan WiFi yang sama, lalu gunakan IP laptop sebagai host API.
