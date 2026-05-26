<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RemoteEntryToken extends Model
{
    protected $fillable = [
        'user_id','token','scope','expires_at','used_at',
        'session_key','session_expires_at',
        'issued_ip','used_ip','user_agent','meta',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'session_expires_at' => 'datetime',
        'meta' => 'array',
    ];

    public function tokenIsValid(): bool
    {
        return !$this->used_at && Carbon::now()->lt($this->expires_at);
    }

    public function sessionIsValid(): bool
    {
        return $this->session_key
            && $this->session_expires_at
            && Carbon::now()->lt($this->session_expires_at);
    }
}
