<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Usuario, Rol};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->paginate(15);
        return Inertia::render('Admin/Usuarios/Index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        return Inertia::render('Admin/Usuarios/Create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string',
            'tipo_documento' => 'required|string',
            'numero_documento' => 'required|string|unique:usuario,numero_documento',
            'email' => 'required|email|unique:usuario,email',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'contrasena' => 'required|string|min:6',
            'rol_id' => 'required|exists:rol,id',
            'estado_usuario' => 'required|in:activo,inactivo,suspendido',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.max' => 'El apellido no puede tener más de 100 caracteres',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'genero.required' => 'El género es obligatorio',
            'tipo_documento.required' => 'El tipo de documento es obligatorio',
            'numero_documento.required' => 'El número de documento es obligatorio',
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'direccion.required' => 'La dirección es obligatoria',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'rol_id.required' => 'El rol es obligatorio',
            'rol_id.exists' => 'El rol seleccionado no es válido',
            'estado_usuario.required' => 'El estado del usuario es obligatorio',
            'estado_usuario.in' => 'El estado debe ser: activo, inactivo o suspendido',
        ]);

        $validated['contrasena'] = Hash::make($validated['contrasena']);
        Usuario::create($validated);
        
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado exitosamente');
    }

    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return Inertia::render('Admin/Usuarios/Edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string',
            'tipo_documento' => 'required|string',
            'numero_documento' => 'required|string|unique:usuario,numero_documento,' . $usuario->id,
            'email' => 'required|email|unique:usuario,email,' . $usuario->id,
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'rol_id' => 'required|exists:rol,id',
            'estado_usuario' => 'required|in:activo,inactivo,suspendido',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.max' => 'El apellido no puede tener más de 100 caracteres',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'genero.required' => 'El género es obligatorio',
            'tipo_documento.required' => 'El tipo de documento es obligatorio',
            'numero_documento.required' => 'El número de documento es obligatorio',
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'direccion.required' => 'La dirección es obligatoria',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres',
            'rol_id.required' => 'El rol es obligatorio',
            'rol_id.exists' => 'El rol seleccionado no es válido',
            'estado_usuario.required' => 'El estado del usuario es obligatorio',
            'estado_usuario.in' => 'El estado debe ser: activo, inactivo o suspendido',
        ]);

        if ($request->filled('contrasena')) {
            $request->validate([
                'contrasena' => 'string|min:6'
            ], [
                'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            ]);
            $validated['contrasena'] = Hash::make($request->contrasena);
        }

        $usuario->update($validated);
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado');
    }
}
