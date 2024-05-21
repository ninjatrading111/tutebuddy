<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionResults extends Model
{
    protected $fillable = ['discussion_id', 'user_id', 'content', 'post_user_id', 'parent'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function parent()
    {
        return $this->belongsTo(DiscussionResults::class, 'parent', 'id');
    }

    public function childs()
    {
        return $this->hasMany(DiscussionResults::class, 'parent', 'id');
    }
}
