<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Vehiculo, TipoVehiculo};
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with('tipoVehiculo')->paginate(15);
        return Inertia::render('Admin/Vehiculos/Index', compact('vehiculos'));
    }

    public function create()
    {
        $tiposVehiculo = TipoVehiculo::all();
        return Inertia::render('Admin/Vehiculos/Create', compact('tiposVehiculo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'placa' => 'required|unique:vehiculo',
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculo,id',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'estado_vehiculo' => 'required|in:disponible,en uso,en mantenimiento,fuera de servicio',
        ], [
            'placa.required' => 'La placa es obligatoria',
            'placa.unique' => 'Esta placa ya está registrada',
            'tipo_vehiculo_id.required' => 'El tipo de vehículo es obligatorio',
            'tipo_vehiculo_id.exists' => 'El tipo de vehículo seleccionado no es válido',
            'marca.required' => 'La marca es obligatoria',
            'marca.max' => 'La marca no puede tener más de 100 caracteres',
            'modelo.required' => 'El modelo es obligatorio',
            'modelo.max' => 'El modelo no puede tener más de 100 caracteres',
            'anio.required' => 'El año es obligatorio',
            'anio.integer' => 'El año debe ser un número entero',
            'anio.min' => 'El año debe ser mayor a 1900',
            'anio.max' => 'El año no puede ser mayor a ' . (date('Y') + 1),
            'estado_vehiculo.required' => 'El estado del vehículo es obligatorio',
            'estado_vehiculo.in' => 'El estado debe ser: disponible, en uso, en mantenimiento o fuera de servicio',
        ]);

        Vehiculo::create($validated);
        return redirect()->route('admin.vehiculos.index')->with('success', 'Vehículo creado');
    }

    public function edit(Vehiculo $vehiculo)
    {
        $tipos = TipoVehiculo::all();
        return Inertia::render('Admin/Vehiculos/Edit', compact('vehiculo', 'tipos'));
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'placa' => 'required|unique:vehiculo,placa,' . $vehiculo->id,
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculo,id',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'estado_vehiculo' => 'required|in:disponible,en uso,en mantenimiento,fuera de servicio',
        ], [
            'placa.required' => 'La placa es obligatoria',
            'placa.unique' => 'Esta placa ya está registrada',
            'tipo_vehiculo_id.required' => 'El tipo de vehículo es obligatorio',
            'tipo_vehiculo_id.exists' => 'El tipo de vehículo seleccionado no es válido',
            'marca.required' => 'La marca es obligatoria',
            'marca.max' => 'La marca no puede tener más de 100 caracteres',
            'modelo.required' => 'El modelo es obligatorio',
            'modelo.max' => 'El modelo no puede tener más de 100 caracteres',
            'anio.required' => 'El año es obligatorio',
            'anio.integer' => 'El año debe ser un número entero',
            'anio.min' => 'El año debe ser mayor a 1900',
            'anio.max' => 'El año no puede ser mayor a ' . (date('Y') + 1),
            'estado_vehiculo.required' => 'El estado del vehículo es obligatorio',
            'estado_vehiculo.in' => 'El estado debe ser: disponible, en uso, en mantenimiento o fuera de servicio',
        ]);

        $vehiculo->update($validated);
        return redirect()->route('admin.vehiculos.index')->with('success', 'Vehículo actualizado');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('admin.vehiculos.index')->with('success', 'Vehículo eliminado');
    }
}
