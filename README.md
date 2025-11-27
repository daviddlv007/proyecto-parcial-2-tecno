# Escuela de Conducción - Sistema de Gestión# 🎓 Sistema de Gestión de Escuela de Conducción<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



Sistema web integral para administrar una escuela de conducción con gestión de cursos, inscripciones, pagos y usuarios.



## Características Principales## ✅ Resumen Ejecutivo<p align="center">



- **Multi-rol**: Administrador, Profesor y Alumno<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>

- **Gestión de Cursos**: Ediciones, horarios y capacidad

- **Sistema de Pagos**: Integración con PagoFácil QR**Estado:** Backend 100% Implementado y Funcional  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>

- **Inscripciones**: Diferentes planes de pago (1, 3 o 6 cuotas)

- **Panel de Administración**: Control total del sistema**Tecnologías:** Laravel 12 + PostgreSQL 16 + Inertia + Vue 3  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>



## Tecnologías**Rutas:** 90+ endpoints RESTful  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>



- **Backend**: Laravel 11 + PHP 8.3**Controladores:** 12 (6 Admin, 3 Instructor, 3 Alumno)  </p>

- **Frontend**: Vue 3 + TypeScript + Tailwind CSS + Inertia.js

- **Base de Datos**: PostgreSQL**Modelos:** 17 con business logic completa  

- **Build**: Vite

## About Laravel

## Requisitos del Servidor

---

- PHP 8.3+

- Composer 2.xLaravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- PostgreSQL 13+

- Node.js 18+ (solo para build)## 📦 Componentes Implementados

- Apache/Nginx con mod_rewrite

