#!/bin/bash

# Railway asigna un puerto dinámico mediante la variable de entorno $PORT.
# Si no está definida (en local por ejemplo), usamos 80.
PORT=${PORT:-80}

# Reemplazar el puerto en la configuración de Nginx
# Usar / como delimitador en lugar de | para evitar conflictos
sed -i "s/listen [0-9]*;/listen ${PORT};/" /etc/nginx/nginx.conf

# Limpiar y cachear configuraciones de Laravel (Mejora de rendimiento en Producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente en el despliegue
php artisan migrate --force

# Arrancando Supervisor (Nginx + PHP-FPM)
echo "Arrancando Supervisor (Nginx + PHP-FPM)..."

# Iniciar Supervisor que se encargará de Nginx y PHP-FPM
exec /usr/bin/supervisord -c /etc/supervisord.conf

