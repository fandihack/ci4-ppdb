#!/bin/sh
# set -e akan menghentikan script jika ada error fatal
set -e

echo "--- Memulai Proses Startup CI4 ---"

# 1. Pindah ke direktori aplikasi
cd /var/www/html

# 2. Jalankan migrasi database
# Menggunakan '|| true' agar jika tabel sudah ada, proses tidak berhenti (crash)
echo "--- Menjalankan Migrasi Database... ---"
php spark migrate --all || echo "WARNING: Migrasi gagal atau database sudah up-to-date."

# 3. Jalankan Apache sebagai proses utama (Foreground)
# Kita hapus pengaturan mpm_event/prefork di sini karena sudah 
# ditangani secara default oleh image php:apache atau via Dockerfile
echo "--- Apache Dimulai ---"
exec apache2-foreground