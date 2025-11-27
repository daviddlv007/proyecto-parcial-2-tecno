<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoVehiculo;
use App\Models\TipoCurso;
use App\Models\Menu;
use App\Models\Rol;

class NuevasFuncionalidadesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener rol de administrador
        $rolAdmin = Rol::where('nombre', 'administrador')->first();

        // Seed Tipos de Vehículo
        $tiposVehiculo = [
            ['nombre' => 'Automóvil', 'descripcion' => 'Vehículo estándar de 4 ruedas'],
            ['nombre' => 'Motocicleta', 'descripcion' => 'Vehículo de 2 ruedas'],
            ['nombre' => 'Camioneta', 'descripcion' => 'Vehículo de carga ligera'],
        ];

        foreach ($tiposVehiculo as $tipo) {
            TipoVehiculo::updateOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }

        // Seed Tipos de Curso
        $tiposCurso = [
            [
                'nombre' => 'Curso Básico',
                'descripcion' => 'Curso de conducción básica para principiantes',
                'duracion_horas' => 30,
                'nivel' => 'principiante',
                'estado_curso' => 'activo'
            ],
            [
                'nombre' => 'Curso Avanzado',
                'descripcion' => 'Curso de manejo defensivo y técnicas avanzadas',
                'duracion_horas' => 20,
                'nivel' => 'avanzado',
                'estado_curso' => 'activo'
            ],
            [
                'nombre' => 'Curso de Motocicleta',
                'descripcion' => 'Conducción de motocicletas de diferentes cilindrajes',
                'duracion_horas' => 25,
                'nivel' => 'intermedio',
                'estado_curso' => 'activo'
            ],
        ];

        foreach ($tiposCurso as $tipo) {
            TipoCurso::updateOrCreate(['nombre' => $tipo['nombre']], $tipo);
        }

        // Seed Menús
        $menus = [
            ['nombre' => 'Dashboard', 'ruta' => '/admin/dashboard', 'orden' => 1, 'rol_id' => $rolAdmin->id],
            ['nombre' => 'Usuarios', 'ruta' => '/admin/usuarios', 'orden' => 2, 'rol_id' => $rolAdmin->id],
            ['nombre' => 'Vehículos', 'ruta' => '/admin/vehiculos', 'orden' => 3, 'rol_id' => $rolAdmin->id],
            ['nombre' => 'Cursos', 'ruta' => '/admin/cursos', 'orden' => 4, 'rol_id' => $rolAdmin->id],
            ['nombre' => 'Reportes', 'ruta' => '/admin/reportes', 'orden' => 5, 'rol_id' => $rolAdmin->id],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['nombre' => $menu['nombre'], 'rol_id' => $menu['rol_id']], 
                $menu
            );
        }

        $this->command->info('✓ Datos de prueba creados exitosamente');
        $this->command->info('  - ' . count($tiposVehiculo) . ' tipos de vehículo');
        $this->command->info('  - ' . count($tiposCurso) . ' tipos de curso');
        $this->command->info('  - ' . count($menus) . ' menús');
    }
}
