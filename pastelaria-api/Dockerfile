FROM php:8.3-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    iproute2 && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos da aplicação Laravel
COPY . .

# Copiar variáveis do .env da raiz para o .env dentro de pastelaria-api
COPY .env ./pastelaria-api/.env

# Garantir que o diretório storage tenha permissões corretas
RUN chmod -R 777 storage bootstrap/cache

# Criar o link simbólico do storage automaticamente
RUN php artisan storage:link || true

# Expôr a porta 9000 para o PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
