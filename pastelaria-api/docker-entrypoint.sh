#!/bin/bash
# Limpeza de cache, configurações, views, etc.
echo "Limpando e otimizando a aplicação..."
php artisan optimize:clear

# Rodar as migrações e seeds
echo "Rodando as migrations..."
php artisan migrate:fresh --force

echo "Rodando os seeds..."
php artisan db:seed --force

# Limpeza de cache, configurações, views, etc.
echo "Limpando e otimizando a aplicação..."
php artisan optimize:clear
