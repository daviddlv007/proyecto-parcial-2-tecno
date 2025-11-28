# Fix para Errores en Deploy

## ❌ Error 1: Conflicto de Dependencias npm

```
npm error code ERESOLVE
npm error ERESOLVE could not resolve
npm error peer vite@"^5.0.0 || ^6.0.0" from @vitejs/plugin-vue@5.2.4
```

**Causa:** `@vitejs/plugin-vue@5.2.4` no es compatible con `vite@7.2.4`

**Solución:**
- `@vitejs/plugin-vue`: v5 → **v6** (compatible con Vite 7)
- `deploy.sh`: Limpia `node_modules` antes de instalar y usa fallback `--legacy-peer-deps`

---

## ❌ Error 2: Conexión PostgreSQL - Ident Authentication Failed

```
SQLSTATE[08006] [7] connection to server at "localhost" (::1)
FATAL: Ident authentication failed for user "grupo17sa"
```

**Causa:** `.env` tenía `DB_HOST=localhost` pero el servidor rechaza conexiones locales. La conexión manual funciona con `DB_HOST=mail.tecnoweb.org.bo`

**Solución:**
- Cambié `DB_HOST=localhost` → `DB_HOST=mail.tecnoweb.org.bo` en:
  - `setup.sh`
  - `.env.production`

---

## 🚀 Instrucciones en Servidor

### Opción 1: Usar setup.sh (RECOMENDADO)

```bash
cd /home/grupo17sa/proyecto2/escuela-conduccion
git pull origin main

cd /home/grupo17sa/proyecto2
bash ./setup.sh
```

### Opción 2: Manual (paso a paso)

```bash
cd /home/grupo17sa/proyecto2/escuela-conduccion
git pull origin main
rm -rf node_modules package-lock.json
npm install
npm run build
php artisan migrate --force
php artisan db:seed --force
```

## 📋 Qué Esperar

✓ `npm install` sin errores de resolución  
✓ `npm run build` genera `public/build/` correctamente  
✓ Conexión a PostgreSQL exitosa  
✓ Migraciones ejecutadas  
✓ Datos de demostración cargados  

## ✨ Resultado Final

La aplicación estará accesible en:
```
http://mail.tecnoweb.org.bo/inf513/grupo17sa/proyecto2/escuela-conduccion/public
```

**Commits:**
- `d5bcd39` - Fix: Update @vitejs/plugin-vue to v6 for Vite 7 compatibility
- `a3b2585` - Fix: Change DB_HOST from localhost to mail.tecnoweb.org.bo

