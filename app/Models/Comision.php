<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $fillable = [
        'nombre',
        'color',
        'ciclo',
    ]; 

    public function docentes()
    {
        return $this->belongsToMany(User::class, 'comision_user', 'comision_id', 'user_id');
    }
}
