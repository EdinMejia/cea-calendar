<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read string $name
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;
    public const PENDIENTE = 0;
    public const ACTIVO = 1;
    public const INACTIVO = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_one',
        'name_two', 
        'last_name_one', 
        'last_name_two', 
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopePendientes($query)
    {
        return $query->where('status', self::PENDIENTE);
    }

    public function getNameAttribute(): string
    {
        return preg_replace('/\s+/', ' ', trim(
            "{$this->name_one} {$this->name_two} {$this->last_name_one} {$this->last_name_two}"
        ));
    }
}
