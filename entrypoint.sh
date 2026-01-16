#!/bin/sh
set -e

echo "--- Memulai Proses Startup CI4 ---"

# 1. Pastikan kita berada di folder aplikasi
cd /var/www/html

# 2. Jalankan migrasi database
echo "--- Menjalankan Migrasi Database... ---"
php spark migrate --all || echo "WARNING: Migrasi gagal, tapi mencoba lanjut ke Apache."

# 3. Konfigurasi Modul Apache (Fix untuk Railway)
echo "--- Mengatur Modul Apache... ---"
a2dismod mpm_event || true
a2enmod mpm_prefork || true

# 4. Jalankan Apache sebagai proses utama
echo "--- Apache Dimulai ---"
exec apache2-foreground