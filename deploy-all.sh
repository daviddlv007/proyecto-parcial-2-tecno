#!/bin/bash
# ============================================================================
# Script de despliegue COMPLETO - Escuela de Conducción
# Ejecutar: bash deploy-all.sh
# 
# Este script hace TODO en un solo paso:
# - Instala PHP y Node deps
# - Build frontend
# - Crea BD
# - Migraciones y seeders
# - Configuración Apache
# ============================================================================

set -e

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

PROYECTO_PATH="/home/grupo17sa/proyecto2/escuela-conduccion"
DB_NAME="db_grupo17sa"
DB_USER="grupo17sa"
DOMINIO="grupo17sa.proyecto2.tecnoweb.org.bo"
APP_KEY=""

# Funciones
log_step() {
    echo -e "${BLUE}[$(date +'%H:%M:%S')]${NC} ${YELLOW}$1${NC}"
}

log_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

log_error() {
    echo -e "${RED}✗ $1${NC}"
}

# ============================================================================
# PASO 1: Validar prerequisitos
# ============================================================================
log_step "PASO 1/7: Validando prerequisitos..."

if ! command -v php &> /dev/null; then
    log_error "PHP no está instalado"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    log_error "Composer no está instalado"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    log_error "npm no está instalado"
    exit 1
fi

if ! command -v psql &> /dev/null; then
    log_error "PostgreSQL client (psql) no está instalado"
    exit 1
fi

log_success "Todos los requisitos están disponibles"

# ============================================================================
# PASO 2: Instalar dependencias
# ============================================================================
log_step "PASO 2/7: Instalando dependencias..."

log_step "  - PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction --working-dir="$PROYECTO_PATH"
log_success "Composer install completado"

log_step "  - Node dependencies..."
npm install --prefix "$PROYECTO_PATH"
log_success "npm install completado"

# ============================================================================
# PASO 3: Build frontend
# ============================================================================
log_step "PASO 3/7: Compilando frontend (Vite)..."
npm run build --prefix "$PROYECTO_PATH"
log_success "Build frontend completado"

# ============================================================================
# PASO 4: Configurar .env
# ============================================================================
log_step "PASO 4/7: Configurando .env..."

if [ ! -f "$PROYECTO_PATH/.env" ]; then
    log_step "  Creando .env desde .env.example..."
    cp "$PROYECTO_PATH/.env.example" "$PROYECTO_PATH/.env"
    
    # Generar APP_KEY
    log_step "  Generando APP_KEY..."
    APP_KEY=$(php "$PROYECTO_PATH/artisan" key:generate --show)
    sed -i "s|APP_KEY=|APP_KEY=$APP_KEY|" "$PROYECTO_PATH/.env"
    
    # Configurar BD
    sed -i "s|DB_DATABASE=db_grupo17sa|DB_DATABASE=$DB_NAME|" "$PROYECTO_PATH/.env"
    sed -i "s|DB_USERNAME=grupo17sa|DB_USERNAME=$DB_USER|" "$PROYECTO_PATH/.env"
    
    # Configurar URL
    sed -i "s|APP_URL=.*|APP_URL=http://$DOMINIO|" "$PROYECTO_PATH/.env"
    
    log_success ".env creado con valores por defecto"
    log_error "⚠️ IMPORTANTE: Edita manualmente:"
    echo "    - DB_PASSWORD (contraseña de PostgreSQL)"
    echo "    - PAGOFACIL_TOKEN_SECRET (si usas pagos)"
    echo ""
    echo "Archivo: $PROYECTO_PATH/.env"
    echo ""
    echo "Una vez editado, ejecuta: bash deploy-all.sh nuevamente"
    exit 0
else
    log_success ".env ya existe"
fi

# ============================================================================
# PASO 5: Base de datos
# ============================================================================
log_step "PASO 5/7: Configurando base de datos..."

# Obtener password de .env
DB_PASS=$(grep "DB_PASSWORD=" "$PROYECTO_PATH/.env" | cut -d'=' -f2)

if [ -z "$DB_PASS" ]; then
    log_error "DB_PASSWORD vacía en .env. Edita el archivo primero."
    exit 1
fi

# Crear BD si no existe
log_step "  Creando base de datos..."
PGPASSWORD="$DB_PASS" psql -U "$DB_USER" -d postgres -h localhost \
    -c "DROP DATABASE IF EXISTS $DB_NAME;" 2>/dev/null || true
PGPASSWORD="$DB_PASS" psql -U "$DB_USER" -d postgres -h localhost \
    -c "CREATE DATABASE $DB_NAME;" 2>/dev/null || {
    log_error "No se pudo crear la BD. Verifica DB_PASSWORD en .env"
    exit 1
}
log_success "Base de datos creada"

# ============================================================================
# PASO 6: Migraciones y Seeders
# ============================================================================
log_step "PASO 6/7: Ejecutando migraciones y seeders..."

cd "$PROYECTO_PATH"

log_step "  Migraciones..."
php artisan migrate --force --quiet

log_step "  Seeders (usuarios y datos)..."
php artisan db:seed --class=DatabaseSeeder --force --quiet
php artisan db:seed --class=DemoDataSeeder --force --quiet

log_success "Migraciones y seeders completados"

# ============================================================================
# PASO 7: Permisos y optimización
# ============================================================================
log_step "PASO 7/7: Permisos y optimización..."

log_step "  Configurando permisos..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R grupo17sa:grupo17sa storage bootstrap/cache 2>/dev/null || true
log_success "Permisos configurados"

log_step "  Optimizando aplicación..."
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
log_success "Optimización completada"

# ============================================================================
# CONFIGURACIÓN APACHE (Opcional - requiere sudo)
# ============================================================================
log_step "APACHE: Copiando configuración..."

if [ -f "$PROYECTO_PATH/apache-config.conf" ]; then
    if sudo -n true 2>/dev/null; then
        log_step "  Usando sudo para copiar a Apache..."
        sudo cp "$PROYECTO_PATH/apache-config.conf" "/etc/httpd/conf.d/escuela-conduccion.conf"
        sudo systemctl restart httpd --quiet
        log_success "Apache configurado y reiniciado"
    else
        log_error "⚠️ No tienes sudo. Pide a tu admin que ejecute:"
        echo "    sudo cp $PROYECTO_PATH/apache-config.conf /etc/httpd/conf.d/escuela-conduccion.conf"
        echo "    sudo systemctl restart httpd"
    fi
else
    log_error "apache-config.conf no encontrado"
fi

# ============================================================================
# RESUMEN FINAL
# ============================================================================
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✓ DESPLIEGUE COMPLETADO EXITOSAMENTE           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}Acceso:${NC}"
echo "  URL: http://$DOMINIO"
echo ""
echo -e "${BLUE}Usuarios de prueba:${NC}"
echo "  Email: admin@escuela.com         Contraseña: password"
echo "  Email: profesor@escuela.com      Contraseña: password"
echo "  Email: alumno@escuela.com        Contraseña: password"
echo ""
echo -e "${BLUE}Datos incluidos:${NC}"
echo "  - 3 cursos"
echo "  - 5 ediciones"
echo "  - 15 horarios"
echo "  - 2 inscripciones"
echo "  - 4 pagos de prueba"
echo ""
echo -e "${BLUE}Gestión:${NC}"
echo "  Admin: http://$DOMINIO/admin"
echo "  Control BD: http://$DOMINIO/admin/database (botones: Limpiar/Poblar/Reset)"
echo ""
echo -e "${BLUE}Logs:${NC}"
echo "  tail -f $PROYECTO_PATH/storage/logs/laravel.log"
echo ""
