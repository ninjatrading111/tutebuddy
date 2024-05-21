<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\TestResult;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Tests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $course_ids = Course::pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');

        $count = [
            'all' => Test::whereIn('lesson_id', $lesson_ids)->count(),
            'published' => Test::whereIn('lesson_id', $lesson_ids)->where('published', 1)->count(),
            'pending' => Test::whereIn('lesson_id', $lesson_ids)->where('published', 0)->count(),
            'deleted' => Test::whereIn('lesson_id', $lesson_ids)->onlyTrashed()->count()
        ];

        return view('backend.tests.index', compact('count'));
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        $course_ids = Course::pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');

        switch ($type) {
            case 'all':
                $tests = Test::whereIn('lesson_id', $lesson_ids)->get();
            break;
            case 'published':
                $tests = Test::whereIn('lesson_id', $lesson_ids)->where('published', 1)->get();
            break;
            case 'pending':
                $tests = Test::whereIn('lesson_id', $lesson_ids)->where('published', 0)->get();
            break;
            case 'deleted':
                $tests = Test::whereIn('lesson_id', $lesson_ids)->onlyTrashed()->get();
            break;
            default:
                $tests = Test::whereIn('lesson_id', $lesson_ids)->get();
        }

        $data = $this->getArrayData($tests);

        $count = [
            'all' => Test::whereIn('lesson_id', $lesson_ids)->count(),
            'published' => Test::whereIn('lesson_id', $lesson_ids)->where('published', 1)->count(),
            'pending' => Test::whereIn('lesson_id', $lesson_ids)->where('published', 0)->count(),
            'deleted' => Test::whereIn('lesson_id', $lesson_ids)->onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getArrayData($tests) {
        $data = [];
        $i = 0;

        foreach($tests as $item) {
            $lesson = Lesson::find($item->lesson->id);
            $course = $lesson->course;
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . mb_substr($item->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $item->title . '</strong></small>
                                    </div>
                                </div>
                            </div>';
            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . mb_substr($course->title, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->title . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['lesson'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                            <div class="avatar avatar-sm mr-8pt">
                                <span class="avatar-title rounded-circle">' . mb_substr($lesson->title, 0, 2) . '</span>
                            </div>
                            <div class="media-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex d-flex flex-column">
                                        <p class="mb-0"><strong class="js-lists-values-lead">'
                                        . $lesson->title . '</strong></p>
                                        <small class="js-lists-values-email text-50">Teacher</small>
                                    </div>
                                </div>
                            </div>
                        </div>';

            if($item->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>';
            }

            if($item->published == 0) {
                $temp['status'] = '<div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">Drafted</small>
                                        <span class="indicator-line rounded bg-warning"></span>
                                    </div>';
            }

            $edit_route = route('admin.tests.edit', $item->id);
            $delete_route = route('admin.tests.destroy', $item->id);
            // $publish_route = route('admin.test.publish', $item->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            $show_route = route('student.test.show', [$item->lesson->slug, $item->id]);
            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);

            // if($item->published == 0) {
            //     $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
            //         data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            // } else {
            //     $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
            //         data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            // }

            if($item->trashed()) {
                $restore_route = route('admin.test.restore', $item->id);
                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-primary btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Restore"><i class="material-icons">arrow_back</i></a>';

                $forever_delete_route = route('admin.test.foreverDelete', $item->id);

                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="restore" data-toggle="tooltip"
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
     * Return Lessons html by Option tag for selected course
     */
    public function getLessons(Request $request) {

        $lessons = Lesson::where('course_id', $request->course_id)->get();

        $html = '';

        foreach($lessons as $lesson) {
            if(strlen($lesson->short_text) > 60) {
                $lesson_desc = mb_substr($lesson->short_text, 0, 60) . '...';
            } else {
                $lesson_desc = $lesson->short_text;
            }
            if(isset($request->lesson_id) && $request->lesson_id == $lesson->id) {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc' selected>$lesson->title</option>";
            } else {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc'>$lesson->title</option>";
            }
        }

        return response()->json([
            'success' => true,
            'options' => $html
        ]);
    }

    /**
     * Create a new Test
     */
    public function create()
    {
        $courses = Course::all();
        return view('backend.tests.create', compact('courses'));
    }

    /**
     * Store new test
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'lesson_id' => 'required'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $duration = 0;
        if(!empty($data['duration_hours'])) {
            $duration = (int)$data['duration_hours'] * 60;
        }
        $duration += (int)$data['duration_mins'];
        $data['duration'] = $duration;

        if(!empty($data['test_id'])) {
            $test = Test::find($data['test_id']);
            $test->update($data);
        } else {
            $test = Test::create($data);
        }

        return response()->json([
            'success' => true,
            'test_id' => $test->id
        ]);
    }

    /**
     * Edit test
     */
    public function edit($id)
    {
        $test = Test::find($id);
        $courses = Course::all();
        return view('backend.tests.edit', compact('test', 'courses'));
    }

    /**
     * Update Test
     */
    public function update(Request $request, $id)
    {
        $test = Test::find($id);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        
        try {
            $test->update($data);

            return response()->json([
                'success' => true,
                'action' => 'update'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Delete a Test
     */
    public function destroy($id)
    {
        try {
            Test::find($id)->delete();

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
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $test = Test::find($id);
        if($test->published == 1) {
            $test->published = 0;
        } else {
            $test->published = 1;
        }

        $test->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $test->published
        ]);
    }

    /**
     * Restore a Test
     */
    public function restore($id) {

        try {
            Test::withTrashed()->find($id)->restore();

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
            
            $questions = Question::where('model_id', $id)->where('model_type', Test::class)->get();
            foreach($questions as $question) {
                $question->forceDelete();
            }

            Test::withTrashed()->where('id', $id)->forceDelete();

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
     * Student Tests
     */
    public function studentTests()
    {
        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $test_result_ids = DB::table('test_results')->where('user_id', auth()->user()->id)->pluck('test_id');

        $count = [
            'all' => Test::whereIn('lesson_id', $lesson_ids)->count(),
            'result' => Test::whereIn('lesson_id', $lesson_ids)->whereIn('id', $test_result_ids)->count()
        ];

        return view('backend.tests.student', compact('count'));
    }

    /**
     * Get Test Data By Ajax
     */
    public function getStudentTestsByAjax($type)
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $test_result_ids = DB::table('test_results')->where('user_id', auth()->user()->id)->pluck('test_id');

        switch($type) {

            case 'all':
                $tests = Test::whereIn('lesson_id', $lesson_ids)->get();
            break;

            case 'result':
                $tests = Test::whereIn('lesson_id', $lesson_ids)->whereIn('id', $test_result_ids)->get();
            break;
        }

        $data = $this->getStudentData($tests);

        $count = [
            'all' => Test::whereIn('lesson_id', $lesson_ids)->count(),
            'result' => Test::whereIn('lesson_id', $lesson_ids)->whereIn('id', $test_result_ids)->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getStudentData($tests)
    {
        $data = [];
        foreach($tests as $item) {
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
                    $btn_show = '<a href="'. $show_route. '" class="btn btn-success btn-sm">Marked</a>';
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
                $btn_show = '<a href="'. $show_route. '" class="btn btn-primary btn-sm">Start</a>';
            }

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return $data;
    }

    /**
     * Submitted Tests
     */
    public function submitedTests()
    {
        $tests = Test::where('user_id', auth()->user()->id)->get();
        $test_ids = Test::where('user_id', auth()->user()->id)->pluck('id');
        $test_results = TestResult::whereIn('test_id', $test_ids);

        $count = [
            'all' => $test_results->count(),
            'marked' => $test_results->whereNotNull('mark')->count()
        ];

        return view('backend.tests.teacher', compact('count'));
    }

    /**
     * Table Data For Submitted Tests
     */
    public function getSubmitedTestsByAjax($type)
    {
        $tests = Test::where('user_id', auth()->user()->id)->get();
        $test_ids = Test::where('user_id', auth()->user()->id)->pluck('id');

        $count = [
            'all' => TestResult::whereIn('test_id', $test_ids)->count(),
            'marked' => TestResult::whereIn('test_id', $test_ids)->whereNotNull('mark')->count()
        ];

        switch($type)
        {
            case 'all':
                $test_results = TestResult::whereIn('test_id', $test_ids)->orderBy('updated_at', 'desc')->get();
            break;

            case 'marked':
                $test_results = TestResult::whereIn('test_id', $test_ids)->orderBy('updated_at', 'desc')->whereNotNull('mark')->get();
            break;
        }

        $data = [];
        foreach($test_results as $result) {
            $temp = [];
            $temp['index'] = '';
            $temp['subject'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded bg-primary text-white">
                                            '. mb_substr($result->test->title, 0, 2) .'
                                        </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-project">
                                                <strong>'. $result->test->title .'</strong>
                                            </small>
                                            <small class="text-70">
                                                Course: '. $result->test->course->title .' | Lesson: '. $result->test->lesson->title .'
                                            </small>
                                        </div>
                                    </div>
                                </div>';

            if(!empty($result->user->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $result->user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. mb_substr($result->user->name, 0, 2) .'</span>';
            }

            $temp['student'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        '. $avatar .'
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-name">'. $result->user->name .'</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

            
            $ext = pathinfo($result->attachment, PATHINFO_EXTENSION);
            if(!empty($result->attachment)) {
                if($ext == 'pdf') {
                    $img = '<img class="rounded w-50" src="'. asset('/images/pdf.png') .'" alt="image">';
                } else {
                    $img = '<img class="rounded w-50" src="'. asset('/images/docx.png') .'" alt="image">';
                }
                $temp['attachment'] = '<a href="'. asset('/storage/attachments/' . $result->attachment ) .'" target="_blank">'. $img .'</a>';
            } else {
                $temp['attachment'] = 'N/A';
            }

            $temp['status'] = '<div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                                        <span class="indicator-line rounded bg-accent"></span>
                                    </div>';

            if($result->status == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">Marked</small>
                                        <span class="indicator-line rounded bg-success"></span>
                                    </div>';
            }

            $btn_show = view('backend.buttons.show', ['show_route' => route('admin.tests.show_result', $result->id)]);
            
            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * Show Result
     */
    public function show_result($test_id)
    {
        $result = TestResult::find($test_id);
        return view('backend.tests.result', compact('result'));
    }

    /**
     * Answer of Assignment Answer
     */
    public function result_answer(Request $request)
    {
        $data = $request->all();
        $result = TestResult::find($data['result_id']);

        if(!empty($data['answer_attach'])) {
            $attachment = $request->file('answer_attach');

            // Delete existing file
            if (File::exists(public_path('/storage/attachments/' . $result->answer_attach))) {
                File::delete(public_path('/storage/attachments/' . $result->answer_attach));
            }

            $attachment_url = $this->saveFile($attachment);
            $data['answer_attach'] = $attachment_url;
        }
        
        $result->mark = $data['mark'];
        $result->answer = $data['answer'];
        $result->answer_attach = $data['answer_attach'];
        $result->status = 1;
        $result->submit_date = Carbon::now();

        $result->save();

        return response()->json([
            'success' => true,
            'action' => 'update'
        ]);
    }
}
