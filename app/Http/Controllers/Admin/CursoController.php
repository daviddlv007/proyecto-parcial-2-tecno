<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Curso, TipoCurso, Usuario};
use Illuminate\Http\Request;
use Inertia\Inertia;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with(['tipoCurso', 'instructor', 'ediciones'])
            ->withCount('ediciones')
            ->paginate(15);
        
        return Inertia::render('Admin/Cursos/Index', compact('cursos'));
    }

    public function horarios(Curso $curso)
    {
        $horarios = $curso->horarios;
        return response()->json(['horarios' => $horarios]);
    }

    public function create()
    {
        $tiposCurso = TipoCurso::all();
        $instructores = Usuario::where('rol_id', 2)->get();
        return Inertia::render('Admin/Cursos/Create', compact('tiposCurso', 'instructores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'tipo_curso_id' => 'required|exists:tipo_curso,id',
            'descripcion' => 'nullable|string',
            'instructor_id' => 'required|exists:usuario,id',
            'capacidad_maxima' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'estado_curso' => 'required|in:activo,inactivo,completo',
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 200 caracteres',
            'tipo_curso_id.required' => 'El tipo de curso es obligatorio',
            'tipo_curso_id.exists' => 'El tipo de curso seleccionado no es válido',
            'instructor_id.required' => 'El instructor es obligatorio',
            'instructor_id.exists' => 'El instructor seleccionado no es válido',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria',
            'capacidad_maxima.integer' => 'La capacidad máxima debe ser un número entero',
            'capacidad_maxima.min' => 'La capacidad máxima debe ser al menos 1',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un número válido',
            'precio.min' => 'El precio debe ser mayor o igual a 0',
            'estado_curso.required' => 'El estado del curso es obligatorio',
            'estado_curso.in' => 'El estado debe ser: activo, inactivo o completo',
        ]);

        Curso::create($validated);

        return redirect()->route('admin.cursos.index')->with('success', 'Curso creado exitosamente. Ahora puedes crear ediciones para este curso.');
    }

    public function edit(Curso $curso)
    {
        $curso->load('ediciones');
        $tiposCurso = TipoCurso::all();
        $instructores = Usuario::where('rol_id', 2)->get();
        return Inertia::render('Admin/Cursos/Edit', compact('curso', 'tiposCurso', 'instructores'));
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'tipo_curso_id' => 'required|exists:tipo_curso,id',
            'instructor_id' => 'required|exists:usuario,id',
            'capacidad_maxima' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'estado_curso' => 'required|in:activo,inactivo,completo',
        ], [
            'nombre.required' => 'El nombre del curso es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 200 caracteres',
            'tipo_curso_id.required' => 'El tipo de curso es obligatorio',
            'tipo_curso_id.exists' => 'El tipo de curso seleccionado no es válido',
            'instructor_id.required' => 'El instructor es obligatorio',
            'instructor_id.exists' => 'El instructor seleccionado no es válido',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria',
            'capacidad_maxima.integer' => 'La capacidad máxima debe ser un número entero',
            'capacidad_maxima.min' => 'La capacidad máxima debe ser al menos 1',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un número válido',
            'precio.min' => 'El precio debe ser mayor o igual a 0',
            'estado_curso.required' => 'El estado del curso es obligatorio',
            'estado_curso.in' => 'El estado debe ser: activo, inactivo o completo',
        ]);

        $curso->update($validated);
        return redirect()->route('admin.cursos.index')->with('success', 'Curso actualizado');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('admin.cursos.index')->with('success', 'Curso eliminado');
    }
}
