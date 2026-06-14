# Task Manager Real-Time App

Aplikasi manajemen tugas berbasis Flutter yang terintegrasi dengan backend PHP dan WebSocket untuk pembaruan data secara _real-time_. Proyek ini mengimplementasikan arsitektur _clean code_ dengan BLoC Pattern.

## Fitur Utama

- **State Management:** Menggunakan **BLoC** untuk alur data yang reaktif dan terprediksi.
- **Real-time Updates:** Menggunakan **Ratchet PHP (WebSocket)** untuk sinkronisasi data antar perangkat secara instan.
- **RESTful API:** Komunikasi data standar dengan backend PHP.
- **Modern UI:** Antarmuka responsif dengan Flutter.

---

## Instalasi & Persiapan

### 1. Backend (PHP & Database)

1. Pastikan **XAMPP** (Apache & MySQL) sudah aktif.
2. Impor file database `database/task_manager_db.sql` ke **phpMyAdmin**.
3. Pindahkan folder `task_api` ke direktori `C:\xampp\htdocs\`.
4. Masuk ke folder `task_api` melalui terminal, kemudian instal dependensi Ratchet:
   ```bash
   composer require cboden/ratchet
   ```

## Aplikasi Flutter

1. Pastikan Flutter SDK sudah terinstal.
2. Di dalam proyek Flutter, pastikan file lib/services/api_service.dart sudah diarahkan ke alamat API yang benar.
   Gunakan http://10.0.2.2/task_api/... untuk Android Emulator.
   Gunakan http://localhost/task_api/... untuk Testing di Browser/Desktop.

## Cara Menjalankan Aplikasi

1. Menjalankan WebSocket Server (Backend)
   Buka terminal baru di direktori task_api, jalankan perintah berikut agar fitur notifikasi aktif:
   php C:\xampp\htdocs\task_api\notification_server.php

## Menjalankan Flutter

Buka terminal di root proyek Flutter, kemudian jalankan:
flutter run

## Arsitektur Sistem

Sistem ini dirancang dengan prinsip pemisahan tanggung jawab (Separation of Concerns) untuk memudahkan maintenance:

1. UI Layer: Menampilkan Task menggunakan Widget yang mendengarkan State dari BLoC.
2. Logic Layer (BLoC): Memproses Event dari pengguna dan mengubah State aplikasi.
3. Data Layer: Mengelola komunikasi HTTP via ApiService dan WebSocket.
4. Backend: Server PHP yang menangani query MySQL dan melakukan broadcast WebSocket.

### Link dokumentasi pengujian Api:

1.  **Link Postman**: Link postman `https://thomaskarlosbaco-23659.postman.co/workspace/Thomas-Carlos-Baco's-Workspace~698730d1-4556-4170-827f-98572c31caac/request/55892489-b35d7098-10e7-4b95-b9f7-156eecb1e04c?action=share&creator=55892489`.
