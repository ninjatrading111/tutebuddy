<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizResults;
use App\Models\QuestionGroup;
use App\Models\Lesson;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Jobs\SendEmail;

class QuizController extends Controller
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
     * List of Quiz
     */
    public function index() {
        $count = [
            'all' => Quiz::all()->count(),
            'published' => Quiz::where('published', 1)->count(),
            'pending' => Quiz::where('published', 0)->count(),
            'deleted' => Quiz::onlyTrashed()->count()
        ];

        return view('backend.quiz.index', compact('count'));
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $quizs = Quiz::all();
            break;
            case 'published':
                $quizs = Quiz::where('published', 1)->get();
            break;
            case 'pending':
                $quizs = Quiz::where('published', 0)->get();
            break;
            case 'deleted':
                $quizs = Quiz::onlyTrashed()->get();
            break;
            default:
                $quizs = Quiz::all();
        }

        $data = $this->getArrayData($quizs);

        $count = [
            'all' => Quiz::all()->count(),
            'published' => Quiz::where('published', 1)->count(),
            'pending' => Quiz::where('published', 0)->count(),
            'deleted' => Quiz::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * Add new quiz
     */
    public function create() {

        $courses = Course::all();
        return view('backend.quiz.create', compact('courses'));
    }

    /**
     * Store a Question
     */
    public function store(Request $request) {

        $data = $request->all();

        $duration = 0;
        if(!empty($data['duration_hours'])) {
            $duration = (int)$data['duration_hours'] * 60;
        }
        $duration += (int)$data['duration_mins'];

        $quiz_data = [
            'user_id' => auth()->user()->id,
            'course_id' => $data['course_id'],
            'lesson_id' => $data['lesson_id'],
            'title' => $data['title'],
            'description' => $data['short_description'],
            'duration' => $duration,
            'type' => $data['type'],
            'take_type' => $request->take_type
        ];

        if($data['type'] == "2") {
            $quiz_data['start_date'] = $data['start_date'];
            $quiz_data['timezone'] = $data['timezone'];
        }

        if(isset($data['model_id']) && ($data['model_id'] != -1)) {
            try {
                Quiz::find($data['model_id'])->update($quiz_data);
    
                return response()->json([
                    'success' => true,
                    'action' => 'update',
                    'quiz_id' => $data['model_id']
                ]);
            } catch (Exception $e) {
    
                return response()->json([
                    'success' => false,
                    'msg' => $e->getMessage()
                ]);
            }
        } else {
            try {
                $quiz = Quiz::create($quiz_data);

                // Send Email to Students
                $student_ids = DB::table('course_student')->where('course_id', $quiz->course_id)->pluck('user_id');
                $student_emails = \App\User::whereIn('id', $student_ids)->pluck('email');
                $email_data = [
                    'template_type' => 'New_Quiz_Created',
                    'mail_data' => [
                        'model_type' => Quiz::class,
                        'model_id' => $quiz->id
                    ]
                ];

                foreach($student_emails as $email) {
                    $email_data['mail_data']['email'] = $email;
                    SendEmail::dispatch($email_data);
                }
    
                return response()->json([
                    'success' => true,
                    'quiz' => $quiz,
                    'quiz_id' => $quiz->id
                ]);
            } catch (Exception $e) {
    
                return response()->json([
                    'success' => false,
                    'msg' => $e->getMessage()
                ]);
            }
        }
        
    }

    /**
     * Edit a quiz
     */
    public function edit($id) {

        $courses = Course::all();
        $quiz = quiz::find($id);
        return view('backend.quiz.edit', compact('courses', 'quiz'));
    }

    /**
     * Update a quiz
     */
    public function update(Request $request, $id) {

        $duration = 0;
        if(!empty($request->duration_hours)) {
            $duration = (int)$request->duration_hours * 60;
        }
        $duration += (int)$request->duration_mins;

        $updateData = [
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'duration' => $duration,
            'title' => $request->title,
            'description' => $request->short_description,
            'type' => $request->type,
            'take_type' => $request->take_type,
            'published' => $request->published
        ];

        if($request->type == "2") {
            $updateData['start_date'] = $request->start_date;
            $updateData['timezone'] = $request->timezone;
        }

        try {
            quiz::find($id)->update($updateData);

            return response()->json([
                'success' => true,
                'action' => 'update'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete quiz
     */
    public function destroy($id) {

        try {
            quiz::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    function getArrayData($quizs) {
        $data = [];
        $i = 0;

        foreach($quizs as $quiz) {

            if(empty($quiz->lesson)) {
                continue;
            }

            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . mb_substr($quiz->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $quiz->title . '</strong></small>
                                    </div>
                                </div>
                            </div>';
            
            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded-circle">' . mb_substr($quiz->course->title, 0, 2) . '</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-lead">'
                                                . $quiz->course->title . '</strong></p>
                                                <small class="js-lists-values-email text-50">'. $quiz->course->teachers[0]->name .'</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

            $temp['lesson'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . mb_substr($quiz->lesson->title, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $quiz->lesson->title . '</strong></p>
                                            <small class="js-lists-values-email text-50">'. $quiz->course->teachers[0]->name .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['questions'] = $quiz->questions->count();

            if(!empty($quiz->lesson_id)) {
                $temp['assigned'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">' . $quiz->lesson->name . '</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['assigned'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">No Assigned</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            $show_route = route('student.quiz.show', [$quiz->lesson->slug, $quiz->id]);
            $edit_route = route('admin.quizs.edit', $quiz->id);
            $delete_route = route('admin.quizs.destroy', $quiz->id);
            // $publish_route = route('admin.quizs.publish', $quiz->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            // if($quiz->published == 0) {
            //     $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
            //         data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            // } else {
            //     $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
            //         data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            // }

            if($quiz->trashed()) {
                $restore_route = route('admin.quizs.restore', $quiz->id);
                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-primary btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Restore"><i class="material-icons">arrow_back</i></a>';

                $forever_delete_route = route('admin.quizs.foreverDelete', $quiz->id);

                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="forever-delete" data-toggle="tooltip"
                data-original-title="Delete Forever"><i class="material-icons">delete_forever</i></a>';

                $temp['action'] = $btn_restore . '&nbsp;' . $perment_delete;
            } else {
                // if(auth()->user()->hasRole('Superadmin')) {
                //     $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
                // } else {
                //     $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
                // }

                $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }

    /**
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $quiz = Quiz::find($id);
        if($quiz->published == 1) {
            $quiz->published = 0;
        } else {
            $quiz->published = 1;
        }

        $quiz->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $quiz->published
        ]);
    }

    /**
     * Restore a Quiz
     */
    public function restore($id)
    {
        try {
            Quiz::withTrashed()->find($id)->restore();

            return response()->json([
                'success' => true,
                'action' => 'restore'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete Forever
     */
    public function foreverDelete($id)
    {
        try {

            Quiz::withTrashed()->where('id', $id)->forceDelete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Student
     */
    public function studentQuizs()
    {
        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $quizs = Quiz::whereIn('lesson_id', $lesson_ids)->get();
        $quiz_result_ids = QuizResults::where('user_id', auth()->user()->id)->pluck('quiz_id');

        $count = [
            'all' => Quiz::whereIn('lesson_id', $lesson_ids)->count(),
            'result' => Quiz::whereIn('id', $quiz_result_ids)->whereIn('lesson_id', $lesson_ids)->count()
        ];

        return view('backend.quiz.student', compact('count'));
    }

    /**
     * Get Student data by Ajax
     */
    public function getStudentQuizsByAjax($type)
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $quiz_result_ids = QuizResults::where('user_id', auth()->user()->id)->pluck('quiz_id');

        switch($type) {

            case 'all':
                $quizs = Quiz::whereIn('lesson_id', $lesson_ids)->get();
            break;

            case 'result':
                $quizs = Quiz::whereIn('id', $quiz_result_ids)->whereIn('lesson_id', $lesson_ids)->get();
            break;
        }

        $data = $this->getStudentData($quizs);

        $count = [
            'all' => Quiz::whereIn('lesson_id', $lesson_ids)->count(),
            'result' => Quiz::whereIn('id', $quiz_result_ids)->whereIn('lesson_id', $lesson_ids)->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getStudentData($quizs)
    {
        $data = [];
        foreach($quizs as $item) {
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
                    $btn_show = '<a href="'. $show_route. '" class="btn btn-primary btn-sm">Start</a>';
                }
                
            } else {
                $show_route = route('student.quiz.result', [$item->lesson->slug, $item->id]);
                $btn_show = '<a href="'. $show_route. '" class="btn btn-success btn-sm">Result</a>';
            }

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return $data;
    }
}
