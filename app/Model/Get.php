<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Get extends Model
{
    protected $table = 'get';
    protected $fillable = [
        'user_id','from','hash','to','status'
        ,'value','mainTime'
    ];
}
