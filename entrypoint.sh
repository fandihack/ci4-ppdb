#!/bin/sh
set -e

# Load variabel environment Apache (penting jika menjalankan command apache2 langsung)
. /etc/apache2/envvars

echo "--- DEBUG: Environment PORT is: $PORT ---"

# 1. Pindah ke direktori aplikasi
cd /var/www/html

# 2. Jalankan migrasi database
echo "--- Menjalankan Migrasi ---"
php spark migrate --all || echo "WARNING: Migrasi dilewati atau sudah up-to-date."

# 3. JAMINAN TERAKHIR (Double Kill MPM Event)
# Jika file ini masih ada, Apache pasti crash. Kita hapus paksa tepat sebelum start.
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf

# 4. Jalankan Apache di Foreground
echo "--- Apache Dimulai ---"
exec apache2 -D FOREGROUND