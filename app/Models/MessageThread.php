<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Thread as Eloquent;

class MessageThread extends Eloquent
{
    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['subject', 'type', 'from', 'to'];
}
