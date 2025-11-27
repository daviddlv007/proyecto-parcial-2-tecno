<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'curso';
    protected $fillable = ['nombre', 'tipo_curso_id', 'descripcion', 'instructor_id', 'capacidad_maxima', 'precio', 'estado_curso'];
    protected $casts = ['precio' => 'decimal:2'];
    
    public function tipoCurso() { return $this->belongsTo(TipoCurso::class, 'tipo_curso_id'); }
    public function tipo_curso() { return $this->tipoCurso(); } // Alias para snake_case
    public function tipo() { return $this->tipoCurso(); } // Alias para consistencia
    public function instructor() { return $this->belongsTo(Usuario::class, 'instructor_id'); }
    public function profesor() { return $this->instructor(); } // Alias
    public function horarios() { return $this->hasMany(CursoHorario::class, 'curso_id'); }
    public function inscripciones() { return $this->hasMany(Inscripcion::class, 'curso_id'); }
    public function ediciones() { return $this->hasMany(CursoEdicion::class, 'curso_id'); }
    public function curso_ediciones() { return $this->ediciones(); } // Alias para snake_case
}
