<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CursoEdicion;
use App\Models\Curso;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CursoEdicionController extends Controller
{
    public function index()
    {
        $ediciones = CursoEdicion::with(['curso.tipo', 'curso.instructor', 'horarios'])
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(15);
        
        $ediciones->getCollection()->transform(function($edicion) {
            return [
                'id' => $edicion->id,
                'curso_nombre' => $edicion->curso->nombre,
                'tipo_curso' => optional($edicion->curso->tipo)->nombre ?? 'N/A',
                'instructor' => $edicion->curso->instructor ? 
                    $edicion->curso->instructor->nombre . ' ' . $edicion->curso->instructor->apellido : 
                    'Sin asignar',
                'fecha_inicio' => $edicion->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $edicion->fecha_fin->format('Y-m-d'),
                'cupo_total' => $edicion->cupo_total,
                'cupo_disponible' => $edicion->cupo_disponible,
                'inscritos' => $edicion->getInscritos(),
                'precio_final' => $edicion->precio_final,
                'estado' => $edicion->estado_edicion,
                'horarios_count' => $edicion->horarios->count(),
            ];
        });
        
        return Inertia::render('Admin/CursoEdiciones/Index', [
            'ediciones' => $ediciones
        ]);
    }
    
    public function create()
    {
        $cursos = Curso::where('estado_curso', 'activo')
            ->with('tipo')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'tipo' => optional($c->tipo)->nombre ?? 'N/A',
                'precio_base' => $c->precio,
                'capacidad_maxima' => $c->capacidad_maxima,
            ]);
        
        return Inertia::render('Admin/CursoEdiciones/Create', compact('cursos'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'cupo_total' => 'required|integer|min:1',
            'precio_final' => 'required|numeric|min:0',
            'estado_edicion' => 'required|in:programada,en_curso,finalizada,cancelada',
            'horarios' => 'nullable|array',
            'horarios.*.dia_semana' => 'required|integer|min:1|max:7',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ], [
            'curso_id.required' => 'El curso es obligatorio',
            'curso_id.exists' => 'El curso seleccionado no es válido',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.date' => 'La fecha de fin debe ser válida',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'cupo_total.required' => 'El cupo total es obligatorio',
            'cupo_total.integer' => 'El cupo total debe ser un número entero',
            'cupo_total.min' => 'El cupo total debe ser al menos 1',
            'precio_final.required' => 'El precio final es obligatorio',
            'precio_final.numeric' => 'El precio final debe ser un número válido',
            'precio_final.min' => 'El precio final debe ser mayor o igual a 0',
            'estado_edicion.required' => 'El estado de la edición es obligatorio',
            'estado_edicion.in' => 'El estado debe ser: programada, en_curso, finalizada o cancelada',
            'horarios.*.dia_semana.required' => 'El día de la semana es obligatorio',
            'horarios.*.dia_semana.min' => 'El día debe estar entre 1 y 7',
            'horarios.*.dia_semana.max' => 'El día debe estar entre 1 y 7',
            'horarios.*.hora_inicio.required' => 'La hora de inicio es obligatoria',
            'horarios.*.hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM',
            'horarios.*.hora_fin.required' => 'La hora de fin es obligatoria',
            'horarios.*.hora_fin.date_format' => 'La hora de fin debe tener formato HH:MM',
            'horarios.*.hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio',
        ]);
        
        $validated['cupo_disponible'] = $validated['cupo_total'];
        
        $edicion = CursoEdicion::create($validated);
        
        // Create horarios if provided
        if (!empty($validated['horarios'])) {
            foreach ($validated['horarios'] as $horario) {
                $edicion->horarios()->create([
                    'curso_id' => $edicion->curso_id,
                    'dia_semana' => $horario['dia_semana'],
                    'hora_inicio' => $horario['hora_inicio'],
                    'hora_fin' => $horario['hora_fin'],
                ]);
            }
        }
        
        return redirect()->route('admin.curso-ediciones.index')
            ->with('success', 'Edición de curso creada exitosamente.');
    }
    
    public function edit($id)
    {
        $edicion = CursoEdicion::with(['curso', 'horarios'])->findOrFail($id);
        
        $cursos = Curso::where('estado_curso', 'activo')
            ->with('tipo')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'tipo' => optional($c->tipo)->nombre ?? 'N/A',
                'precio_base' => $c->precio,
                'capacidad_maxima' => $c->capacidad_maxima,
            ]);
        
        $edicionData = [
            'id' => $edicion->id,
            'curso_id' => $edicion->curso_id,
            'fecha_inicio' => $edicion->fecha_inicio->format('Y-m-d'),
            'fecha_fin' => $edicion->fecha_fin->format('Y-m-d'),
            'cupo_total' => $edicion->cupo_total,
            'cupo_disponible' => $edicion->cupo_disponible,
            'precio_final' => $edicion->precio_final,
            'estado_edicion' => $edicion->estado_edicion,
            'horarios' => $edicion->horarios->map(fn($h) => [
                'id' => $h->id,
                'dia_semana' => $h->dia_semana,
                'hora_inicio' => $h->hora_inicio,
                'hora_fin' => $h->hora_fin,
            ]),
        ];
        
        return Inertia::render('Admin/CursoEdiciones/Edit', [
            'edicion' => $edicionData,
            'cursos' => $cursos
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $edicion = CursoEdicion::findOrFail($id);
        
        $validated = $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'cupo_total' => 'required|integer|min:1',
            'precio_final' => 'required|numeric|min:0',
            'estado_edicion' => 'required|in:programada,en_curso,finalizada,cancelada',
        ], [
            'curso_id.required' => 'El curso es obligatorio',
            'curso_id.exists' => 'El curso seleccionado no es válido',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida',
            'fecha_fin.required' => 'La fecha de fin es obligatoria',
            'fecha_fin.date' => 'La fecha de fin debe ser válida',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'cupo_total.required' => 'El cupo total es obligatorio',
            'cupo_total.integer' => 'El cupo total debe ser un número entero',
            'cupo_total.min' => 'El cupo total debe ser al menos 1',
            'precio_final.required' => 'El precio final es obligatorio',
            'precio_final.numeric' => 'El precio final debe ser un número válido',
            'precio_final.min' => 'El precio final debe ser mayor o igual a 0',
            'estado_edicion.required' => 'El estado de la edición es obligatorio',
            'estado_edicion.in' => 'El estado debe ser: programada, en_curso, finalizada o cancelada',
        ]);
        
        $edicion->update($validated);
        
        return redirect()->route('admin.curso-ediciones.index')
            ->with('success', 'Edición actualizada exitosamente.');
    }
    
    public function destroy($id)
    {
        $edicion = CursoEdicion::findOrFail($id);
        
        if ($edicion->getInscritos() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar una edición con inscripciones.']);
        }
        
        $edicion->delete();
        
        return redirect()->route('admin.curso-ediciones.index')
            ->with('success', 'Edición eliminada exitosamente.');
    }
    
    public function getHorariosData($id)
    {
        $edicion = CursoEdicion::with('horarios')->findOrFail($id);
        
        return response()->json([
            'id' => $edicion->id,
            'horarios' => $edicion->horarios->map(fn($h) => [
                'id' => $h->id,
                'dia_semana' => $h->dia_semana,
                'hora_inicio' => $h->hora_inicio,
                'hora_fin' => $h->hora_fin,
            ]),
        ]);
    }
}
