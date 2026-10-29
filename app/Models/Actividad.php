<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actividad extends Model
{
    use HasFactory;
    protected $table = 'actividades';
    protected $fillable = [
        'nombre', 'lugar', 'hora_fecha', 'hora_finalizacion', 'estado', 'descripcion', 'user_id', 'comisiones_id',
    ];

    protected $casts = [
        'hora_fecha' => 'datetime',
    ];

    public function comision(){
        return $this->belongsTo(Comision::class, 'comisiones_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeProximas($query)
    {
        return $query->where('hora_fecha', '>=', now()->startOfDay());
    }

    public function scopePasadas($query)
    {
        return $query->where('hora_fecha', '<', now()->startOfDay());
    }
}
