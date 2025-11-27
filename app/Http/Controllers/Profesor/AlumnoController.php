<?php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\{Curso, Inscripcion, Usuario};
use Illuminate\Support\Facades\{Auth, DB};
use Inertia\Inertia;

class AlumnoController extends Controller
{
    public function index()
    {
        $profesor = Auth::user();
        
        // Obtener ediciones de los cursos que imparte el profesor
        $edicionIds = \App\Models\CursoEdicion::whereHas('curso', function($q) use ($profesor) {
            $q->where('instructor_id', $profesor->id);
        })->pluck('id');

        // Obtener inscripciones vinculadas a esas ediciones
        $inscripciones = Inscripcion::whereIn('curso_edicion_id', $edicionIds)
            ->with(['alumno', 'edicion.curso', 'planPago'])
            ->get();
        
        // Agrupar por alumno
        $alumnosData = $inscripciones->groupBy('alumno_id')->map(function($inscripcionesAlumno) {
            $alumno = $inscripcionesAlumno->first()->alumno;
            $cursosInscritos = $inscripcionesAlumno->count();
            $cursosActivos = $inscripcionesAlumno->where('estado_inscripcion', 'activa')->count();
            
            return [
                'id' => $alumno->id,
                'nombre' => $alumno->nombre . ' ' . $alumno->apellido,
                'email' => $alumno->email,
                'telefono' => $alumno->telefono,
                'cursos_inscritos' => $cursosInscritos,
                'cursos_activos' => $cursosActivos,
                'cursos' => $inscripcionesAlumno->map(fn($ins) => [
                    'nombre' => $ins->edicion->curso->nombre ?? ($ins->curso?->nombre ?? 'N/A'),
                    'edicion_id' => $ins->curso_edicion_id,
                    'fecha_inicio' => $ins->edicion?->fecha_inicio?->format('Y-m-d') ?? null,
                    'fecha_fin' => $ins->edicion?->fecha_fin?->format('Y-m-d') ?? null,
                    'estado' => $ins->estado_inscripcion
                ])->values()
            ];
        })->values();
        
        return Inertia::render('Profesor/Alumnos/Index', [
            'alumnos' => $alumnosData
        ]);
    }
}
