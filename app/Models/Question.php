<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        // if (auth()->check()) {
        //     if (auth()->user()->hasRole('Instructor')) {
        //         static::addGlobalScope('filter', function (Builder $builder) {
        //             $courses = auth()->user()->courses->pluck('id');
        //             $builder->whereHas('tests', function ($q) use ($courses) {
        //                 $q->whereIn('tests.course_id', $courses);
        //             });
        //         });
        //     }
        // }

        static::deleting(function ($question) { // before delete() method call this
            // if ($question->isForceDeleting()) {
            //     if (File::exists(public_path('/storage/uploads/' . $question->image))) {
            //         File::delete(public_path('/storage/uploads/' . $question->image));
            //     }
            // }

            foreach($question->options as $option) {
                $option->delete();
            }
        });

    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setScoreAttribute($input)
    {
        $this->attributes['score'] = $input ? $input : null;
    }

    public function options()
    {
        return $this->hasMany('App\Models\QuestionOption');
    }

    public function isAttempted($result_id){
        $result = TestsResultsAnswer::where('tests_result_id', '=', $result_id)
            ->where('question_id', '=', $this->id)
            ->first();
        if($result != null){
            return true;
        }
        return false;
    }
}
