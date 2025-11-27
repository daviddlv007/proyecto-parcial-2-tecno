<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ROLES (3 roles)
        if (DB::table('rol')->count() === 0) {
            DB::table('rol')->insert([
                ['nombre' => 'administrador', 'descripcion' => 'Acceso completo al sistema', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'profesor', 'descripcion' => 'Instructor de cursos', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'alumno', 'descripcion' => 'Estudiante inscrito', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. USUARIOS (5 usuarios: 3 de presentación + 1 profesor + 1 alumno)
        if (!DB::table('usuario')->where('email', 'admin@escuela.com')->exists()) {
            DB::table('usuario')->insert([
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'fecha_nacimiento' => '1990-01-01',
                'genero' => 'Masculino',
                'tipo_documento' => 'CI',
                'numero_documento' => '1111111',
                'email' => 'admin@escuela.com',
                'telefono' => '70000001',
                'direccion' => 'Av. 6 de Agosto',
                'contrasena' => Hash::make('password'),
                'rol_id' => 1,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (!DB::table('usuario')->where('email', 'profesor@escuela.com')->exists()) {
            DB::table('usuario')->insert([
                'nombre' => 'Carlos',
                'apellido' => 'Profesor',
                'fecha_nacimiento' => '1985-05-15',
                'genero' => 'Masculino',
                'tipo_documento' => 'CI',
                'numero_documento' => '2222222',
                'email' => 'profesor@escuela.com',
                'telefono' => '70000002',
                'direccion' => 'Calle Instructores 123',
                'contrasena' => Hash::make('password'),
                'rol_id' => 2,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (!DB::table('usuario')->where('email', 'alumno@escuela.com')->exists()) {
            DB::table('usuario')->insert([
                'nombre' => 'Juan',
                'apellido' => 'Alumno',
                'fecha_nacimiento' => '2000-03-20',
                'genero' => 'Masculino',
                'tipo_documento' => 'CI',
                'numero_documento' => '3333333',
                'email' => 'alumno@escuela.com',
                'telefono' => '70000003',
                'direccion' => 'Zona Sur',
                'contrasena' => Hash::make('password'),
                'rol_id' => 3,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Usuario adicional profesor
        if (!DB::table('usuario')->where('email', 'maria.lopez@escuela.com')->exists()) {
            DB::table('usuario')->insert([
                'nombre' => 'María',
                'apellido' => 'López',
                'fecha_nacimiento' => '1988-08-10',
                'genero' => 'Femenino',
                'tipo_documento' => 'CI',
                'numero_documento' => '4444444',
                'email' => 'maria.lopez@escuela.com',
                'telefono' => '70000004',
                'direccion' => 'Sopocachi',
                'contrasena' => Hash::make('password'),
                'rol_id' => 2,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Usuario adicional alumno
        if (!DB::table('usuario')->where('email', 'ana.perez@escuela.com')->exists()) {
            DB::table('usuario')->insert([
                'nombre' => 'Ana',
                'apellido' => 'Pérez',
                'fecha_nacimiento' => '2001-11-25',
                'genero' => 'Femenino',
                'tipo_documento' => 'CI',
                'numero_documento' => '5555555',
                'email' => 'ana.perez@escuela.com',
                'telefono' => '70000005',
                'direccion' => 'Miraflores',
                'contrasena' => Hash::make('password'),
                'rol_id' => 3,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 3. TIPOS DE VEHÍCULO (2 tipos)
        if (DB::table('tipo_vehiculo')->count() === 0) {
            DB::table('tipo_vehiculo')->insert([
                ['nombre' => 'Automóvil', 'descripcion' => 'Vehículo particular de 4 ruedas', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Motocicleta', 'descripcion' => 'Vehículo de 2 ruedas', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 4. VEHÍCULOS (3 vehículos)
        if (DB::table('vehiculo')->count() === 0) {
            DB::table('vehiculo')->insert([
                [
                    'placa' => 'ABC-123',
                    'marca' => 'Toyota',
                    'modelo' => 'Corolla',
                    'anio' => 2020,
                    'tipo_vehiculo_id' => 1,
                    'estado_vehiculo' => 'disponible',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'placa' => 'DEF-456',
                    'marca' => 'Suzuki',
                    'modelo' => 'Swift',
                    'anio' => 2019,
                    'tipo_vehiculo_id' => 1,
                    'estado_vehiculo' => 'disponible',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'placa' => 'GHI-789',
                    'marca' => 'Honda',
                    'modelo' => 'CB 190',
                    'anio' => 2021,
                    'tipo_vehiculo_id' => 2,
                    'estado_vehiculo' => 'disponible',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }

        // 5. TIPOS DE CURSO (1 tipo)
        if (DB::table('tipo_curso')->count() === 0) {
            DB::table('tipo_curso')->insert([
                'nombre' => 'Conducción Básica',
                'descripcion' => 'Curso fundamental de conducción para principiantes',
                'duracion_horas' => 40,
                'nivel' => 'Básico',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 10. PLANES DE PAGO (3 planes)
        if (DB::table('plan_pago')->count() === 0) {
            DB::table('plan_pago')->insert([
                ['nombre' => '1 Cuota', 'numero_cuotas' => 1, 'dias_intervalo' => 0, 'dias_maximo_total' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => '3 Cuotas', 'numero_cuotas' => 3, 'dias_intervalo' => 30, 'dias_maximo_total' => 90, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => '7 Cuotas', 'numero_cuotas' => 7, 'dias_intervalo' => 30, 'dias_maximo_total' => 210, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 11. MÉTODOS DE PAGO (3 métodos)
        if (DB::table('metodo_pago')->count() === 0) {
            DB::table('metodo_pago')->insert([
                ['nombre' => 'QR', 'descripcion' => 'Pago mediante código QR', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Tarjeta', 'descripcion' => 'Tarjeta de crédito/débito', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Efectivo', 'descripcion' => 'Pago en efectivo', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 12. MENÚS (se llama al MenuSeeder separado para los 26 menús)
        $this->call(MenuSeeder::class);
    }
}
