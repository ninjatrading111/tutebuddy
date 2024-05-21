<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionGroup extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany(Question::class, 'group_id');
    }
}
