#!/bin/bash
# ============================================================================
# Setup Script - Clone o Pull + Crear .env + Ejecutar deploy
# Uso: bash setup.sh (desde /home/grupo17sa/proyecto2)
# 
# INDEPENDIENTE: Contiene .env completo incrustrado
# NO requiere archivos externos
# ============================================================================

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

REPO_URL="https://github.com/daviddlv007/proyecto-parcial-2-tecno.git"
REPO_DIR="/home/grupo17sa/proyecto2/escuela-conduccion"
PROYECTO_ROOT="/home/grupo17sa/proyecto2"

# ============================================================================
# PASO 1: Clone o Pull (primero)
# ============================================================================
echo -e "${BLUE}[PASO 1]${NC} ${YELLOW}Verificando repositorio...${NC}"

if [ ! -d "$REPO_DIR" ]; then
    echo -e "${YELLOW}  → Clonando repositorio por primera vez...${NC}"
    cd "$PROYECTO_ROOT"
    git clone "$REPO_URL" escuela-conduccion
    echo -e "${GREEN}  ✓ Repositorio clonado${NC}"
else
    echo -e "${YELLOW}  → Actualizando repositorio existente...${NC}"
    cd "$REPO_DIR"
    git pull origin main --quiet
    echo -e "${GREEN}  ✓ Repositorio actualizado${NC}"
fi

# ============================================================================
# CONTENIDO DE .env (incrustrado completamente)
# ============================================================================
cat > "$REPO_DIR/.env" << 'ENVEOF'
APP_NAME="Escuela de Conducción"
APP_ENV=production
APP_KEY=base64:dOYpGy49gBc9zrLa3a1j0HBo7GEsUZwYCh0BIG4oO1Q=
APP_DEBUG=false
APP_URL=http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

APP_TIMEZONE=America/La_Paz

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=db_grupo17sa
DB_USERNAME=grupo17sa
DB_PASSWORD=grup017grup017*

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@escuela.com"
MAIL_FROM_NAME="${APP_NAME}"

PAGOFACIL_API_URL=https://masterqr.pagofacil.com.bo/api/services/v2
PAGOFACIL_TOKEN_SERVICE=e4c3f89a3c284cd3e0ce05ff4fe5282b4f21e9a6
PAGOFACIL_TOKEN_SECRET=MIIEowIBAAKCAQEAvpzXjFAYS2H1HdqKn42M5SWb6cXvmwX3JZrHH0SJ0WaKqQfm2Y4lLZHtKmYZ8JcPT3E+DfSJpgOmhKN8bpP4iXN9IjWL8uyT1d3z1cK3VmGjLLLnZJHPvUh1Z8v5aGcaYx3cDI4XDWF9C7xDHiIx/RKPjJLi8fqLqVfGV9v2EEk+1xdcJwLnE3r2p0Ev0hDK8VKpPLOHFJj7h/pePJbcH8VrJbJ0eW6+Yv8aMN2dJz8fq6cV+JjqT0fLb8E9KcJPzY3W9cHrN0D7jZk3Y/7aK7j8c3F2vPbT1JCZL2EhqZ3b9QKJ3j9cT8cqVjQdC7lLJPp3dF8eKbZH3cX8K9fJ3kQIDAQABAoIBAEH2YVlCx9P2Iy9fqFkfqPkVRHXrJ3JMI0kJ8L9tHJrZKIj0b3H8CpL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bQKBgQD5N0P8z3Z8K0X8Y0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bQKBgQD0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bQKBgGc0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bQKBgQC3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bQKBgBL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0bL8VNJ8v3cZ0b3FcLpZ0L8cJp3c0b
PAGOFACIL_CALLBACK_URL=http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public/api/pagofacil/callback
PAGOFACIL_PAYMENT_AMOUNT=0.10

VITE_APP_NAME="${APP_NAME}"
ENVEOF

# ============================================================================
# PASO 2: Validar .env creado
# ============================================================================
echo -e "${BLUE}[PASO 2]${NC} ${YELLOW}Validando configuración...${NC}"

if [ ! -f "$REPO_DIR/.env" ]; then
    echo -e "${RED}  ✗ Error: .env no fue creado${NC}"
    exit 1
fi

if ! grep -q "DB_PASSWORD=grup017grup017" "$REPO_DIR/.env"; then
    echo -e "${RED}  ✗ Error: DB_PASSWORD incorrecto en .env${NC}"
    exit 1
fi

chmod 600 "$REPO_DIR/.env"
echo -e "${GREEN}  ✓ .env validado${NC}"

# ============================================================================
# PASO 3: Ejecutar deploy
# ============================================================================
echo -e "${BLUE}[PASO 3]${NC} ${YELLOW}Iniciando despliegue...${NC}"
echo ""

cd "$PROYECTO_ROOT"
bash escuela-conduccion/deploy.sh

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✓ SETUP COMPLETADO EXITOSAMENTE         ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Acceso en:${NC}"
echo "  http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public"
echo ""
