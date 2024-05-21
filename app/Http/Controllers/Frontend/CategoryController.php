<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Course;

class CategoryController extends Controller
{
    /**
     * Get data for category page
     */
    public function index()
    {
        $parentCategories = Category::where('parent', 0)->get();
        return view('frontend.category.index', compact('parentCategories'));
    }
}
