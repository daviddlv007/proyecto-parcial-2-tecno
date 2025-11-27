<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pago';
    protected $fillable = ['alumno_id', 'fecha', 'monto', 'metodo_pago_id', 'inscripcion_id'];
    protected $casts = ['monto' => 'decimal:2'];
    public function alumno() { return $this->belongsTo(Usuario::class, 'alumno_id'); }
    public function metodoPago() { return $this->belongsTo(MetodoPago::class, 'metodo_pago_id'); }
    public function inscripcion() { return $this->belongsTo(Inscripcion::class); }
}
