<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Course;
use App\Models\Review;

class CoursesController extends Controller
{
    //
    public function index()
    {
        // List of Courses
    }

    /**
     * Show Selected Course
     */
    public function show($slug)
    {
        $course = Course::where('slug', $slug)->first();
       
        if(!$course) {
            return abort(404);
        }

        if(auth()->check()) {
            $is_mine = empty(DB::table('course_user')->where('course_id', $course->id)->where('user_id', auth()->user()->id)->first()) ? false : true;
        } else {
            $is_mine = false;
        }

        $course_rating = 0;
        $total_ratings = 0;
        if (isset($course->reviews) && $course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }

        $is_reviewed = false;
        if(auth()->check() && $course->reviews()->where('user_id', '=', auth()->user()->id)->first()){
            $is_reviewed = true;
        }

        return view('frontend.course.course', compact('course', 'course_rating', 'total_ratings', 'is_reviewed', 'is_mine'));
    }

    public function addReview(Request $request, $id) {

        $course = Course::findORFail($id);

        $review_data = [
            'user_id' => auth()->user()->id,
            'reviewable_id' => $course->id,
            'reviewable_type' => Course::class,
            'rating' => $request->rating,
            'content' => $request->review
        ];

        try {
            $review = Review::create($review_data);
            return response()->json([
                'success' => true,
                'review' => $review
            ]);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function getCoursesByKey()
    {
        //
    }
}
