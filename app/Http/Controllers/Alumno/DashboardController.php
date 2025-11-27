<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $alumno = Auth::user();
        
        // Estadísticas del alumno
        $inscripciones = Inscripcion::where('alumno_id', $alumno->id)
            ->with(['edicion.curso.tipo', 'edicion.horarios', 'planPago'])
            ->get();
        
        $stats = [
            'cursos_activos' => $inscripciones->where('estado_inscripcion', 'activa')->count(),
            'cursos_completados' => $inscripciones->where('estado_inscripcion', 'completado')->count(),
            'saldo_pendiente' => number_format($inscripciones->sum(function($inscripcion) {
                return $inscripcion->getSaldoPendiente();
            }), 2),
        ];
        
        // Cursos actuales del alumno
        $misCursos = $inscripciones->where('estado_inscripcion', 'activa')->map(function($inscripcion) {
            $edicion = $inscripcion->edicion;
            $curso = $edicion ? $edicion->curso : $inscripcion->curso;
            
            return [
                'id' => $curso->id,
                'edicion_id' => $edicion ? $edicion->id : null,
                'nombre' => $curso->nombre,
                'tipo' => optional($curso->tipo)->nombre ?? 'N/A',
                'estado' => $inscripcion->estado_inscripcion,
                'fecha_inicio' => $edicion ? $edicion->fecha_inicio->format('Y-m-d') : null,
                'fecha_fin' => $edicion ? $edicion->fecha_fin->format('Y-m-d') : null,
                'progreso' => 0, // Placeholder para futuro tracking
                'inscripcion_id' => $inscripcion->id,
            ];
        })->values();
        
        // Próximas clases (basado en horarios de ediciones)
        $proximasClases = [];
        foreach ($inscripciones->where('estado_inscripcion', 'activa') as $inscripcion) {
            $edicion = $inscripcion->edicion;
            $curso = $edicion ? $edicion->curso : $inscripcion->curso;
            $horarios = $edicion ? $edicion->horarios : ($inscripcion->curso->horarios ?? collect());
            
            if ($horarios->isNotEmpty()) {
                $proximasClases[] = [
                    'curso' => $curso->nombre,
                    'edicion_id' => $edicion ? $edicion->id : null,
                    'horarios' => $horarios->map(fn($h) => [
                        'dia' => $h->dia_semana,
                        'hora_inicio' => substr($h->hora_inicio, 0, 5),
                        'hora_fin' => substr($h->hora_fin, 0, 5),
                    ])->take(3)->values()
                ];
            }
        }
        
        return Inertia::render('Alumno/Dashboard', compact('stats', 'misCursos', 'proximasClases'));
    }
}
