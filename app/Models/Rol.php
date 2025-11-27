<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $fillable = ['nombre', 'descripcion'];
    public function usuarios() { return $this->hasMany(Usuario::class, 'rol_id'); }
    public function menus() { return $this->hasMany(Menu::class, 'rol_id'); }
}
