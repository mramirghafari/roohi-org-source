<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'agt_otp';
    protected $fillable = ['id','mobile','otp','status','register_date'];

    public $timestamps = false;
}
