<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResultAnswers extends Model
{
    protected $guarded = [];
    
    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function option(){
        return $this->belongsTo(QuestionsOption::class);
    }

    public function quizResult(){
        return $this->belongsTo(QuizResults::class);
    }
}
