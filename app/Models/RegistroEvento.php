<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RegistroEvento extends Model
{
    protected $table = 'registro_evento';
    protected $fillable = ['usuario_id', 'ruta', 'descripcion', 'fecha_evento'];
    public function usuario() { return $this->belongsTo(Usuario::class); }
    public static function registrar($ruta, $descripcion = null, $usuarioId = null) {
        return static::create([
            'usuario_id' => $usuarioId ?? auth()->id(),
            'ruta' => $ruta,
            'descripcion' => $descripcion,
            'fecha_evento' => now()
        ]);
    }
}
