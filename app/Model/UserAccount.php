<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $table = 'user_account';
    protected $fillable = [
        'user_id','account_id'
    ];
}
