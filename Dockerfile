# Stage 1: Composer
FROM composer:latest AS composer
WORKDIR /var/www/sipapii
COPY . .
RUN composer install --optimize-autoloader --ignore-platform-reqs

# Stage 2: PHP-FPM untuk Laravel
FROM php:8.3-fpm
WORKDIR /var/www/sipapii

# Install dependencies & ekstensi yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \ 
    zip \
    unzip \
    git \
    curl \
    default-mysql-client \
    libonig-dev \ 
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring pdo pdo_mysql zip

# Copy semua file Laravel ke dalam container
COPY . .

# Copy vendor hasil instalasi Composer dari stage sebelumnya
COPY --from=composer /var/www/sipapii/vendor /var/www/sipapii/vendor

# Set permission agar Laravel bisa menulis ke storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Expose port PHP-FPM
EXPOSE 9000

# Jalankan PHP-FPM
CMD ["php-fpm"]
