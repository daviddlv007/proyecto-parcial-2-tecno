<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menu')->truncate();
        
        $menus = [
            // Admin (rol_id = 1)
            ['nombre' => 'Dashboard', 'ruta' => '/admin/dashboard', 'orden' => 1, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Usuarios', 'ruta' => '/admin/usuarios', 'orden' => 2, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Roles', 'ruta' => '/admin/roles', 'orden' => 3, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Menús', 'ruta' => '/admin/menus', 'orden' => 4, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Tipos Vehículo', 'ruta' => '/admin/tipos-vehiculo', 'orden' => 5, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Vehículos', 'ruta' => '/admin/vehiculos', 'orden' => 6, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Tipos Curso', 'ruta' => '/admin/tipos-curso', 'orden' => 7, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Cursos', 'ruta' => '/admin/cursos', 'orden' => 8, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Ediciones Cursos', 'ruta' => '/admin/curso-ediciones', 'orden' => 9, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Inscripciones', 'ruta' => '/admin/inscripciones', 'orden' => 10, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Pagos', 'ruta' => '/admin/pagos', 'orden' => 11, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Planes Pago', 'ruta' => '/admin/planes-pago', 'orden' => 12, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Métodos Pago', 'ruta' => '/admin/metodos-pago', 'orden' => 13, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Eventos Sistema', 'ruta' => '/admin/eventos', 'orden' => 14, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Visitas Páginas', 'ruta' => '/admin/visitas', 'orden' => 15, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Admin BD', 'ruta' => '/admin/database', 'orden' => 16, 'rol_id' => 1, 'activo' => true],

            // Profesor (rol_id = 2)
            ['nombre' => 'Dashboard', 'ruta' => '/profesor/dashboard', 'orden' => 1, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mis Cursos', 'ruta' => '/profesor/cursos', 'orden' => 2, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mis Alumnos', 'ruta' => '/profesor/alumnos', 'orden' => 3, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mi Horario', 'ruta' => '/profesor/horarios', 'orden' => 4, 'rol_id' => 2, 'activo' => true],

            // Alumno (rol_id = 3)
            ['nombre' => 'Dashboard', 'ruta' => '/alumno/dashboard', 'orden' => 1, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Cursos Disponibles', 'ruta' => '/alumno/cursos-disponibles', 'orden' => 2, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Cursos', 'ruta' => '/alumno/mis-cursos', 'orden' => 3, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Planes de Pago', 'ruta' => '/alumno/planes-pago', 'orden' => 4, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Pagos', 'ruta' => '/alumno/pagos', 'orden' => 5, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mi Horario', 'ruta' => '/alumno/horarios', 'orden' => 6, 'rol_id' => 3, 'activo' => true],
        ];

        foreach ($menus as $menu) {
            DB::table('menu')->insert(array_merge($menu, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Menús creados exitosamente: ' . count($menus));
    }
}
