# ===================================
# STAGE 1: Build assets with Node.js
# ===================================
FROM node:18 AS node_builder

# Set working directory
WORKDIR /app

# Copy package.json and package-lock.json first
COPY package*.json ./

# Install frontend dependencies
RUN npm install

# Copy all files to /app
COPY . .
RUN npm rebuild vite && npm run build
# Build assets (Vite)
RUN npm run build


# ================================
# STAGE 2: Build Laravel PHP app
# ================================
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy semua file project ke dalam container
COPY . .

# Copy asset build dari stage sebelumnya
COPY --from=node_builder /app/public/build /var/www/public/build

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Set permission
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Jalankan Laravel server di Cloud Run
CMD php artisan serve --host=0.0.0.0 --port=8080

EXPOSE 8080
