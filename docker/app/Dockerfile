FROM php:8.2-fpm

# Install system dependencies for PostgreSQL extensions
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev git unzip \
 && docker-php-ext-install pdo pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www


