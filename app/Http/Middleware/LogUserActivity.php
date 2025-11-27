<?php
namespace App\Http\Middleware;
use Closure;
use App\Models\RegistroEvento;

class LogUserActivity
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            RegistroEvento::registrar(
                $request->path(),
                $request->method() . ' ' . $request->path(),
                auth()->id()
            );
        }
        return $next($request);
    }
}
