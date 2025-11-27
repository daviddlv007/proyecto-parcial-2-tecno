<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    UsuarioController,
    VehiculoController, 
    CursoController,
    CursoHorarioController,
    CursoEdicionController,
    CursoEdicionHorarioController,
    TipoCursoController,
    TipoVehiculoController,
    InscripcionController,
    PagoController,
    PlanPagoController,
    MetodoPagoController,
    RolController,
    MenuController,
    RegistroEventoController,
    VisitaPaginaController,
    DatabaseManagerController
};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rutas públicas
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/cursos-disponibles', [PublicController::class, 'cursos'])->name('cursos.publicos');
Route::get('/nosotros', [PublicController::class, 'nosotros'])->name('nosotros');

// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro público (solo alumnos)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Perfil de usuario (fuera del prefijo admin para evitar admin.profile.edit)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return Inertia::render('Profile/Edit');
    })->name('profile.edit');
    
    Route::put('/profile', function (Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . auth()->id(),
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:Masculino,Femenino,Otro'
        ]);
        
        auth()->user()->update($request->only(['nombre', 'apellido', 'email', 'telefono', 'fecha_nacimiento', 'genero']));
        
        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente');
    })->name('profile.update');
    
    Route::put('/password', function (Request $request) {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed'
        ]);
        
        auth()->user()->update([
            'password' => bcrypt($request->password)
        ]);
        
        return redirect()->route('profile.edit')->with('success', 'Contraseña actualizada correctamente');
    })->name('password.update');
});

// Rutas del Administrador
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Gestión de Usuarios
    Route::resource('usuarios', UsuarioController::class);
    
    // Gestión de Roles
    Route::get('roles', [RolController::class, 'index'])->name('roles.index');
    
    // Gestión de Menús
    Route::resource('menus', MenuController::class);
    
    // Gestión de Vehículos
    Route::resource('vehiculos', VehiculoController::class);
    
    // Gestión de Tipos de Vehículo
    Route::resource('tipos-vehiculo', TipoVehiculoController::class);
    
    // Gestión de Tipos de Curso
    Route::resource('tipos-curso', TipoCursoController::class);
    
    // Gestión de Cursos
    Route::resource('cursos', CursoController::class);
    Route::get('cursos/{curso}/horarios', [CursoController::class, 'horarios'])->name('cursos.horarios');
    Route::post('cursos-horarios', [CursoHorarioController::class, 'store'])->name('cursos-horarios.store');
    Route::delete('cursos-horarios/{cursoHorario}', [CursoHorarioController::class, 'destroy'])->name('cursos-horarios.destroy');
    
    // Gestión de Ediciones de Cursos
    Route::resource('curso-ediciones', CursoEdicionController::class);
    Route::get('curso-ediciones/{edicion}/horarios-data', [CursoEdicionController::class, 'getHorariosData'])->name('curso-ediciones.horarios-data');
    Route::post('curso-ediciones/{edicion}/horarios', [CursoEdicionHorarioController::class, 'store'])->name('curso-ediciones.horarios.store');
    Route::put('curso-ediciones/{edicion}/horarios/{horario}', [CursoEdicionHorarioController::class, 'update'])->name('curso-ediciones.horarios.update');
    Route::delete('curso-ediciones/{edicion}/horarios/{horario}', [CursoEdicionHorarioController::class, 'destroy'])->name('curso-ediciones.horarios.destroy');
    
    // Gestión de Inscripciones
    // Forzar el nombre del parámetro a 'inscripcion' para que coincida con la
    // inyección de modelo en el controlador (evita placeholders como {inscripcione}).
    Route::resource('inscripciones', InscripcionController::class)->parameters([
        'inscripciones' => 'inscripcion'
    ]);
    
    // Gestión de Pagos
    Route::resource('pagos', PagoController::class);
    
    // Gestión de Planes de Pago
    Route::resource('planes-pago', PlanPagoController::class);
    
    // Gestión de Métodos de Pago
    Route::resource('metodos-pago', MetodoPagoController::class);
    
    // Registro de Eventos
    Route::get('eventos', [RegistroEventoController::class, 'index'])->name('eventos.index');
    
    // Visitas de Páginas
    Route::get('visitas', [VisitaPaginaController::class, 'index'])->name('visitas.index');
    
    // Administración de Base de Datos
    Route::get('database', [DatabaseManagerController::class, 'index'])->name('database.index');
    Route::post('database/truncate', [DatabaseManagerController::class, 'truncate'])->name('database.truncate');
    Route::post('database/seed', [DatabaseManagerController::class, 'seed'])->name('database.seed');
    Route::post('database/reset', [DatabaseManagerController::class, 'reset'])->name('database.reset');
});

// Rutas para Profesores (rol_id = 2)
Route::middleware(['auth', 'verified'])->prefix('profesor')->name('profesor.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Profesor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('cursos', [\App\Http\Controllers\Profesor\CursoController::class, 'index'])->name('cursos');
    Route::get('cursos/{curso}', [\App\Http\Controllers\Profesor\CursoController::class, 'show'])->name('cursos.show');
    Route::get('alumnos', [\App\Http\Controllers\Profesor\AlumnoController::class, 'index'])->name('alumnos');
    Route::get('horarios', [\App\Http\Controllers\Profesor\HorarioController::class, 'index'])->name('horarios');
});

// Rutas para Alumnos (rol_id = 3)
Route::middleware(['auth', 'verified'])->prefix('alumno')->name('alumno.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Alumno\DashboardController::class, 'index'])->name('dashboard');
    Route::get('mis-cursos', [\App\Http\Controllers\Alumno\CursoController::class, 'index'])->name('cursos');
    Route::get('mis-cursos/{inscripcion}', [\App\Http\Controllers\Alumno\CursoController::class, 'show'])->name('cursos.show');
    Route::get('cursos-disponibles', [\App\Http\Controllers\Alumno\CursoController::class, 'disponibles'])->name('cursos.disponibles');
    Route::get('inscribirse/{curso_edicion}', [\App\Http\Controllers\Alumno\CursoController::class, 'inscribirse'])->name('cursos.inscribirse');
    Route::post('inscribirse', [\App\Http\Controllers\Alumno\CursoController::class, 'storeInscripcion'])->name('cursos.store-inscripcion');
    Route::get('planes-pago', [\App\Http\Controllers\Alumno\PagoController::class, 'planes'])->name('planes-pago');
    Route::get('pagos', [\App\Http\Controllers\Alumno\PagoController::class, 'index'])->name('pagos');
    Route::get('pagos/crear', [\App\Http\Controllers\Alumno\PagoController::class, 'create'])->name('pagos.create');
    Route::post('pagos', [\App\Http\Controllers\Alumno\PagoController::class, 'store'])->name('pagos.store');
    Route::post('pagos/generar-qr', [\App\Http\Controllers\Alumno\PagoController::class, 'generarQR'])->name('pagos.generar-qr');
    Route::get('horarios', [\App\Http\Controllers\Alumno\HorarioController::class, 'index'])->name('horarios');
});
