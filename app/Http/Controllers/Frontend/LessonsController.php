<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestResults;
use App\Models\TestResultAnswers;
use App\Models\ChapterStudent;
use App\Models\Assignment;
use App\Models\AssignmentResult;
use App\Models\Schedule;
use App\Models\Demo;

use Carbon\Carbon;
use App\Helpers\General\Timezone;

class LessonsController extends Controller
{
    use FileUploadTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show selected Lesson
     */
    public function show_new($course_slug, $lesson_slug)
    {
        $course = Course::where('slug', $course_slug)->first();
        $lesson = Lesson::where('course_id', $course->id)->where('slug', $slug)->first();
        $discussions = $course->discussions->take(5);
    }

    /**
     * Show selected lesson
     */
    public function show($course_slug, $slug, $step_number)
    {
        $course = Course::where('slug', $course_slug)->first();
        $lesson = Lesson::where('course_id', $course->id)->where('slug', $slug)->first();
        
        // if ($lesson->steps->count() < 1) {
        //     return back()->with('error', 'Lesson has no any steps. Please add at least one step');
        // }

        $order = $step_number == 'start' ? 1 : $step_number;

        $prev = Step::where('lesson_id', $lesson->id)->where('step', $order - 1)->first();
        $step = Step::where('lesson_id', $lesson->id)->where('step', $order)->first();
        $next = Step::where('lesson_id', $lesson->id)->where('step', $order + 1)->first();
        $discussions = $course->discussions->take(5);

        return view('frontend.course.lesson', compact('lesson', 'step', 'prev', 'next', 'discussions'));
    }

