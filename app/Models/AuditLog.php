<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event',
        'action',
        'area',
        'method',
        'route_name',
        'path',
        'full_url',
        'ip_address',
        'user_agent',
        'status_code',
        'auditable_type',
        'auditable_id',
        'actor_roles',
        'actor_permissions',
        'changes',
        'metadata',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'actor_roles' => 'array',
            'actor_permissions' => 'array',
            'changes' => 'array',
            'metadata' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}