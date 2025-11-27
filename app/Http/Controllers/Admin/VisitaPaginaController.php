<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitaPagina;
use Inertia\Inertia;

class VisitaPaginaController extends Controller
{
    public function index()
    {
        $visitas = VisitaPagina::orderBy('contador', 'desc')->paginate(50);
        return Inertia::render('Admin/Visitas/Index', [
            'visitas' => $visitas
        ]);
    }
}
