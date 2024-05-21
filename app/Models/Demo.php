<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Lesson;

class Demo extends Model
{
    protected  $guarded = [];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function schedule()
    {
        $course_id = $this->course->id;
        $demo_lesson_id = Lesson::where('course_id', $course_id)->where('lesson_type', 2)->pluck('id');
        $schedule = Schedule::where('course_id', $course_id)->whereIn('lesson_id', $demo_lesson_id)->first();

        return $schedule;
    }
}
