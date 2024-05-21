<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\User;

class Review extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        
        if (auth()->check()) {
            if (auth()->user()->hasRole('Administrator')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->whereHas('course');
                });
            }
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class, 'reviewable_id');
    }
}

