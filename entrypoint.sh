#!/bin/sh

# 1. Jalankan migrasi database dulu
# Kita pakai '|| true' agar jika database belum siap, Apache tidak ikutan gagal start
php spark migrate --all || echo "Migration failed, check database connection."

# 2. Nonaktifkan mpm_event dan aktifkan mpm_prefork (Fix Apache Error)
a2dismod mpm_event || true
a2enmod mpm_prefork || true

# 3. Jalankan Apache (Harus baris terakhir)
exec apache2-foreground