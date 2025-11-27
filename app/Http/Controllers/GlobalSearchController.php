<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Vehiculo;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\CursoEdicion;
use App\Models\CursoHorario;
use App\Models\MetodoPago;
use App\Models\PlanPago;
use App\Models\TipoCurso;
use App\Models\TipoVehiculo;
use App\Models\Rol;
use App\Models\Menu;
use App\Models\RegistroEvento;
use App\Models\VisitaPagina;
use Illuminate\Support\Facades\Log;

class GlobalSearchController extends Controller
{
    /**
     * Buscar en TODAS las tablas del sistema
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q', '');

            // Validar que haya al menos 2 caracteres
            if (strlen($query) < 2) {
                return response()->json([
                    'usuarios' => [],
                    'cursos' => [],
                    'curso_ediciones' => [],
                    'vehiculos' => [],
                    'inscripciones' => [],
                    'pagos' => [],
                    'tipos_curso' => [],
                    'tipos_vehiculo' => [],
                    'metodos_pago' => [],
                    'planes_pago' => [],
                    'roles' => [],
                    'menus' => [],
                    'horarios' => [],
                    'eventos' => [],
                    'visitas' => []
                ]);
            }

            $searchTerm = '%' . $query . '%';

            // 1. Usuarios
            $usuarios = Usuario::with('rol')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('apellido', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('telefono', 'like', $searchTerm)
                      ->orWhere('numero_documento', 'like', $searchTerm)
                      ->orWhere('direccion', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 2. Cursos
            $cursos = Curso::with('tipoCurso')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 3. Ediciones de Cursos
            $curso_ediciones = CursoEdicion::with(['curso'])
                ->whereHas('curso', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 4. Vehículos
            $vehiculos = Vehiculo::with('tipoVehiculo')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('placa', 'like', $searchTerm)
                      ->orWhere('marca', 'like', $searchTerm)
                      ->orWhere('modelo', 'like', $searchTerm)
                      ->orWhere('anio', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 5. Inscripciones
            $inscripciones = Inscripcion::with(['usuario', 'cursoEdicion.curso'])
                ->whereHas('usuario', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('apellido', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm);
                })
                ->orWhereHas('cursoEdicion.curso', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm);
                })
                ->orWhere('estado_inscripcion', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 6. Pagos
            $pagos = Pago::with(['inscripcion.usuario', 'metodoPago'])
                ->whereHas('inscripcion.usuario', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('apellido', 'like', $searchTerm);
                })
                ->orWhere('monto', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 7. Tipos de Curso
            $tipos_curso = TipoCurso::where('nombre', 'like', $searchTerm)
                ->orWhere('descripcion', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 8. Tipos de Vehículo
            $tipos_vehiculo = TipoVehiculo::where('nombre', 'like', $searchTerm)
                ->orWhere('descripcion', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 9. Métodos de Pago
            $metodos_pago = MetodoPago::where('nombre', 'like', $searchTerm)
                ->orWhere('descripcion', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 10. Planes de Pago
            $planes_pago = PlanPago::where('nombre', 'like', $searchTerm)
                ->orWhere('numero_cuotas', 'like', $searchTerm)
                ->orWhere('periodicidad', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 11. Roles
            $roles = Rol::where('nombre', 'like', $searchTerm)
                ->orWhere('descripcion', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 12. Menús
            $menus = Menu::with('rol')
                ->where('nombre', 'like', $searchTerm)
                ->orWhere('ruta', 'like', $searchTerm)
                ->orderBy('orden')
                ->limit(5)
                ->get();

            // 13. Horarios de Cursos
            $horarios = CursoHorario::with('edicion.curso')
                ->whereHas('edicion.curso', function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm);
                })
                ->orWhere('dia_semana', 'like', $searchTerm)
                ->orWhere('hora_inicio', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 14. Eventos del Sistema
            $eventos = RegistroEvento::with('usuario')
                ->where('ruta', 'like', $searchTerm)
                ->orWhere('descripcion', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 15. Visitas a Páginas
            $visitas = VisitaPagina::where('ruta', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'usuarios' => $usuarios,
                'cursos' => $cursos,
                'curso_ediciones' => $curso_ediciones,
                'vehiculos' => $vehiculos,
                'inscripciones' => $inscripciones,
                'pagos' => $pagos,
                'tipos_curso' => $tipos_curso,
                'tipos_vehiculo' => $tipos_vehiculo,
                'metodos_pago' => $metodos_pago,
                'planes_pago' => $planes_pago,
                'roles' => $roles,
                'menus' => $menus,
                'horarios' => $horarios,
                'eventos' => $eventos,
                'visitas' => $visitas
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda global: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error en la búsqueda',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
