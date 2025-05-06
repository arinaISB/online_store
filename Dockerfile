FROM php:8.2-fpm

# Установка зависимостей (без Nginx)
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpq-dev \
    librabbitmq-dev \
    libssl-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql

RUN pecl install amqp \
    && docker-php-ext-enable amqp

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer

RUN mkdir -p /var/log/supervisor

WORKDIR /var/www/html
COPY . /var/www/html
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer install

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
