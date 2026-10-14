FROM dunglas/frankenphp

RUN docker-php-ext-install pdo_mysql

WORKDIR /app
COPY . /app

# Comentado porque por ahora no se va a usar composer PSR-7/PSR-15
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN composer install --no-dev --optimize-autoloader
