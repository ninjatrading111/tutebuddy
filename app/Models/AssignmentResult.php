<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\User;
use Illuminate\Support\Facades\Auth;

class AssignmentResult extends Model
{
    protected $fillable = ['assignment_id', 'user_id', 'content', 'attachment_url', 'mark'];

    protected static function boot()
    {
        parent::boot();
        
        if (auth()->check()) {
            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('user_id', '=', Auth::user()->id);
                });
            }
        }
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
