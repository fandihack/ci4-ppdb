FROM php:8.2-apache

# 1. Install dependencies & PHP extensions yang dibutuhkan CI4
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# 2. FIX: Matikan mpm_event dan nyalakan mpm_prefork (Penting agar tidak error di Railway)
RUN a2dismod mpm_event && a2enmod mpm_prefork

# 3. Enable Apache Rewrite Mode (Untuk routing CI4)
RUN a2enmod rewrite

# 4. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Set Document Root ke /public sesuai struktur CodeIgniter 4
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Set Working Directory
WORKDIR /var/www/html

# 7. Copy semua file project
COPY . /var/www/html

# 8. Install library via Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 9. Set Permissions (Sangat penting agar CI4 bisa menulis logs/cache)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# Railway akan menggunakan port 80 secara default dari Apache
EXPOSE 80