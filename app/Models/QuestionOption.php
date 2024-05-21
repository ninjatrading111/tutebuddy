<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $guarded = [];
    
    /**
     * Set to null if empty
     * @param $input
     */
    public function setQuestionIdAttribute($input)
    {
        $this->attributes['question_id'] = $input ? $input : null;
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answered($result_id)
    {
        $result = TestResultAnswers::where('test_result_id', '=', $result_id)
            ->where('option_id', '=', $this->id)
            ->first();

        if ($result) {
            if ($result->correct == 1) {
                return 1;
            } elseif($result->correct == 0){
                return 2;
            }
        }
        return 0;
    }
}
