<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistroEvento;
use Inertia\Inertia;

class RegistroEventoController extends Controller
{
    public function index()
    {
        $eventos = RegistroEvento::with('usuario')->orderBy('fecha_evento', 'desc')->paginate(50);
        return Inertia::render('Admin/RegistroEventos/Index', [
            'eventos' => $eventos
        ]);
    }
}
