FROM php:8.2-fpm

# Установка зависимостей (без Nginx)
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html

USER www-data

RUN composer install

CMD ["php-fpm"]