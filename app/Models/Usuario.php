<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;
    protected $table = 'usuario';
    protected $fillable = ['nombre', 'apellido', 'fecha_nacimiento', 'genero', 'tipo_documento', 'numero_documento', 'email', 'telefono', 'direccion', 'contrasena', 'rol_id', 'estado_usuario'];
    protected $hidden = ['contrasena', 'remember_token'];
    public function getAuthPassword() { return $this->contrasena; }
    public function rol() { return $this->belongsTo(Rol::class); }
    public function cursosComoInstructor() { return $this->hasMany(Curso::class, 'instructor_id'); }
    public function inscripciones() { return $this->hasMany(Inscripcion::class, 'alumno_id'); }
    public function pagos() { return $this->hasMany(Pago::class, 'alumno_id'); }
}
