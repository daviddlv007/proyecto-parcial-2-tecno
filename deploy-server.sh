#!/bin/bash
# Script de despliegue para servidor académico
# Ejecutar como: bash deploy-server.sh

set -e

echo "==================================="
echo "  DESPLIEGUE ESCUELA DE CONDUCCIÓN "
echo "==================================="

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# 1. Instalar dependencias
echo -e "${YELLOW}[1/7] Instalando dependencias PHP...${NC}"
composer install --optimize-autoloader --no-dev --no-interaction

# 2. Configurar entorno
if [ ! -f .env ]; then
    echo -e "${YELLOW}[2/7] Creando archivo .env...${NC}"
    cp .env.example .env
    echo -e "${GREEN}✓ Archivo .env creado. IMPORTANTE: Edita .env con tus credenciales de BD${NC}"
    echo "Ejecuta: nano .env"
    echo "Luego vuelve a ejecutar este script"
    exit 0
else
    echo -e "${GREEN}[2/7] Archivo .env ya existe${NC}"
fi

# 3. Generar key si no existe
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}[3/7] Generando APP_KEY...${NC}"
    php artisan key:generate --force
else
    echo -e "${GREEN}[3/7] APP_KEY ya configurada${NC}"
fi

# 4. Migraciones
echo -e "${YELLOW}[4/7] Ejecutando migraciones...${NC}"
php artisan migrate --force

# 5. Seeders
echo -e "${YELLOW}[5/7] Poblando base de datos...${NC}"
php artisan db:seed --class=DatabaseSeeder --force
php artisan db:seed --class=DemoDataSeeder --force

# 6. Permisos
echo -e "${YELLOW}[6/7] Configurando permisos...${NC}"
chmod -R 775 storage bootstrap/cache
chown -R grupo17sa:grupo17sa storage bootstrap/cache 2>/dev/null || echo "No se pudieron cambiar los owners (normal si no eres root)"

# 7. Optimizaciones
echo -e "${YELLOW}[7/7] Optimizando aplicación...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo -e "${GREEN}==================================="
echo "  ✓ DESPLIEGUE COMPLETADO"
echo "===================================${NC}"
echo ""
echo "Usuarios de prueba:"
echo "  - admin@escuela.com / password"
echo "  - profesor@escuela.com / password"
echo "  - alumno@escuela.com / password"
echo ""
echo "Accede a: http://grupo17sa.proyecto2.tecnoweb.org.bo"
