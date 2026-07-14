#!/bin/bash

# Ejecutar configuraciones de inicio de Laravel...
echo "Ejecutando configuraciones de inicio de Laravel..."

# Limpiar y cachear configuraciones de Laravel (Mejora de rendimiento en Producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente en el despliegue
echo "Ejecutando migraciones de base de datos..."
php artisan migrate --force

# Usar entrypoint.sh en lugar del que viene en docker/entrypoint.sh
echo "Arrancando Supervisor (Nginx + PHP-FPM)..."

# Iniciar Supervisor que se encargará de Nginx y PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisord.conf

