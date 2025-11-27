<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\TipoCurso;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        $cursosDestacados = Curso::with(['tipo_curso', 'curso_ediciones'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return Inertia::render('Public/Home', [
            'cursos' => $cursosDestacados
        ]);
    }

    public function cursos()
    {
        $cursos = Curso::with(['tipo_curso', 'curso_ediciones'])
            ->orderBy('nombre')
            ->get();

        $tipos = TipoCurso::orderBy('nombre')->get();

        return Inertia::render('Public/Cursos', [
            'cursos' => $cursos,
            'tipos' => $tipos
        ]);
    }

    public function nosotros()
    {
        return Inertia::render('Public/Nosotros');
    }
}
