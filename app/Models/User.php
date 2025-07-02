<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

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

    // ======================= METODITOS DE AYUDA =======================
    // Muestra el email desencriptado
    public function getEmail()
    {
        return Crypt::decryptString($this->email);
    }

    // Necesita un email desencriptado para comparar
    public static function compareEmail($email)
    {
        $users = self::all();

        foreach ($users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }

        return null;
    }

    // Necesita un password desencriptado para comparar
    public function comparePassword($password)
    {
        $decryptedPassword = Crypt::decryptString($this->password);
        return $decryptedPassword === $password;
    }
}
