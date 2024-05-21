<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Test;
use App\Models\Quiz;
use App\User;

class ResultsController extends Controller
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

    /**
     *  Show all of Courses
     */
    public function student() {

        return view('backend.results.student');
    }

    /**
     * Get Student Results Data By Ajax
     */
    public function getStudentTableData()
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $course_ids)->get();

        $data = $this->getArrayData($courses);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getArrayData($courses) {
        $data = [];
        $i = 0;

        foreach($courses as $course) {
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . mb_substr($course->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $course->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $course->slug .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . mb_substr($course->teachers[0]->name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->teachers[0]->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">'. $course->teachers[0]->roles->pluck('name')[0] . '</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            if(!empty($course->category))
                $temp['category'] = $course->category->name;
            else 
                $temp['category'] = 'No Category';

            if($course->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            $detail_route = route('admin.results.detail', $course->id);

            $temp['action'] = '<a href="'. $detail_route. '" class="btn btn-success btn-sm">Detail</a>';

            array_push($data, $temp);
        }

        return $data;
    }

    public function getResultDetail($id)
    {
        $course = Course::find($id);
        $lesson_ids = Lesson::where('course_id', $id)->pluck('id');

        // Assignments
        $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->get();

        // Tests
        $tests = Test::whereIn('lesson_id', $lesson_ids)->get();

        // Quizzes
        $quizs = Quiz::whereIn('lesson_id', $lesson_ids)->get();

        return view('backend.results.student_detail', compact('course', 'assignments', 'tests', 'quizs'));
    }

    public function getStudentResultDetail($user_id, $course_id)
    {
        $user = User::find($user_id);
        $course = Course::find($course_id);
        
        $lesson_ids = Lesson::where('course_id', $course_id)->pluck('id');

        // Assignments
        $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->get();

        // Tests
        $tests = Test::whereIn('lesson_id', $lesson_ids)->get();

        // Quizzes
        $quizs = Quiz::whereIn('lesson_id', $lesson_ids)->get();

        return view('backend.results.student_performance', compact('user', 'course', 'assignments', 'tests', 'quizs'));
    }

    /**
     * Students Badges
     */
    public function badges()
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $course_ids)->get();
        $badges = new Collection();

        foreach($courses as $course) {
            $lesson_ids = Lesson::where('course_id', $course->id)->pluck('id');

            $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->get();
            foreach($assignments as $assignment) {
                if($assignment->result) {
                    $percent = round($assignment->result->mark / $assignment->total_mark * 100);
                    if($percent > 70) {
                        $badges->push((object)[
                            'percent' => $percent,
                            'type' => 'assignment',
                            'data' => $assignment
                        ]);
                    }
                }
            }

            $tests = Test::whereIn('lesson_id', $lesson_ids)->get();
            foreach($tests as $test) {
                if($test->result) {
                    $percent = round($test->result->mark / $test->score * 100);
                    if($percent > 70) {
                        $badges->push((object)[
                            'percent' => $percent,
                            'type' => 'test',
                            'data' => $test
                        ]);
                    }
                }
            }

            $quizs = Quiz::whereIn('lesson_id', $lesson_ids)->get();
            foreach($quizs as $quiz) {
                if($quiz->result) {
                    $percent = round($quiz->result->quiz_result / $quiz->score * 100);
                    if($percent > 70) {
                        $badges->push((object)[
                            'percent' => $percent,
                            'type' => 'quiz',
                            'data' => $quiz
                        ]);
                    }
                }
            }
        }

        // Sort badge array
        $badges = $badges->sortBy('percent')->reverse();

        return view('backend.results.badges', compact('badges'));
    }
}
