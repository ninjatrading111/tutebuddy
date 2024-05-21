<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public $table = 'lesson_steps';
    protected $guarded = [];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getTest()
    {
        return $this->hasOne(Test::class, 'id', 'test');
    }

    /**
     * Get completed status
     */
    public function isCompleted()
    {
        $c = ChapterStudent::where('model_type', Step::class)->where('model_id', $this->id)->get();

        if(count($c) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
