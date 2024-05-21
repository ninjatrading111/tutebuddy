<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // one to many
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent', 'id');
    }

    public function level()
    {
        return $this->hasOne(Level::class);
    }

    public function coursesWithSubs()
    {
        $categoryIds = Category::where('parent', $parentId = $this->id)
            ->pluck('id')
            ->push($parentId)
            ->all();
        return Course::whereIn('category_id', $categoryIds)
            ->where('published', 1)
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->limit(16)
            ->get();
    }
}
