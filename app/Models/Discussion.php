<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discussion extends Model
{
    protected $fillable = ['user_id', 'course_id', 'lesson_id', 'title', 'question', 'topics'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function topic($id)
    {
        $topic = DB::table('discussion_topics')->where('id', $id)->first();
        return $topic->topic;
    }

    public function results()
    {
        return $this->hasMany(DiscussionResults::class);
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
