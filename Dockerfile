FROM php:8.2-apache

# 1. Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache Rewrite Mode
RUN a2enmod rewrite

# 3. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Set Document Root ke folder /public (Khas CI4)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# 5. Copy project & Install dependencies
COPY . /var/www/html
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. Set Permissions
# Menggunakan 777 pada writable agar Apache (www-data) pasti bisa menulis logs/sessions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/writable

# 7. SINKRONISASI PORT (PENTING UNTUK RAILWAY)
# Memastikan Apache mendengarkan port yang diberikan Railway (biasanya 80)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 8. SETUP ENTRYPOINT
RUN cp /var/www/html/entrypoint.sh /usr/local/bin/entrypoint.sh \
    && sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

# Jalankan menggunakan shell agar lebih stabil
ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]