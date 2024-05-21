<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Refund extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
