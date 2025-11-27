<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Artisan};
use Inertia\Inertia;

class DatabaseManagerController extends Controller
{
    public function index()
    {
        $stats = [
            'roles' => DB::table('rol')->count(),
            'usuarios' => DB::table('usuario')->count(),
            'vehiculos' => DB::table('vehiculo')->count(),
            'tipos_vehiculo' => DB::table('tipo_vehiculo')->count(),
            'tipos_curso' => DB::table('tipo_curso')->count(),
            'cursos' => DB::table('curso')->count(),
            'ediciones' => DB::table('curso_edicion')->count(),
            'horarios' => DB::table('curso_horario')->count(),
            'inscripciones' => DB::table('inscripcion')->count(),
            'pagos' => DB::table('pago')->count(),
            'planes_pago' => DB::table('plan_pago')->count(),
            'metodos_pago' => DB::table('metodo_pago')->count(),
            'menus' => DB::table('menu')->count(),
            'visitas' => DB::table('visita_pagina')->count(),
            'eventos' => DB::table('registro_evento')->count(),
        ];
        
        $testUsers = [
            'admin' => [
                'email' => 'admin@escuela.com',
                'password' => 'password',
                'exists' => DB::table('usuario')->where('email', 'admin@escuela.com')->exists()
            ],
            'profesor' => [
                'email' => 'profesor@escuela.com',
                'password' => 'password',
                'exists' => DB::table('usuario')->where('email', 'profesor@escuela.com')->exists()
            ],
            'alumno' => [
                'email' => 'alumno@escuela.com',
                'password' => 'password',
                'exists' => DB::table('usuario')->where('email', 'alumno@escuela.com')->exists()
            ]
        ];
        
        return Inertia::render('Admin/DatabaseManager/Index', compact('stats', 'testUsers'));
    }

    public function truncate()
    {
        try {
            DB::beginTransaction();
            
            // Orden de limpieza respetando FKs: de hijos a padres
            // Solo limpiamos tablas de datos variables, preservando configuración
            $tables = [
                'pago',                 // depende de inscripcion
                'inscripcion',          // depende de curso_edicion
                'curso_horario',        // depende de curso_edicion
                'curso_edicion',        // depende de curso
                'curso',                // depende de tipo_curso
                'vehiculo',             // depende de tipo_vehiculo
                'visita_pagina',        // independiente
                'registro_evento'       // independiente
            ];
            
            foreach ($tables as $table) {
                DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
            }
            
            // Limpiar usuarios excepto los 3 de prueba y los 2 adicionales
            DB::table('usuario')
                ->whereNotIn('email', [
                    'admin@escuela.com',
                    'profesor@escuela.com',
                    'alumno@escuela.com',
                    'maria.lopez@escuela.com',
                    'ana.perez@escuela.com'
                ])
                ->delete();
            
            DB::commit();
            return back()->with('success', 'Datos variables limpiados. Configuración base y usuarios de prueba preservados.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al limpiar BD: ' . $e->getMessage());
        }
    }

    public function seed()
    {
        try {
            // Primero ejecutar DatabaseSeeder (usuarios, tipos, planes, menús)
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
            
            // Luego DemoDataSeeder (cursos, ediciones, horarios, inscripciones, pagos)
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DemoDataSeeder']);
            
            return back()->with('success', 'Datos de demostración insertados exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al poblar BD: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::beginTransaction();
            
            // Truncar todas las tablas en orden (de hijos a padres)
            $tables = [
                'pago',
                'inscripcion',
                'curso_horario',
                'curso_edicion',
                'curso',
                'vehiculo',
                'usuario',
                'tipo_vehiculo',
                'tipo_curso',
                'plan_pago',
                'metodo_pago',
                'menu',
                'visita_pagina',
                'registro_evento'
            ];
            
            foreach ($tables as $table) {
                DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
            }
            
            DB::commit();
            
            // Poblar con seeders
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DemoDataSeeder']);
            
            return back()->with('success', 'Base de datos reseteada y poblada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al resetear BD: ' . $e->getMessage());
        }
    }
}
