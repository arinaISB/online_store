FROM php:8.2-fpm

# Установка зависимостей (без Nginx)
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo pdo_mysql

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install

# Настройка прав
RUN chown -R www-data:www-data /var/www/html/var

CMD ["php-fpm"]