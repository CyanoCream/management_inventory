Laravel 10 Setup Guide

Persyaratan Sistem

PHP >= 8.1

Composer

MySQL >= 8.0 (Rekomendasi) atau 5.7 (Sesuaikan di konfigurasi)

Node.js (Opsional, untuk asset build menggunakan Vite)

Langkah-Langkah Setup

1. Clone Repository

git clone https://github.com/CyanoCream/management_inventory.git
cd repo

2. Install Dependency

composer install

3. Buat File Konfigurasi

Salin file .env.example menjadi .env dan sesuaikan konfigurasi database.

cp .env.example .env

4. Konfigurasi Database

Pastikan MySQL berjalan dan sesuaikan .env jika menggunakan MySQL versi berbeda:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=management_db
DB_USERNAME=root

Untuk MySQL versi 5.7, tambahkan opsi berikut di config/database.php:

'connections' => [
'mysql' => [
'strict' => false,
],
],

5. Generate Key

php artisan key:generate

6. Jalankan Migrasi Database

php artisan migrate

Jika ingin menghapus dan mengisi ulang database dengan data awal:

php artisan migrate:fresh --seed

7. Jalankan Server

php artisan serve

Akses aplikasi di http://127.0.0.1:8000

Opsi Tambahan

Jika menggunakan npm/Vite untuk asset:

npm install && npm run dev

Jika mengalami error MySQL terkait collation utf8mb4_0900_ai_ci, ubah .env:

DB_CONNECTION=mysql
DB_COLLATION=utf8mb4_unicode_ci

Kemudian jalankan ulang migrasi:

php artisan migrate:fresh

🚀 Proyek Laravel 10 siap digunakan!
