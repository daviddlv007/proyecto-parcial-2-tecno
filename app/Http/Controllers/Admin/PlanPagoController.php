<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\PlanPago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanPagoController extends Controller
{
    public function index()
    {
        $planesPago = PlanPago::paginate(15);
        return Inertia::render('Admin/PlanesPago/Index', [
            'planesPago' => $planesPago
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PlanesPago/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|unique:plan_pago,nombre|max:100',
            'numero_cuotas' => 'required|integer|min:1|max:24',
            'periodicidad' => 'required|in:semanal,quincenal,mensual',
            'dias_intervalo' => 'nullable|integer|min:1',
            'dias_maximo_total' => 'required|integer|min:1',
            'activo' => 'boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este plan de pago ya existe',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'numero_cuotas.required' => 'El número de cuotas es obligatorio',
            'numero_cuotas.integer' => 'El número de cuotas debe ser un número entero',
            'numero_cuotas.min' => 'El número de cuotas debe ser al menos 1',
            'numero_cuotas.max' => 'El número de cuotas no puede ser mayor a 24',
            'periodicidad.required' => 'La periodicidad es obligatoria',
            'periodicidad.in' => 'La periodicidad debe ser: semanal, quincenal o mensual',
            'dias_intervalo.integer' => 'Los días de intervalo deben ser un número entero',
            'dias_intervalo.min' => 'Los días de intervalo deben ser al menos 1',
            'dias_maximo_total.required' => 'Los días máximo total son obligatorios',
            'dias_maximo_total.integer' => 'Los días máximo total deben ser un número entero',
            'dias_maximo_total.min' => 'Los días máximo total deben ser al menos 1',
        ]);

        PlanPago::create($validated);
        return redirect()->route('admin.planes-pago.index')->with('success', 'Plan de pago creado');
    }

    public function edit(PlanPago $planesPago)
    {
        return Inertia::render('Admin/PlanesPago/Edit', [
            'plan' => $planesPago
        ]);
    }

    public function update(Request $request, PlanPago $planesPago)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:plan_pago,nombre,' . $planesPago->id,
            'numero_cuotas' => 'required|integer|min:1|max:24',
            'periodicidad' => 'required|in:semanal,quincenal,mensual',
            'dias_intervalo' => 'nullable|integer|min:1',
            'dias_maximo_total' => 'required|integer|min:1',
            'activo' => 'boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este plan de pago ya existe',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'numero_cuotas.required' => 'El número de cuotas es obligatorio',
            'numero_cuotas.integer' => 'El número de cuotas debe ser un número entero',
            'numero_cuotas.min' => 'El número de cuotas debe ser al menos 1',
            'numero_cuotas.max' => 'El número de cuotas no puede ser mayor a 24',
            'periodicidad.required' => 'La periodicidad es obligatoria',
            'periodicidad.in' => 'La periodicidad debe ser: semanal, quincenal o mensual',
            'dias_intervalo.integer' => 'Los días de intervalo deben ser un número entero',
            'dias_intervalo.min' => 'Los días de intervalo deben ser al menos 1',
            'dias_maximo_total.required' => 'Los días máximo total son obligatorios',
            'dias_maximo_total.integer' => 'Los días máximo total deben ser un número entero',
            'dias_maximo_total.min' => 'Los días máximo total deben ser al menos 1',
        ]);

        $planesPago->update($validated);
        return redirect()->route('admin.planes-pago.index')->with('success', 'Plan de pago actualizado');
    }

    public function destroy(PlanPago $planesPago)
    {
        $planesPago->delete();
        return redirect()->route('admin.planes-pago.index')->with('success', 'Plan de pago eliminado');
    }
}
