<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodosPago = MetodoPago::paginate(15);
        return Inertia::render('Admin/MetodosPago/Index', [
            'metodosPago' => $metodosPago
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/MetodosPago/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|unique:metodo_pago,nombre|max:100',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este método de pago ya existe',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
        ]);

        MetodoPago::create($validated);
        return redirect()->route('admin.metodos-pago.index')->with('success', 'Método de pago creado');
    }

    public function edit(MetodoPago $metodosPago)
    {
        return Inertia::render('Admin/MetodosPago/Edit', [
            'metodo' => $metodosPago
        ]);
    }

    public function update(Request $request, MetodoPago $metodosPago)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:metodo_pago,nombre,' . $metodosPago->id,
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Este método de pago ya existe',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
        ]);

        $metodosPago->update($validated);
        return redirect()->route('admin.metodos-pago.index')->with('success', 'Método de pago actualizado');
    }

    public function destroy(MetodoPago $metodosPago)
    {
        $metodosPago->delete();
        return redirect()->route('admin.metodos-pago.index')->with('success', 'Método de pago eliminado');
    }
}
