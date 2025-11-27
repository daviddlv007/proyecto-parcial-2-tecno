# ESTRATEGIA DE DESPLIEGUE - SERVIDOR ACADÉMICO

## ANÁLISIS DEL SERVIDOR

**✅ DISPONIBLE:**
- PHP 8.3.23 (compatible con Laravel 11)
- Composer 2.8.10
- Apache HTTP Server (activo y habilitado)
- PostgreSQL en puerto 5432 (accesible)
- Puertos: 80 (HTTP), 443 (HTTPS) activos
- Git 2.50.1
- Usuario: grupo17sa
- Directorio: /home/grupo17sa/proyecto2
- Sistema: Fedora 41, 3.8GB RAM, 1.8TB disco

**❌ NO DISPONIBLE:**
- Node.js/npm (no aparece en diagnóstico)

**⚠️ IMPLICACIÓN CRÍTICA:**
Como no hay Node.js en servidor, **DEBES hacer el build LOCALMENTE** antes de desplegar.

---

## ESTRATEGIA ÓPTIMA DE DESPLIEGUE

### FASE 1: PREPARACIÓN LOCAL (En tu máquina)

```bash
cd /home/ubuntu/proyectos/proyecto-parcial-2-tecno/escuela-conduccion

# 1. Build de producción
npm run build

# 2. Commit del build
git add public/build -f
git commit -m "Add production build"
git push origin main
```

### FASE 2: EN EL SERVIDOR (Cockpit Terminal)

```bash
# 1. Clonar proyecto
cd /home/grupo17sa/proyecto2
git clone https://github.com/TU_USUARIO/escuela-conduccion.git
cd escuela-conduccion

# 2. Instalar dependencias PHP
composer install --optimize-autoloader --no-dev

# 3. Configurar .env
cp .env.example .env
nano .env
```

**Configuración .env requerida:**
```properties
APP_ENV=production
APP_DEBUG=false
APP_URL=http://grupo17sa.proyecto2.tecnoweb.org.bo

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=db_grupo17sa
DB_USERNAME=grupo17sa
DB_PASSWORD=[CONTRASEÑA_QUE_TE_DIERON]

# PagoFácil (mismo que tienes)
PAGOFACIL_API_URL=https://masterqr.pagofacil.com.bo/api/services/v2
PAGOFACIL_TOKEN_SERVICE=e4c3f89a3c284cd3e0ce05ff4fe5282b4f21e9a6
PAGOFACIL_TOKEN_SECRET=[TU_TOKEN_LARGO]
PAGOFACIL_CALLBACK_URL=http://grupo17sa.proyecto2.tecnoweb.org.bo/api/pagofacil/callback
PAGOFACIL_PAYMENT_AMOUNT=0.10
```

```bash
# 4. Generar key
php artisan key:generate

# 5. Crear base de datos PostgreSQL
psql -U grupo17sa -d postgres
CREATE DATABASE db_grupo17sa;
\q

# 6. Ejecutar migraciones
php artisan migrate --force

# 7. Poblar datos
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=DemoDataSeeder

# 8. Permisos
chmod -R 775 storage bootstrap/cache
chown -R grupo17sa:grupo17sa storage bootstrap/cache

# 9. Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### FASE 3: CONFIGURACIÓN APACHE

Crear archivo: `/etc/httpd/conf.d/escuela-conduccion.conf`

```apache
<VirtualHost *:80>
    ServerName grupo17sa.proyecto2.tecnoweb.org.bo
    DocumentRoot /home/grupo17sa/proyecto2/escuela-conduccion/public

    <Directory /home/grupo17sa/proyecto2/escuela-conduccion/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/httpd/escuela-error.log
    CustomLog /var/log/httpd/escuela-access.log combined
</VirtualHost>
```

```bash
# Reiniciar Apache
sudo systemctl restart httpd
```

### FASE 4: VERIFICACIÓN

1. Acceder a: `http://grupo17sa.proyecto2.tecnoweb.org.bo`
2. Login con: `admin@escuela.com` / `password`
3. Verificar `/admin/database` funcional
4. Probar inscripción y pago QR

---

## COMANDOS RÁPIDOS DE MANTENIMIENTO

```bash
# Ver logs
tail -f storage/logs/laravel.log

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Reset BD desde terminal
php artisan migrate:fresh --seed

# Ver estado Apache
sudo systemctl status httpd
```

---

## CHECKLIST FINAL

- [ ] Build hecho localmente (`npm run build`)
- [ ] Build commiteado y pusheado a GitHub
- [ ] Proyecto clonado en servidor
- [ ] Composer install ejecutado
- [ ] .env configurado con datos correctos
- [ ] Base de datos creada y migrada
- [ ] Seeders ejecutados (usuarios + datos demo)
- [ ] Permisos configurados (775)
- [ ] Apache configurado con VirtualHost
- [ ] Apache reiniciado
- [ ] Sitio accesible públicamente
- [ ] Login funcional
- [ ] Pagos QR probados

**TIEMPO ESTIMADO:** 15-20 minutos
