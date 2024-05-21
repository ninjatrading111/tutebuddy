<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'order_item_id',
        'transaction_id',
        'payout_id',
        'amount',
        'tax',
        'account_fee',
        'account_gst',
        'teacher_fee',
        'teacher_gst',
        'currency',
        'type',
        'type_format',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(\App\Models\OrderItem::class, 'order_item_id');
    }
}
