<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportDepartmentAssignment extends Model
{
    use HasFactory;

    protected $table = 'support_department_user';

    protected $fillable = [
        'user_id',
        'department',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
