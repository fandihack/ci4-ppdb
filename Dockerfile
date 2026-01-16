FROM php:8.2-apache

# 1. Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# 2. FIX MPM: Hapus semua symlink modul MPM yang aktif dan paksa ke prefork
# Ini akan membersihkan folder mods-enabled dari segala jenis mpm yang bentrok
RUN rm -f /etc/apache2/mods-enabled/mpm_* \
    && a2enmod mpm_prefork

# 3. Enable Apache Rewrite Mode
RUN a2enmod rewrite

# 4. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Set Document Root ke /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Set Workdir
WORKDIR /var/www/html

# 7. Copy files & Install
COPY . /var/www/html
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Set Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

EXPOSE 80