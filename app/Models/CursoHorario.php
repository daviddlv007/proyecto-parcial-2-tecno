<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CursoHorario extends Model
{
    protected $table = 'curso_horario';
    protected $fillable = ['curso_id', 'curso_edicion_id', 'dia_semana', 'hora_inicio', 'hora_fin'];
    
    public function curso() { return $this->belongsTo(Curso::class); }
    public function edicion() { return $this->belongsTo(CursoEdicion::class, 'curso_edicion_id'); }
}
