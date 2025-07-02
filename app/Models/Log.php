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
        'question_id',
        'provided_answer',
        'success',
        'ip_address',
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
}
