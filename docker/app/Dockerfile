# Используем PHP-FPM 8.2
FROM php:8.2-fpm

# Устанавливаем системные зависимости для PostgreSQL и Git
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev git unzip supervisor \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Копируем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем Nginx-конфиг в контейнер
COPY ../../nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

# Копируем supervisord.conf
COPY ../../docker/supervisord.conf /etc/supervisord.conf

# Команда запуска supervisord, чтобы запускались одновременно PHP-FPM и Nginx
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
