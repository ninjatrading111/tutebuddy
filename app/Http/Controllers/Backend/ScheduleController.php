<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Services\CalendarService;
use App\Services\ColorService;

class ScheduleController extends Controller
{
    public function index() {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $course_ids)
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->where('published', 1)
            ->get();
        return view('backend.schedule.index', compact('courses'));
    }

    public function getScheduleData(CalendarService $calendarService, ColorService $colorService, Request $request) {

        $data = $request->all();
        $weekly_schedule_data = $calendarService->generateCalendarData($data, $colorService);

        return response()->json([
            'data' => $weekly_schedule_data
        ]);
    }

    public function storeSchedule(Request $request) {

        $base_date = Carbon::parse($request->start)->format('Y-m-d');
        $start_time = Carbon::parse($request->start)->format('H:i:s');
        $end_time = Carbon::parse($request->end)->format('H:i:s');
        $week_num = Carbon::parse($request->start)->dayOfWeek;

        if($end_time == '00:00:00') {
            $end_time = '23:59:00';
        }

        $new_data = [
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'date' => $base_date,
            'week_num' => $week_num,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'timezone' => $request->timezone
        ];

        $schedule = Schedule::create($new_data);

        return response()->json([
            'success' => true,
            'schedule_id' => $schedule->id
        ]);
    }

    /**
     * Return Lessons html by Option tag for selected course
     */
    public function getLessons(Request $request) {

        $schedule = Schedule::find($request->id);

        $course = $schedule->course;
        $course_id = $course->id;
        $course_title = $course->title;
        $lessons = Lesson::where('course_id', $course_id)->get();

        $html = '';

        foreach($lessons as $lesson) {
            if(strlen($lesson->short_text) > 60) {
                $lesson_desc = mb_substr($lesson->short_text, 0, 60) . '...';
            } else {
                $lesson_desc = $lesson->short_text;
            }
            if(!empty($schedule->lesson_id) && $schedule->lesson_id == $lesson->id) {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc' selected>$lesson->title</option>";
            } else {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc'>$lesson->title</option>";
            }
        }

        return response()->json([
            'success' => true,
            'options' => $html,
            'course_title' => $course_title,
            'lesson_id' => $schedule->lesson_id
        ]);
    }

    public function addLesson(Request $request) {

        $schedule = Schedule::find($request->id);
        $schedule->lesson_id = $request->lesson_id;
        $schedule->start_time = Carbon::parse($request->start)->format('H:i:s');
        $schedule->end_time = Carbon::parse($request->end)->format('H:i:s');

        try {
            $schedule->save();

            // Check lesson to live lesson
            $lesson = Lesson::find($request->lesson_id);
            $lesson->lesson_type = 1;
            $lesson->save();

            return response()->json([
                'success' => true,
                'action' => 'addLesson'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'action' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update Schedule
     */
    public function updateSchedule(Request $request) {

        $end_time = $request->end;
        if($end_time == '00:00:00') {
            $end_time = '23:59:00';
        }

        $schedule = Schedule::find($request->id);

        $schedule->date = Carbon::parse($request->start)->format('Y-m-d');
        $schedule->start_time = Carbon::parse($request->start)->format('H:i:s');
        $schedule->end_time = Carbon::parse($end_time)->format('H:i:s');
        $schedule->week_num = Carbon::parse($request->start)->dayOfWeek;

        try {
            $schedule->save();

            return response()->json([
                'success' => true,
                'action' => 'addLesson'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'action' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete Schedule
     */
    public function deleteSchedule(Request $request) {

        $schedule = Schedule::find($request->id);

        // Change lesson type
        $lesson = $schedule->lesson;
        $lesson->lesson_type = 0;
        $lesson->save();

        // Delete Schedule
        try {
            $schedule->delete();

            return response()->json([
                'success' => true,
                'action' => 'delete'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'action' => $e->getMessage()
            ]);
        }
        
    }
}
