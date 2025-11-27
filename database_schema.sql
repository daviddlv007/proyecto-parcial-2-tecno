-- PostgreSQL Database Schema - Escuela de Conducción ACB
-- Tablas de Negocio Únicamente

-- ===========================================
-- 1. Roles
-- ===========================================
CREATE TABLE rol (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 2. Tipos de Vehículo
-- ===========================================
CREATE TABLE tipo_vehiculo (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 3. Usuarios
-- ===========================================
CREATE TABLE usuario (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    genero VARCHAR(20),
    tipo_documento VARCHAR(50),
    numero_documento VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(50),
    direccion TEXT,
    contrasena VARCHAR(255) NOT NULL,
    rol_id INTEGER NOT NULL REFERENCES rol(id),
    estado_usuario VARCHAR(20) DEFAULT 'activo',
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_usuario_email ON usuario(email);
CREATE INDEX idx_usuario_numero_documento ON usuario(numero_documento);

-- ===========================================
-- 4. Vehículos
-- ===========================================
CREATE TABLE vehiculo (
    id SERIAL PRIMARY KEY,
    placa VARCHAR(20) UNIQUE NOT NULL,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    anio INTEGER,
    tipo_vehiculo_id INTEGER REFERENCES tipo_vehiculo(id),
    estado_vehiculo VARCHAR(50),
    capacidad INTEGER,
    fecha_mantenimiento DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 5. Tipo de Curso
-- ===========================================
CREATE TABLE tipo_curso (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion_horas NUMERIC(4, 2),
    estado_curso VARCHAR(20) DEFAULT 'activo',
    nivel VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 6. Curso
-- ===========================================
CREATE TABLE curso (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo_curso_id INTEGER NOT NULL REFERENCES tipo_curso(id),
    descripcion TEXT,
    instructor_id INTEGER REFERENCES usuario(id),
    capacidad_maxima INTEGER,
    precio NUMERIC(12, 2) DEFAULT 0,
    estado_curso VARCHAR(20) DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_curso_tipo ON curso(tipo_curso_id);
CREATE INDEX idx_curso_instructor ON curso(instructor_id);

-- ===========================================
-- 7. Curso Edición
-- ===========================================
CREATE TABLE curso_edicion (
    id SERIAL PRIMARY KEY,
    curso_id INTEGER NOT NULL REFERENCES curso(id) ON DELETE CASCADE,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    cupo_total INTEGER NOT NULL,
    cupo_disponible INTEGER NOT NULL,
    precio_final NUMERIC(12, 2) NOT NULL,
    estado_edicion VARCHAR(20) DEFAULT 'programado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_curso_edicion_curso ON curso_edicion(curso_id);

-- ===========================================
-- 8. Curso Horario
-- ===========================================
CREATE TABLE curso_horario (
    id SERIAL PRIMARY KEY,
    curso_edicion_id INTEGER NOT NULL REFERENCES curso_edicion(id) ON DELETE CASCADE,
    dia_semana SMALLINT NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(curso_edicion_id, dia_semana, hora_inicio, hora_fin)
);

-- ===========================================
-- 9. Planes de Pago
-- ===========================================
CREATE TABLE plan_pago (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    numero_cuotas INTEGER NOT NULL,
    periodicidad VARCHAR(20) DEFAULT 'mensual',
    dias_intervalo INTEGER,
    dias_maximo_total INTEGER NOT NULL,
    activo BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 10. Inscripción
-- ===========================================
CREATE TABLE inscripcion (
    id SERIAL PRIMARY KEY,
    alumno_id INTEGER NOT NULL REFERENCES usuario(id),
    curso_edicion_id INTEGER NOT NULL REFERENCES curso_edicion(id) ON DELETE CASCADE,
    plan_pago_id INTEGER NOT NULL REFERENCES plan_pago(id),
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado_inscripcion VARCHAR(20) DEFAULT 'pendiente',
    monto_total NUMERIC(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_inscripcion_alumno ON inscripcion(alumno_id);
CREATE INDEX idx_inscripcion_edicion ON inscripcion(curso_edicion_id);

-- ===========================================
-- 11. Métodos de Pago
-- ===========================================
CREATE TABLE metodo_pago (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 12. Pagos
-- ===========================================
CREATE TABLE pago (
    id SERIAL PRIMARY KEY,
    alumno_id INTEGER NOT NULL REFERENCES usuario(id),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto NUMERIC(12, 2) NOT NULL,
    metodo_pago_id INTEGER REFERENCES metodo_pago(id),
    inscripcion_id INTEGER NOT NULL REFERENCES inscripcion(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_pago_alumno ON pago(alumno_id);
CREATE INDEX idx_pago_inscripcion ON pago(inscripcion_id);
CREATE INDEX idx_pago_fecha ON pago(fecha);

-- ===========================================
-- 13. Menú Dinámico
-- ===========================================
CREATE TABLE menu (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ruta VARCHAR(255),
    orden INTEGER DEFAULT 0,
    rol_id INTEGER REFERENCES rol(id) ON DELETE CASCADE,
    activo BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_menu_rol ON menu(rol_id);

-- ===========================================
-- 14. Visitas a Páginas
-- ===========================================
CREATE TABLE visita_pagina (
    id SERIAL PRIMARY KEY,
    ruta VARCHAR(255) UNIQUE NOT NULL,
    contador BIGINT DEFAULT 1,
    fecha_ultimo_acceso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_visita_pagina_contador ON visita_pagina(contador);

-- ===========================================
-- 15. Registro de Eventos
-- ===========================================
CREATE TABLE registro_evento (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuario(id) ON DELETE SET NULL,
    ruta VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_registro_evento_fecha ON registro_evento(fecha_evento);
