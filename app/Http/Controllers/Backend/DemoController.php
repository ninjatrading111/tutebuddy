<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Demo;
use App\Models\Course;
use App\Jobs\SendEmail;
use Carbon\Carbon;

class DemoController extends Controller
{
    
    private $_times = ['00:00 AM', '00:30 AM', '01:00 AM', '01:30 AM', '02:00 AM', '02:30 AM', 
            '03:00 AM', '03:30 AM', '04:00 AM', '04:30 AM', '05:00 AM', '05:30 AM', '06:00 AM', 
            '06:30 AM', '07:00 AM', '07:30 AM', '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM', 
            '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 AM', '12:30 AM', '01:00 PM', 
            '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM', 
            '05:00 PM', '05:30 PM', '06:30 PM', '07:00 PM', '07:30 PM', '08:00 PM', '08:30 PM', 
            '09:00 PM', '09:30 PM', '10:00 PM', '10:30 PM', '11:00 PM', '11:30 PM'];
    
    private $_timezone = 'Asia/Kolkata';

    /**
     * List of Demos
     */
    public function index()
    {
        if (auth()->user()->hasRole('User') || auth()->user()->hasRole('Child')) {
            $demos = Demo::where('user_id', auth()->user()->id)->paginate(25);
            return view('backend/demo/student', compact('demos'));
        }

        if (auth()->user()->hasRole('admin')) {
            $course_ids = Course::pluck('id');
            $demos = Demo::whereIn('course_id', $course_ids)->paginate(25);
            $times = $this->_times;
            return view('backend/demo/teacher', compact('demos', 'times'));
        }

        if (auth()->user()->hasRole('Superadmin')) {
            $demos = Demo::paginate(25);
            $times = $this->_times;
            return view('backend/demo/teacher', compact('demos', 'times'));
        }
    }

    /**
     * Get Available Times for admin
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTimes(Request $request)
    {
        $times = [];

        if ($this->_timezone != $request->timezone) {
            $local_tz = Carbon::parse($request->start_time)->setTimezone($this->_timezone)->format('H:i');
            $request_tz = Carbon::parse($request->start_time)->setTimezone($request->timezone)->format('H:i');

            $local_time = strToTime($local_tz);
            $request_time = strToTime($request_tz);
            $diff_timestamp = $request_time - $local_time;
            
            foreach($this->_times as $time) {
                $timestamp = strToTime($time);
                $new_time = Carbon::parse($timestamp - $diff_timestamp)->format('g:i A');
                array_push($times, $new_time);
            }
        } else {
            $times = $this->_times;
        }

        $course_id = $request->course_id;
        $scheduled_times = Demo::where('course_id', $course_id)
            ->where('date', $request->date)
            ->where('status', '!=', 2)
            ->pluck('start_time')
            ->toArray();

        $diff_times = array_diff($times, $scheduled_times);
        
        return response()->json([
            'success' => true,
            'times' => $diff_times
        ]);
    }
    
    /**
     * Create a request
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = [
            'course_id' => $request->course_id,
            'user_id' => $request->user_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'timezone' => $request->timezone
        ];

        $demo = Demo::updateOrCreate([
            'course_id' => $request->course_id,
            'user_id' => $request->user_id,
        ], $data);

        $this->sendRequestEmail($demo);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Setup Demo session
     */
    public function setup(Request $request)
    {
        $validator = Validator::make($request->only('title'), [
            'title' => 'required|min:6|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()
            ], 200);
        }

        $demo = Demo::find($request->id);
        $demo->title = $request->title;
        $demo->date = $request->date;
        $demo->start_time = $request->start_time;
        $demo->status = 1;
        $demo->save();

        $this->sendConfirmEmail($demo);

        return response()->json([
            'success' => true
        ], 200);
    }

    private function sendRequestEmail(Demo $demo)
    {
        $teacher = $demo->course->teachers->first();

        $email_data = [
            'template_type' => 'Demo_Request_By_Student',
            'mail_data' => [
                'email' => $teacher->email,
                'demo_teacher_name' => $teacher->name,
                'demo_student_name' => auth()->user()->name,
                'demo_course_title' => $demo->course->title,
                'demo_schedule_time' => $demo->date . ' ' . $demo->start_time,
                'demo_timezone' => $demo->timezone
            ]
        ];

        try {
            SendEmail::dispatch($email_data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Send Email
     */
    private function sendConfirmEmail(Demo $demo)
    {
        $user = $demo->user;

        $email_data = [
            'template_type' => 'Demo_Confirm_By_admin',
            'mail_data' => [
                'email' => $user->email,
                'demo_teacher_name' => auth()->user()->name,
                'demo_student_name' => $user->name,
                'demo_schedule_time' => $demo->date . ' ' . $demo->start_time,
                'demo_duration' => '30'
            ]
        ];

        try {
            SendEmail::dispatch($email_data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success' => true
        ]);
        
    }
}
