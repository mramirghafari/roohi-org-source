<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserGroupSupportAccount extends Pivot
{
    protected $table = 'user_group_support_accounts';

    protected $fillable = [
        'user_group_id',
        'support_user_id',
        'assigned_by',
        'assignment_role',
    ];

    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    public function supportUser()
    {
        return $this->belongsTo(User::class, 'support_user_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
