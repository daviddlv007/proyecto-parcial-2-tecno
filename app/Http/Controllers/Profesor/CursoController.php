<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\{Curso, Inscripcion};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CursoController extends Controller
{
    public function index()
    {
        $profesor = Auth::user();
        
        $cursos = Curso::where('instructor_id', $profesor->id)
            ->with(['tipoCurso', 'ediciones.horarios', 'ediciones.inscripciones'])
            ->get()
            ->map(function($curso) {
                // Calcular totales de inscripciones desde todas las ediciones
                $totalInscritos = $curso->ediciones->sum(function($edicion) {
                    return $edicion->inscripciones->count();
                });
                
                $totalInscritosActivos = $curso->ediciones->sum(function($edicion) {
                    return $edicion->inscripciones->where('estado_inscripcion', 'activa')->count();
                });
                
                // Capacidad real basada en ediciones (suma de cupos de todas las ediciones)
                $cupoTotalEdiciones = $curso->ediciones->sum('cupo_total');
                $capacidad = $cupoTotalEdiciones > 0 ? $cupoTotalEdiciones : $curso->capacidad_maxima;
                
                return [
                    'id' => $curso->id,
                    'nombre' => $curso->nombre,
                    'descripcion' => $curso->descripcion,
                    'tipo' => $curso->tipoCurso->nombre ?? 'N/A',
                    'capacidad_maxima' => $capacidad,
                    'estado' => $curso->estado_curso,
                    'inscritos' => $totalInscritos,
                    'inscritos_activos' => $totalInscritosActivos,
                    'ocupacion' => $capacidad > 0 
                        ? round(($totalInscritos / $capacidad) * 100) 
                        : 0,
                    'ediciones_count' => $curso->ediciones->count(),
                    'proxima_edicion' => $curso->ediciones->where('fecha_inicio', '>=', now())
                        ->sortBy('fecha_inicio')
                        ->first()?->fecha_inicio?->format('Y-m-d')
                ];
            });
        
        return Inertia::render('Profesor/Cursos/Index', [
            'cursos' => $cursos
        ]);
    }
    
    public function show(Curso $curso)
    {
        // Verificar que el curso pertenece al profesor
        if ($curso->instructor_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
        
        $curso->load(['tipoCurso', 'ediciones.horarios', 'ediciones.inscripciones.alumno', 'ediciones.inscripciones.planPago']);
        
        // Obtener todas las inscripciones de todas las ediciones de este curso
        $inscripciones = $curso->ediciones->flatMap(function($edicion) {
            return $edicion->inscripciones->map(function($inscripcion) use ($edicion) {
                return [
                    'id' => $inscripcion->id,
                    'alumno' => [
                        'id' => $inscripcion->alumno->id,
                        'nombre' => $inscripcion->alumno->nombre . ' ' . $inscripcion->alumno->apellido,
                        'email' => $inscripcion->alumno->email,
                        'telefono' => $inscripcion->alumno->telefono
                    ],
                    'fecha_inscripcion' => $inscripcion->fecha_inscripcion,
                    'fecha_inicio' => $edicion->fecha_inicio?->format('Y-m-d'),
                    'fecha_fin' => $edicion->fecha_fin?->format('Y-m-d'),
                    'edicion_id' => $edicion->id,
                    'estado' => $inscripcion->estado_inscripcion,
                    'plan_pago' => $inscripcion->planPago->nombre ?? 'N/A'
                ];
            });
        });
        
        // Agrupar ediciones con sus datos
        $ediciones = $curso->ediciones->map(function($edicion) {
            return [
                'id' => $edicion->id,
                'fecha_inicio' => $edicion->fecha_inicio?->format('Y-m-d'),
                'fecha_fin' => $edicion->fecha_fin?->format('Y-m-d'),
                'cupo_total' => $edicion->cupo_total,
                'cupo_disponible' => $edicion->cupo_disponible,
                'estado' => $edicion->estado_edicion,
                'inscritos' => $edicion->inscripciones->count(),
                'horarios' => $edicion->horarios->map(fn($h) => [
                    'dia_nombre' => ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'][$h->dia_semana],
                    'hora_inicio' => substr($h->hora_inicio, 0, 5),
                    'hora_fin' => substr($h->hora_fin, 0, 5)
                ])
            ];
        });
        
        $cursoData = [
            'id' => $curso->id,
            'nombre' => $curso->nombre,
            'descripcion' => $curso->descripcion,
            'tipo' => $curso->tipoCurso->nombre ?? 'N/A',
            'capacidad_maxima' => $curso->capacidad_maxima,
            'estado' => $curso->estado_curso,
        ];
        
        return Inertia::render('Profesor/Cursos/Show', [
            'curso' => $cursoData,
            'ediciones' => $ediciones,
            'inscripciones' => $inscripciones
        ]);
    }
}
