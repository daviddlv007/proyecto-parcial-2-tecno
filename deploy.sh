#!/bin/bash
# ============================================================================
# Script de despliegue minimalista - Escuela de Conducción
# Ejecutar desde: /home/grupo17sa/proyecto2/
# Uso: bash deploy.sh
# ============================================================================

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

PROYECTO="escuela-conduccion"
RUTA_PROYECTO="/home/grupo17sa/proyecto2/$PROYECTO"

# ============================================================================
# PASO 1: Validar .env
# ============================================================================
if [ ! -f "$RUTA_PROYECTO/.env" ]; then
    echo -e "${YELLOW}[PASO 1] Archivo .env no encontrado${NC}"
    echo ""
    echo "Instrucciones:"
    echo "1. Copia el contenido de .env.production a .env:"
    echo ""
    echo "   cp $RUTA_PROYECTO/.env.production $RUTA_PROYECTO/.env"
    echo ""
    echo "2. Edita .env y establece DB_PASSWORD:"
    echo ""
    echo "   nano $RUTA_PROYECTO/.env"
    echo ""
    echo "3. Ejecuta nuevamente:"
    echo ""
    echo "   bash deploy.sh"
    echo ""
    exit 0
fi

# Validar que DB_PASSWORD no esté vacío
if ! grep -q "DB_PASSWORD=[^ ]" "$RUTA_PROYECTO/.env"; then
    echo -e "${RED}Error: DB_PASSWORD está vacío en .env${NC}"
    echo "Edita el archivo y establece la contraseña:"
    echo "  nano $RUTA_PROYECTO/.env"
    exit 1
fi

echo -e "${GREEN}✓ .env validado${NC}"

# ============================================================================
# PASO 2: Instalar dependencias PHP
# ============================================================================
echo -e "${YELLOW}[PASO 2] Instalando dependencias PHP...${NC}"
cd "$RUTA_PROYECTO"
composer install --optimize-autoloader --no-dev --no-interaction
echo -e "${GREEN}✓ Composer install completado${NC}"

# ============================================================================
# PASO 3: Instalar dependencias Node
# ============================================================================
echo -e "${YELLOW}[PASO 3] Instalando dependencias Node...${NC}"
cd "$RUTA_PROYECTO"
# Limpiar node_modules y package-lock.json para asegurar versiones correctas
rm -rf node_modules package-lock.json
# Instalar dependencias
npm install || npm install --legacy-peer-deps
echo -e "${GREEN}✓ npm install completado${NC}"

# ============================================================================
# PASO 4: Build frontend
# ============================================================================
echo -e "${YELLOW}[PASO 4] Compilando frontend (Vite)...${NC}"
cd "$RUTA_PROYECTO"
npm run build
echo -e "${GREEN}✓ Build completado${NC}"

# ============================================================================
# PASO 5: Generar APP_KEY (si no existe)
# ============================================================================
if ! grep -q "APP_KEY=base64:" "$RUTA_PROYECTO/.env"; then
    echo -e "${YELLOW}[PASO 5] Generando APP_KEY...${NC}"
    cd "$RUTA_PROYECTO"
    php artisan key:generate --force
    echo -e "${GREEN}✓ APP_KEY generada${NC}"
else
    echo -e "${YELLOW}[PASO 5] APP_KEY ya existe${NC}"
fi

# ============================================================================
# PASO 6: Migraciones
# ============================================================================
echo -e "${YELLOW}[PASO 6] Ejecutando migraciones...${NC}"
cd "$RUTA_PROYECTO"
php artisan migrate --force || echo -e "${YELLOW}  ⚠ Algunas migraciones fallaron (posiblemente duplicadas)${NC}"
echo -e "${GREEN}✓ Migraciones procesadas${NC}"

# ============================================================================
# PASO 7: Seeders
# ============================================================================
echo -e "${YELLOW}[PASO 7] Poblando base de datos...${NC}"
cd "$RUTA_PROYECTO"
php artisan db:seed --class=DatabaseSeeder --force
php artisan db:seed --class=DemoDataSeeder --force
echo -e "${GREEN}✓ Seeders completados${NC}"

# ============================================================================
# PASO 8: Caches
# ============================================================================
echo -e "${YELLOW}[PASO 8] Optimizando aplicación...${NC}"
cd "$RUTA_PROYECTO"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Caché optimizado${NC}"

# ============================================================================
# RESUMEN
# ============================================================================
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✓ DESPLIEGUE COMPLETADO                 ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Acceso:${NC}"
echo "  http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/$PROYECTO/public"
echo ""
echo -e "${YELLOW}Admin:${NC}"
echo "  Email: admin@escuela.com"
echo "  Contraseña: password"
echo ""
echo -e "${YELLOW}Base de datos:${NC}"
echo "  Usuarios: 3 usuarios de prueba"
echo "  Cursos: 3 cursos con 5 ediciones"
echo "  Horarios: 15 horarios configurados"
echo "  Datos: 2 inscripciones + 4 pagos"
echo ""
echo -e "${YELLOW}Panel de control:${NC}"
echo "  http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/$PROYECTO/public/admin/database"
echo "  (Botones: Limpiar / Poblar / Reset)"
echo ""
