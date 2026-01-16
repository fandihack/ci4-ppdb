#!/bin/sh
set -e

# Load variabel environment Apache
. /etc/apache2/envvars

echo "--- DEBUG: Environment PORT is: $PORT ---"

# 1. Pindah ke direktori aplikasi
cd /var/www/html

# 2. Jalankan migrasi database
echo "--- Menjalankan Migrasi ---"
php spark migrate --all || echo "Migrasi dilewati."

# 3. JALANKAN SEEDER (Data Dummy) - DINONAKTIFKAN
# echo "--- Menjalankan Seeder: DummySeeder ---"
# php spark db:seed DummySeeder || echo "Seeder dilewati (mungkin data sudah ada)."

# 4. JAMINAN TERAKHIR (MPM Fix)
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf

# 5. Jalankan Apache
echo "--- Apache Dimulai ---"
exec apache2 -D FOREGROUND