<?php
namespace App\Http\Middleware;
use Closure;
use App\Models\VisitaPagina;

class TrackPageVisits
{
    public function handle($request, Closure $next)
    {
        $ruta = $request->path();
        VisitaPagina::incrementar($ruta);
        return $next($request);
    }
}
