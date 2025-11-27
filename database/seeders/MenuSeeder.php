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
            ['nombre' => 'Dashboard', 'ruta' => '/admin/dashboard', 'icono' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'orden' => 1, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Usuarios', 'ruta' => '/admin/usuarios', 'icono' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'orden' => 2, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Roles', 'ruta' => '/admin/roles', 'icono' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'orden' => 3, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Menús', 'ruta' => '/admin/menus', 'icono' => 'M4 6h16M4 12h16M4 18h16', 'orden' => 4, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Tipos Vehículo', 'ruta' => '/admin/tipos-vehiculo', 'icono' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'orden' => 5, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Vehículos', 'ruta' => '/admin/vehiculos', 'icono' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'orden' => 6, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Tipos Curso', 'ruta' => '/admin/tipos-curso', 'icono' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'orden' => 7, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Cursos', 'ruta' => '/admin/cursos', 'icono' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'orden' => 8, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Ediciones Cursos', 'ruta' => '/admin/curso-ediciones', 'icono' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'orden' => 9, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Inscripciones', 'ruta' => '/admin/inscripciones', 'icono' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'orden' => 10, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Pagos', 'ruta' => '/admin/pagos', 'icono' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'orden' => 11, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Planes Pago', 'ruta' => '/admin/planes-pago', 'icono' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'orden' => 12, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Métodos Pago', 'ruta' => '/admin/metodos-pago', 'icono' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'orden' => 13, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Eventos Sistema', 'ruta' => '/admin/eventos', 'icono' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'orden' => 14, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Visitas Páginas', 'ruta' => '/admin/visitas', 'icono' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'orden' => 15, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Admin BD', 'ruta' => '/admin/database', 'icono' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4', 'orden' => 16, 'rol_id' => 1, 'activo' => true],

            // Profesor (rol_id = 2)
            ['nombre' => 'Dashboard', 'ruta' => '/profesor/dashboard', 'icono' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'orden' => 1, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mis Cursos', 'ruta' => '/profesor/cursos', 'icono' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'orden' => 2, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mis Alumnos', 'ruta' => '/profesor/alumnos', 'icono' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'orden' => 3, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mi Horario', 'ruta' => '/profesor/horarios', 'icono' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'orden' => 4, 'rol_id' => 2, 'activo' => true],

            // Alumno (rol_id = 3)
            ['nombre' => 'Dashboard', 'ruta' => '/alumno/dashboard', 'icono' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'orden' => 1, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Cursos Disponibles', 'ruta' => '/alumno/cursos-disponibles', 'icono' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'orden' => 2, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Cursos', 'ruta' => '/alumno/mis-cursos', 'icono' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'orden' => 3, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Planes de Pago', 'ruta' => '/alumno/planes-pago', 'icono' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'orden' => 4, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Pagos', 'ruta' => '/alumno/pagos', 'icono' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'orden' => 5, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mi Horario', 'ruta' => '/alumno/horarios', 'icono' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'orden' => 6, 'rol_id' => 3, 'activo' => true],
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
