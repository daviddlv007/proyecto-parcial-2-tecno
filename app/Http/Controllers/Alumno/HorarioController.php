<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HorarioController extends Controller
{
    public function index()
    {
        $alumno = Auth::user();
        
        $inscripciones = Inscripcion::where('alumno_id', $alumno->id)
            ->where('estado_inscripcion', 'activa')
            ->with(['edicion.curso', 'edicion.horarios'])
            ->get();
        
        // Organizar horarios por día de la semana
        $horariosPorDia = [];
        for ($dia = 1; $dia <= 7; $dia++) {
            $horariosPorDia[$dia] = [];
        }
        
        foreach ($inscripciones as $inscripcion) {
            $edicion = $inscripcion->edicion;
            $curso = $edicion ? $edicion->curso : $inscripcion->curso;
            $horarios = $edicion ? $edicion->horarios : ($inscripcion->curso->horarios ?? collect());
            
            foreach ($horarios as $horario) {
                $horariosPorDia[$horario->dia_semana][] = [
                    'curso' => $curso->nombre,
                    'edicion_id' => $edicion ? $edicion->id : null,
                    'fecha_inicio' => $edicion ? $edicion->fecha_inicio->format('Y-m-d') : null,
                    'fecha_fin' => $edicion ? $edicion->fecha_fin->format('Y-m-d') : null,
                    'hora_inicio' => substr($horario->hora_inicio, 0, 5),
                    'hora_fin' => substr($horario->hora_fin, 0, 5),
                    'curso_id' => $curso->id,
                ];
            }
        }
        
        // Ordenar horarios por hora de inicio en cada día
        foreach ($horariosPorDia as $dia => $horarios) {
            usort($horariosPorDia[$dia], fn($a, $b) => strcmp($a['hora_inicio'], $b['hora_inicio']));
        }
        
        return Inertia::render('Alumno/Horarios/Index', compact('horariosPorDia'));
    }
}
