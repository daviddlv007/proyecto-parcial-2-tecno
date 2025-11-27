<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirigir según el rol del usuario
        $user = Auth::user();
        
        // LOG PARA DEBUG - incluimos valor raw y casteado a int
        \Log::info('LOGIN DEBUG', [
            'user_id' => $user->id,
            'email' => $user->email,
            'rol_raw' => $user->rol_id,
            'rol_id' => (int) $user->rol_id,
            'rol_nombre' => $user->rol->nombre ?? 'N/A'
        ]);

        // Cast a int because match() hace comparación estricta (===) en PHP 8
        $redirectRoute = match((int) $user->rol_id) {
            1 => 'admin.dashboard',
            2 => 'profesor.dashboard',
            3 => 'alumno.dashboard',
            default => 'admin.dashboard',
        };
        
        \Log::info('REDIRECT DEBUG', [
            'route_name' => $redirectRoute,
            'route_url' => route($redirectRoute)
        ]);
        
        return redirect()->route($redirectRoute);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
