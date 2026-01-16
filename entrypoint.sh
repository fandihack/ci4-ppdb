#!/bin/sh
set -e

echo "--- DEBUG: Environment PORT is: $PORT ---"
cd /var/www/html

# Migrasi Database
echo "--- Menjalankan Migrasi ---"
php spark migrate --all || echo "Migrasi dilewati."

# Jalankan Apache
echo "--- Apache Dimulai ---"
exec apache2-foreground