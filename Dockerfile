FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# Matikan event, nyalakan prefork. "|| true" agar build tidak stop kalau modul sudah mati.
RUN a2dismod mpm_event || true \
    && a2enmod mpm_prefork

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . /var/www/html
RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

EXPOSE 80