# ===================================
# STAGE 1: Build assets with Node.js
# ===================================
FROM node:18 AS node_builder

# Set working directory
WORKDIR /app

# Salin file package.json & lock
COPY package*.json ./

# Install frontend dependencies
RUN npm install

# Salin semua source code
COPY . .

# Build asset Vite
RUN npm run build


# ===================================
# STAGE 2: Laravel PHP + Composer
# ===================================
FROM php:8.2-fpm

# Install PHP dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Salin seluruh Laravel project
COPY . .

# Salin hasil build Vite dari stage Node.js
COPY --from=node_builder /app/public/build /var/www/public/build
COPY --from=node_builder /app/public/mix-manifest.json /var/www/public/mix-manifest.json
COPY --from=node_builder /app/public/hot /var/www/public/hot
COPY --from=node_builder /app/public/assets /var/www/public/assets
COPY --from=node_builder /app/public/manifest.json /var/www/public/manifest.json
COPY --from=node_builder /app/resources/views /var/www/resources/views

# Install Laravel dependency
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions & optimize
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Laravel commands
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear

# Jalankan Laravel server di Cloud Run
CMD php artisan serve --host=0.0.0.0 --port=8080

# Expose port 8080 (untuk Cloud Run)
EXPOSE 8080
