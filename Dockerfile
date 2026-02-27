FROM php:8.2-apache

WORKDIR /var/www/html

# เปิด mod_rewrite
RUN a2enmod rewrite

# ติดตั้ง extension
RUN docker-php-ext-install pdo pdo_mysql

# ติดตั้ง composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy ไฟล์โปรเจค
COPY . .

# รัน composer install
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# เปลี่ยน permission
RUN chown -R www-data:www-data /var/www/html

# ชี้ไปที่ public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
