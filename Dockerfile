FROM php:8.4-fpm-alpine

# 1. ติดตั้ง Library ของระบบที่จำเป็นสำหรับ Extension ต่างๆ
# icu-dev จำเป็นสำหรับ intl, libzip-dev สำหรับ zip
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    git \
    unzip

# 2. สั่งติดตั้ง PHP Extension (เพิ่ม intl และ zip เข้าไป)
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql intl zip

# 3. คัดลอก Composer เหมือนเดิม
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# 4. รันคำสั่งติดตั้ง
RUN composer install --no-dev --optimize-autoloader --no-scripts

WORKDIR /var/www

COPY . .

# 4. เพิ่ม --no-scripts เพื่อป้องกันไม่ให้ Laravel รันสคริปต์ที่ต้องพึ่งพาไฟล์ระบบในขณะ Build
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 5. (แนะนำเพิ่มเติม) ตั้งค่า Permission ให้ Laravel เขียนไฟล์ได้
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
