<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\{Curso, Inscripcion, Usuario};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $profesor = Auth::user();
        
        // Cursos del profesor (con ediciones y tipo)
        $cursos = Curso::where('instructor_id', $profesor->id)
            ->where('estado_curso', 'activo')
            ->with(['ediciones.horarios', 'ediciones.inscripciones', 'tipoCurso'])
            ->get();

        // Recolectar IDs de edicion para estadísticas
        $edicionIds = $cursos->flatMap(fn($c) => $c->ediciones->pluck('id'))->unique()->values();

        // Estadísticas usando ediciones
        $stats = [
            'cursos_activos' => $cursos->count(),
            'total_alumnos' => Inscripcion::whereIn('curso_edicion_id', $edicionIds)
                ->distinct('alumno_id')
                ->count(),
            'inscripciones_activas' => Inscripcion::whereIn('curso_edicion_id', $edicionIds)
                ->where('estado_inscripcion', 'activa')
                ->count()
        ];

        // Próximas clases (hoy y mañana) — obtener desde horarios de ediciones
        $hoy = Carbon::now()->dayOfWeek ?: 7;
        $manana = Carbon::now()->addDay()->dayOfWeek ?: 7;

        $proximasClases = $cursos->map(function($curso) use ($hoy, $manana) {
            $horariosHoy = collect();
            $horariosMana = collect();

            foreach ($curso->ediciones as $edicion) {
                foreach ($edicion->horarios as $h) {
                    if ($h->dia_semana == $hoy) {
                        $horariosHoy->push([ 'edicion' => $edicion, 'hora' => $h ]);
                    }
                    if ($h->dia_semana == $manana) {
                        $horariosMana->push([ 'edicion' => $edicion, 'hora' => $h ]);
                    }
                }
            }

            return [
                'curso' => $curso,
                'hoy' => $horariosHoy->map(fn($item) => [
                    'hora_inicio' => substr($item['hora']->hora_inicio, 0, 5),
                    'hora_fin' => substr($item['hora']->hora_fin, 0, 5),
                    'edicion_id' => $item['edicion']->id
                ])->values(),
                'manana' => $horariosMana->map(fn($item) => [
                    'hora_inicio' => substr($item['hora']->hora_inicio, 0, 5),
                    'hora_fin' => substr($item['hora']->hora_fin, 0, 5),
                    'edicion_id' => $item['edicion']->id
                ])->values()
            ];
        })->filter(fn($item) => $item['hoy']->count() > 0 || $item['manana']->count() > 0)->values();

        // Cursos recientes con más detalles (inscritos sumando ediciones)
        $cursosRecientes = $cursos->take(5)->map(function($curso) {
            $inscritos = $curso->ediciones->sum(fn($e) => $e->inscripciones->count());
            $cupoTotalEdiciones = $curso->ediciones->sum('cupo_total');
            $capacidad = $cupoTotalEdiciones > 0 ? $cupoTotalEdiciones : $curso->capacidad_maxima;
            
            return [
                'id' => $curso->id,
                'nombre' => $curso->nombre,
                'tipo' => $curso->tipoCurso->nombre ?? 'N/A',
                'capacidad_maxima' => $capacidad,
                'inscritos' => $inscritos,
                'ocupacion' => $capacidad > 0 
                    ? round(($inscritos / $capacidad) * 100) 
                    : 0
            ];
        });
        
        return Inertia::render('Profesor/Dashboard', [
            'stats' => $stats,
            'proximasClases' => $proximasClases,
            'cursosRecientes' => $cursosRecientes
        ]);
    }
}
