<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Test;
use App\Models\Quiz;

class ChildsController extends Controller
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
    public function getChild($id)
    {
        $child = User::find($id);

        // Get Child courses
        $course_ids = DB::table('course_student')->where('user_id', $id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
        $courses = Course::whereIn('id', $course_ids);

        // Get Child Assignments
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $assignments = Assignment::whereIn('lesson_id', $lesson_ids);

        // Get Tests
        $tests = Test::whereIn('lesson_id', $lesson_ids);

        // Get Quizzes
        $quizzes = Quiz::whereIn('lesson_id', $lesson_ids);

        $count = [
            'courses' => $courses->count(),
            'assignments' => $assignments->count(),
            'tests' => $tests->count(),
            'quizzes' => $quizzes->count()
        ];

        return view('backend.childs.index', compact('count', 'child'));
    }

    /**
     * Get Child Courses
     */
    public function getChildCourses($id)
    {
        // Get Child courses
        $course_ids = DB::table('course_student')->where('user_id', $id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
        $courses = Course::whereIn('id', $course_ids);
        $count = $courses->count();

        $data = [];
        $i = 0;

        foreach($courses->get() as $course) {
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $avatar = '<div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">CO</span>
                        </div>';

            if(!empty($course->course_image)) {
                $avatar = '<div class="avatar avatar-sm mr-8pt">
                                <img src="'. asset('storage/uploads/thumb/' . $course->course_image) .'" alt="Avatar" class="avatar-img rounded">
                            </div>';
            }
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">'. $avatar .'
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
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            if(!empty($course->category))
                $temp['category'] = $course->category->name;
            else 
                $temp['category'] = 'No Category';

            $progress = 0;
            $completed_lessons = User::find($id)->chapters()
                ->where('model_type', Lesson::class)
                ->where('course_id', $course->id)
                ->pluck('model_id')->toArray();

            if (count($completed_lessons) > 0) {
                $progress = intval(count($completed_lessons) / $course->lessons->count() * 100);
            }
            
            $temp['progress'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. $progress . '% </small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';

            $show_route = route('courses.show', $course->slug);
            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getChildAssignments($id)
    {
        // Get Child courses
        $course_ids = DB::table('course_student')->where('user_id', $id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');

        // Get Child Assignments
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $assignments = Assignment::whereIn('lesson_id', $lesson_ids);
        $count = $assignments->count();

        $data = [];
        foreach($assignments->get() as $item) {
            $lesson = Lesson::find($item->lesson->id);
            $course = $lesson->course;
            $temp = [];

            $temp['index'] = '';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'. mb_substr($item->title, 0, 2) .'</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>'. $item->title .'</strong></small>
                                        <small class="text-70">
                                            Course: '. $lesson->course->title .' |
                                            Lesson: '. $lesson->title .'
                                        </small>
                                    </div>
                                </div>
                            </div>';

            $temp['due'] = '<strong>' . $item->due_date . '</strong>';
            $temp['mark'] = '<strong>' . $item->total_mark . '</strong>';

            if($item->result && $item->result->count() > 0) {
                $show_route = route('student.assignment.result', [$lesson->slug, $item->id]);
                if(!empty($item->result->mark)) {
                    $btn_show = '<a href="'. $show_route . '" class="btn btn-success btn-sm">Reviewed</a>';
                } else {
                    $btn_show = '<a href="javascript:void(0)" class="btn btn-secondary btn-sm">Reviewing</a>';
                }
            } else {
                // $btn_show = '<a href="'. route('student.assignment.show', [$lesson->slug, $item->id]). '" class="btn btn-primary btn-sm">Start</a>';
                $btn_show = 'N/A';
            }

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getChildTests($id)
    {
        // Get Child courses
        $course_ids = DB::table('course_student')->where('user_id', $id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');

        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $tests = Test::whereIn('lesson_id', $lesson_ids);

        $count = $tests->count();

        $data = [];
        foreach($tests->get() as $item) {
            $lesson = Lesson::find($item->lesson->id);
            $course = $lesson->course;
            $temp = [];
            $temp['index'] = '';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'. mb_substr($item->title, 0, 2) .'</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>'. $item->title .'</strong></small>
                                        <small class="text-70">
                                            '. $item->lesson->course->title .' |
                                            '. $item->lesson->title .'
                                        </small>
                                    </div>
                                </div>
                            </div>';


            $hours = floor($item->duration / 60);
            $mins = $item->duration % 60;

            $temp['duration'] = $hours . ' Hours ' . $mins . ' Mins';
            $temp['mark'] = '<strong>' . $item->score . '</strong>';

            if($item->result && $item->result->count() > 0) {

                $hours = floor($item->result->due_time / 3600);
                $mins = floor($item->result->due_time % 3600 / 60);
                $temp['duration'] = $hours . ' Hours ' . $mins . ' Mins';

                $show_route = route('student.test.result', [$item->lesson->slug, $item->id]);

                if(!empty($item->result->mark)) {
                    $temp['mark'] = '<strong>' . $item->result->mark . '/' . $item->score . '</strong>';
                    $btn_show = '<a href="'. $show_route. '" class="btn btn-success btn-sm">Reviewed</a>';
                } else {
                    $temp['mark'] = '<strong>' . $item->score . '</strong>';
                    $btn_show = '<a href="javascript:void(0)" class="btn btn-secondary btn-sm">Reviewing</a>';
                }

            } else {

                $hours = floor($item->duration / 60);
                $mins = $item->duration % 60;
                $temp['duration'] = $hours . ' Hours ' . $mins . ' Mins';
                $temp['mark'] = '<strong>' . $item->score . '</strong>';

                $show_route = route('student.test.show', [$item->lesson->slug, $item->id]);
                // $btn_show = '<a href="'. $show_route. '" class="btn btn-primary btn-sm">Start</a>';
                $btn_show = 'N/A';
            }

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getChildQuizzes($id)
    {
        $course_ids = DB::table('course_student')->where('user_id', $id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');

        $quizzes = Quiz::whereIn('lesson_id', $lesson_ids);

        $count = $quizzes->count();

        $data = [];
        foreach($quizzes->get() as $item) {
            $lesson = Lesson::find($item->lesson->id);
            $course = $lesson->course;
            $temp = [];
            $temp['index'] = '';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'. mb_substr($item->title, 0, 2) .'</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>'. $item->title .'</strong></small>
                                        <small class="text-70">
                                            '. $item->lesson->course->title .' |
                                            '. $item->lesson->title .'
                                        </small>
                                    </div>
                                </div>
                            </div>';

            $temp['type'] = 'Any time';
            if($item->type == 2) {
                $temp['type'] = 'Fixed time';
            }

            $hours = floor($item->duration / 60);
            $mins = $item->duration % 60;

            $temp['duration'] = $hours . ' Hours ' . $mins . ' Mins';

            $temp['due'] = '<strong>N/A</strong>';
            if(!empty($item->start_date)) {
                $temp['due'] = '<strong>' . $item->start_date . '</strong>';
            }
            $temp['mark'] = '<strong>' . $item->score . '</strong>';

            if(empty($item->result)) {
                $show_route = route('student.quiz.show', [$item->lesson->slug, $item->id]);

                if($item->type == 2) {
                    $now = timezone()->convertFromTimezone(\Carbon\Carbon::now(), $item->timezone, 'H:i:s');
                    $start_time = timezone()->convertFromTimezone($item->start_date, $item->timezone, 'H:i:s');

                    $diff = strtotime($start_time) - strtotime($now);

                    if($diff < 1800) {
                        $btn_show = '<a href="'. $show_route. '" class="btn btn-primary btn-sm">Start</a>';
                    } else {
                        $btn_show = '<button class="btn btn-outline-primary btn-sm" disabled>Scheduled</button>';
                    }
                } else {
                    // $btn_show = '<a href="'. $show_route. '" class="btn btn-primary btn-sm">Start</a>';
                    $btn_show = 'N/A';
                }
                
            } else {
                $show_route = route('student.quiz.result', [$item->lesson->slug, $item->id]);
                $btn_show = '<a href="'. $show_route. '" class="btn btn-success btn-sm">Result</a>';
            }

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }
}
