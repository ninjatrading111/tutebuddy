<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bundle;

class BundlesController extends Controller
{
    public function show($slug)
    {
        $bundle = Bundle::where('slug', $slug)->first();

        $bundle_rating = 0;
        $total_ratings = 0;
        if ($bundle->reviews->count() > 0) {
            $bundle_rating = $bundle->getRatingAttribute();
            $total_ratings = $bundle->reviews()->where('rating', '!=', "")->get()->count();
        }

        return view('frontend.course.bundle', compact('bundle', 'bundle_rating', 'total_ratings'));
    }
}
