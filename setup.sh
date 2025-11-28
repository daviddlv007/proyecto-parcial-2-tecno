#!/bin/bash
# ============================================================================
# Setup Script - Clone o Pull + Crear .env + Ejecutar deploy
# Uso: bash setup.sh (desde cualquier lugar)
# NO se sube a GitHub - es solo para el servidor
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
# PASO 1: Clone o Pull
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
# PASO 2: Crear .env desde .env.production
# ============================================================================
echo -e "${BLUE}[PASO 2]${NC} ${YELLOW}Configurando .env...${NC}"

if [ ! -f "$REPO_DIR/.env.production" ]; then
    echo -e "${RED}  ✗ Error: .env.production no encontrado${NC}"
    exit 1
fi

# Copiar forzadamente (sobreescribir si existe)
cp -f "$REPO_DIR/.env.production" "$REPO_DIR/.env"
echo -e "${GREEN}  ✓ .env creado desde .env.production${NC}"

# Verificar que DB_PASSWORD esté configurado
if ! grep -q "DB_PASSWORD=[^ ]" "$REPO_DIR/.env"; then
    echo -e "${YELLOW}"
    echo "  ⚠️  IMPORTANTE: Edita DB_PASSWORD en .env"
    echo ""
    echo "  nano $REPO_DIR/.env"
    echo ""
    echo "  Luego ejecuta:"
    echo "  bash setup.sh"
    echo -e "${NC}"
    exit 0
fi

# ============================================================================
# PASO 3: Ejecutar deploy
# ============================================================================
echo -e "${BLUE}[PASO 3]${NC} ${YELLOW}Iniciando despliegue...${NC}"
echo ""

cd "$PROYECTO_ROOT"
bash escuela-conduccion/deploy.sh

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✓ SETUP COMPLETADO                      ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Acceso:${NC}"
echo "  http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public"
echo ""
