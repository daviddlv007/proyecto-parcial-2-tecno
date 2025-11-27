<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RegistroEvento;
use Symfony\Component\HttpFoundation\Response;

class RegistrarEvento
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // No registrar eventos en operaciones de database management (truncate/seed/reset)
        if (str_contains($request->path(), 'admin/database')) {
            return $response;
        }

        // Solo registrar eventos CUD y autenticación
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            // CRÍTICO: Solo registrar si hay un usuario autenticado válido
            // Esto previene errores de foreign key cuando la BD está vacía
            if (!auth()->check() || !auth()->id()) {
                return $response;
            }

            $descripcion = $this->getDescripcion($request);
            
            try {
                RegistroEvento::create([
                    'usuario_id' => auth()->id(),
                    'ruta' => $request->path(),
                    'descripcion' => $descripcion,
                ]);
            } catch (\Exception $e) {
                // Silenciar errores de foreign key (usuario eliminado, BD reseteada, etc)
                // El evento no es crítico para la operación principal
                \Log::debug('RegistrarEvento falló: ' . $e->getMessage());
            }
        }

        return $response;
    }

    private function getDescripcion(Request $request): string
    {
        $method = $request->method();
        $path = $request->path();

        if (str_contains($path, 'login')) return 'Inicio de sesión';
        if (str_contains($path, 'logout')) return 'Cierre de sesión';
        
        $action = match($method) {
            'POST' => 'Creación',
            'PUT', 'PATCH' => 'Actualización',
            'DELETE' => 'Eliminación',
            default => 'Operación'
        };

        return "$action en $path";
    }
}
