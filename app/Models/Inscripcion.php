<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripcion';
    protected $fillable = ['alumno_id', 'curso_id', 'curso_edicion_id', 'plan_pago_id', 'estado_inscripcion', 'monto_total'];
    protected $casts = ['monto_total' => 'decimal:2'];
    
    public function alumno() { return $this->belongsTo(Usuario::class, 'alumno_id'); }
    public function usuario() { return $this->belongsTo(Usuario::class, 'alumno_id'); } // Alias para búsqueda
    public function curso() { return $this->belongsTo(Curso::class); }
    public function edicion() { return $this->belongsTo(CursoEdicion::class, 'curso_edicion_id'); }
    public function cursoEdicion() { return $this->belongsTo(CursoEdicion::class, 'curso_edicion_id'); } // Alias
    public function planPago() { return $this->belongsTo(PlanPago::class, 'plan_pago_id'); }
    public function pagos() { return $this->hasMany(Pago::class, 'inscripcion_id'); }
    
    /**
     * Calcula el monto de cada cuota según el plan de pago
     * IMPORTANTE: Los planes de pago DIVIDEN el monto_total, NO lo modifican
     */
    public function calcularMontoCuota()
    {
        if (!$this->planPago) {
            return $this->monto_total;
        }
        
        $numero_cuotas = $this->planPago->numero_cuotas ?? 1;
        return round($this->monto_total / $numero_cuotas, 2);
    }
    
    /**
     * Obtiene el saldo pendiente de pago
     */
    public function getSaldoPendiente()
    {
        $total_pagado = $this->pagos()->sum('monto');
        return $this->monto_total - $total_pagado;
    }
}

