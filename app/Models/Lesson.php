<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Lesson extends Model
{
    use SoftDeletes;

    public $table = 'lesson';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    if(!str_contains(request()->route()->uri(), 'myprofile')) {
                        $builder->whereNotIn('lesson_type',  [2]);
                    }
                });
            }
        } else {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->whereNotIn('lesson_type',  [2]);
            });
        }
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }

    public function chapterStudents()
    {
        return $this->morphMany(ChapterStudent::class, 'model');
    }

    public function lessonDuration()
    {
        return $this->steps->sum('duration');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get completed status
     */
    public function isCompleted($user_id = null)
    {
        if(!$user_id) {
            $c = ChapterStudent::where('model_type', Lesson::class)
            ->where('model_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->get();
        } else {
            $c = ChapterStudent::where('model_type', Lesson::class)
            ->where('model_id', $this->id)
            ->where('user_id', $user_id)
            ->get();
        }

        if(count($c) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getIsAddedToCart(){
        if(auth()->check() && (auth()->user()->hasRole('student')) && (\Cart::session(auth()->user()->id)->get( $this->id))){
            return true;
        }
        return false;
    }

    
}
