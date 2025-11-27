<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table = 'plan_pago';
    protected $fillable = ['nombre', 'numero_cuotas', 'periodicidad', 'dias_intervalo', 'dias_maximo_total', 'activo'];
    protected $casts = ['activo' => 'boolean'];
    
    /**
     * Boot del modelo - Calcula dias_intervalo automáticamente si está vacío
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($planPago) {
            // Si dias_intervalo está vacío, calcularlo automáticamente según periodicidad
            if (empty($planPago->dias_intervalo)) {
                $planPago->dias_intervalo = match($planPago->periodicidad) {
                    'semanal' => 7,
                    'quincenal' => 15,
                    'mensual' => 30,
                    default => null
                };
            }
        });
    }
    
    public function inscripciones() { return $this->hasMany(Inscripcion::class, 'plan_pago_id'); }
}
