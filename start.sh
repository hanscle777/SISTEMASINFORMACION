#!/bin/bash
set -e

echo "🚀 Starting Salon de Belleza..."

# Run migrations
echo "📦 Running migrations..."
php artisan migrate --force

# Seed roles and permissions
echo "🔐 Seeding roles and permissions..."
php artisan db:seed --class=RolePermissionSeeder --force

echo "✅ Application ready!"

# Start the application (will be handled by Heroku/Railpack)
exec "$@"
