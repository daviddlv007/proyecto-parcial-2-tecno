<?php

namespace App\Http\Controllers\Alumno;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Curso;
use App\Models\PlanPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CursoController extends Controller
{
    public function index()
    {
        $alumno = Auth::user();
        // Cargar inscripciones junto a la edición y el curso
        $inscripciones = Inscripcion::where('alumno_id', $alumno->id)
            ->with(['edicion.curso.tipo', 'edicion.horarios', 'planPago'])
            ->orderBy('created_at', 'desc')
            ->get();

        $cursos = $inscripciones->map(function($inscripcion) {
            $edicion = $inscripcion->edicion;
            $curso = $edicion ? $edicion->curso : $inscripcion->curso;

            return [
                'id' => $curso->id,
                'nombre' => $curso->nombre,
                'tipo' => optional($curso->tipo)->nombre ?? 'N/A',
                'descripcion' => $curso->descripcion ?? '',
                'estado' => $inscripcion->estado_inscripcion,
                'fecha_inicio' => $edicion->fecha_inicio ?? null,
                'fecha_fin' => $edicion->fecha_fin ?? null,
                'monto_total' => $inscripcion->monto_total,
                'saldo_pendiente' => $inscripcion->getSaldoPendiente(),
                'inscripcion_id' => $inscripcion->id,
            ];
        });

        return Inertia::render('Alumno/Cursos/Index', compact('cursos'));
    }
    
    public function disponibles()
    {
        $alumno = Auth::user();
        
        // Obtener ediciones disponibles (esas son las ofertas reales con fechas y cupos)
        $edicionesInscritas = Inscripcion::where('alumno_id', $alumno->id)
            ->pluck('curso_edicion_id')
            ->filter();

        $ediciones = \App\Models\CursoEdicion::whereIn('estado_edicion', ['programada', 'programado', 'en_curso'])
            ->where('cupo_disponible', '>', 0)
            ->where('fecha_inicio', '>=', now())
            ->when($edicionesInscritas->isNotEmpty(), fn($q) => $q->whereNotIn('id', $edicionesInscritas))
            ->with(['curso.tipo', 'curso.instructor', 'horarios'])
            ->orderBy('fecha_inicio')
            ->get()
            ->map(fn($e) => [
                'id' => $e->id,
                'curso_id' => $e->curso->id,
                'nombre' => $e->curso->nombre,
                'tipo' => optional($e->curso->tipo)->nombre ?? 'N/A',
                'descripcion' => $e->curso->descripcion ?? '',
                'precio' => $e->precio_final,
                'cupo_total' => $e->cupo_total,
                'cupo_disponible' => $e->cupo_disponible,
                'profesor' => $e->curso->instructor ? $e->curso->instructor->nombre . ' ' . $e->curso->instructor->apellido : 'N/A',
                'fecha_inicio' => $e->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $e->fecha_fin->format('Y-m-d'),
                'horarios' => $e->horarios->map(fn($h) => [
                    'dia' => $h->dia_semana,
                    'hora_inicio' => substr($h->hora_inicio, 0, 5),
                    'hora_fin' => substr($h->hora_fin, 0, 5),
                ])
            ]);

        return Inertia::render('Alumno/Cursos/Disponibles', compact('ediciones'));
    }
    
    public function inscribirse($id)
    {
        // $id corresponde a la edición del curso
        $edicion = \App\Models\CursoEdicion::with(['curso.tipo', 'horarios'])->findOrFail($id);

        // Verificar que no esté inscrito en esta edición
        $yaInscrito = Inscripcion::where('alumno_id', Auth::id())
            ->where('curso_edicion_id', $id)
            ->exists();

        if ($yaInscrito) {
            return redirect()->route('alumno.cursos')->with('error', 'Ya estás inscrito en esta edición del curso');
        }

        if ($edicion->cupo_disponible <= 0) {
            return redirect()->route('alumno.cursos.disponibles')->with('error', 'No hay cupos disponibles para esta edición');
        }

        $planesPago = PlanPago::where('activo', true)->get();

        $cursoData = [
            'edicion_id' => $edicion->id,
            'curso_id' => $edicion->curso->id,
            'nombre' => $edicion->curso->nombre,
            'tipo' => optional($edicion->curso->tipo)->nombre ?? 'N/A',
            'descripcion' => $edicion->curso->descripcion ?? '',
            'precio' => $edicion->precio_final,
            'profesor' => $edicion->curso->instructor ? $edicion->curso->instructor->nombre . ' ' . $edicion->curso->instructor->apellido : 'N/A',
            'fecha_inicio' => $edicion->fecha_inicio->format('Y-m-d'),
            'fecha_fin' => $edicion->fecha_fin->format('Y-m-d'),
            'horarios' => $edicion->horarios->map(fn($h) => [
                'dia' => $h->dia_semana,
                'hora_inicio' => substr($h->hora_inicio, 0, 5),
                'hora_fin' => substr($h->hora_fin, 0, 5),
            ])
        ];

        return Inertia::render('Alumno/Cursos/Inscribirse', compact('cursoData', 'planesPago'));
    }
    
    public function storeInscripcion(Request $request)
    {
        $validated = $request->validate([
            'curso_edicion_id' => 'required|exists:curso_edicion,id',
            'plan_pago_id' => 'required|exists:plan_pago,id',
        ]);

        $edicion = \App\Models\CursoEdicion::findOrFail($validated['curso_edicion_id']);

        // Verificar cupo
        if ($edicion->cupo_disponible <= 0) {
            return back()->withErrors(['curso_edicion_id' => 'No hay cupos disponibles para esta edición.']);
        }

        // Verificar que no esté inscrito en esta edición
        $yaInscrito = Inscripcion::where('alumno_id', Auth::id())
            ->where('curso_edicion_id', $edicion->id)
            ->exists();

        if ($yaInscrito) {
            return back()->withErrors(['curso_edicion_id' => 'Ya estás inscrito en esta edición']);
        }

        // Crear la inscripción
        $inscripcion = Inscripcion::create([
            'alumno_id' => Auth::id(),
            'curso_id' => $edicion->curso_id,
            'curso_edicion_id' => $edicion->id,
            'plan_pago_id' => $validated['plan_pago_id'],
            'estado_inscripcion' => 'activa',
            'monto_total' => $edicion->precio_final,
        ]);

        // Reducir cupo disponible
        $edicion->decrement('cupo_disponible');

        return redirect()->route('alumno.cursos')->with('success', '¡Inscripción exitosa! Ya puedes acceder a tu curso.');
    }
    
    public function show($id)
    {
        $alumno = Auth::user();
        
        $inscripcion = Inscripcion::where('alumno_id', $alumno->id)
            ->where('id', $id)
            ->with(['edicion.curso.tipo', 'edicion.horarios', 'edicion.curso.instructor', 'planPago', 'pagos.metodoPago'])
            ->firstOrFail();

        $edicion = $inscripcion->edicion;
        $cursoModel = $edicion ? $edicion->curso : $inscripcion->curso;

        $curso = [
            'id' => $cursoModel->id,
            'nombre' => $cursoModel->nombre,
            'tipo' => optional($cursoModel->tipo)->nombre ?? 'N/A',
            'descripcion' => $cursoModel->descripcion ?? '',
            'profesor' => optional($cursoModel->instructor)->nombre . ' ' . optional($cursoModel->instructor)->apellido ?? 'N/A',
            'horarios' => ($edicion ? $edicion->horarios : $cursoModel->horarios)->map(fn($h) => [
                'dia' => $h->dia_semana,
                'hora_inicio' => substr($h->hora_inicio, 0, 5),
                'hora_fin' => substr($h->hora_fin, 0, 5),
            ]),
            'estado' => $inscripcion->estado_inscripcion,
            'fecha_inicio' => $edicion->fecha_inicio ?? null,
            'fecha_fin' => $edicion->fecha_fin ?? null,
            'monto_total' => $inscripcion->monto_total,
            'saldo_pendiente' => $inscripcion->getSaldoPendiente(),
            'plan_pago' => optional($inscripcion->planPago)->nombre ?? 'N/A',
            'inscripcion_id' => $inscripcion->id,
        ];

        $pagos = $inscripcion->pagos->map(fn($p) => [
            'id' => $p->id,
            'fecha' => $p->fecha,
            'monto' => $p->monto,
            'metodo' => optional($p->metodoPago)->nombre ?? 'N/A',
        ]);

        return Inertia::render('Alumno/Cursos/Show', compact('curso', 'pagos'));
    }
}
