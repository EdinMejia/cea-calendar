<?php

namespace App\Models;
use App\Models\User;

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
}
