FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# FIX MPM: Langsung timpa file config mpm agar hanya memuat prefork
RUN echo "LoadModule mpm_prefork_module modules/mod_mpm_prefork.so" > /etc/apache2/mods-available/mpm_prefork.load \
    && rm -f /etc/apache2/mods-enabled/mpm_event.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load

RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Config Apache Document Root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy project & Install dependencies
COPY . /var/www/html
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

EXPOSE 80