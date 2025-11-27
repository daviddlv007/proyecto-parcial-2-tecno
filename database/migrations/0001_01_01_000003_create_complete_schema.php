<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rol
        Schema::create('rol', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // 2. Tipo de Vehículo
        Schema::create('tipo_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // 3. Usuario
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero', 20)->nullable();
            $table->string('tipo_documento', 50)->nullable();
            $table->string('numero_documento', 50)->unique()->nullable();
            $table->string('email', 100)->unique();
            $table->string('telefono', 50)->nullable();
            $table->text('direccion')->nullable();
            $table->string('contrasena', 255);
            $table->foreignId('rol_id')->constrained('rol');
            $table->string('estado_usuario', 20)->default('activo');
            $table->rememberToken();
            $table->timestamps();
        });

        // 4. Vehículo
        Schema::create('vehiculo', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 20)->unique();
            $table->string('marca', 50)->nullable();
            $table->string('modelo', 50)->nullable();
            $table->integer('anio')->nullable();
            $table->foreignId('tipo_vehiculo_id')->nullable()->constrained('tipo_vehiculo');
            $table->string('estado_vehiculo', 50)->nullable();
            $table->integer('capacidad')->nullable();
            $table->date('fecha_mantenimiento')->nullable();
            $table->timestamps();
        });

        // 5. Tipo de Curso
        Schema::create('tipo_curso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->decimal('duracion_horas', 4, 2)->nullable();
            $table->string('estado_curso', 20)->default('activo');
            $table->string('nivel', 50)->nullable();
            $table->timestamps();
        });

        // 6. Curso
        Schema::create('curso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->foreignId('tipo_curso_id')->constrained('tipo_curso');
            $table->text('descripcion')->nullable();
            $table->foreignId('instructor_id')->nullable()->constrained('usuario');
            $table->integer('capacidad_maxima')->nullable();
            $table->string('estado_curso', 20)->default('activo');
            $table->timestamps();
        });

        // 7. Curso Horario
        Schema::create('curso_horario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('curso')->onDelete('cascade');
            $table->tinyInteger('dia_semana'); // 1=Lunes, 7=Domingo
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            
            $table->unique(['curso_id', 'dia_semana', 'hora_inicio', 'hora_fin'], 'curso_horario_unique');
        });

        // 8. Plan de Pago
        Schema::create('plan_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->integer('numero_cuotas');
            $table->string('periodicidad', 20)->default('mensual');
            $table->integer('dias_intervalo')->nullable();
            $table->integer('dias_maximo_total');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 9. Inscripción
        Schema::create('inscripcion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('usuario');
            $table->foreignId('curso_id')->constrained('curso');
            $table->foreignId('plan_pago_id')->constrained('plan_pago');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->string('estado_inscripcion', 20)->default('pendiente');
            $table->decimal('monto_total', 12, 2);
            $table->timestamps();
        });

        // 10. Método de Pago
        Schema::create('metodo_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // 11. Pago
        Schema::create('pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('usuario');
            $table->timestamp('fecha')->useCurrent();
            $table->decimal('monto', 12, 2);
            $table->foreignId('metodo_pago_id')->nullable()->constrained('metodo_pago');
            $table->foreignId('inscripcion_id')->constrained('inscripcion');
            $table->timestamps();
        });

        // 12. Menú Dinámico
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('ruta', 200)->nullable();
            $table->integer('orden')->default(0);
            $table->foreignId('rol_id')->nullable()->constrained('rol');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 13. Visita de Página
        Schema::create('visita_pagina', function (Blueprint $table) {
            $table->id();
            $table->string('ruta', 255)->unique();
            $table->integer('contador')->default(1);
            $table->timestamp('fecha_ultimo_acceso')->useCurrent();
            $table->timestamps();
        });

        // 14. Registro de Evento
        Schema::create('registro_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuario');
            $table->string('ruta', 255);
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_evento')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_evento');
        Schema::dropIfExists('visita_pagina');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('pago');
        Schema::dropIfExists('metodo_pago');
        Schema::dropIfExists('inscripcion');
        Schema::dropIfExists('plan_pago');
        Schema::dropIfExists('curso_horario');
        Schema::dropIfExists('curso');
        Schema::dropIfExists('tipo_curso');
        Schema::dropIfExists('vehiculo');
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('tipo_vehiculo');
        Schema::dropIfExists('rol');
    }
};
