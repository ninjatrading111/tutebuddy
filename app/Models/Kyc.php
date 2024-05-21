<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Kyc extends Model
{
    protected $table = "user_kyc";
    protected  $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
