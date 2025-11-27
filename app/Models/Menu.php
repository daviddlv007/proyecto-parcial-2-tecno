<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = ['nombre', 'ruta', 'icono', 'orden', 'rol_id', 'activo'];
    protected $casts = ['activo' => 'boolean'];
    
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
    
    public static function menuParaRol($rolId)
    {
        return static::where('activo', true)
            ->where(function($q) use ($rolId) {
                $q->whereNull('rol_id')->orWhere('rol_id', $rolId);
            })
            ->orderBy('orden')
            ->orderBy('id')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->nombre,
                'href' => $m->ruta,
                'icon' => $m->icono,
                'orden' => $m->orden,
                'activo' => $m->activo,
            ]);
    }
}

