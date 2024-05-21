<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use DB;

class EnrollController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function subscribe(Request $request) {
        $data = $request->all();

        $course = Course::find($data['course_id']);
        $price = ($data['type'] == 'group') ? $course->group_price : $course->private_price;

        // Make Enroll
        DB::table('course_student')->insert([
            'course_id' => $course->id,
            'user_id' => auth()->user()->id,
            'type' => $data['type']
        ]);

        return response()->json([
            'success' => true,
            'action' => 'subscribe'
        ]);
    }
}
