FROM php:8.2-fpm
RUN apt-get update && apt-get install -y unzip curl git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
RUN chmod +x /usr/local/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install
