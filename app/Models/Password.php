<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $table = 'passwords';
    protected $fillable = [
        'user_id',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
