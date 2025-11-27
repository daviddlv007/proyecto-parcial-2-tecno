<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Inscripcion, Usuario, Curso, CursoEdicion, PlanPago};
use Illuminate\Http\Request;
use Inertia\Inertia;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with(['alumno', 'curso', 'edicion.curso', 'planPago'])
            ->latest()
            ->paginate(15);
        
        return Inertia::render('Admin/Inscripciones/Index', compact('inscripciones'));
    }

    public function create()
    {
        $alumnos = Usuario::where('rol_id', 3)->get();
        $ediciones = CursoEdicion::with('curso')
            ->where('estado_edicion', '!=', 'finalizada')
            ->where('cupo_disponible', '>', 0)
            ->get()
            ->map(function($edicion) {
                return [
                    'id' => $edicion->id,
                    'curso_nombre' => $edicion->curso->nombre,
                    'fecha_inicio' => $edicion->fecha_inicio->format('Y-m-d'),
                    'fecha_fin' => $edicion->fecha_fin->format('Y-m-d'),
                    'cupo_total' => $edicion->cupo_total,
                    'cupo_disponible' => $edicion->cupo_disponible,
                    'precio_final' => $edicion->precio_final,
                ];
            });
        $planesPago = PlanPago::where('activo', true)->get();
        
        return Inertia::render('Admin/Inscripciones/Create', compact('alumnos', 'ediciones', 'planesPago'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumno_id' => 'required|exists:usuario,id',
            'curso_edicion_id' => 'required|exists:curso_edicion,id',
            'plan_pago_id' => 'required|exists:plan_pago,id',
            'estado_inscripcion' => 'required|in:pendiente,activa,completada,cancelada',
        ], [
            'alumno_id.required' => 'El alumno es obligatorio',
            'alumno_id.exists' => 'El alumno seleccionado no es válido',
            'curso_edicion_id.required' => 'La edición del curso es obligatoria',
            'curso_edicion_id.exists' => 'La edición del curso seleccionada no es válida',
            'plan_pago_id.required' => 'El plan de pago es obligatorio',
            'plan_pago_id.exists' => 'El plan de pago seleccionado no es válido',
            'estado_inscripcion.required' => 'El estado de la inscripción es obligatorio',
            'estado_inscripcion.in' => 'El estado debe ser: pendiente, activa, completada o cancelada',
        ]);

        // Obtener la edición y el curso
        $edicion = CursoEdicion::findOrFail($validated['curso_edicion_id']);
        
        // Verificar cupo disponible
        if ($edicion->cupo_disponible <= 0) {
            return back()->withErrors(['curso_edicion_id' => 'No hay cupos disponibles para esta edición.']);
        }

        // Verificar que el alumno no esté ya inscrito en esta edición
        $yaInscrito = Inscripcion::where('alumno_id', $validated['alumno_id'])
            ->where('curso_edicion_id', $validated['curso_edicion_id'])
            ->exists();
        
        if ($yaInscrito) {
            return back()->withErrors(['alumno_id' => 'El alumno ya está inscrito en esta edición.']);
        }

        // Crear inscripción
        $validated['curso_id'] = $edicion->curso_id;
        $validated['monto_total'] = $edicion->precio_final;
        
        Inscripcion::create($validated);
        
        // Decrementar cupo disponible
        $edicion->decrement('cupo_disponible');

        return redirect()->route('admin.inscripciones.index')->with('success', 'Inscripción creada exitosamente');
    }

    public function edit(Inscripcion $inscripcion)
    {
        if (!$inscripcion->exists) {
            abort(404, 'Inscripción no encontrada');
        }
        
        $inscripcion->load(['alumno', 'curso', 'edicion.curso', 'planPago']);
        $alumnos = Usuario::where('rol_id', 3)->get();
        $ediciones = CursoEdicion::with('curso')
            ->where('estado_edicion', '!=', 'finalizada')
            ->get()
            ->map(function($edicion) {
                return [
                    'id' => $edicion->id,
                    'curso_nombre' => $edicion->curso->nombre,
                    'fecha_inicio' => $edicion->fecha_inicio->format('Y-m-d'),
                    'fecha_fin' => $edicion->fecha_fin->format('Y-m-d'),
                    'cupo_total' => $edicion->cupo_total,
                    'cupo_disponible' => $edicion->cupo_disponible,
                    'precio_final' => $edicion->precio_final,
                ];
            });
        $planesPago = PlanPago::where('activo', true)->get();
        
        return Inertia::render('Admin/Inscripciones/Edit', [
            'inscripcion' => $inscripcion,
            'alumnos' => $alumnos,
            'ediciones' => $ediciones,
            'planesPago' => $planesPago
        ]);
    }

    public function update(Request $request, Inscripcion $inscripcion)
    {
        $validated = $request->validate([
            'alumno_id' => 'required|exists:usuario,id',
            'curso_edicion_id' => 'nullable|exists:curso_edicion,id',
            'plan_pago_id' => 'required|exists:plan_pago,id',
            'estado_inscripcion' => 'required|in:pendiente,activa,completada,cancelada',
        ], [
            'alumno_id.required' => 'El alumno es obligatorio',
            'alumno_id.exists' => 'El alumno seleccionado no es válido',
            'curso_edicion_id.exists' => 'La edición del curso seleccionada no es válida',
            'plan_pago_id.required' => 'El plan de pago es obligatorio',
            'plan_pago_id.exists' => 'El plan de pago seleccionado no es válido',
            'estado_inscripcion.required' => 'El estado de la inscripción es obligatorio',
            'estado_inscripcion.in' => 'El estado debe ser: pendiente, activa, completada o cancelada',
        ]);

        // Si cambia la edición, actualizar datos relacionados
        if (isset($validated['curso_edicion_id']) && $inscripcion->curso_edicion_id != $validated['curso_edicion_id']) {
            $edicionAnterior = $inscripcion->edicion;
            $edicionNueva = CursoEdicion::findOrFail($validated['curso_edicion_id']);
            
            // Verificar cupo de la nueva edición
            if ($edicionNueva->cupo_disponible <= 0) {
                return back()->withErrors(['curso_edicion_id' => 'No hay cupos disponibles para esta edición.']);
            }
            
            // Restaurar cupo de edición anterior
            if ($edicionAnterior) {
                $edicionAnterior->increment('cupo_disponible');
            }
            
            // Decrementar cupo de nueva edición
            $edicionNueva->decrement('cupo_disponible');
            
            // Actualizar curso_id y monto
            $validated['curso_id'] = $edicionNueva->curso_id;
            $validated['monto_total'] = $edicionNueva->precio_final;
        }

        $inscripcion->update($validated);
        return redirect()->route('admin.inscripciones.index')->with('success', 'Inscripción actualizada exitosamente');
    }

    public function destroy(Inscripcion $inscripcion)
    {
        // Restaurar cupo si tiene edición asignada
        if ($inscripcion->edicion) {
            $inscripcion->edicion->increment('cupo_disponible');
        }
        
        $inscripcion->delete();
        return redirect()->route('admin.inscripciones.index')->with('success', 'Inscripción eliminada exitosamente');
    }
}
