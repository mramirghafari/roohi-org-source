<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserGroupMember extends Pivot
{
    protected $table = 'user_group_user';

    protected $fillable = [
        'user_group_id',
        'user_id',
        'joined_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
