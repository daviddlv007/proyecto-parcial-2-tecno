<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // Laravel intentará usar 'contrasena' como campo de password gracias a Usuario::getAuthPassword()
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirigir según el rol del usuario (coincide con AuthenticatedSessionController)
            $user = Auth::user();

            \Log::info('LOGIN DEBUG (legacy LoginController)', [
                'user_id' => $user->id ?? null,
                'email' => $user->email ?? null,
                'rol_raw' => $user->rol_id ?? null,
                'rol_id' => isset($user->rol_id) ? (int) $user->rol_id : null,
                'rol_nombre' => $user->rol->nombre ?? 'N/A'
            ]);

            $redirectRoute = match((int) ($user->rol_id ?? 0)) {
                1 => 'admin.dashboard',
                2 => 'profesor.dashboard',
                3 => 'alumno.dashboard',
                default => 'admin.dashboard',
            };

            \Log::info('REDIRECT DEBUG (legacy LoginController)', [
                'route_name' => $redirectRoute,
                'route_url' => route($redirectRoute)
            ]);

            return redirect()->route($redirectRoute);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
