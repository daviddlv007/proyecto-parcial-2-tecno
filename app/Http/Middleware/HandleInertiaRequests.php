<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\VisitaPagina;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'apellido' => $user->apellido,
                    'email' => $user->email,
                    'rol_id' => $user->rol_id,
                    'rol' => $user->rol,
                ] : null,
            ],
            'menu' => $user ? Menu::menuParaRol($user->rol_id) : [],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'visitasPagina' => VisitaPagina::where('ruta', $request->path())->first()?->contador ?? 0,
        ];
    }
}
