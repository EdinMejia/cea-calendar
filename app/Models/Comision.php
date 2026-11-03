<?php

namespace App\Models;
use App\Models\User;
use App\Models\Actividad; 

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $fillable = [
        'name_comision',
        'color_comision',
        'ciclo_comision',
    ]; 

    public function docentes()
    {
        return $this->belongsToMany(User::class, 'comision_user', 'comision_id', 'user_id');
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'comisiones_id');
    }
    public function getColorCssAttribute(): string
    {
    $nombres = [
        'amarillo' => '#f1c40f', 'verde'  => '#2ecc71', 'azul' => '#3498db',
        'naranja'  => '#e67e22', 'rojo'   => '#e74c3c', 'morado' => '#9b59b6',
        'rosa'     => '#ff8fd0',
    ];
    $color = strtolower(trim((string) $this->color_comision));

    if (preg_match('/^#[0-9a-f]{6}$/', $color)) {
        return $color;
    }

    return $nombres[$color] ?? '#3B82F6';
    }
    public function getColorTextoAttribute(): string
    {
    [$r, $g, $b] = array_map(function ($par){
        $c = hexdec($par) / 255;
        return $c <= 0.03928 ? $c / 12.92 : (($c + 0.055) / 1.055) ** 2.4;
    }, str_split(ltrim($this->color_css, '#'), 2));

    $luminancia = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

    return $luminancia > 0.179 ? '#000000' : '#ffffff';
    }

}
