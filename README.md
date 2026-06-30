# How to Use

## Setup Database
1. Buat database baru di MySQL dengan nama `medicom`.
2. Buka terminal/command prompt di dalam folder project ini.
3. Jalankan migrasi untuk membuat seluruh struktur tabel:
   ```bash
   php spark migrate
   ```

## Menjalankan Seeder (Data Dummy)
Setelah struktur tabel berhasil dibuat, isi tabel dengan data awal (termasuk soal kuis, konten dummy, dan akun admin) menggunakan satu perintah praktis:
```bash
php spark db:seed
```
*(Seeder ini akan otomatis membuat akun admin dengan username: `admin` dan password: `admin123`)*

## Run CI4
1. Jalankan instalasi dependensi (jika belum):
   ```bash
   composer install
   ```
2. Ketik perintah berikut untuk menyalakan server:
   ```bash
   php spark serve
   ```

## Dokumentasi API
Silakan buka file `Document-API.md` untuk melihat dokumentasi lengkap penggunaan REST API aplikasi ini.

---

### Data Default Kuis (Seeder)
Seeder kuis akan memasukkan data awal berikut ke dalam database ketika dijalankan:

#### Daftar Soal & Opsi Jawaban:
1. **Bagaimana Anda mendefinisikan "kepemimpinan yang transformasional" dalam konteks organisasi non-profit?**
   - A. Fokus pada aturan dan prosedur
   - B. Memotivasi anggota untuk mencapai visi bersama
   - C. Menghindari risiko konflik internal
   - D. Mengambil keputusan secara otoriter
2. **Apa peran utama Divisi Humas dalam sebuah UKM Jurnalistik dan Multimedia?**
   - A. Menulis dan menerbitkan berita harian
   - B. Mengelola citra publik dan komunikasi eksternal
   - C. Bertanggung jawab penuh atas anggaran UKM
   - D. Melakukan pelatihan teknis videografi
3. **Dalam desain grafis, apa fungsi dari prinsip "Hierarchy" (Hierarki)?**
   - A. Membuat semua elemen memiliki ukuran yang sama
   - B. Menarik perhatian ke elemen paling penting melalui ukuran dan kontras
   - C. Menggunakan hanya dua jenis font
   - D. Memastikan semua warna adalah gradien
4. **Proses apa yang harus dilakukan sebelum memulai pengeditan video yang efektif?**
   - A. Langsung menambahkan musik latar yang populer
   - B. Membuat log footage dan menyusun storyboard awal
   - C. Menghapus semua file asli untuk menghemat ruang disk
   - D. Mengubah semua warna menjadi hitam putih
5. **Jika Anda menemukan bug pada sistem website UKM, apa tindakan pertama yang harus dilakukan?**
   - A. Menginformasikan kepada semua pengguna melalui media sosial
   - B. Mencari bantuan di forum online tanpa menjelaskan detail
   - C. Melaporkan, mereplikasi, dan mendokumentasikan bug tersebut
   - D. Mengganti seluruh sistem dengan yang baru

#### Rekomendasi Divisi:
- **DIVISI PUBLIKASI**: Kelola konten dan jadwal posting media sosial.
- **DIVISI FOTOGRAFI**: Ambil dan edit foto kegiatan.
- **DIVISI VIDEOGRAFI**: Rekam video dan atur teknis pengambilan gambar.
- **DIVISI VIDEOEDITING**: Edit video siap unggah untuk semua platform.
- **DIVISI DESAIN GRAFIS**: Buat desain visual poster dan feed.
- **DIVISI JURNALISTIK**: Tulis berita dan caption kegiatan.
- **DIVISI PEMROGRAMAN**: Bangun dan rawat website atau sistem.
