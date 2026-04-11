# Build stage for Node dependencies
FROM node:22-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci

# Build frontend assets
COPY . .
RUN npm run build

# PHP runtime
FROM php:8.3-fpm-alpine

WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    sqlite

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    gd \
    pdo \
    pdo_sqlite \
    pdo_mysql \
    bcmath \
    ctype \
    fileinfo \
    json \
    phar \
    posix \
    tokenizer

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY --chown=www-data:www-data . .

# Copy built Node assets from builder
COPY --from=node-builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set environment
RUN cp .env.example .env && \
    echo "DB_DATABASE=/app/database/database.sqlite" >> .env && \
    php artisan key:generate

# Create cache directories
RUN mkdir -p bootstrap/cache storage && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data .

EXPOSE 8000

CMD ["php-fpm"]
