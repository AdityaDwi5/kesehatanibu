# Gunakan image resmi PHP
FROM php:8.2-fpm

# Install dependency sistem + Node.js
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua file project ke dalam container
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Install dependensi Node.js dan build assets
RUN npm install
RUN npm run build

# Set permission
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Jalankan PHP server saat container aktif (untuk Cloud Run)
CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080
