<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Usuario, Curso, Inscripcion, Pago, RegistroEvento};
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_alumnos' => Usuario::where('rol_id', 3)->count(),
            'total_cursos' => Curso::where('estado_curso', 'activo')->count(),
            'total_inscripciones' => Inscripcion::count(),
            'total_ingresos' => Pago::sum('monto'),
            'inscripciones_pendientes' => Inscripcion::where('estado_inscripcion', 'pendiente')->count(),
        ];
        
        // Inscripciones por estado
        $inscripciones_por_estado = Inscripcion::select('estado_inscripcion', DB::raw('count(*) as total'))
            ->groupBy('estado_inscripcion')
            ->get();
        
        // Ingresos mensuales (últimos 6 meses)
        $ingresos_mensuales = Pago::select(
                DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
                DB::raw('EXTRACT(YEAR FROM fecha) as anio'),
                DB::raw('SUM(monto) as total')
            )
            ->where('fecha', '>=', now()->subMonths(6))
            ->groupBy('mes', 'anio')
            ->orderBy('anio', 'asc')
            ->orderBy('mes', 'asc')
            ->get();
        
        // Cursos más populares
        $cursos_populares = Curso::select('curso.*', DB::raw('COUNT(inscripcion.id) as total_inscripciones'))
            ->leftJoin('inscripcion', 'curso.id', '=', 'inscripcion.curso_id')
            ->groupBy('curso.id')
            ->orderBy('total_inscripciones', 'desc')
            ->limit(5)
            ->get();
        
        $eventos_recientes = RegistroEvento::with('usuario')->latest()->limit(10)->get();
        $inscripciones_recientes = Inscripcion::with(['alumno', 'curso'])->latest()->limit(5)->get();
        
        return Inertia::render('Admin/Dashboard', compact(
            'stats', 
            'eventos_recientes', 
            'inscripciones_recientes',
            'inscripciones_por_estado',
            'ingresos_mensuales',
            'cursos_populares'
        ));
    }
}
