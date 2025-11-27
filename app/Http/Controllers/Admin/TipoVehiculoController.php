<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoVehiculoController extends Controller
{
    public function index()
    {
        $tipos = TipoVehiculo::paginate(15);
        return Inertia::render('Admin/TiposVehiculo/Index', [
            'tipos' => $tipos
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TiposVehiculo/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:tipo_vehiculo,nombre',
            'descripcion' => 'nullable|string'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
            'nombre.unique' => 'Este nombre de tipo de vehículo ya existe',
        ]);

        TipoVehiculo::create($validated);
        return redirect()->route('admin.tipos-vehiculo.index')->with('success', 'Tipo de vehículo creado');
    }

    public function edit(TipoVehiculo $tiposVehiculo)
    {
        return Inertia::render('Admin/TiposVehiculo/Edit', [
            'tipo' => $tiposVehiculo
        ]);
    }

    public function update(Request $request, TipoVehiculo $tiposVehiculo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:tipo_vehiculo,nombre,' . $tiposVehiculo->id,
            'descripcion' => 'nullable|string'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
            'nombre.unique' => 'Este nombre de tipo de vehículo ya existe',
        ]);

        $tiposVehiculo->update($validated);
        return redirect()->route('admin.tipos-vehiculo.index')->with('success', 'Tipo actualizado');
    }

    public function destroy(TipoVehiculo $tiposVehiculo)
    {
        $tiposVehiculo->delete();
        return redirect()->route('admin.tipos-vehiculo.index')->with('success', 'Tipo eliminado');
    }
}
