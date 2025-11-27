<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\CursoEdicion;
use Carbon\Carbon;

class CursoEdicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener cursos existentes
        $cursos = Curso::all();

        if ($cursos->isEmpty()) {
            $this->command->warn('No hay cursos en la base de datos. Por favor, crea cursos primero.');
            return;
        }

        $this->command->info('Creando ediciones de cursos...');

        $estadosDisponibles = ['programada', 'en_curso', 'finalizada'];
        
        foreach ($cursos as $curso) {
            // Crear 3 ediciones por curso: pasada, actual y futura
            
            // Edición pasada (finalizada)
            CursoEdicion::create([
                'curso_id' => $curso->id,
                'fecha_inicio' => Carbon::now()->subMonths(6)->startOfMonth(),
                'fecha_fin' => Carbon::now()->subMonths(4)->endOfMonth(),
                'cupo_total' => 20,
                'cupo_disponible' => 0,
                'precio_final' => $curso->precio ?? 1000,
                'estado_edicion' => 'finalizada',
            ]);

            // Edición actual (en curso)
            CursoEdicion::create([
                'curso_id' => $curso->id,
                'fecha_inicio' => Carbon::now()->subDays(15),
                'fecha_fin' => Carbon::now()->addMonths(2),
                'cupo_total' => 25,
                'cupo_disponible' => rand(5, 15),
                'precio_final' => $curso->precio ?? 1200,
                'estado_edicion' => 'en_curso',
            ]);

            // Edición futura (programada)
            CursoEdicion::create([
                'curso_id' => $curso->id,
                'fecha_inicio' => Carbon::now()->addMonth()->startOfMonth(),
                'fecha_fin' => Carbon::now()->addMonths(3)->endOfMonth(),
                'cupo_total' => 30,
                'cupo_disponible' => 30,
                'precio_final' => $curso->precio ?? 1500,
                'estado_edicion' => 'programada',
            ]);

            // Edición extra futura (programada)
            CursoEdicion::create([
                'curso_id' => $curso->id,
                'fecha_inicio' => Carbon::now()->addMonths(4)->startOfMonth(),
                'fecha_fin' => Carbon::now()->addMonths(6)->endOfMonth(),
                'cupo_total' => 20,
                'cupo_disponible' => 20,
                'precio_final' => ($curso->precio ?? 1500) * 1.1, // 10% más caro
                'estado_edicion' => 'programada',
            ]);

            $this->command->info("✓ Creadas 4 ediciones para: {$curso->nombre}");
        }

        $totalEdiciones = CursoEdicion::count();
        $this->command->info("🎉 Seeder completado! Total de ediciones creadas: {$totalEdiciones}");
        
        // Mostrar resumen
        $this->command->table(
            ['Estado', 'Total'],
            [
                ['Programadas', CursoEdicion::where('estado_edicion', 'programada')->count()],
                ['En Curso', CursoEdicion::where('estado_edicion', 'en_curso')->count()],
                ['Finalizadas', CursoEdicion::where('estado_edicion', 'finalizada')->count()],
            ]
        );
    }
}
