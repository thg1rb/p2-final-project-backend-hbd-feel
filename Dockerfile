# ---------- Stage 1: Build Frontend ----------
FROM node:20 AS nodebuilder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ---------- Stage 2: PHP + Apache ----------
FROM php:8.4-apache
WORKDIR /var/www/html

# เพิ่มการติดตั้ง lib สำหรับ GD
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring intl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ดึงไฟล์โค้ด
COPY . .

# 🌟 ย้ายการดึงไฟล์ที่ Build แล้วมาไว้ตรงนี้
COPY --from=nodebuilder /app/public/build ./public/build

# ติดตั้ง PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ตั้งสิทธิ์การเข้าถึงไฟล์
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80