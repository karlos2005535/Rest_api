# Task Master App

## 1. Project Overview

Task Master adalah aplikasi manajemen tugas (Task Management) yang dirancang untuk membantu pengguna melacak produktivitas. Sistem ini terdiri dari aplikasi mobile/web yang dibangun dengan antarmuka yang interaktif, serta dihubungkan ke server REST API secara real-time untuk mengelola data tugas (Create, Read, Update, Delete).

## 2. Tech Stack

Sistem ini dibangun menggunakan perpaduan teknologi berikut:

- **Frontend:** Flutter (Dart), `http` (untuk API Request).
- **Backend / API:** PHP Native (PDO).
- **Database:** MySQL (XAMPP).
- **Architecture:** Client-Server / RESTful API.

## 3. Database Diagram

Database menggunakan satu tabel utama bernama `tasks`. Berikut adalah struktur skema databasenya:

| Column Name | Data Type    | Constraints                 |
| ----------- | ------------ | --------------------------- |
| id          | INT(11)      | PRIMARY KEY, AUTO_INCREMENT |
| title       | VARCHAR(255) | NOT NULL                    |
| description | TEXT         | NOT NULL                    |
| status      | VARCHAR(50)  | DEFAULT 'To Do'             |
| created_at  | TIMESTAMP    | DEFAULT CURRENT_TIMESTAMP   |

## 4. Installation Guide

langkah-langkah untuk menjalankan proyek ini secara lokal:

### Setup Backend (API & Database)

1. Aktifkan **Apache** dan **MySQL** pada XAMPP.
2. Membuka `http://localhost/phpmyadmin`, lalu buat database baru bernama `task_master`.
3. Buat tabel `tasks`.
4. Membuat folder di `C:\xampp\htdocs\` untuk menyimpan semua code php.
5. Kemudian pastikan konfigurasi _username_ dan _password_ di file `db.php` sudah sesuai dengan XAMPP.

### Setup Frontend (Flutter)

1. Buka terminal pada folder proyek Flutter.
2. Jalankan perintah untuk mengunduh semua _library_:
   ```bash
   flutter pub get
   ```
