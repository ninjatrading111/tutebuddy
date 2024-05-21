<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Http\Controllers\Traits\FileUploadTrait;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Step;
use App\Models\Test;

class LessonController extends Controller
{
    use FileUploadTrait;

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
     * Show a lesson
     */
    public function show(Request $request, $id) {
        $lesson = Lesson::find($id);

        if ($lesson->steps->count() < 1) {
            return back()->with('error', 'Lesson has no any steps. Please add at least one step');
        }
        
        $step = Step::find($request->step);
        $next = Step::where('lesson_id', $id)->where('step', $step->step + 1)->first();
        return view('backend.lesson.show', compact('lesson', 'step', 'next'));
    }

    /**
     * Store a Lesson
     */
    public function store(Request $request) {
        
        $data = $request->all();

        $lesson_data = [
            'course_id' => $data['course_id'],
            'title' => $data['lesson_title'],
            'slug' => str_slug($data['lesson_title']),
            'video' => $data['lesson_intro_video'],
            'short_text' => $data['lesson_short_description'],
            'lesson_type' => $data['lesson_type']
        ];

        if(empty($lesson_data['title'])) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson title is required',
                'action'  => 'title'
            ]);
        }

        if(empty($lesson_data['short_text'])) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson short description is required',
                'action'  => 'description'
            ]);
        }

        if(!empty($data['lesson_file_image'])) {
            $image = $request->file('lesson_file_image');
            $lesson_image_url = $this->saveImage($image);
            $lesson_data['image'] = $lesson_image_url;
        }

        // Attachment
        if(!empty($data['lesson_file_download'])) {
            $attachment = $request->file('lesson_file_download');
            $attachment_url = $this->saveFile($attachment);
            $lesson_data['attachment'] = $attachment_url;
        }

        if($data['action'] == 'new') {

            // Get last lesson position
            $position = 1;
            $last_lesson = Lesson::where('course_id', $data['course_id'])->orderBy('position', 'desc')->first();
            if(!empty($last_lesson)) {
                $position = (int)$last_lesson->position + 1;
            }

            $lesson_data['position'] = $position;

            try {

                $lesson = Lesson::create($lesson_data);
                if(!empty($data['lesson_schedule'])) {
                    $schedule = Schedule::find($data['lesson_schedule']);
                    $schedule->lesson_id = $lesson->id;
                    $schedule->save();
                }
    
                // step Data
                $stepData = $this->stepData($data);

                foreach($stepData as $step) {
                    $new_data = [
                        'lesson_id' => $lesson->id,
                        'type' => $step['type'],
                        'step' => $step['step'],
                        'duration' => $step['duration']
                    ];

                    $new_data['title'] = empty($step['title']) ? 'Untitled' : $step['title'];

                    if ($step['type'] == 'document' && !empty($step['content'])) {
                        $document_url = $this->saveFile($step['content']);
                        $new_data[$step['type']] = $document_url;
                    } else {
                        $new_data[$step['type']] = $step['content'];
                    }

                    Step::create($new_data);

                    if($new_data[$step['type']] == 'test') {
                        $test_id = $step['content'];
                        Test::find($test_id)->update(['lesson_id' => $lesson->id]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'lesson'  => $lesson,
                    'action'  => 'new'
                ]);

            } catch(Exception $e) {
                $error = $e->getMessage();
                return response()->json([
                    'success' => false,
                    'message' => $error
                ]);
            }
        }

        if($data['action'] == 'edit') {

            try {
                $lesson = Lesson::find($data['lesson_id'])->update($lesson_data);
                if(!empty($data['lesson_schedule'])) {
                    $schedule = Schedule::find($data['lesson_schedule']);
                    $schedule->lesson_id = $data['lesson_id'];
                    $schedule->save();
                }

                // step Data
                $stepData = $this->stepData($data);

                foreach($stepData as $step) {

                    if(empty($step['id'])) {
                        $new_data = [
                            'lesson_id' => $data['lesson_id'],
                            'type' => $step['type'],
                            'step' => $step['step'],
                            'duration' => $step['duration']
                        ];

                        $new_data['title'] = empty($step['title']) ? 'Untitled' : $step['title'];

                        if ($step['type'] == 'document') {

                            if (!empty($step['content'])) {
                                $document_url = $this->saveFile($step['content']);
                                $new_data[$step['type']] = $document_url;
                            }
                        } else {
                            $new_data[$step['type']] = $step['content'];
                        }
    
                        Step::create($new_data);
                    } else {
                        $update_data = [];
                        $title = empty($step['title']) ? 'Untitled' : $step['title'];
                        $update_data['title'] = $title;
                        $update_data['duration'] = $step['duration'];

                        if ($step['type'] == 'document') {

                            if (!empty($step['content'])) {
                                $document_url = $this->saveFile($step['content']);
                                $update_data[$step['type']] = $document_url;
                            }
                        } else {
                            $update_data[$step['type']] = $step['content'];
                        }

                        $editStep = Step::find($step['id'])->update($update_data);
                    }

                    if($step['type'] == 'test') {
                        $test_id = $step['content'];
                        Test::find($test_id)->update(['lesson_id' => $data['lesson_id']]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'action' => 'edit'
                ]);

            } catch(Exception $e) {
                $error = $e->getMessage();
                return response()->json([
                    'success' => false,
                    'message' => $error
                ]);
            }
        }
        
    }

    /**
     * Delete a lesson
     */
    public function deleteLesson($id) {

        try {
            Lesson::find($id)->delete();

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
     * Delete a Step
     */
    public function deleteStep($id) {

        try {
            Step::find($id)->delete();

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
     * Get Lesson by Id
     */
    public function getLesson($id) {

        $lesson = Lesson::find($id);
        $schedule = Schedule::where('lesson_id', $id)->first();
        $steps = DB::table('lesson_steps')->where('lesson_id', $id)->orderBy('step')->get();

        return response()->json([
            'success' => true,
            'lesson' => $lesson,
            'schedule' => $schedule,
            'steps' => $steps
        ]);
    }

    function stepData($data) {
        $stepData = [];
        $types = ['video', 'text', 'document'];

        foreach($data as $type => $items) {

            if (in_array($type, $types)) {

                foreach($items as $step => $item) {

                    $itemData = [
                        'type'     => $type,
                        'title'    => $item['title'][0],
                        'content'  => $item['content'][0],
                        'duration' => $item['duration'][0],
                        'step'     => $step
                    ];

                    if(!empty($item['id'])) {

                        $itemData['id'] = $item['id'][0];
                    }

                    array_push($stepData, $itemData);
                }
            }
        }

        return $stepData;
    }

    function stepData_old($data) {

        $stepData = [];

        foreach($data as $key => $content) {

            if(preg_match('/__/', $key) && !preg_match('/id__/', $key) && !preg_match('/title__/', $key) && !preg_match('/duration__/', $key)) {

                $itemData = [
                    'content' => $content,
                    'type' => 'text',
                    'id' => '',
                    'step' => 1,
                ];

                $itemData['step'] = (int)mb_substr($key, strpos($key, '__') + 2);

                if(preg_match('/lesson_description__/', $key)) {
                    $itemData['type'] = 'text';
                    $itemData['id'] = $this->getValue($data, '/lesson_description_id__' . $itemData['step'] . '/');
                    $itemData['title'] = $this->getValue($data, '/lesson_description_title__'. $itemData['step'] .'/');
                    $itemData['duration'] = $this->getValue($data, '/lesson_description_duration__'. $itemData['step'] .'/');
                }
                if(preg_match('/lesson_video__/', $key)) {
                    $itemData['type'] = 'video';
                    $itemData['id'] = $this->getValue($data, '/lesson_video_id__' . $itemData['step'] . '/');
                    $itemData['title'] = $this->getValue($data, '/lesson_video_title__' . $itemData['step'] . '/');
                    $itemData['duration'] = $this->getValue($data, '/lesson_video_duration__' . $itemData['step'] . '/');
                }
                if(preg_match('/test__/', $key)) {
                    $itemData['type'] = 'test';
                    $itemData['id'] = $this->getValue($data, '/test_id__' . $itemData['step'] . '/');
                    $itemData['title'] = $this->getValue($data, '/test_title__' . $itemData['step'] . '/');
                    $itemData['duration'] = $this->getValue($data, '/test_duration__' . $itemData['step'] . '/');
                }
                
                array_push($stepData, $itemData);
            }
        }

        return $stepData;
    }

    function getValue($data, $regex) {

        foreach($data as $key => $item) {
            if(preg_match($regex, $key)) {
                return $item;
            }
        }

        return '';
    }


    // Student Dashboard
    public function studentLiveSessions()
    {
        $count = $this->getStudentCounts();
        return view('backend.lesson.student', compact('count'));
    }

    function getStudentCounts()
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->pluck('id');

        $schedules = Schedule::whereIn('course_id', $course_ids)->get();
        $all_count = count($schedules);
        $today_count = 0;
        
        foreach($schedules as $schedule) {
            if($schedule && Carbon::parse($schedule->date . ' ' . $schedule->start_time)->dayOfWeek == Carbon::now()->dayOfWeek) {
                $today_count++;
            }
        }

        $count = [
            'all' => $all_count,
            'today' => $today_count
        ];

        return $count;
    }

    public function getStudentLiveSessionsByAjax($type)
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->pluck('id');

        if($type == 'all') {
            $schedules = Schedule::whereIn('course_id', $course_ids)->orderBy('week_num')->get();
        }

        if($type == 'today') {
            $all = Schedule::whereIn('course_id', $course_ids)->orderBy('week_num')->get();
            $schedules = collect();

            foreach($all as $schedule) {
                if($schedule && Carbon::parse($schedule->date . ' ' . $schedule->start_time)->dayOfWeek == Carbon::now()->dayOfWeek) {
                    $schedules->push($schedule);
                }
            }
        }

        $array_schedules = $schedules->toArray();
        usort($array_schedules, array($this, 'schedule_sort'));
        $data = $this->getArrayData($array_schedules);

        $count = $this->getStudentCounts();

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
        
    }

    function getArrayData($schedules)
    {
        $data = [];

        foreach($schedules as $item) {

            $schedule = Schedule::find($item['id']);

            $temp = [];
            $temp['index'] = '';

            $new_date = new \DateTime;
            $dayofweek = strtolower(Schedule::WEEK_DAYS[Carbon::parse($schedule->date . ' ' . $schedule->start_time)->dayOfWeek]);
            $new_date->modify($dayofweek . ' this week');
            $my_date = Schedule::WEEK_DAYS[Carbon::parse($schedule->date . ' ' . $schedule->start_time)->dayOfWeek] . ', ' . Carbon::parse($new_date)->toFormattedDateString();

            $temp['weekday'] = '<strong>' . $my_date . '</strong>';
            $temp['start_time'] = '<strong>' . timezone()->convertFromTimezone($schedule->start_time, $schedule->timezone, 'H:i:s') . '</strong>';
            $temp['end_time'] = '<strong>' . timezone()->convertFromTimezone($schedule->end_time, $schedule->timezone, 'H:i:s') . '</strong>';

            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                        <div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">'. mb_substr($schedule->course->title, 0, 2) .'</span>
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column">
                                <small class="js-lists-values-project">
                                    <strong>'. $schedule->course->title .'</strong></small>
                                <small
                                    class="js-lists-values-location text-50">'. $schedule->course->teachers[0]->name .'</small>
                            </div>
                        </div>
                    </div>';

            $temp['lesson'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                        <div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-accent text-white">'. mb_substr($schedule->lesson->title, 0, 2) .'</span>
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column">
                                <small class="js-lists-values-project">
                                    <strong>'. $schedule->lesson->title .'</strong></small>
                            </div>
                        </div>
                    </div>';

            $route = route('lessons.live', [$schedule->lesson->slug, $schedule->lesson->id, $schedule->id]);
            $result = live_schedule($schedule);

            if($result['today'] && $result['status']) {
                $temp['action'] = '<a href="'. $route .'" target="_blank" class="btn btn-primary btn-sm">Join</a>';
            } else if ($result['today'] && !$result['status']) {
                if($result['result'] == 1) {
                    $temp['action'] = '<button type="button" class="btn btn-md btn-outline-primary" disabled>Finished</button>';
                } else {
                    $temp['action'] = '<button type="button" class="btn btn-md btn-outline-secondary btn-timer" 
                        data-sec="'. $result['diff'] .'">'. $result['timestr'] .'</button>';
                }
            } else {
                $temp['action'] = '<button type="button" class="btn btn-md btn-outline-primary" disabled>Scheduled</button>';
            }

            array_push($data, $temp);

        }

        return $data;
    }

    // admin dashboard
    public function adminLiveSessions()
    {
        $count = $this->getadminCounts();
        return view('backend.lesson.teacher', compact('count'));
    }

    function getadminCounts()
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->pluck('id');

        $schedules = Schedule::whereIn('course_id', $course_ids)->get();
        $all_count = count($schedules);
        $today_count = 0;
        
        foreach($schedules as $schedule) {
            if(Carbon::parse($schedule->date . ' ' . $schedule->start_time)->dayOfWeek == Carbon::now()->dayOfWeek) {
                $today_count++;
            }
        }

        $count = [
            'all' => $all_count,
            'today' => $today_count
        ];

        return $count;
    }

    public function getadminLiveSessionsByAjax($type)
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->pluck('id');

        if($type == 'all') {
            $schedules = Schedule::whereIn('course_id', $course_ids)->orderBy('week_num')->get();
        }

        if($type == 'today') {
            $all = Schedule::whereIn('course_id', $course_ids)->orderBy('week_num')->get();
            $schedules = collect();

            foreach($all as $schedule) {
                if(Carbon::parse($schedule->date . ' ' . $schedule->start_time)->dayOfWeek == Carbon::now()->dayOfWeek) {
                    $schedules->push($schedule);
                }
            }
        }

        $array_schedules = $schedules->toArray();
        usort($array_schedules, array($this, 'schedule_sort'));
        $data = $this->getArrayData($array_schedules);

        $count = $this->getadminCounts();

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
        
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
     * Sort Schedules by date
     */
    private function schedule_sort($a, $b) {
        $date_a = Carbon::parse($a['date'] . ' ' . $a['start_time']);
        $date_b = Carbon::parse($b['date'] . ' ' . $b['start_time']);

        if($date_a > $date_b) {
            return 1;
        } else {
            return -1;
        }
        return 0;
    }
}
