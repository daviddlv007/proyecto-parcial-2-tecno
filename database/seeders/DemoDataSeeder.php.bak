<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si ya existen datos demo
        $cursoCount = DB::table('curso')->count();
        $edicionCount = DB::table('curso_edicion')->count();
        
        if ($cursoCount > 0 && $edicionCount > 0) {
            $this->command->warn('⚠️  Ya existen datos en la base de datos.');
            $this->command->info('Actualizando solo estructura de horarios y ediciones...');
            $this->printSummary();
            return;
        }
        
        $this->createTestUsers();
        $this->createAdditionalUsers();
        $this->createVehicles();
        $this->createCourses();
        $this->createCourseEditions();
        $this->createSchedules();
        $this->createEnrollments();
        $this->createPayments();
        $this->printSummary();
    }

    private function createTestUsers()
    {
        $adminExists = DB::table('usuario')->where('email', 'admin@escuela.com')->exists();
        $profesorExists = DB::table('usuario')->where('email', 'profesor@escuela.com')->exists();
        $alumnoExists = DB::table('usuario')->where('email', 'alumno@escuela.com')->exists();
        
        if (!$adminExists) {
            DB::table('usuario')->insert([
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'fecha_nacimiento' => '1990-01-01',
                'genero' => 'Masculino',
                'tipo_documento' => 'CI',
                'numero_documento' => '1111111',
                'email' => 'admin@escuela.com',
                'telefono' => '099111111',
                'direccion' => 'Oficina Central',
                'contrasena' => Hash::make('password'),
                'rol_id' => 1,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        if (!$profesorExists) {
            DB::table('usuario')->insert([
                'nombre' => 'Ana',
                'apellido' => 'Profesor',
                'fecha_nacimiento' => '1985-05-15',
                'genero' => 'Femenino',
                'tipo_documento' => 'CI',
                'numero_documento' => '2222222',
                'email' => 'profesor@escuela.com',
                'telefono' => '099222222',
                'direccion' => 'Av. Instructores 123',
                'contrasena' => Hash::make('password'),
                'rol_id' => 2,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        if (!$alumnoExists) {
            DB::table('usuario')->insert([
                'nombre' => 'Juan',
                'apellido' => 'Alumno',
                'fecha_nacimiento' => '2000-03-20',
                'genero' => 'Masculino',
                'tipo_documento' => 'CI',
                'numero_documento' => '3333333',
                'email' => 'alumno@escuela.com',
                'telefono' => '099333333',
                'direccion' => 'Calle Estudiantes 456',
                'contrasena' => Hash::make('password'),
                'rol_id' => 3,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function createAdditionalUsers()
    {
        // Solo crear usuarios adicionales si no existen
        $existingCount = DB::table('usuario')->where('email', 'LIKE', 'alumno%@test.com')->count();
        if ($existingCount > 0) {
            $this->command->info('Usuarios adicionales ya existen, saltando creación...');
            return;
        }
        
        $alumnos = [];
        for ($i = 1; $i <= 50; $i++) {
            $alumnos[] = [
                'nombre' => 'Alumno' . $i,
                'apellido' => 'Apellido' . $i,
                'fecha_nacimiento' => Carbon::now()->subYears(rand(18, 50))->format('Y-m-d'),
                'genero' => rand(0, 1) ? 'Masculino' : 'Femenino',
                'tipo_documento' => 'CI',
                'numero_documento' => '1' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'email' => 'alumno' . $i . '@test.com',
                'telefono' => '777' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'direccion' => 'Dirección #' . $i,
                'contrasena' => bcrypt('password'),
                'rol_id' => 3,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('usuario')->insert($alumnos);

        $profesores = [];
        for ($i = 1; $i <= 5; $i++) {
            $profesores[] = [
                'nombre' => 'Profesor' . $i,
                'apellido' => 'Instructor' . $i,
                'fecha_nacimiento' => Carbon::now()->subYears(rand(25, 55))->format('Y-m-d'),
                'genero' => rand(0, 1) ? 'Masculino' : 'Femenino',
                'tipo_documento' => 'CI',
                'numero_documento' => '2' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'email' => 'profesor' . $i . '@test.com',
                'telefono' => '766' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'direccion' => 'Av. Instructor #' . $i,
                'contrasena' => bcrypt('password'),
                'rol_id' => 2,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('usuario')->insert($profesores);
    }

    private function createVehicles()
    {
        if (DB::table('vehiculo')->count() > 0) {
            $this->command->info('Vehículos ya existen, saltando creación...');
            return;
        }
        
        $marcas = ['Toyota', 'Honda', 'Mazda', 'Nissan', 'Suzuki'];
        $modelos = ['Corolla', 'Civic', 'CX-5', 'Sentra', 'Swift'];
        $vehiculos = [];
        
        for ($i = 1; $i <= 15; $i++) {
            $vehiculos[] = [
                'placa' => 'ABC' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'marca' => $marcas[array_rand($marcas)],
                'modelo' => $modelos[array_rand($modelos)],
                'anio' => rand(2015, 2024),
                'tipo_vehiculo_id' => rand(1, 3),
                'estado_vehiculo' => ['disponible', 'en uso', 'en mantenimiento'][rand(0, 2)],
                'capacidad' => rand(2, 5),
                'fecha_mantenimiento' => Carbon::now()->addDays(rand(1, 90))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        DB::table('vehiculo')->insert($vehiculos);
    }

    private function createCourses()
    {
        if (DB::table('curso')->count() > 0) {
            $this->command->info('Cursos ya existen, saltando creación...');
            return;
        }
        
        $instructorIds = DB::table('usuario')->where('rol_id', 2)->pluck('id')->toArray();
        $tipoCursoIds = DB::table('tipo_curso')->pluck('id')->toArray();
        $cursos = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $cursos[] = [
                'nombre' => 'Curso Grupo ' . chr(64 + $i),
                'tipo_curso_id' => $tipoCursoIds[array_rand($tipoCursoIds)],
                'descripcion' => 'Curso de conducción grupo ' . $i,
                'instructor_id' => $instructorIds[array_rand($instructorIds)],
                'estado_curso' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        DB::table('curso')->insert($cursos);
    }

    private function createCourseEditions()
    {
        $cursoIds = DB::table('curso')->pluck('id')->toArray();
        $ediciones = [];
        
        foreach ($cursoIds as $cursoId) {
            $numEdiciones = rand(1, 2);
            for ($j = 0; $j < $numEdiciones; $j++) {
                $fechaInicio = Carbon::now()->addDays(rand(-60, 90));
                $cupoTotal = rand(20, 30);
                $ediciones[] = [
                    'curso_id' => $cursoId,
                    'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                    'fecha_fin' => $fechaInicio->copy()->addMonths(3)->format('Y-m-d'),
                    'cupo_total' => $cupoTotal,
                    'cupo_disponible' => rand(10, $cupoTotal),
                    'precio_final' => rand(1000, 3000),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        DB::table('curso_edicion')->insert($ediciones);
    }

    private function createSchedules()
    {
        $edicionIds = DB::table('curso_edicion')->pluck('id')->toArray();
        $horarios = [];
        
        foreach ($edicionIds as $edicionId) {
            $edicion = DB::table('curso_edicion')->find($edicionId);
            for ($j = 0; $j < 2; $j++) {
                $horaInicio = rand(8, 16);
                $horarios[] = [
                    'curso_id' => $edicion->curso_id,
                    'curso_edicion_id' => $edicionId,
                    'dia_semana' => rand(1, 5),
                    'hora_inicio' => sprintf('%02d:00:00', $horaInicio),
                    'hora_fin' => sprintf('%02d:00:00', $horaInicio + rand(2, 4)),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        DB::table('curso_horario')->insert($horarios);
    }

    private function createEnrollments()
    {
        $alumnoIds = DB::table('usuario')->where('rol_id', 3)->pluck('id')->toArray();
        $edicionIds = DB::table('curso_edicion')->pluck('id')->toArray();
        $planIds = DB::table('plan_pago')->pluck('id')->toArray();
        $inscripciones = [];
        
        for ($i = 0; $i < 60; $i++) {
            $edicionId = $edicionIds[array_rand($edicionIds)];
            $edicion = DB::table('curso_edicion')->find($edicionId);
            $inicio = Carbon::parse($edicion->fecha_inicio);
            
            $inscripciones[] = [
                'alumno_id' => $alumnoIds[array_rand($alumnoIds)],
                'curso_edicion_id' => $edicionId,
                'plan_pago_id' => $planIds[array_rand($planIds)],
                'fecha_inicio' => $inicio->format('Y-m-d'),
                'fecha_fin' => Carbon::parse($edicion->fecha_fin)->format('Y-m-d'),
                'fecha_inscripcion' => $inicio->copy()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
                'estado_inscripcion' => ['pendiente', 'activa', 'completada'][rand(0, 2)],
                'monto_total' => $edicion->precio_final,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        $insertedIds = [];
        foreach ($inscripciones as $inscripcion) {
            $id = DB::table('inscripcion')->insertGetId($inscripcion);
            $insertedIds[] = $id;
            DB::table('curso_edicion')
                ->where('id', $inscripcion['curso_edicion_id'])
                ->decrement('cupo_disponible');
        }
    }

    private function createPayments()
    {
        $inscripcionIds = DB::table('inscripcion')->pluck('id')->toArray();
        $metodoIds = DB::table('metodo_pago')->pluck('id')->toArray();
        $pagos = [];
        
        for ($i = 0; $i < 120; $i++) {
            $inscripcionId = $inscripcionIds[array_rand($inscripcionIds)];
            $inscripcion = DB::table('inscripcion')->find($inscripcionId);
            
            $pagos[] = [
                'alumno_id' => $inscripcion->alumno_id,
                'fecha' => Carbon::now()->subDays(rand(0, 60))->format('Y-m-d H:i:s'),
                'monto' => rand(100, 500),
                'metodo_pago_id' => $metodoIds[array_rand($metodoIds)],
                'inscripcion_id' => $inscripcionId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        DB::table('pago')->insert($pagos);
    }

    private function printSummary()
    {
        $stats = [
            'usuarios' => DB::table('usuario')->count(),
            'vehiculos' => DB::table('vehiculo')->count(),
            'cursos' => DB::table('curso')->count(),
            'ediciones' => DB::table('curso_edicion')->count(),
            'horarios' => DB::table('curso_horario')->count(),
            'inscripciones' => DB::table('inscripcion')->count(),
            'pagos' => DB::table('pago')->count(),
        ];
        
        $this->command->info('');
        $this->command->info('✅ Datos de demostración creados:');
        $this->command->info('   👥 Usuarios: ' . $stats['usuarios'] . ' (3 de prueba + 55 adicionales)');
        $this->command->info('   🚗 Vehículos: ' . $stats['vehiculos']);
        $this->command->info('   📚 Cursos: ' . $stats['cursos']);
        $this->command->info('   📅 Ediciones: ' . $stats['ediciones']);
        $this->command->info('   ⏰ Horarios: ' . $stats['horarios']);
        $this->command->info('   📝 Inscripciones: ' . $stats['inscripciones']);
        $this->command->info('   💰 Pagos: ' . $stats['pagos']);
        $this->command->info('   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('   📊 Total: ' . array_sum($stats) . ' registros');
        $this->command->info('');
        $this->command->info('🔑 Credenciales de prueba:');
        $this->command->info('   Admin:    admin@escuela.com / password');
        $this->command->info('   Profesor: profesor@escuela.com / password');
        $this->command->info('   Alumno:   alumno@escuela.com / password');
        $this->command->info('');
    }
}
