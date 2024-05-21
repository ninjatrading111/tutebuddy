<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class OrderItem extends Model
{
    protected $guarded = [];

    public function course() {
        return $this->BelongsTo(Course::class, 'item_id');
    }

    public function item()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
