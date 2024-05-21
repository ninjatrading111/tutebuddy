<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;
use Carbon\Carbon;

class CalendarService
{
    public function generateCalendarData($data, $colorService)
    {
        $calendarData = [];

        $courses = Course::where('start_date', '<', $data['end'])
            ->where('end_date', '>', $data['start'])->get();

        foreach($courses as $course) {

            $repeat_type = $course->repeat_type;
            $repeat_value = $course->repeat_value;
            $schedules = $course->schedule;
            $color_set = $colorService->getScheduleColor($course->style);

            if(!empty($schedules)) {
                foreach($schedules as $schedule) {

                    $course_title = (strlen($course->title) > 12) ? (substr($course->title, 0, 12) . '...') : $course->title;
                    $full_course_title = $course->title;

                    $base_date = Carbon::parse($schedule['date'] . ' ' . $schedule['start_time']);
                    $start_date = Carbon::parse($data['start']); // start date
                    $end_date = Carbon::parse($data['end']); // End date

                    if($repeat_type == 'week') {

                        $day_index = $base_date->dayOfWeek; // Week of scheduled date
                        $view_weeks = $end_date->diffInWeeks($start_date);

                        for ($i = 0; $i < $view_weeks; $i++) {

                            $skip = ($i * 7) + $day_index - 1;
                            $i_date = $start_date->addDays($skip);

                            // Difference i_date and base_date
                            $diff_weeks = $base_date->diffInWeeks($i_date);
                            $repeat_check = $diff_weeks % $repeat_value;

                            if($repeat_check == 0) {

                                $start = $i_date->format('Y-m-d') . 'T' . Carbon::parse($schedule['start_time'])->format('H:i:s');
                                $end = $i_date->format('Y-m-d') . 'T' . Carbon::parse($schedule['end_time'])->format('H:i:s');

                                $item = [
                                    'id' => $schedule->id,
                                    'title' => 'Course: '. $course_title,
                                    'full_course_title' => $full_course_title,
                                    'start' => $start,
                                    'end' => $end,
                                    'display' => 'block',
                                    'color' => $color_set['background_color'],
                                    'textColor' => $color_set['font_color'],
                                    'timezone' => $schedule->timezone,
                                    'course_id' => $course->id,
                                    'start_time' => $schedule['start_time'],
                                    'end_time' => $schedule['end_time']
                                ];

                                // lesson check
                                if(!empty($schedule->lesson_id)) {
                                    $lesson = Lesson::find($schedule->lesson_id);
                                    if(!empty($lesson)) {
                                        $lesson_title = (strlen($lesson->title) > 10) ? (substr($lesson->title, 0, 10) . '...') : $lesson->title;
                                        $item['lesson'] = $lesson->position . '. ' . $lesson_title;
                                        $item['full_lesson_title'] = $lesson->title;
                                    }
                                }
    
                                array_push($calendarData, $item);
                            }
                            // Init start Date
                            $start_date = Carbon::parse($data['start']);
                        }

                    }
                    
                    if($repeat_type == 'month') {
                        
                        $view_months = $end_date->diffInMonths($start_date) + 1;
                        $date_index = $base_date->day;

                        for($i = 0; $i <= $view_months; $i++) {

                            $f_m_date = $start_date->firstOfMonth();
                            $m_date = $f_m_date->addMonths($i);
                            $m_date = $f_m_date->addDays($date_index - 1)->format('Y-m-d');

                            $start = $m_date . 'T' . Carbon::parse($schedule['start_time'])->format('H:i:s');
                            $end = $m_date . 'T' . Carbon::parse($schedule['end_time'])->format('H:i:s');

                            $item = [
                                'id' => $schedule->id,
                                'title' => 'Course: '. $course_title,
                                'start' => $start,
                                'end' => $end,
                                'display' => 'block',
                                'color' => $color_set['background_color'],
                                'textColor' => $color_set['font_color'],
                                'timezone' => $schedule->timezone
                            ];

                            array_push($calendarData, $item);
                        }
                    }
                }
            }
        }
        
        return $calendarData;
    }

    public function getOnePeriodSchedule($course_id) {

        $course = Course::find($course_id);
        $repeat_type = $course->repeat_type;
        $repeat_value = $course->repeat_value;
        $schedules = $course->schedule->sortBy('date');

        $calendarData = [];

        if(count($schedules) > 0) {

            $start_date = Carbon::parse($schedules[0]->date);

            foreach($schedules as $schedule) {
                if($repeat_type == 'week') {

                    $day_index = Carbon::parse($schedule->date)->dayOfWeek;
                    $day_str = Schedule::WEEK_DAYS[$day_index];
                    
                    $cur_date = Carbon::parse($schedule->date);
                    $diff_date = $cur_date->diffInDays($start_date);

                    $start_time = Carbon::parse($schedule->start_time)->format('H:i');
                    $end_time = Carbon::parse($schedule->end_time)->format('H:i');

                    $week_str = $start_time . '-' . $end_time . ' ' . $day_str . ' of Week ' . (ceil(($diff_date + 1) / 7));
                    $available = !empty($schedule->lesson_id) ? 'false' : 'true';

                    array_push($calendarData, ['id' => $schedule->id, 'content' => $week_str, 'available' => $available]);

                }
            }
        }

        return $calendarData;
    }
}
