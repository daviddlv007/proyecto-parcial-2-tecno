# Despliegue - Escuela de Conducción

## 🚀 UN SOLO COMANDO

Desde `/home/grupo17sa/proyecto2` ejecuta:

```bash
bash setup.sh
```

**Eso es todo.**

---

## ¿Qué hace `setup.sh`?

1. **Clone o Pull** del repositorio
   - Si no existe: clona desde GitHub
   - Si existe: actualiza con `git pull`

2. **Crear `.env`**
   - Copia `.env.production` → `.env`
   - Valida que `DB_PASSWORD` esté configurado
   - Si no lo está: pide que lo edites y luego ejecutes nuevamente

3. **Ejecutar `deploy.sh`**
   - Instala composer + npm
   - Build Vite
   - Migraciones + seeders
   - Caché optimizado

---

## Acceso

**URL:** `http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public`

**Admin panel:** `/admin`

**Usuarios de prueba:**
```
admin@escuela.com        / password
profesor@escuela.com     / password
alumno@escuela.com       / password
```

---

## Base de datos

- 3 cursos
- 5 ediciones
- 15 horarios
- 2 inscripciones
- 4 pagos de prueba

Control desde: `/admin/database` (botones: Limpiar / Poblar / Reset)

---

## Troubleshooting

```bash
# Ver logs
tail -f escuela-conduccion/storage/logs/laravel.log

# Reset completo de BD
cd escuela-conduccion
php artisan migrate:fresh --seed

# Ejecutar setup nuevamente
cd /home/grupo17sa/proyecto2
bash setup.sh
```

---

## Entrega

```bash
cd /home/grupo17sa/proyecto2
tar -czvf 2025-2_INF513-P2_grupo17sa.tar.gz escuela-conduccion/
```
