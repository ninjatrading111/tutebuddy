<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // one to many
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function parent()
    {
        return $this->belongsTo(Level::class, 'parent', 'id');
    }

    public function children()
    {
        return $this->hasMany(Level::class, 'parent', 'id')->orderBy('order');
    }

}