    /**
     * Get progress
     */
    public function courseProgress(Request $request)
    {
        if (\Auth::check()) {
            $lesson = Lesson::find($request->model_id);
            if ($lesson != null) {
                if ($lesson->chapterStudents()->where('user_id', \Auth::id())->get()->count() == 0) {
                    $lesson->chapterStudents()->create([
                        'model_type' => $request->model_type,
                        'model_id' => $request->model_id,
                        'user_id' => auth()->user()->id,
                        'course_id' => $lesson->course->id
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    public function completeStep($id, $type)
    {
        $step = Step::find($id);
        $course = $step->lesson->course;
        $update_data = [
            'model_type' => Step::class,
            'model_id' => $id,
            'user_id' => auth()->user()->id,
            'course_id' => $course->id
        ];

        if($type == 1) {
            try {
                ChapterStudent::updateOrCreate($update_data, $update_data);
    
                return response()->json([
                    'success' => 'true',
                    'action' => 'complete'
                ]);
            } catch (Exception $e) {
    
                return response()->json([
                    'success' => 'false',
                    'msg' => $e->getMessage()
                ]);
            }
        } elseif ($type == 0) {

            try {
                ChapterStudent::where('model_type', $update_data['model_type'])
                    ->where('model_id', $update_data['model_id'])
                    ->where('user_id', $update_data['user_id'])
                    ->delete();
    
                return response()->json([
                    'success' => 'true',
                    'action' => 'uncomplete'
                ]);
            } catch (Exception $e) {
    
                return response()->json([
                    'success' => 'false',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    public function completeLesson($id)
    {
        $lesson = Lesson::find($id);
        $update_data = [
            'model_type' => Lesson::class,
            'model_id' => $id,
            'user_id' => auth()->user()->id,
            'course_id' => $lesson->course->id
        ];

        try {
            ChapterStudent::updateOrCreate($update_data, $update_data);
            return redirect()->route('courses.show', $lesson->course->slug);
        } catch (Exception $e) {

            return back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Live Demo Lesson
     */
    public function demoSession($id)
    {
        $demo = Demo::find($id);
        $course = $demo->course;
        $meeting_name = preg_replace('/\s+/', '+', $demo->title . ' ' . $demo->start_time . ' - 30min call');
        $meeting_name = preg_replace('/[^A-Za-z0-9\-\+]/', '', $meeting_name);

        $moderatorPW = 'mp';
        $attendeePW = 'ap';

        $is_room_run = false;

        if(auth()->user()->hasRole('Instructor') || auth()->user()->hasRole('Administrator')) {

            $join_type = 'moderator';
            
            // Checking this meeting is runing or not
            if(!empty($demo->meeting_id)) {
                $is_room_run = $this->is_meet_run($demo->meeting_id);
            }

            if(!$is_room_run) { // If meeting is not runing

                $now = Carbon::now(auth()->user()->timezone)->format('H:i:s');

                $meta_start = timezone()->convertFromTimezone($demo->start_time, $demo->timezone, 'H:i:s');
                $duration_minutes = 30;
                
                // Create a new meeting
                $meeting_id = 'live-' . mb_substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 9);
                $callback_url = urlencode(config('app.url') . 'session/callback?meetingID=' . $meeting_id);

                $room_str = 'name=' . $meeting_name . '&meetingID=' . $meeting_id . '&attendeePW=' . $attendeePW;
                $room_str .= '&moderatorPW=' . $moderatorPW . '&duration=' . $duration_minutes . '&meta_start=' . $meta_start;
                $room_str .= '&meta_timezone=' . auth()->user()->timezone .'&meta_endCallbackUrl=' .$callback_url;
    
                $create_room_str = 'create' . $room_str . config('liveapp.key');
                $checksum = sha1($create_room_str);
                $room_str_checksum = $room_str . '&checksum=' . $checksum;
    
                $create_endPoint = config('liveapp.url') . 'bigbluebutton/api/create?' . $room_str_checksum;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $create_endPoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $output = curl_exec($ch);
                curl_close($ch);

                $json = json_encode(simplexml_load_string($output));
                $array = json_decode($json, true);

                if($array['returncode'] == 'SUCCESS') {
                    $meetingId = $array['meetingID'];
                    $demo->meeting_id = $meetingId;
                    $demo->save();

                    $is_room_run = true;
                }

                $join_room = $this->get_join_room($demo->meeting_id, $join_type, $moderatorPW);

                return view('frontend.live', compact('join_room', 'is_room_run'));

            } else {

                // If running meeting then check already joined teacher or not

                // Get Meeting Info
                $meeting_info = $this->get_meet_info($demo->meeting_id);

                if($meeting_info['returncode'] == 'SUCCESS') {
                    if(intval($meeting_info['moderatorCount']) > 0) {
                        return back()->with('error', 'Previous session still exist, try in 2 miniutes');
                    } else {

                        $join_room = $this->get_join_room($demo->meeting_id, $join_type, $moderatorPW);
                        return view('frontend.live', compact('join_room', 'is_room_run'));
                    }
                } else {
                    return back()->with('error', 'Something wrong');
                }
            }
        }

        if(auth()->user()->hasRole('Student') || auth()->user()->hasRole('Child')) {

            $join_type = 'attendee';
            $is_room_run = $this->is_meet_run($demo->meeting_id);

            if($is_room_run) {

                // Get Meeting Info
                $meeting_info = $this->get_meet_info($demo->meeting_id);

                if($meeting_info['returncode'] == 'SUCCESS') {
                    $attendees = $meeting_info['attendees'];
                    if(!isset($attendees['attendee']['userID'])) {
                        foreach($attendees['attendee'] as $attendee) {
                            if($attendee['role'] == 'VIEWER' && $attendee['userID'] == auth()->user()->id) {
                                return back()->with('error', 'You are already joined');
                            }
                        }
                    }
                }

                $join_room = $this->get_join_room($demo->meeting_id, $join_type, $attendeePW);

                // Make demo completed if student is joined
                // $demo->status = 2;
                // $demo->save();
                
                return view('frontend.live', compact('join_room', 'is_room_run'));
            } else {
                return back()->with('error', 'Instructor did not joined yet, please wait');
            }
        }
    }

    /**
     * Live Lesson
     */
    public function liveSession($course_slug, $lesson_id, $schedule_id)
    {
        $schedule = Schedule::find($schedule_id);
        $lesson = Lesson::find($lesson_id);
        $course = $lesson->course;

        $moderatorPW = 'mp';
        $attendeePW = 'ap';
        
        $meeting_name = preg_replace('/\s+/', '+', $course->title . ' - ' . $lesson->title . ' ' . $schedule->start_time . ' to ' . $schedule->end_time);
        $meeting_name = preg_replace('/[^A-Za-z0-9\-\+]/', '', $meeting_name);

        $is_room_run = false;

        if(auth()->user()->hasRole('Instructor') || auth()->user()->hasRole('Administrator')) {

            $join_type = 'moderator';
            
            // Checking this meeting is runing or not
            if(!empty($lesson->meeting_id)) {
                $is_room_run = $this->is_meet_run($lesson->meeting_id);
            }

            if(!$is_room_run) { // If meeting is not runing

                $now = Carbon::now(auth()->user()->timezone)->format('H:i:s');

                $meta_start = timezone()->convertFromTimezone($schedule->start_time, $schedule->timezone, 'H:i:s');
                $meta_end = timezone()->convertFromTimezone($schedule->end_time, $schedule->timezone, 'H:i:s');
                $duration_minutes = Carbon::parse($now)->diffInMinutes(Carbon::parse($meta_end));
                
                // Create a new meeting
                $meeting_id = 'live-' . mb_substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 9);
                $room_str = 'name=' . $meeting_name . '&meetingID=' . $meeting_id . '&attendeePW=' . $attendeePW;
                $room_str .= '&moderatorPW=' . $moderatorPW . '&duration=' . $duration_minutes . '&meta_start=' . $meta_start;
                $room_str .= '&meta_end=' . $meta_end . '&meta_timezone=' . auth()->user()->timezone;
    
                $create_room_str = 'create' . $room_str . config('liveapp.key');
                $checksum = sha1($create_room_str);
                $room_str_checksum = $room_str . '&checksum=' . $checksum;
    
                $create_endPoint = config('liveapp.url') . 'bigbluebutton/api/create?' . $room_str_checksum;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $create_endPoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $output = curl_exec($ch);
                curl_close($ch);

                $json = json_encode(simplexml_load_string($output));
                $array = json_decode($json, true);

                if($array['returncode'] == 'SUCCESS') {
                    $meetingId = $array['meetingID'];
                    $lesson->meeting_id = $meetingId;
                    $lesson->save();

                    $is_room_run = true;
                }

                $join_room = $this->get_join_room($lesson->meeting_id, $join_type, $moderatorPW);

                return view('frontend.live', compact('join_room', 'is_room_run'));

            } else {

                // If running meeting then check already joined teacher or not

                // Get Meeting Info
                $meeting_info = $this->get_meet_info($lesson->meeting_id);

                if($meeting_info['returncode'] == 'SUCCESS') {
                    if(intval($meeting_info['moderatorCount']) > 0) {
                        return back()->with('error', 'Already joined');
                    } else {

                        $join_room = $this->get_join_room($lesson->meeting_id, $join_type, $moderatorPW);
                        return view('frontend.live', compact('join_room', 'is_room_run'));
                    }
                } else {
                    return back()->with('error', 'Something wrong');
                }
            }
        }

        if(auth()->user()->hasRole('Student') || auth()->user()->hasRole('Child')) {

            $join_type = 'attendee';
            $is_room_run = $this->is_meet_run($lesson->meeting_id);

            if($is_room_run) {

                // Get Meeting Info
                $meeting_info = $this->get_meet_info($lesson->meeting_id);

                if($meeting_info['returncode'] == 'SUCCESS') {
                    $attendees = $meeting_info['attendees'];
                    if(!isset($attendees['attendee']['userID'])) {
                        foreach($attendees['attendee'] as $attendee) {
                            if($attendee['role'] == 'VIEWER' && $attendee['userID'] == auth()->user()->id) {
                                return back()->with('error', 'You are already joined');
                            }
                        }
                    }
                }

                $join_room = $this->get_join_room($lesson->meeting_id, $join_type, $attendeePW);

                // Set lesson completed
                $update_data = [
                    'model_type' => Lesson::class,
                    'model_id' => $lesson->id,
                    'user_id' => auth()->user()->id,
                    'course_id' => $lesson->course->id
                ];

                ChapterStudent::updateOrCreate($update_data, $update_data);

                return view('frontend.live', compact('join_room', 'is_room_run'));
            } else {
                return back()->with('error', 'Instructor did not joined yet, please wait');
            }
        }
    }

    /**
     * Session End callback function
     */
    public function endCallback(Request $request)
    {
        $demo = Demo::where('meeting_id', $request->meetingID)->first();
        $demo->status = 2;
        $demo->save();
        return redirect()->route('admin.dashboard');
    }

    /**
     * Get meeting information
     */
    private function get_meet_info($meeting_id)
    {
        $checksum = sha1('getMeetingInfomeetingID='. $meeting_id . config('liveapp.key'));
        $endpoint = config('liveapp.url') . 'bigbluebutton/api/getMeetingInfo?meetingID='. $meeting_id .'&checksum=' . $checksum;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        curl_close($ch);

        $json = json_encode(simplexml_load_string($output));
        
        return json_decode($json, true);
    }

    /**
     * Check Meeting runing or not
     */
    private function is_meet_run($meeting_id)
    {
        $checksum = sha1('isMeetingRunningmeetingID='. $meeting_id . config('liveapp.key'));
        $check_endpoint = config('liveapp.url') . 'bigbluebutton/api/isMeetingRunning?meetingID=' 
            . $meeting_id .'&checksum=' . $checksum;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $check_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        curl_close($ch);

        $json = json_encode(simplexml_load_string($output));
        $array = json_decode($json, true);
        
        if($array['returncode'] == 'SUCCESS') {
            return $array['running'] === 'true' ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Join to existing Meeting
     */
    private function get_join_room($meeting_id, $join_type, $password)
    {
        $url = config('liveapp.url') . 'bigbluebutton/api/join?';
        $callback_url = config('app.url') . 'session/callback?meetingID=' . $meeting_id;

        $room_str = 'userID='. auth()->user()->id .'&fullName=' . preg_replace('/\s+/', '+', auth()->user()->name) 
            . '&meetingID=' . $meeting_id . '&password=' . $password .'&meta_endCallbackUrl=' .$callback_url;
        $join_room_str = 'join' . $room_str . config('liveapp.key');
        $checksum = sha1($join_room_str);
        $join_room = $url . $room_str . '&checksum=' . $checksum;

        return $join_room;
    }
}
