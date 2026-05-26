<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApiLbank extends Model
{
    protected $table = 'user_api_lbank';

    protected $fillable = [
        'user_id',
        'api_key',
        'api_secret',
        'is_connected',
        'last_checked_at',
    ];

    protected $casts = [
        'api_secret' => 'encrypted',
        'is_connected' => 'boolean',
        'last_checked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
