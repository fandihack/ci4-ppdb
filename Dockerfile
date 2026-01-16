FROM php:8.2-apache

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libzip-dev libpng-dev \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache Rewrite Mode
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set Document Root ke /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Set Workdir
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install dependencies via composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set Permissions
RUN chown -R www-data:www-data /var/www/html/writable

# Railway secara otomatis akan mendeteksi port 80 dari image apache ini
EXPOSE 80