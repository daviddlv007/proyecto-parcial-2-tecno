# Despliegue - Escuela de Conducción

## Resumen rápido

**Ubicación en servidor:** `/home/grupo17sa/proyecto2/escuela-conduccion/public`

**URL pública:** `http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public`

---

## Pasos de despliegue

### 1. Clonar proyecto

```bash
cd /home/grupo17sa/proyecto2
git clone https://github.com/daviddlv007/proyecto-parcial-2-tecno.git escuela-conduccion
cd escuela-conduccion
```

### 2. Crear .env

```bash
cp .env.production .env
nano .env
```

**Editar solo:** `DB_PASSWORD` (contraseña PostgreSQL proporcionada por admin)

### 3. Ejecutar deploy

```bash
cd /home/grupo17sa/proyecto2
bash escuela-conduccion/deploy.sh
```

El script hace todo automáticamente:
- Instala dependencias (composer + npm)
- Build frontend (Vite)
- Genera APP_KEY
- Ejecuta migraciones
- Popula BD (usuarios + datos de prueba)
- Optimiza caché

---

## Usuarios de prueba

```
admin@escuela.com          / password
profesor@escuela.com       / password
alumno@escuela.com         / password
```

---

## Panel de control

**Admin:** `http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public/admin`

**Control BD:** `/admin/database` (Limpiar / Poblar / Reset)

---

## Si algo falla

```bash
# Ver logs
tail -f /home/grupo17sa/proyecto2/escuela-conduccion/storage/logs/laravel.log

# Reiniciar BD
php artisan migrate:fresh --seed
```

---

## Entrega final

```bash
cd /home/grupo17sa/proyecto2
tar -czvf 2025-2_INF513-P2_grupo17sa.tar.gz escuela-conduccion/
```
