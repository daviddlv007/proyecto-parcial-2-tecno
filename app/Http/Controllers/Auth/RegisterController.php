<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:usuario,email',
            'numero_documento' => 'required|string|unique:usuario,numero_documento',
            'tipo_documento' => 'required|in:CI,Pasaporte,Otro',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no debe exceder 100 caracteres',
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.max' => 'El apellido no debe exceder 100 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'numero_documento.required' => 'El número de documento es obligatorio',
            'numero_documento.unique' => 'Este número de documento ya está registrado',
            'tipo_documento.required' => 'El tipo de documento es obligatorio',
            'tipo_documento.in' => 'El tipo de documento debe ser: CI, Pasaporte u Otro',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'genero.required' => 'El género es obligatorio',
            'genero.in' => 'El género debe ser: Masculino, Femenino u Otro',
            'telefono.max' => 'El teléfono no debe exceder 20 caracteres',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Buscar el rol de alumno (asumiendo que existe en la base de datos)
        $rolAlumno = Rol::where('nombre', 'alumno')->first();

        if (!$rolAlumno) {
            return back()->withErrors(['error' => 'Error en la configuración del sistema. Contacte al administrador.']);
        }

        // Crear el usuario con rol de alumno
        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'email' => $validated['email'],
            'numero_documento' => $validated['numero_documento'],
            'tipo_documento' => $validated['tipo_documento'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'genero' => $validated['genero'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => null, // Opcional, se puede agregar después
            'contrasena' => Hash::make($validated['password']),
            'rol_id' => $rolAlumno->id,
            'estado_usuario' => 'activo',
        ]);

        // Autenticar automáticamente al usuario
        Auth::login($usuario);

        // Redirigir al dashboard del alumno
        return redirect()->route('alumno.dashboard');
    }
}
