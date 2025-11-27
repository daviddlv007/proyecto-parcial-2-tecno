<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model
{
    protected $table = 'tipo_curso';
    protected $fillable = ['nombre', 'descripcion', 'duracion_horas', 'estado_curso', 'nivel'];
    public function cursos() { return $this->hasMany(Curso::class, 'tipo_curso_id'); }
}
