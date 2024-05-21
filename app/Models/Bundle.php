<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Bundle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'group_price',
        'private_price',
        'bundle_image',
        'start_date',
        'published',
        'free',
        'featured',
        'trending',
        'popular',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'user_id'
    ];

    protected $appends = ['image'];


    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($bundle) { // before delete() method call this
            if($bundle->isForceDeleting()){
                if(File::exists(public_path('/storage/uploads/'.$bundle->bundle_image))) {
                    File::delete(public_path('/storage/uploads/'.$bundle->bundle_image));
                    File::delete(public_path('/storage/uploads/thumb/'.$bundle->bundle_image));
                }
            }
        });
    }

    public function scopeOfTeacher($query)
    {
        if (!Auth::user()->isAdmin()) {
            return $query->where('user_id', Auth::user()->id);
        }
        return $query;
    }


    public function getPriceAttribute()
    {
        if (($this->attributes['price'] == null)) {
            return round(0.00);
        }
        return $this->attributes['price'];
    }


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'bundle_courses');
    }

    public function mediaVideo()
    {
        $types = ['video', 'pdf', 'audio', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeOfAuthor($query)
    {
        if (!auth()->user()->hasRole('Administrator')) {
            return  $query->where('user_id', \Auth::user()->id);
        }
        return $query;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'bundle_student')->withTimestamps()->withPivot(['rating']);
    }

    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }

    public function item()
    {
        return $this->morphMany(OrderItem::class,'item');
    }

    public function getImageAttribute()
    {
        return url('storage/uploads/'.$this->bundle_image);
    }
}
