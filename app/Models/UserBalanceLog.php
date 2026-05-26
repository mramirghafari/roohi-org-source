<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalanceLog extends Model
{
    protected $table = 'user_balance_log';

    public $timestamps = false; // چون ستون created_at/updated_at نداره (طبق اسکرین)

    protected $fillable = [
        'user_id',
        'lbank_uid',
        'balance',
        'time',
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'time'    => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
