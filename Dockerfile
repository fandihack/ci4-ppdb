FROM php:8.2-apache

# 1. Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# 2. FIX AGRESIF: Hapus paksa file load mpm_event
# Kita hapus filenya langsung agar Apache tidak punya pilihan selain pakai mpm_prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf /etc/apache2/mods-enabled/mpm_event.load \
    && a2enmod mpm_prefork

# 3. Enable Apache Rewrite Mode
RUN a2enmod rewrite

# 4. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Set Document Root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# 6. Copy & Install
COPY . /var/www/html
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Set Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

EXPOSE 80