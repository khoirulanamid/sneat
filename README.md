# Sneat

Singkat: project template dashboard berbasis PHP (Sneat) siap dijalankan di server lokal seperti Laragon.

Quick start
- Prasyarat: PHP (7.4+), web server (Laragon / XAMPP), MySQL, dan Composer bila ada dependensi.
- Salin folder ke `C:\laragon\www\sneat` atau folder htdocs yang Anda gunakan.
- Atur koneksi database di `config/koneksi.php` (jangan commit kredensial sensitif ke repo publik).
- Buka browser: `http://localhost/sneat` atau sesuai konfigurasi Laragon Anda.

Konfigurasi penting
- `config/koneksi.php` : koneksi database (username/password/dbname).
- `config/base_url.php` : base URL aplikasi.
- `config/routes.php` : rute sederhana aplikasi.

