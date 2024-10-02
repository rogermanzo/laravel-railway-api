FROM php:8.3.6

RUN apt-get update && apt-get install -y sudo
RUN apt-get update && apt-get install -y libzip-dev && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000