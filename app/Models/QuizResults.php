<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\User;

class QuizResults extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('user_id', auth()->user()->id);
                });
            }
        }
    }
    
    public function answers()
    {
        return $this->hasMany(QuizResultAnswers::class);
    }

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
