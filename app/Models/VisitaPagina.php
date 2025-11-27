<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VisitaPagina extends Model
{
    protected $table = 'visita_pagina';
    protected $fillable = ['ruta', 'contador', 'fecha_ultimo_acceso'];
    public static function incrementar($ruta) {
        $visita = static::firstOrCreate(['ruta' => $ruta], ['contador' => 0]);
        $visita->increment('contador');
        $visita->update(['fecha_ultimo_acceso' => now()]);
        return $visita->contador;
    }
}
