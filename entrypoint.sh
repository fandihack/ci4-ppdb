#!/bin/sh
set -e

echo "--- DEBUG: Environment PORT is: $PORT ---"
cd /var/www/html

echo "--- Menjalankan Migrasi ---"
php spark migrate --all || echo "Migrasi dilewati."

echo "--- Menjalankan Apache ---"
# Pastikan tidak ada modifikasi MPM yang aneh di sini
exec apache2-foreground