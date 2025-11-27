<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitaPagina;
use Symfony\Component\HttpFoundation\Response;

class ContadorVisitas
{
    public function handle(Request $request, Closure $next): Response
    {
        // Contar visitas GET (excluyendo assets, API, archivos)
        if ($request->isMethod('get') && 
            !str_starts_with($request->path(), 'build/') && 
            !str_starts_with($request->path(), 'api/') &&
            !str_contains($request->path(), '.')) {
            
            $ruta = $request->path();
            
            // Buscar registro existente
            $visita = VisitaPagina::where('ruta', $ruta)->first();
            
            if ($visita) {
                // Si existe, incrementar contador
                $visita->increment('contador');
                $visita->update(['fecha_ultimo_acceso' => now()]);
            } else {
                // Si no existe, crear nuevo
                VisitaPagina::create([
                    'ruta' => $ruta,
                    'contador' => 1,
                    'fecha_ultimo_acceso' => now()
                ]);
            }
        }

        return $next($request);
    }
}
