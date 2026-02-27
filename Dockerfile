FROM php:8.2-apache

WORKDIR /var/www/html

# เปิด mod_rewrite
RUN a2enmod rewrite

# ติดตั้ง extension ที่ Laravel ใช้
RUN docker-php-ext-install pdo pdo_mysql

# copy ไฟล์โปรเจค
COPY . .

# เปลี่ยน permission
RUN chown -R www-data:www-data /var/www/html

# ให้ Apache ชี้ไปที่ public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
