<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'name', 'email', 'password', 'ativo',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
