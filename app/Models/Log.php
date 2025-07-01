<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'auth_type',
        'provided_otp_code',
        'real_otp_code',
        'question_id',
        'provided_answer',
        'success',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'success' => 'boolean'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Métodos adicionales para estadísticas
    public static function getFailedAttemptsForUser($userId, $authType = null, $minutes = 60)
    {
        $query = self::where('user_id', $userId)
            ->where('success', false)
            ->where('created_at', '>=', now()->subMinutes($minutes));

        if ($authType) {
            $query->where('auth_type', $authType);
        }

        return $query->count();
    }

    public static function getLastAttemptForUser($userId, $authType = null)
    {
        $query = self::where('user_id', $userId);

        if ($authType) {
            $query->where('auth_type', $authType);
        }

        return $query->latest()->first();
    }
}
