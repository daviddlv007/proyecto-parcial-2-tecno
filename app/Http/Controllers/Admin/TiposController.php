<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoVehiculo;
use App\Models\TipoActividad;
use App\Models\TipoPago;
use App\Models\MetodoPago;
use App\Models\RegistroEvento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TiposController extends Controller
{
    // TIPOS DE VEHÍCULO
    public function tiposVehiculo()
    {
        $tipos = TipoVehiculo::orderBy('nombre_tipo')->paginate(15);
        
        return Inertia::render('Admin/Tipos/Vehiculos', [
            'tipos' => $tipos,
        ]);
    }

    public function storeTipoVehiculo(Request $request)
    {
        $validated = $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipo_vehiculo',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = TipoVehiculo::create($validated);
        
        RegistroEvento::registrar('crear', 'tipos_vehiculo', $tipo->id, 'Tipo de vehículo creado', null, $validated);

        return back()->with('message', 'Tipo de vehículo creado exitosamente');
    }

    public function updateTipoVehiculo(Request $request, TipoVehiculo $tipo)
    {
        $validated = $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipo_vehiculo,nombre_tipo,' . $tipo->id,
            'descripcion' => 'nullable|string',
        ]);

        $datosAnteriores = $tipo->toArray();
        $tipo->update($validated);
        
        RegistroEvento::registrar('actualizar', 'tipos_vehiculo', $tipo->id, 'Tipo de vehículo actualizado', $datosAnteriores, $validated);

        return back()->with('message', 'Tipo de vehículo actualizado exitosamente');
    }

    public function destroyTipoVehiculo(TipoVehiculo $tipo)
    {
        if ($tipo->vehiculos()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar. Hay vehículos asociados.']);
        }

        $datosAnteriores = $tipo->toArray();
        $tipo->delete();
        
        RegistroEvento::registrar('eliminar', 'tipos_vehiculo', $tipo->id, 'Tipo de vehículo eliminado', $datosAnteriores, null);

        return back()->with('message', 'Tipo de vehículo eliminado exitosamente');
    }

    // TIPOS DE ACTIVIDAD
    public function tiposActividad()
    {
        $tipos = TipoActividad::orderBy('nombre_tipo')->paginate(15);
        
        return Inertia::render('Admin/Tipos/Actividades', [
            'tipos' => $tipos,
        ]);
    }

    public function storeTipoActividad(Request $request)
    {
        $validated = $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipos_actividad',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = TipoActividad::create($validated);
        
        RegistroEvento::registrar('crear', 'tipos_actividad', $tipo->id, 'Tipo de actividad creado', null, $validated);

        return back()->with('message', 'Tipo de actividad creado exitosamente');
    }

    public function updateTipoActividad(Request $request, TipoActividad $tipo)
    {
        $validated = $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipos_actividad,nombre_tipo,' . $tipo->id,
            'descripcion' => 'nullable|string',
        ]);

        $datosAnteriores = $tipo->toArray();
        $tipo->update($validated);
        
        RegistroEvento::registrar('actualizar', 'tipos_actividad', $tipo->id, 'Tipo de actividad actualizado', $datosAnteriores, $validated);

        return back()->with('message', 'Tipo de actividad actualizado exitosamente');
    }

    public function destroyTipoActividad(TipoActividad $tipo)
    {
        if ($tipo->actividades()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar. Hay actividades asociadas.']);
        }

        $datosAnteriores = $tipo->toArray();
        $tipo->delete();
        
        RegistroEvento::registrar('eliminar', 'tipos_actividad', $tipo->id, 'Tipo de actividad eliminado', $datosAnteriores, null);

        return back()->with('message', 'Tipo de actividad eliminado exitosamente');
    }

    // TIPOS DE PAGO
    public function tiposPago()
    {
        $tipos = TipoPago::orderBy('nombre_tipo')->paginate(15);
        
        return Inertia::render('Admin/Tipos/Pagos', [
            'tipos' => $tipos,
        ]);
    }

    public function storeTipoPago(Request $request)
    {
        $validated = $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipos_pago',
            'descripcion' => 'nullable|string',
            'permite_cuotas' => 'boolean',
        ]);

        $tipo = TipoPago::create($validated);
        
        RegistroEvento::registrar('crear', 'tipos_pago', $tipo->id, 'Tipo de pago creado', null, $validated);

        return back()->with('message', 'Tipo de pago creado exitosamente');
    }

    public function updateTipoPago(Request $request, TipoPago $tipo)
    {
        $validated = $request->validate([
            'nombre_tipo' => 'required|string|max:50|unique:tipos_pago,nombre_tipo,' . $tipo->id,
            'descripcion' => 'nullable|string',
            'permite_cuotas' => 'boolean',
        ]);

        $datosAnteriores = $tipo->toArray();
        $tipo->update($validated);
        
        RegistroEvento::registrar('actualizar', 'tipos_pago', $tipo->id, 'Tipo de pago actualizado', $datosAnteriores, $validated);

        return back()->with('message', 'Tipo de pago actualizado exitosamente');
    }

    public function destroyTipoPago(TipoPago $tipo)
    {
        if ($tipo->inscripciones()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar. Hay inscripciones asociadas.']);
        }

        $datosAnteriores = $tipo->toArray();
        $tipo->delete();
        
        RegistroEvento::registrar('eliminar', 'tipos_pago', $tipo->id, 'Tipo de pago eliminado', $datosAnteriores, null);

        return back()->with('message', 'Tipo de pago eliminado exitosamente');
    }

    // MÉTODOS DE PAGO
    public function metodosPago()
    {
        $metodos = MetodoPago::orderBy('nombre_metodo')->paginate(15);
        
        return Inertia::render('Admin/Tipos/MetodosPago', [
            'metodos' => $metodos,
        ]);
    }

    public function storeMetodoPago(Request $request)
    {
        $validated = $request->validate([
            'nombre_metodo' => 'required|string|max:50|unique:metodo_pago',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $metodo = MetodoPago::create($validated);
        
        RegistroEvento::registrar('crear', 'metodos_pago', $metodo->id, 'Método de pago creado', null, $validated);

        return back()->with('message', 'Método de pago creado exitosamente');
    }

    public function updateMetodoPago(Request $request, MetodoPago $metodo)
    {
        $validated = $request->validate([
            'nombre_metodo' => 'required|string|max:50|unique:metodo_pago,nombre_metodo,' . $metodo->id,
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $datosAnteriores = $metodo->toArray();
        $metodo->update($validated);
        
        RegistroEvento::registrar('actualizar', 'metodos_pago', $metodo->id, 'Método de pago actualizado', $datosAnteriores, $validated);

        return back()->with('message', 'Método de pago actualizado exitosamente');
    }

    public function destroyMetodoPago(MetodoPago $metodo)
    {
        if ($metodo->pagos()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar. Hay pagos asociados.']);
        }

        $datosAnteriores = $metodo->toArray();
        $metodo->delete();
        
        RegistroEvento::registrar('eliminar', 'metodos_pago', $metodo->id, 'Método de pago eliminado', $datosAnteriores, null);

        return back()->with('message', 'Método de pago eliminado exitosamente');
    }

    // REGISTRO DE EVENTOS
    public function registroEventos(Request $request)
    {
        $query = RegistroEvento::with('usuario');

        if ($request->filled('evento')) {
            $query->where('evento', $request->evento);
        }

        if ($request->filled('entidad')) {
            $query->where('entidad', $request->entidad);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $eventos = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return Inertia::render('Admin/RegistroEventos', [
            'eventos' => $eventos,
            'filters' => $request->only(['evento', 'entidad', 'usuario_id', 'fecha_desde', 'fecha_hasta']),
        ]);
    }
}

