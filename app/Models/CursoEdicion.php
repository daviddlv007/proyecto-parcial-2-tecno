<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoEdicion extends Model
{
    protected $table = 'curso_edicion';
    
    protected $fillable = [
        'curso_id',
        'fecha_inicio',
        'fecha_fin',
        'cupo_total',
        'cupo_disponible',
        'precio_final',
        'estado_edicion'
    ];
    
    protected $casts = [
        'precio_final' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
    
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
    
    public function horarios()
    {
        return $this->hasMany(CursoHorario::class, 'curso_edicion_id');
    }
    
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'curso_edicion_id');
    }
    
    public function getInscritos()
    {
        return $this->inscripciones()->count();
    }
    
    public function actualizarCupoDisponible()
    {
        $this->cupo_disponible = $this->cupo_total - $this->getInscritos();
        $this->save();
    }
}