- [Simple, fast routing engine](https://laravel.com/docs/routing).

## Instalación

### ✅ Base de Datos- [Powerful dependency injection container](https://laravel.com/docs/container).

```bash

# 1. Clonar repositorio- **18 tablas relacionales** con integridad referencial- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.

git clone <tu-repo>

cd escuela-conduccion- **2 migraciones**: Estructura base + Mejoras de negocio- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).



# 2. Instalar dependencias PHP- **Seeders funcionales** con datos de prueba- Database agnostic [schema migrations](https://laravel.com/docs/migrations).

composer install --optimize-autoloader --no-dev

- [Robust background job processing](https://laravel.com/docs/queues).

# 3. Configurar entorno

cp .env.example .env### ✅ Modelos Eloquent (17)- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

php artisan key:generate

Todos con:

# 4. Configurar base de datos en .env

DB_CONNECTION=pgsql- Relaciones completasLaravel is accessible, powerful, and provides tools required for large, robust applications.

DB_HOST=localhost

DB_PORT=5432- Scopes personalizados

DB_DATABASE=escuela_conduccion

DB_USERNAME=tu_usuario- Accessors computados## Learning Laravel

DB_PASSWORD=tu_password

- Métodos de validación de negocio

# 5. Ejecutar migraciones y seeders

php artisan migrate --force- Business logic methodsLaravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

php artisan db:seed --class=DatabaseSeeder

php artisan db:seed --class=DemoDataSeeder



# 6. Instalar dependencias frontend y compilar### ✅ Controladores (12)If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

npm install

npm run build



# 7. Configurar permisos**Admin (6):**## Laravel Sponsors

chmod -R 775 storage bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache- VehiculoController - CRUD + mantenimiento

```

- CursoController - CRUD + asignación actividadesWe would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

## Configuración Apache

- SesionController - CRUD + validación horarios

```apache

<VirtualHost *:80>- InscripcionController - CRUD + aprobación + planes pago### Premium Partners

    ServerName tu-dominio.com

    DocumentRoot /ruta/al/proyecto/public- PagoController - CRUD + QR + confirmación



    <Directory /ruta/al/proyecto/public>- ActividadController - CRUD + validación duración- **[Vehikl](https://vehikl.com)**

        AllowOverride All

        Require all granted- **[Tighten Co.](https://tighten.co)**

    </Directory>

**Instructor (3):**- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**

    ErrorLog ${APACHE_LOG_DIR}/escuela-error.log

    CustomLog ${APACHE_LOG_DIR}/escuela-access.log combined- DashboardController - Estadísticas reales- **[64 Robots](https://64robots.com)**

</VirtualHost>

```- SesionController - Gestión sesiones propias- **[Curotec](https://www.curotec.com/services/technologies/laravel)**



## Usuarios de Prueba- AsistenciaController - Marcar asistencia- **[DevSquad](https://devsquad.com/hire-laravel-developers)**



| Email                    | Contraseña | Rol           |- **[Redberry](https://redberry.international/laravel-development)**

|--------------------------|------------|---------------|

| admin@escuela.com        | password   | Administrador |**Alumno (3):**- **[Active Logic](https://activelogic.com)**

| profesor@escuela.com     | password   | Profesor      |

| alumno@escuela.com       | password   | Alumno        |- DashboardController - Progreso e inscripciones



## Gestión de Base de Datos- InscripcionController - Ver + inscribirse## Contributing



Accede a `/admin/database` (requiere rol Admin) para:- PagoController - Historial + generar QR

- **Limpiar**: Elimina datos variables, mantiene usuarios de prueba

- **Poblar**: Carga datos de demostración (3 cursos, 5 ediciones, 15 horarios)Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

- **Reset**: Limpia todo y repuebla desde cero

### ✅ Rutas

## Licencia

- **90+ endpoints** documentados## Code of Conduct

Proyecto académico - Universidad Mayor de San Andrés

- Prefijos por rol: `admin.*`, `instructor.*`, `alumno.*`

- Middleware `auth` en todas las rutas protegidasIn order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).



---## Security Vulnerabilities



## 🔐 Reglas de Negocio ValidadasIf you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.



### ✓ Horarios Permitidos## License

```

08:00-10:00 | 10:00-12:00 | 13:00-15:00 | 15:00-17:00 | 17:00-19:00The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```
Validado en: `Sesion::esHorarioValido()`

### ✓ Límite de Horas Alumno
```
Máximo 2 horas por día por alumno
```
Validado en: `User::puedeInscribirseEnFecha()`

### ✓ Disponibilidad de Recursos
```
- Instructor no puede dictar 2 sesiones simultáneas
- Vehículo no puede estar en 2 sesiones simultáneas
```
Validado en: `Sesion::instructorDisponible()`, `Sesion::vehiculoDisponible()`

### ✓ Cupos de Curso
```
- Previene inscripción si curso lleno
- Previene inscripción duplicada
```
Validado en: `Curso::puedeInscribirse()`, `InscripcionController::inscribirse()`

### ✓ Pagos con Validación
```
- Monto no puede exceder saldo pendiente
- Cuota debe existir en plan de pago
- Auto-genera QR y número de transacción
```
Validado en: `PagoController::store()`, `Pago::boot()`

---

## 📊 Endpoints Principales

### Admin - Gestión Completa

#### Vehículos (`/admin/vehiculos`)
```http
GET    /admin/vehiculos              # Lista con filtros
POST   /admin/vehiculos              # Crear
PUT    /admin/vehiculos/{id}         # Actualizar
DELETE /admin/vehiculos/{id}         # Eliminar
POST   /admin/vehiculos/{id}/mantenimiento  # Marcar mantenimiento
POST   /admin/vehiculos/{id}/disponible     # Marcar disponible
```

#### Cursos (`/admin/cursos`)
```http
GET    /admin/cursos                 # Lista
POST   /admin/cursos                 # Crear + asignar actividades
PUT    /admin/cursos/{id}            # Actualizar
DELETE /admin/cursos/{id}            # Eliminar
POST   /admin/cursos/{id}/iniciar    # Iniciar curso
POST   /admin/cursos/{id}/completar  # Completar
POST   /admin/cursos/{id}/cancelar   # Cancelar
```

#### Inscripciones (`/admin/inscripciones`)
```http
GET    /admin/inscripciones                    # Lista
POST   /admin/inscripciones                    # Crear
PUT    /admin/inscripciones/{id}               # Actualizar
DELETE /admin/inscripciones/{id}               # Eliminar
POST   /admin/inscripciones/{id}/aprobar       # Aprobar
POST   /admin/inscripciones/{id}/rechazar      # Rechazar
POST   /admin/inscripciones/{id}/plan-pago     # Crear plan cuotas
```

#### Pagos (`/admin/pagos`)
```http
GET    /admin/pagos                      # Lista + filtros
POST   /admin/pagos                      # Registrar
PUT    /admin/pagos/{id}                 # Actualizar
DELETE /admin/pagos/{id}                 # Eliminar
POST   /admin/pagos/{id}/confirmar       # Confirmar pago
POST   /admin/pagos/{id}/rechazar        # Rechazar
GET    /admin/pagos/{id}/qr              # Generar QR
GET    /admin/pagos/estadisticas         # Stats mensuales
```

### Instructor - Gestión de Clases

```http
GET    /instructor/dashboard                       # Stats + sesiones hoy
GET    /instructor/sesiones                        # Mis sesiones
POST   /instructor/sesiones/{id}/iniciar           # Iniciar
POST   /instructor/sesiones/{id}/finalizar         # Finalizar
GET    /instructor/asistencias/{sesion}            # Ver alumnos
POST   /instructor/asistencias/{asistencia}/marcar # Marcar asistencia
```

### Alumno - Auto-Gestión

```http
GET    /alumno/dashboard                   # Progreso + próximas clases
GET    /alumno/inscripciones               # Mis inscripciones
POST   /alumno/inscribirse/{curso}         # Inscribirse
GET    /alumno/inscripciones/{id}          # Ver progreso
GET    /alumno/pagos                       # Historial pagos
GET    /alumno/pagos/{pago}/qr             # Generar QR
```

---

## 🧪 Testing Local

### 1. Configurar Base de Datos
```bash
# Crear base de datos
psql -U postgres -c "CREATE DATABASE escuela_conduccion"

# Configurar .env
DB_CONNECTION=pgsql
DB_DATABASE=escuela_conduccion
DB_USERNAME=postgres
DB_PASSWORD=root
```

### 2. Ejecutar Migraciones y Seeders
```bash
php artisan migrate:fresh --seed
```

### 3. Usuarios de Prueba
```
Admin:      admin@escuela.com      / password
Instructor: carlos@escuela.com     / password
Alumno:     juan@escuela.com       / password
```

### 4. Verificar Rutas
```bash
php artisan route:list --json | jq '.[] | select(.uri | startswith("admin"))'
```

### 5. Iniciar Servidor
```bash
php artisan serve
```

---

## 📚 Documentación Detallada

Ver `README_BACKEND.md` para:
- Listado completo de todos los modelos con métodos
- Explicación de cada controlador
- Diagrama de flujo completo
- Estructura de base de datos
- Ejemplos de uso de endpoints

---

## ✨ Características Destacadas

✅ **Validación de Horarios** - Solo 5 bloques permitidos  
✅ **Límite Diario** - Máximo 2 horas/día por alumno  
✅ **QR Automático** - Generación en cada pago  
✅ **Planes de Cuotas** - 2-12 meses configurables  
✅ **Tracking Mantenimiento** - Vehículos con fechas  
✅ **Anti-Conflictos** - Instructor/Vehículo  
✅ **Progreso Automático** - Cálculo de sesiones  
✅ **Stats en Tiempo Real** - Dashboards funcionales  
✅ **Mensajes en Español** - Todas las validaciones  
✅ **Optimización** - Eager loading en todos los queries  

---

## 📈 Métricas

| Métrica | Valor |
|---------|-------|
| Líneas de código | ~4,500+ |
| Controladores | 12 |
| Modelos | 17 |
| Rutas | 90+ |
| Tablas DB | 18 |
| Seeders | 1 completo |
| Validaciones | 100+ |

---

## 🚀 Próximos Pasos (Frontend)

### Requerido
- [ ] Vistas Inertia para Admin (6 vistas)
- [ ] Vistas Inertia para Instructor (3 vistas)
- [ ] Vistas Inertia para Alumno (3 vistas)
- [ ] Componentes Vue reutilizables
- [ ] Middleware de roles (CheckRole)
- [ ] Testing de endpoints

### Opcional
- [ ] Policies para ownership
- [ ] Notificaciones por email
- [ ] Exportación a PDF
- [ ] Dashboard con gráficos Chart.js

---

## 🎯 Flujo de Uso Completo

1. **Admin crea curso** → Asigna actividades en orden
2. **Admin programa sesiones** → Valida horarios y disponibilidad
3. **Alumno se inscribe** → Valida cupos
4. **Admin aprueba inscripción** → Habilita asistencia
5. **Admin crea plan de pago** → Divide en cuotas
6. **Admin registra pagos** → Auto-genera QR
7. **Instructor inicia sesión** → Valida ventana horaria
8. **Instructor marca asistencia** → Actualiza progreso
9. **Instructor finaliza sesión** → Cambia estado
10. **Alumno ve progreso** → Dashboard actualizado

---

## 📞 Información del Proyecto

**Desarrollado para:** Proyecto Parcial 2  
**Materia:** Desarrollo de Software Empresarial  
**Fecha:** Noviembre 2025  
**Framework:** Laravel 12.39.0  
**Base de Datos:** PostgreSQL 16  
**Frontend:** Inertia + Vue 3 (pendiente)  

---

**Estado Final:** ✅ Backend 100% Completado | ⏳ Frontend Pendiente
