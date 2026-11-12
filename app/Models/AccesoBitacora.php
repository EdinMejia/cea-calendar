<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoBitacora extends Model
{
    public const UPDATED_AT = null; 
    protected $fillable = [
        'user_id',
        'usuario',
        'correo',
        'accion',
        'detalle',
        'realizado_por',
    ];
}
