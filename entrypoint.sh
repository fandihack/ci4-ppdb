#!/bin/sh

# Nonaktifkan mpm_event dan pastikan mpm_prefork aktif
a2dismod mpm_event || true
a2enmod mpm_prefork || true

# Jalankan perintah bawaan Apache
exec apache2-foreground