<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalSmsJob extends Model
{
    protected $table = 'signal_sms_jobs';

    protected $fillable = [
        'signal_id',
        'user_id',
        'status',
        'attempts',
        'scheduled_at',
        'sent_at',
        'failed_at',
        'last_error',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at'      => 'datetime',
        'failed_at'    => 'datetime',
    ];

    public function signal()
    {
        return $this->belongsTo(Signal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
