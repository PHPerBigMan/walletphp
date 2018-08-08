<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notice';
    protected $fillable = [
        'type','title','content','abbreviations','user_id','is_read'
    ];
}
