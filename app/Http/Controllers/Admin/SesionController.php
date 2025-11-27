<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sesion;
use App\Models\Curso;
use App\Models\Actividad;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class SesionController extends Controller
{
    public function index(Request $request)
    {
        $query = Sesion::with(['curso', 'actividad', 'instructor', 'vehiculo']);

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado_sesion', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora_inicio', $request->fecha);
        }

        $sesiones = $query->orderBy('fecha_hora_inicio', 'desc')->paginate(20)->withQueryString();

        $cursos = Curso::all();
        $instructores = User::where('rol_id', 2)->get(); // rol_id 2 = Instructor
        $actividades = Actividad::all();
        $vehiculos = Vehiculo::all();

        return Inertia::render('Admin/Sesiones', [
            'sesiones' => $sesiones,
            'cursos' => $cursos,
            'instructores' => $instructores,
            'actividades' => $actividades,
            'vehiculos' => $vehiculos,
            'filters' => $request->only(['curso_id', 'instructor_id', 'estado', 'fecha']),
        ]);
    }

    public function create(Request $request)
    {
        $cursos = Curso::whereIn('estado_curso', ['Planificado', 'En Progreso'])->get();
        $actividades = Actividad::where('estado_actividad', 'Activa')->with('tipoActividad')->get();
        $instructores = User::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Instructor'))
            ->where('estado_usuario', 'Activo')->get();
        $vehiculos = Vehiculo::where('estado_vehiculo', 'Disponible')->with('tipoVehiculo')->get();

        $cursoId = $request->get('curso_id');

        return Inertia::render('Admin/Sesiones/Create', [
            'cursos' => $cursos,
            'actividades' => $actividades,
            'instructores' => $instructores,
            'vehiculos' => $vehiculos,
            'cursoId' => $cursoId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'actividad_id' => 'required|exists:actividades,id',
            'instructor_id' => 'required|exists:users,id',
            'vehiculo_id' => 'nullable|exists:vehiculo,id',
            'fecha_hora_inicio' => 'required|date',
            'fecha_hora_fin' => 'required|date|after:fecha_hora_inicio',
            'lugar' => 'nullable|string|max:200',
            'capacidad_maxima' => 'required|integer|min:1|max:20',
        ], [
            'curso_id.required' => 'El curso es obligatorio',
            'actividad_id.required' => 'La actividad es obligatoria',
            'instructor_id.required' => 'El instructor es obligatorio',
            'fecha_hora_inicio.required' => 'La fecha y hora de inicio son obligatorias',
            'fecha_hora_fin.required' => 'La fecha y hora de fin son obligatorias',
            'fecha_hora_fin.after' => 'La hora de fin debe ser posterior a la de inicio',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria',
        ]);

        $inicio = Carbon::parse($validated['fecha_hora_inicio']);
        $fin = Carbon::parse($validated['fecha_hora_fin']);

        // Validar horario permitido
        if (!Sesion::esHorarioValido($inicio, $fin)) {
            return back()->withErrors([
                'fecha_hora_inicio' => 'El horario debe ser uno de los permitidos: 8-10, 10-12, 13-15, 15-17, 17-19'
            ])->withInput();
        }

        // Verificar disponibilidad del instructor
        if (!Sesion::instructorDisponible($validated['instructor_id'], $inicio, $fin)) {
            return back()->withErrors([
                'instructor_id' => 'El instructor no está disponible en ese horario'
            ])->withInput();
        }

        // Verificar disponibilidad del vehículo
        if (isset($validated['vehiculo_id']) && !Sesion::vehiculoDisponible($validated['vehiculo_id'], $inicio, $fin)) {
            return back()->withErrors([
                'vehiculo_id' => 'El vehículo no está disponible en ese horario'
            ])->withInput();
        }

        Sesion::create($validated);

        return redirect()->route('admin.sesiones.index')->with('message', 'Sesión creada exitosamente');
    }

    public function show(Sesion $sesion)
    {
        $sesion->load(['curso', 'actividad', 'instructor', 'vehiculo', 'asistencias.alumno']);

        return Inertia::render('Admin/Sesiones/Show', ['sesion' => $sesion]);
    }

    public function edit(Sesion $sesion)
    {
        $sesion->load(['curso', 'actividad', 'instructor', 'vehiculo']);
        
        $cursos = Curso::whereIn('estado_curso', ['Planificado', 'En Progreso'])->get();
        $actividades = Actividad::where('estado_actividad', 'Activa')->with('tipoActividad')->get();
        $instructores = User::whereHas('rol', fn($q) => $q->where('nombre_rol', 'Instructor'))
            ->where('estado_usuario', 'Activo')->get();
        $vehiculos = Vehiculo::where('estado_vehiculo', 'Disponible')->with('tipoVehiculo')->get();

        return Inertia::render('Admin/Sesiones/Edit', [
            'sesion' => $sesion,
            'cursos' => $cursos,
            'actividades' => $actividades,
            'instructores' => $instructores,
            'vehiculos' => $vehiculos,
        ]);
    }

    public function update(Request $request, Sesion $sesion)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'actividad_id' => 'required|exists:actividades,id',
            'instructor_id' => 'required|exists:users,id',
            'vehiculo_id' => 'nullable|exists:vehiculo,id',
            'fecha_hora_inicio' => 'required|date',
            'fecha_hora_fin' => 'required|date|after:fecha_hora_inicio',
            'lugar' => 'nullable|string|max:200',
            'capacidad_maxima' => 'required|integer|min:1|max:20',
            'estado_sesion' => 'required|in:Programada,En Curso,Completada,Cancelada',
        ]);

        $inicio = Carbon::parse($validated['fecha_hora_inicio']);
        $fin = Carbon::parse($validated['fecha_hora_fin']);

        if (!Sesion::esHorarioValido($inicio, $fin)) {
            return back()->withErrors([
                'fecha_hora_inicio' => 'El horario debe ser uno de los permitidos'
            ])->withInput();
        }

        if (!Sesion::instructorDisponible($validated['instructor_id'], $inicio, $fin, $sesion->id)) {
            return back()->withErrors([
                'instructor_id' => 'El instructor no está disponible'
            ])->withInput();
        }

        if (isset($validated['vehiculo_id']) && !Sesion::vehiculoDisponible($validated['vehiculo_id'], $inicio, $fin, $sesion->id)) {
            return back()->withErrors([
                'vehiculo_id' => 'El vehículo no está disponible'
            ])->withInput();
        }

        $sesion->update($validated);

        return redirect()->route('admin.sesiones.index')->with('message', 'Sesión actualizada exitosamente');
    }

    public function destroy(Sesion $sesion)
    {
        if (in_array($sesion->estado_sesion, ['En Curso', 'Completada'])) {
            return back()->with('error', 'No se puede eliminar una sesión en curso o completada');
        }

        $sesion->delete();

        return redirect()->route('admin.sesiones.index')->with('message', 'Sesión eliminada exitosamente');
    }
}
