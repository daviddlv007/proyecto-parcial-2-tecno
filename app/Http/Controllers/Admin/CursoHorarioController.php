<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CursoHorario;
use Illuminate\Http\Request;

class CursoHorarioController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:curso,id',
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio'
        ]);

        CursoHorario::create($validated);
        return back()->with('success', 'Horario agregado');
    }

    public function destroy(CursoHorario $cursoHorario)
    {
        $cursoHorario->delete();
        return back()->with('success', 'Horario eliminado');
    }
}
