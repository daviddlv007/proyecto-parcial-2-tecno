<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Pago, Usuario, MetodoPago, Inscripcion};
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['alumno', 'metodoPago', 'inscripcion.edicion.curso'])
            ->latest('fecha')
            ->paginate(20);
        return Inertia::render('Admin/Pagos/Index', compact('pagos'));
    }

    public function create()
    {
        $inscripciones = Inscripcion::with(['alumno', 'edicion.curso', 'planPago'])
            ->whereIn('estado_inscripcion', ['pendiente', 'activa'])
            ->get()
            ->map(function($inscripcion) {
                return [
                    'id' => $inscripcion->id,
                    'alumno_nombre' => $inscripcion->alumno->nombre . ' ' . $inscripcion->alumno->apellido,
                    'curso_nombre' => $inscripcion->edicion->curso->nombre ?? 'Sin curso',
                    'fecha_inicio' => $inscripcion->edicion ? $inscripcion->edicion->fecha_inicio->format('d/m/Y') : '-',
                    'fecha_fin' => $inscripcion->edicion ? $inscripcion->edicion->fecha_fin->format('d/m/Y') : '-',
                    'monto_total' => $inscripcion->monto_total,
                    'monto_cuota' => $inscripcion->calcularMontoCuota(),
                    'saldo_pendiente' => $inscripcion->getSaldoPendiente(),
                ];
            });
        
        $metodosPago = MetodoPago::all();
        return Inertia::render('Admin/Pagos/Create', compact('inscripciones', 'metodosPago'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inscripcion_id' => 'required|exists:inscripcion,id',
            'metodo_pago_id' => 'required|exists:metodo_pago,id',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
        ], [
            'inscripcion_id.required' => 'La inscripción es obligatoria',
            'inscripcion_id.exists' => 'La inscripción seleccionada no es válida',
            'metodo_pago_id.required' => 'El método de pago es obligatorio',
            'metodo_pago_id.exists' => 'El método de pago seleccionado no es válido',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha debe ser válida',
            'monto.required' => 'El monto es obligatorio',
            'monto.numeric' => 'El monto debe ser un número válido',
            'monto.min' => 'El monto debe ser mayor o igual a 0',
        ]);

        $inscripcion = Inscripcion::findOrFail($validated['inscripcion_id']);
        $validated['alumno_id'] = $inscripcion->alumno_id;

        Pago::create($validated);
        return redirect()->route('admin.pagos.index')->with('success', 'Pago registrado exitosamente');
    }

    public function edit(Pago $pago)
    {
        $pago->load(['alumno', 'inscripcion.edicion.curso', 'metodoPago']);
        
        $inscripciones = Inscripcion::with(['alumno', 'edicion.curso'])
            ->get()
            ->map(function($inscripcion) {
                return [
                    'id' => $inscripcion->id,
                    'alumno_nombre' => $inscripcion->alumno->nombre . ' ' . $inscripcion->alumno->apellido,
                    'curso_nombre' => $inscripcion->edicion->curso->nombre ?? 'Sin curso',
                    'fecha_inicio' => $inscripcion->edicion ? $inscripcion->edicion->fecha_inicio->format('d/m/Y') : '-',
                    'fecha_fin' => $inscripcion->edicion ? $inscripcion->edicion->fecha_fin->format('d/m/Y') : '-',
                    'monto_total' => $inscripcion->monto_total,
                ];
            });
        
        $metodosPago = MetodoPago::all();
        return Inertia::render('Admin/Pagos/Edit', compact('pago', 'inscripciones', 'metodosPago'));
    }

    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'metodo_pago_id' => 'required|exists:metodo_pago,id',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0',
        ], [
            'metodo_pago_id.required' => 'El método de pago es obligatorio',
            'metodo_pago_id.exists' => 'El método de pago seleccionado no es válido',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha debe ser válida',
            'monto.required' => 'El monto es obligatorio',
            'monto.numeric' => 'El monto debe ser un número válido',
            'monto.min' => 'El monto debe ser mayor o igual a 0',
        ]);

        $pago->update($validated);
        return redirect()->route('admin.pagos.index')->with('success', 'Pago actualizado');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('admin.pagos.index')->with('success', 'Pago eliminado');
    }
}
