<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CursoHorario;
use App\Models\CursoEdicion;
use Illuminate\Http\Request;

class CursoEdicionHorarioController extends Controller
{
    public function store(Request $request, $edicionId)
    {
        $validated = $request->validate([
            'dia_semana' => 'required|integer|min:1|max:7',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $edicion = CursoEdicion::findOrFail($edicionId);
        
        CursoHorario::create([
            'curso_id' => $edicion->curso_id,
            'curso_edicion_id' => $edicion->id,
            'dia_semana' => $validated['dia_semana'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
        ]);

        return back()->with('success', 'Horario agregado exitosamente.');
    }

    public function update(Request $request, $edicionId, $horarioId)
    {
        $validated = $request->validate([
            'dia_semana' => 'required|integer|min:1|max:7',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $horario = CursoHorario::where('id', $horarioId)
            ->where('curso_edicion_id', $edicionId)
            ->firstOrFail();
        
        $horario->update($validated);

        return back()->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy($edicionId, $horarioId)
    {
        $horario = CursoHorario::where('id', $horarioId)
            ->where('curso_edicion_id', $edicionId)
            ->firstOrFail();
        
        $horario->delete();

        return back()->with('success', 'Horario eliminado exitosamente.');
    }
}
