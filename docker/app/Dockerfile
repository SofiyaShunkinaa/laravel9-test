FROM php:8.2-fpm-alpine

# Устанавливаем зависимости
RUN apk add --no-cache nginx supervisor bash

# Устанавливаем расширения PHP
RUN docker-php-ext-install pdo pdo_pgsql

# Настройка Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Настройка рабочей директории
WORKDIR /var/www

# Копируем проект
COPY ./src /var/www

# Конфиг Supervisor для запуска PHP-FPM + Nginx
COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
