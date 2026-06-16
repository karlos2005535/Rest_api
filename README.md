# 📝 Task Master - Real-Time Task Management App

Aplikasi manajemen tugas berbasis **Flutter** yang terintegrasi secara harmonis dengan backend **PHP (REST API)** dan **WebSocket** untuk pembaruan data secara *real-time*. Proyek ini dirancang secara kolaboratif untuk memenuhi Ujian Akhir Semester dengan menerapkan arsitektur *Clean Code*, *Separation of Concerns*, dan optimasi performa tinggi.

---

## 🚀 Fitur Utama & Kriteria Teknis

Aplikasi ini telah memenuhi seluruh spesifikasi teknis yang diwajibkan dalam rubrik penilaian:

### Sisi Client (Frontend - Flutter)
* **Penerapan Arsitektur:** Struktur kode terpisah secara rapi menjadi layer UI (`screens`), Business Logic (`blocs`), dan Data Access (`services`/`models`).
* **State Management:** Menggunakan **BLoC (Business Logic Component Pattern)** untuk mengelola alur data global aplikasi yang responsif tanpa dependensi pada `setState` manual.
* **Asynchronous Programming:**
    * **Future:** Digunakan untuk penanganan operasi *fetching* data HTTP standar (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`).
    * **Stream:** Digunakan untuk mendengarkan (*listening*) aliran data asinkronus secara terus-menerus dari WebSocket backend.

### Sisi Server (Backend - PHP API)
* **Arsitektur Backend:** Menggunakan pola terstruktur berorientasi objek: **Controller ➔ Service ➔ Repository** untuk memastikan isolasi logika bisnis.
* **Implementasi WebSocket:** Menggunakan library **Ratchet PHP** untuk menangani komunikasi dua arah (*real-time broadcast*) saat terjadi perubahan tugas.
* **Implementasi Caching:** Menggunakan **Redis Cache Layer** pada `TaskService.php` untuk mempercepat *high-read throughput* pemanggilan data list tugas dan mengurangi beban query langsung ke database MySQL.

---

## 🛠️ Panduan Instalasi & Persiapan Lokal

### 1. Persiapan Database (MySQL)
1. Aktifkan modul **MySQL** pada XAMPP / Laragon Control Panel.
2. Akses **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Buat database baru bernama **`task_master`**.
4. Impor struktur tabel murni ke dalam database tersebut. Pastikan tabel bernama `tasks` telah siap dengan kolom utama: `id`, `title`, `description`, `status`, dan `created_at`.

### 2. Konfigurasi Jaringan & Alamat API
Sesuaikan alamat variabel `apiUrl` pada file `lib/services/api_service.dart` di proyek Flutter Anda dengan target pengujian:
* **Android Emulator:** Gunakan bridge IP khusus `http://10.0.2.2:8000/controllers/TaskController.php`
* **Testing Lokal Browser/Desktop:** Gunakan `http://localhost:8000/controllers/TaskController.php`

---

## 🏃‍♂️ Cara Menjalankan Aplikasi

Pastikan Anda membuka terminal terpisah untuk setiap komponen berikut:

### Langkah 1: Jalankan Redis Server (Untuk Caching)
Pastikan layanan Redis lokal Anda telah aktif (via WSL atau Windows Native Port) agar lapisan cache internal backend tidak mengalami kegagalan jabat tangan (*handshake*).

### Langkah 2: Jalankan Server REST API (PHP Backend)
Masuk ke root direktori folder backend (`REST_API`), lalu jalankan server internal PHP bawaan dengan melakukan binding ke port `8000`:
```bash
php -S 0.0.0.0:8000