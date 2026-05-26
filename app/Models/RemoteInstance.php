<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RemoteAccessToken;

class RemoteInstance extends Model
{
    protected $fillable = [
        'remote_access_token_id',
        'instance',
        'container_name',
        'internal_port',
        'external_port',
        'access_url',
        'status',
        'last_seen_at',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function token()
    {
        return $this->belongsTo(RemoteAccessToken::class, 'remote_access_token_id');
    }
}
