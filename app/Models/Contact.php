<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'company', 'company_email', 'business_phone', 'ext', 'mobile_phone', 'meet_time', 'contact_type', 'message'];
}
