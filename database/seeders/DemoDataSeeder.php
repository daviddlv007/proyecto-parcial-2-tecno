<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 6. CURSOS (3 cursos)
        if (DB::table('curso')->count() === 0) {
            DB::table('curso')->insert([
                [
                    'nombre' => 'Conducción Nivel A',
                    'descripcion' => 'Curso básico de conducción para principiantes - Automóvil',
                    'tipo_curso_id' => 1,
                    'capacidad_maxima' => 20,
                    'estado_curso' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nombre' => 'Conducción Nivel B',
                    'descripcion' => 'Curso intermedio de conducción en ciudad - Automóvil',
                    'tipo_curso_id' => 1,
                    'capacidad_maxima' => 15,
                    'estado_curso' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'nombre' => 'Conducción Motocicleta',
                    'descripcion' => 'Curso básico de conducción para motocicletas',
                    'tipo_curso_id' => 1,
                    'capacidad_maxima' => 12,
                    'estado_curso' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }

        // 7. EDICIONES DE CURSO (5 ediciones: 1 pasada + 4 próximas)
        if (DB::table('curso_edicion')->count() === 0) {
            $ediciones = [
                // Edición pasada (finalizó hace 1 mes)
                [
                    'curso_id' => 1,
                    'fecha_inicio' => Carbon::now()->subMonths(2)->startOfMonth(),
                    'fecha_fin' => Carbon::now()->subMonth()->endOfMonth(),
                    'cupo_total' => 20,
                    'cupo_disponible' => 18,
                    'precio_final' => 1500.00,
                    'estado_edicion' => 'finalizado',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                // Ediciones próximas
                [
                    'curso_id' => 1,
                    'fecha_inicio' => Carbon::now()->addDays(5),
                    'fecha_fin' => Carbon::now()->addDays(35),
                    'cupo_total' => 20,
                    'cupo_disponible' => 20,
                    'precio_final' => 1500.00,
                    'estado_edicion' => 'programado',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'curso_id' => 2,
                    'fecha_inicio' => Carbon::now()->addDays(40),
                    'fecha_fin' => Carbon::now()->addDays(70),
                    'cupo_total' => 15,
                    'cupo_disponible' => 15,
                    'precio_final' => 1800.00,
                    'estado_edicion' => 'programado',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'curso_id' => 2,
                    'fecha_inicio' => Carbon::now()->addDays(75),
                    'fecha_fin' => Carbon::now()->addDays(105),
                    'cupo_total' => 15,
                    'cupo_disponible' => 15,
                    'precio_final' => 1800.00,
                    'estado_edicion' => 'programado',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'curso_id' => 3,
                    'fecha_inicio' => Carbon::now()->addDays(10),
                    'fecha_fin' => Carbon::now()->addDays(40),
                    'cupo_total' => 12,
                    'cupo_disponible' => 12,
                    'precio_final' => 1200.00,
                    'estado_edicion' => 'programado',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ];

            foreach ($ediciones as $edicion) {
                DB::table('curso_edicion')->insert($edicion);
            }
        }

        // 8. HORARIOS (15 horarios: 3 días/semana, 2 horas/curso, 5 ediciones sin solaparse)
        if (DB::table('curso_horario')->count() === 0) {
            $horarios = [
                // Edición 1 (pasada) - Curso A - Lun/Mie/Vie 08:00-10:00
                ['curso_id' => 1, 'curso_edicion_id' => 1, 'dia_semana' => 1, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 1, 'curso_edicion_id' => 1, 'dia_semana' => 3, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 1, 'curso_edicion_id' => 1, 'dia_semana' => 5, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],

                // Edición 2 - Curso A - Mar/Jue/Sáb 10:00-12:00
                ['curso_id' => 1, 'curso_edicion_id' => 2, 'dia_semana' => 2, 'hora_inicio' => '10:00:00', 'hora_fin' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 1, 'curso_edicion_id' => 2, 'dia_semana' => 4, 'hora_inicio' => '10:00:00', 'hora_fin' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 1, 'curso_edicion_id' => 2, 'dia_semana' => 6, 'hora_inicio' => '10:00:00', 'hora_fin' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],

                // Edición 3 - Curso B - Lun/Mie/Vie 14:00-16:00
                ['curso_id' => 2, 'curso_edicion_id' => 3, 'dia_semana' => 1, 'hora_inicio' => '14:00:00', 'hora_fin' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 2, 'curso_edicion_id' => 3, 'dia_semana' => 3, 'hora_inicio' => '14:00:00', 'hora_fin' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 2, 'curso_edicion_id' => 3, 'dia_semana' => 5, 'hora_inicio' => '14:00:00', 'hora_fin' => '16:00:00', 'created_at' => now(), 'updated_at' => now()],

                // Edición 4 - Curso B - Mar/Jue/Sáb 16:00-18:00
                ['curso_id' => 2, 'curso_edicion_id' => 4, 'dia_semana' => 2, 'hora_inicio' => '16:00:00', 'hora_fin' => '18:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 2, 'curso_edicion_id' => 4, 'dia_semana' => 4, 'hora_inicio' => '16:00:00', 'hora_fin' => '18:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 2, 'curso_edicion_id' => 4, 'dia_semana' => 6, 'hora_inicio' => '16:00:00', 'hora_fin' => '18:00:00', 'created_at' => now(), 'updated_at' => now()],

                // Edición 5 - Motocicleta - Mar/Jue/Sáb 08:00-10:00
                ['curso_id' => 3, 'curso_edicion_id' => 5, 'dia_semana' => 2, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 3, 'curso_edicion_id' => 5, 'dia_semana' => 4, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],
                ['curso_id' => 3, 'curso_edicion_id' => 5, 'dia_semana' => 6, 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'created_at' => now(), 'updated_at' => now()],
            ];

            foreach ($horarios as $horario) {
                DB::table('curso_horario')->insert($horario);
            }
        }

        // 9. INSCRIPCIONES (2 inscripciones a la edición pasada)
        if (DB::table('inscripcion')->count() === 0) {
            // Inscripción 1: Ana Pérez (alumno extra) - 3 cuotas
            DB::table('inscripcion')->insert([
                'alumno_id' => 5, // Ana Pérez
                'curso_id' => 1, // Conducción A
                'curso_edicion_id' => 1, // Edición pasada
                'fecha_inscripcion' => Carbon::now()->subMonths(2)->subDays(5),
                'estado_inscripcion' => 'activa',
                'plan_pago_id' => 2, // 3 cuotas
                'monto_total' => 1500.00,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Inscripción 2: Juan Alumno (presentación) - 1 cuota
            DB::table('inscripcion')->insert([
                'alumno_id' => 3, // alumno@escuela.com
                'curso_id' => 1, // Conducción A
                'curso_edicion_id' => 1, // Edición pasada
                'fecha_inscripcion' => Carbon::now()->subMonths(2)->subDays(5),
                'estado_inscripcion' => 'activa',
                'plan_pago_id' => 1, // 1 cuota
                'monto_total' => 1500.00,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 14. PAGOS (4 pagos según los planes)
        if (DB::table('pago')->count() === 0) {
            // Pago 1 - Ana Pérez cuota 1/3
            DB::table('pago')->insert([
                'alumno_id' => 5,
                'inscripcion_id' => 1,
                'fecha' => Carbon::now()->subMonths(2),
                'monto' => 500.00,
                'metodo_pago_id' => 3, // Efectivo
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Pago 2 - Ana Pérez cuota 2/3
            DB::table('pago')->insert([
                'alumno_id' => 5,
                'inscripcion_id' => 1,
                'fecha' => Carbon::now()->subMonth(),
                'monto' => 500.00,
                'metodo_pago_id' => 2, // Tarjeta
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Pago 3 - Ana Pérez cuota 3/3 (QR con campos PagoFácil)
            DB::table('pago')->insert([
                'alumno_id' => 5,
                'inscripcion_id' => 1,
                'fecha' => Carbon::now(),
                'monto' => 500.00,
                'metodo_pago_id' => 1, // QR
                'transaction_id' => 'TXN-DEMO-001',
                'payment_number' => 'PAY-DEMO-001',
                'estado_pago' => 'pagado',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Pago 4 - Juan Alumno (pago completo)
            DB::table('pago')->insert([
                'alumno_id' => 3,
                'inscripcion_id' => 2,
                'fecha' => Carbon::now()->subMonths(2),
                'monto' => 1500.00,
                'metodo_pago_id' => 3, // Efectivo
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
