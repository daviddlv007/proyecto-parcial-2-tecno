<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class HorarioController extends Controller
{
    public function index()
    {
        $profesor = Auth::user();
        
        // Obtener ediciones de los cursos que imparte el profesor
        $ediciones = \App\Models\CursoEdicion::whereHas('curso', function($q) use ($profesor) {
            $q->where('instructor_id', $profesor->id);
        })->with('horarios', 'curso')->get();

        // Organizar horarios por día de semana (1..7)
        $horariosSemana = [];
        for ($i = 1; $i <= 7; $i++) {
            $horariosSemana[$i] = [];
        }

        foreach ($ediciones as $edicion) {
            foreach ($edicion->horarios as $horario) {
                $horariosSemana[$horario->dia_semana][] = [
                    'curso_edicion_id' => $edicion->id,
                    'curso_id' => $edicion->curso->id,
                    'curso_nombre' => $edicion->curso->nombre,
                    'fecha_inicio' => $edicion->fecha_inicio?->format('Y-m-d'),
                    'fecha_fin' => $edicion->fecha_fin?->format('Y-m-d'),
                    'hora_inicio' => substr($horario->hora_inicio, 0, 5),
                    'hora_fin' => substr($horario->hora_fin, 0, 5),
                    'duracion' => $this->calcularDuracion($horario->hora_inicio, $horario->hora_fin)
                ];
            }
        }
        
        // Ordenar por hora de inicio
        foreach ($horariosSemana as $dia => $horarios) {
            usort($horariosSemana[$dia], function($a, $b) {
                return strcmp($a['hora_inicio'], $b['hora_inicio']);
            });
        }
        
        $diasNombres = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        
        return Inertia::render('Profesor/Horarios/Index', [
            'horariosSemana' => $horariosSemana,
            'diasNombres' => $diasNombres,
            'totalClases' => collect($horariosSemana)->flatten(1)->count()
        ]);
    }
    
    private function calcularDuracion($inicio, $fin)
    {
        $start = Carbon::parse($inicio);
        $end = Carbon::parse($fin);
        $diff = $start->diffInMinutes($end);
        
        $horas = floor($diff / 60);
        $minutos = $diff % 60;
        
        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}m";
        } elseif ($horas > 0) {
            return "{$horas}h";
        } else {
            return "{$minutos}m";
        }
    }
}
