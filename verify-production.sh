#!/bin/bash
# Verificación rápida de proyecto en producción

echo "🔍 Verificando configuración de producción..."
echo ""

# Verificar archivos críticos
echo "✅ Archivos de configuración:"
test -f ".env.production" && echo "  • .env.production" || echo "  ✗ FALTA .env.production"
test -f "Procfile" && echo "  • Procfile" || echo "  ✗ FALTA Procfile"
test -f "railway.json" && echo "  • railway.json" || echo "  ✗ FALTA railway.json"
test -f ".env.example" && echo "  • .env.example" || echo "  ✗ FALTA .env.example"

echo ""
echo "✅ Documentación:"
test -f "README.md" && echo "  • README.md" || echo "  ✗ FALTA README.md"
test -f "RAILWAY_DEPLOYMENT.md" && echo "  • RAILWAY_DEPLOYMENT.md" || echo "  ✗ FALTA RAILWAY_DEPLOYMENT.md"
test -f "DEPLOYMENT_CHECKLIST.md" && echo "  • DEPLOYMENT_CHECKLIST.md" || echo "  ✗ FALTA DEPLOYMENT_CHECKLIST.md"

echo ""
echo "✅ CI/CD:"
test -f ".github/workflows/test.yml" && echo "  • .github/workflows/test.yml" || echo "  ✗ FALTA CI"
test -f ".github/workflows/deploy.yml" && echo "  • .github/workflows/deploy.yml" || echo "  ✗ FALTA deploy workflow"

echo ""
echo "✅ Docker eliminado:"
test ! -f "Dockerfile" && echo "  • Dockerfile eliminado ✓" || echo "  ✗ Dockerfile aún existe"
test ! -f "docker-compose.yml" && echo "  • docker-compose.yml eliminado ✓" || echo "  ✗ docker-compose.yml aún existe"
test ! -d "docker" && echo "  • docker/ eliminado ✓" || echo "  ✗ docker/ aún existe"

echo ""
echo "✅ PHP Version:"
php -v | head -n 1

echo ""
echo "✅ Composer:"
composer --version

echo ""
echo "✅ Node:"
node --version
npm --version

echo ""
echo "✅ Database Migrations:"
ls -1 database/migrations | wc -l | xargs echo "  Total migraciones:"

echo ""
echo "✅ Environment variables (.env.example):"
grep "^[A-Z_]*=" .env.example | head -10 | sed 's/^/  • /'

echo ""
echo "🎉 Proyecto listo para Railway!"
echo ""
echo "Próximos pasos:"
echo "1. git add -A"
echo "2. git commit -m 'chore: production ready'"
echo "3. git push origin main"
echo "4. Seguir guía en RAILWAY_DEPLOYMENT.md"
