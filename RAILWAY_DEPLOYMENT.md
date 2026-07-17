# 🚀 Railway Deployment Guide

## Configuración Previa (Una sola vez)

### 1. Railway CLI Installation (Opcional)
```bash
npm install -g @railway/cli
railway login
```

### 2. Preparar GitHub
```bash
git add -A
git commit -m "chore: prepare for production deployment"
git push origin main
```

## Despliegue en Railway

### Opción 1: Desde Dashboard de Railway (Recomendado)

1. Ir a https://railway.app
2. Crear nuevo proyecto
3. Conectar GitHub:
   - Click en "Deploy from GitHub"
   - Seleccionar el repositorio "SALON-DE-BELLEZA"
   - Autorizar Railway
4. Railway detectará automáticamente que es PHP
5. Agregar variables de entorno (ver sección Variables)

### Opción 2: Desde CLI

```bash
railway login
railway init
railway up
```

## Variables de Entorno en Railway

En la pestaña "Variables" del proyecto en Railway, agregar:

```env
APP_NAME=Salón de Belleza
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.railway.app
APP_KEY=base64:GENERAR_UNA_KEY_NUEVA

# Database - Supabase PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=abc123.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=TU_PASSWORD_AQUI

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Stripe (si aplica)
STRIPE_PUBLIC_KEY=pk_live_xxxx
STRIPE_SECRET_KEY=sk_live_xxxx

# Supabase Auth (si usas)
SUPABASE_URL=https://abc123.supabase.co
SUPABASE_KEY=eyJxxx
```

### Generar APP_KEY Seguro
```bash
php artisan key:generate
```

## Build Configuration

Railway automáticamente:
- ✅ Instala dependencias PHP (composer install --no-dev)
- ✅ Instala dependencias Node (npm install)
- ✅ Compila assets (npm run build)
- ✅ Limpia caché (config:clear)
- ✅ Ejecuta migraciones (php artisan migrate --force)
- ✅ Seed roles y permisos

### Procfile
```
release: php artisan migrate --force && php artisan db:seed --class=RolePermissionSeeder --force
web: heroku-php-nginx public/
```

## Health Checks

Railway automáticamente verifica que la aplicación esté respondiendo en `/`.

Para verificar manualmente:
```bash
curl https://tu-dominio.railway.app/health
```

## Debugging en Railway

### Ver Logs en Vivo
```bash
railway logs --follow
```

### Ver últimas 100 líneas
```bash
railway logs --lines=100
```

### Conectar a Shell de Producción
```bash
railway shell
php artisan tinker
```

## Base de Datos Supabase

### Configuración Recomendada

1. **Crear Proyecto en Supabase** (https://supabase.com)
2. **Obtener Credenciales**:
   - Go to Settings > Database
   - Copiar Host, Port, Database, Username, Password

3. **Configurar SSL**
   - En Railway, agregar a la conexión de PostgreSQL: `?sslmode=require`

### Conexión de Prueba
```bash
railway shell
php artisan db --write
# En la consola psql, probar conexión
```

## Post-Deployment Checklist

- [ ] Configurar dominio personalizado en Railway
- [ ] Verificar que los logs no muestren errores
- [ ] Crear primer usuario administrador
- [ ] Verificar que el sistema de roles está activo
- [ ] Probar login y funcionalidades básicas
- [ ] Configurar backups de BD en Supabase
- [ ] Agregar SSL certificate (Railway lo hace automáticamente)

## Troubleshooting

### Error: "Database connection refused"
```bash
# Verificar credenciales de BD
railway shell
echo $DB_HOST
echo $DB_PORT
echo $DB_PASSWORD
```

### Error: "Permission denied" en storage/
```bash
railway shell
chmod -R 775 storage/ bootstrap/cache/
```

### Error: "CSRF token mismatch"
```bash
# Limpiar sesiones
railway shell
php artisan cache:clear
php artisan config:clear
```

### Migraciones no ejecutadas
```bash
railway shell
php artisan migrate --force
php artisan db:seed --class=RolePermissionSeeder --force
```

## Monitoreo

### Logs Importantes
- `storage/logs/` - Todos los logs de aplicación
- Error rate en dashboard de Railway
- Database query performance

### Configurar Alertas
- En Railway: Settings > Alerts
- Configurar alertas para CPU, Memoria, Requests

## Rollback

Si necesitas revertir a una versión anterior:

```bash
# Ver deployment history
railway deployments

# Redeploy una versión anterior
railway redeploy [deployment-id]
```

## Escalado (Cuando Crezca)

- Aumentar replica count en Railway
- Configurar Redis para cache distribuido
- Implementar queue workers adicionales
- Considerar CDN para assets estáticos

## Costos Estimados

- Railway: ~$5-20/mes (dependiendo de uso)
- Supabase BD: ~$0-50/mes (gratuito hasta 500MB)
- Total: ~$5-70/mes

## Soporte y Recursos

- Documentación Railway: https://docs.railway.app
- Documentación Laravel: https://laravel.com/docs
- Documentación Supabase: https://supabase.com/docs
