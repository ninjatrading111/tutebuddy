<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessHistory extends Model
{
    protected $fillable = ['user_id', 'user_name', 'role', 'user_email', 'logined_at', 'logined_ip', 'logined_location'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
