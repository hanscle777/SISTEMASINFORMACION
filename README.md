# Salón de Belleza - Sistema de Gestión

Sistema integral de gestión para salones de belleza con funcionalidades de citas, inventario, ventas y gestión de personal.

## 🚀 Deployment en Railway

### Requisitos Previos
- GitHub repository conectado
- Cuenta de Railway (railway.app)
- Base de datos PostgreSQL en Supabase (o similar)

### Variables de Entorno en Railway

```
APP_URL=https://tu-dominio.railway.app
APP_KEY=base64:tu-llave-generada
DB_HOST=tu-base-datos.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu-password
STRIPE_PUBLIC_KEY=pk_live_xxx
STRIPE_SECRET_KEY=sk_live_xxx
SUPABASE_URL=https://xxx.supabase.co
SUPABASE_KEY=eyJxxx
```

### Pasos de Deployment

1. **Conectar GitHub**
   - En Railway, crear nuevo proyecto
   - Seleccionar "Deploy from GitHub"
   - Seleccionar este repositorio

2. **Configurar Variables de Entorno**
   - En la pestaña "Variables", agregar todas las variables listadas arriba
   - Usar Supabase PostgreSQL para la base de datos

3. **Build & Deploy**
   - Railway detectará automáticamente que es una aplicación PHP
   - Ejecutará `composer install` y `npm run build`
   - Ejecutará migraciones en el comando `release`

4. **Post-Deployment**
   - Las migraciones corren automáticamente
   - Se seed automáticamente los roles y permisos
   - Verificar logs en Railway: `railway logs --follow`

### Estructura de Base de Datos

Migraciones limpias y organizadas:
- Users, Roles, Permissions, ActivityLogs
- Productos, Servicios, Promociones
- Citas (Appointments)
- Ventas y detalles
- Comisiones y alertas de stock

### Características

✅ Sistema de roles y permisos  
✅ Gestión de citas  
✅ Control de inventario  
✅ Sistema de ventas con Stripe  
✅ Comisiones automáticas  
✅ Reportes y auditoría  
✅ API RESTful  
✅ Frontend con Vite + Tailwind CSS

### Tecnologías

- **Backend**: Laravel 12 + PHP 8.2
- **Frontend**: Vite + Tailwind CSS
- **Database**: PostgreSQL (Supabase)
- **Pagos**: Stripe
- **Auth**: Supabase Auth + Laravel Sessions
- **Hosting**: Railway

### Desarrollo Local

```bash
# Instalar dependencias
composer install
npm install

# Configurar .env
cp .env.example .env
php artisan key:generate

# Base de datos (desarrollo)
php artisan migrate:fresh --seed

# Ejecutar servidor
npm run dev
php artisan serve
```

### Testing

```bash
php artisan test
```

### Build para Producción

```bash
npm run build
composer install --no-dev --optimize-autoloader
```

## 📝 Notas Importantes

- Los datos de prueba (TestDataSeeder) solo se ejecutan en ambiente local
- Las migraciones están optimizadas para producción
- OPcache está configurado para máximo rendimiento
- Los logs se guardan en `storage/logs`

## 🔒 Seguridad

- `APP_DEBUG=false` en producción
- `APP_KEY` generada automáticamente
- Contraseñas hasheadas con Bcrypt
- CORS y CSRF protegidos
- RLS (Row-Level Security) en base de datos

## 📞 Soporte

Para reportar issues o sugerencias, crear un issue en GitHub.
