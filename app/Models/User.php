<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'email',
        'password',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password'
    ];

    // Relaciones
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function passwords()
    {
        return $this->hasMany(Password::class);
    }
}
