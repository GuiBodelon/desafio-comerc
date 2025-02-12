FROM php:8.1-apache

RUN apt-get update && \
    apt-get install -y \
        nano \
        libzip-dev \
        zip \
        unzip \
        git \
    && docker-php-ext-install zip pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    a2enmod rewrite && \
    a2enmod headers

CMD ["apache2-foreground"]
