<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TipoCurso;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoCursoController extends Controller
{
    public function index()
    {
        $tiposCurso = TipoCurso::paginate(15);
        return Inertia::render('Admin/TiposCurso/Index', [
            'tiposCurso' => $tiposCurso
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TiposCurso/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|unique:tipo_curso,nombre|max:100',
            'descripcion' => 'nullable|string',
            'duracion_horas' => 'nullable|numeric|min:0',
            'nivel' => 'nullable|string|max:50',
            'estado_curso' => 'nullable|string|max:20'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este nombre de tipo de curso ya existe',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'duracion_horas.numeric' => 'La duración debe ser un número válido',
            'duracion_horas.min' => 'La duración debe ser mayor o igual a 0',
            'nivel.max' => 'El nivel no puede tener más de 50 caracteres',
            'estado_curso.max' => 'El estado no puede tener más de 20 caracteres',
        ]);

        TipoCurso::create($validated);
        return redirect()->route('admin.tipos-curso.index')->with('success', 'Tipo de curso creado');
    }

    public function edit(TipoCurso $tiposCurso)
    {
        return Inertia::render('Admin/TiposCurso/Edit', [
            'tipo' => $tiposCurso
        ]);
    }

    public function update(Request $request, TipoCurso $tiposCurso)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:tipo_curso,nombre,' . $tiposCurso->id,
            'descripcion' => 'nullable|string',
            'duracion_horas' => 'nullable|numeric|min:0',
            'nivel' => 'nullable|string|max:50',
            'estado_curso' => 'nullable|string|max:20'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este nombre de tipo de curso ya existe',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'duracion_horas.numeric' => 'La duración debe ser un número válido',
            'duracion_horas.min' => 'La duración debe ser mayor o igual a 0',
            'nivel.max' => 'El nivel no puede tener más de 50 caracteres',
            'estado_curso.max' => 'El estado no puede tener más de 20 caracteres',
        ]);

        $tiposCurso->update($validated);
        return redirect()->route('admin.tipos-curso.index')->with('success', 'Tipo de curso actualizado');
    }

    public function destroy(TipoCurso $tiposCurso)
    {
        $tiposCurso->delete();
        return redirect()->route('admin.tipos-curso.index')->with('success', 'Tipo de curso eliminado');
    }
}
