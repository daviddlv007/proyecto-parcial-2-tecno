<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Rol;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('rol')->orderBy('orden')->paginate(15);
        return Inertia::render('Admin/Menus/Index', [
            'menus' => $menus
        ]);
    }

    public function create()
    {
        $roles = Rol::all();
        return Inertia::render('Admin/Menus/Create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'ruta' => 'required|string|max:200',
            'orden' => 'required|integer',
            'rol_id' => 'nullable|exists:rol,id',
            'activo' => 'boolean'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'ruta.required' => 'La ruta es obligatoria',
            'ruta.max' => 'La ruta no puede tener más de 200 caracteres',
            'orden.required' => 'El orden es obligatorio',
            'orden.integer' => 'El orden debe ser un número entero',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ]);

        Menu::create($validated);
        return redirect()->route('admin.menus.index')->with('success', 'Menú creado exitosamente');
    }

    public function edit(Menu $menu)
    {
        $roles = Rol::all();
        return Inertia::render('Admin/Menus/Edit', [
            'menu' => $menu,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'ruta' => 'required|string|max:200',
            'orden' => 'required|integer',
            'rol_id' => 'nullable|exists:rol,id',
            'activo' => 'boolean'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'ruta.required' => 'La ruta es obligatoria',
            'ruta.max' => 'La ruta no puede tener más de 200 caracteres',
            'orden.required' => 'El orden es obligatorio',
            'orden.integer' => 'El orden debe ser un número entero',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ]);

        $menu->update($validated);
        return redirect()->route('admin.menus.index')->with('success', 'Menú actualizado exitosamente');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menú eliminado exitosamente');
    }
}
