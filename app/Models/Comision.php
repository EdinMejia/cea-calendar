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
    [$r, $g, $b] = array_map('hexdec', str_split(ltrim($this->color_css, '#'), 2));
    $luminancia = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

    return $luminancia > 0.6 ? '#000000' : '#ffffff';
    }

}
