<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculo';
    protected $fillable = ['placa', 'marca', 'modelo', 'anio', 'tipo_vehiculo_id', 'estado_vehiculo', 'capacidad', 'fecha_mantenimiento'];
    
    // Accessor para mantener compatibilidad con la interfaz
    public function getAñoAttribute()
    {
        return $this->attributes['anio'] ?? null;
    }
    
    // Mutator para mantener compatibilidad con la interfaz
    public function setAñoAttribute($value)
    {
        $this->attributes['anio'] = $value;
    }
    
    public function tipoVehiculo() { return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id'); }
}
