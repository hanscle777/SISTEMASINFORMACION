# ✅ Production Deployment Checklist

## Pre-Deployment

### Code Quality
- [ ] Revisar que no hay datos de desarrollo en el código
- [ ] Ejecutar `php artisan test` y confirmar que pasan
- [ ] Ejecutar `./vendor/bin/pint` para verificar code style
- [ ] Revisar logs para warning o errors
- [ ] Verificar que no hay secretos en archivos (API keys, passwords)

### Database
- [ ] Crear base de datos en Supabase
- [ ] Obtener credenciales: Host, Port, Database, Username, Password
- [ ] Verificar que las migraciones son puras (sin datos)
- [ ] Revisar que solo RolePermissionSeeder corre en producción

### Environment
- [ ] Generó `APP_KEY` segura: `php artisan key:generate`
- [ ] Configuró `APP_DEBUG=false`
- [ ] Configuró `APP_ENV=production`
- [ ] Todos los secretos están en Railway (nunca en .env)

### GitHub
- [ ] Subió todo a GitHub
- [ ] Verificó que .env no está en el repositorio (.gitignore)
- [ ] Creó rama main o develop limpia

## Railway Setup

### Project Configuration
- [ ] Conectó GitHub repository
- [ ] Railway auto-detectó PHP/Laravel
- [ ] Agregó todas las variables de entorno requeridas
- [ ] Habilitó Railway PostgreSQL (o usó Supabase)

### Variables de Entorno
```
Verification:
- [ ] APP_NAME=Salón de Belleza
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] APP_URL=https://tu-dominio.railway.app
- [ ] APP_KEY=base64:XXXXXXXXXXX (NO VACIO)
- [ ] DB_CONNECTION=pgsql
- [ ] DB_HOST=supabase host
- [ ] DB_PASSWORD=******* (nunca vacío)
- [ ] STRIPE_SECRET_KEY=sk_live_***
```

## Deployment

### Build & Deploy
- [ ] Hizo push a GitHub (trigger automático de deploy)
- [ ] Railway ejecutó build correctamente
- [ ] No hay errores en los logs de build
- [ ] Las migraciones ejecutaron sin errores
- [ ] Los seeders ejecutaron (solo RolePermissionSeeder)

### Verificación de Salud
```bash
# Verificar desde Railway shell
railway shell

# Tests básicos
php artisan tinker
>>> User::count()
>>> Role::count()
>>> exit()

# Verificar configuración
php artisan config:show app
php artisan config:show database
```

## Post-Deployment

### Funcionalidades Críticas
- [ ] La aplicación responde en https://tu-dominio.railway.app
- [ ] Puede acceder a la página de login
- [ ] Los roles y permisos están presentes en BD
- [ ] El dashboard carga sin errores
- [ ] Las migraciones están en la BD

### Base de Datos
- [ ] Verificar en Supabase que todas las tablas se crearon:
  - [ ] users
  - [ ] roles
  - [ ] permissions
  - [ ] permission_role
  - [ ] activity_logs
  - [ ] horarios
  - [ ] servicios
  - [ ] citas
  - [ ] ventas
  - [ ] venta_detalles
  - [ ] alertas
  - [ ] comisiones

### Security
- [ ] Verificar que APP_DEBUG=false en Dashboard
- [ ] Verificar SSL certificate está activo (🔒 en URL)
- [ ] Probar que no hay información sensible en error pages
- [ ] Verificar CORS está configurado correctamente

### Performance
- [ ] Verificar tiempo de respuesta (<1s)
- [ ] Revisar CPU/Memory usage en Railway
- [ ] Verificar que OPcache está activo

### Logging
- [ ] Verificar `storage/logs/` tiene estructura correcta
- [ ] Configurar alertas en Railway para errors
- [ ] Verificar que puedes ver logs: `railway logs --follow`

## Monitoring (Primeras 24 horas)

- [ ] No hay spike en error rate
- [ ] Database query performance es aceptable
- [ ] Memory/CPU usage es estable
- [ ] No hay requests rechazadas (5xx errors)
- [ ] Usuarios pueden crear citas correctamente
- [ ] Sistema de ventas funciona (si está implementado)

## Problemas Comunes

### Error: Database connection refused
```bash
railway shell
# Verificar credenciales
env | grep DB_
# Conectar manualmente
psql postgresql://$DB_USERNAME:$DB_PASSWORD@$DB_HOST:$DB_PORT/$DB_DATABASE
```

### Error: CSRF token mismatch
```bash
railway shell
php artisan cache:clear
php artisan config:clear
```

### Error: Permission denied
```bash
railway shell
chmod -R 775 storage/ bootstrap/cache/
```

## Rollback (Si es necesario)

```bash
# Ver deployment history
railway deployments

# Revert a versión anterior
railway redeploy [deployment-id]

# O desde GitHub: hacer push de versión anterior
git revert HEAD
git push origin main
# Railway auto-redeploy
```

## Próximos Pasos

1. **Configurar Dominio Personalizado**
   - En Railway: Settings > Domains
   - Agregar tu dominio personalizado
   - Configurar DNS records

2. **Backups Automáticos**
   - En Supabase: Database > Backups
   - Configurar backups diarios

3. **Monitoreo**
   - Railway Alerts para CPU/Memory
   - Email notifications para deployments

4. **Updates**
   - Crear rama develop para nuevas features
   - Usar PR para merge a main
   - GitHub Actions automáticamente testea antes de merge

5. **Scale**
   - Si crece tráfico, aumentar replicas en Railway
   - Considerar Redis para cache distribuido
   - Configurar CDN para assets estáticos

## Documentación Útil

- Railway Docs: https://docs.railway.app
- Laravel Deployment: https://laravel.com/docs/deployment
- Supabase: https://supabase.com/docs
