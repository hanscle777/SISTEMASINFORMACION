#!/bin/bash

# Script de inicialización para Railway

echo "🚀 Iniciando aplicación Salón de Belleza..."

# Generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
fi

# Limpiar caché
echo "Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders solo para roles y permisos
if [ "$APP_ENV" = "production" ]; then
    echo "Seedeando roles y permisos..."
    php artisan db:seed --class=RolePermissionSeeder --force
fi

echo "✓ Aplicación lista"
