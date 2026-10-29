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
}
