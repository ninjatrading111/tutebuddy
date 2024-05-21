<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Lesson;
use App\Models\Course;

class Test extends Model
{
    use SoftDeletes;

    protected $fillable = ['lesson_id', 'course_id', 'user_id', 'description', 'title', 'content', 'type', 'duration', 'score', 'start_date', 'timezone', 'published'];

    protected static function boot()
    {
        parent::boot();
        
        if (auth()->check()) {
            if (auth()->user()->hasRole('Instructor')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('user_id', '=', Auth::user()->id);
                });
            }

            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('published', 1);
                });
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function result($user_id = null)
    {
        if(!$user_id) {
            return $this->hasOne(TestResult::class)->where('user_id', auth()->user()->id);
        } else {
            return $this->hasOne(TestResult::class)->where('user_id', $user_id)->first();
        }
    }

    public function questions()
    {
        return $this->morphMany(Question::class, 'model');
    }
}
