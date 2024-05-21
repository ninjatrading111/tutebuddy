<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Assignment;
use App\Models\Bundle;
use App\Models\AssignmentResult;
use App\Models\TestResultAnswers;
use App\Models\Discussion;
use App\Models\Quiz;
use App\Models\QuizResults;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Services\OrderService;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(OrderService $orderService)
    {
        if(auth()->user()->getRoleNames()->first() == 'Superadmin') {

            $teachers_count = User::role('admin')->count();
            $students_count = User::role('User')->count();
            $active_courses = Course::where('published', 1)->count();
            $enrolled_course_ids = DB::table('course_student')->pluck('course_id');
            $enrolled_courses = Course::whereIn('id', $enrolled_course_ids)->count();

            $total_sales = Order::all()->sum('price');

            // Get courses end_date to today
            $course_ids_since_now = Course::where('end_date', '<', Carbon::now()->format('Y-m-d')." 23:59:59")
                ->pluck('id');

            $total_payments = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
            $course_approval = Course::where('published', 2)->count();
            $live_lessons = Lesson::where('lesson_type', 1)->count();

            $pending_courses = Course::where('published', 2)->limit(5)->orderBy('updated_at', 'desc')->get();
            $withdraw_requests = Transaction::where('type', 'withdraw')->where('status', 'pending')->limit(5)->get();
            $orders = Order::limit(5)->orderBy('created_at', 'desc')->get();

            // Daily signup data
            $days = Carbon::now()->daysInMonth;
            $sign_days = [];
            $sign_admins = [];
            $sign_students = [];
            $start = new Carbon('first day of this month');
            $end = new Carbon('last day of this month');
            $today = $start;

            for($i = 0; $i < $days; $i++) {
                array_push($sign_days, $i+1);
                $s_i_c = User::role('admin')
                    ->whereBetween('created_at', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])
                    ->count();
                $s_s_c = User::role('User')
                    ->whereBetween('created_at', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])
                    ->count();
                array_push($sign_admins, $s_i_c);
                array_push($sign_students, $s_s_c);
                $today = $start->add(1, 'day');
            }

            $json_sign_days = json_encode($sign_days);
            $json_sign_admins = json_encode($sign_admins);
            $json_sign_students = json_encode($sign_students);

            $start = new Carbon('first day of this month');
            $today = $start;

            // Daily Order Data
            $daily_orders = [];
            for($i = 0; $i < $days; $i++) {
                $d_order_c = (float)Order::whereBetween('created_at', [$today->format('Y-m-d')." 00:00:00", $today->format('Y-m-d')." 23:59:59"])
                            ->sum('price');

                array_push($daily_orders, $d_order_c);
                $today = $start->add(1, 'day');
            }
            $json_daily_orders = json_encode($daily_orders);

            return view('backend.dashboard.super_admin',
                compact(
                    'teachers_count',
                    'students_count',
                    'active_courses',
                    'enrolled_courses',
                    'total_sales',
                    'total_payments',
                    'course_approval',
                    'live_lessons',
                    'pending_courses',
                    'withdraw_requests',
                    'orders',
                    'json_sign_days',
                    'json_sign_admins',
                    'json_sign_students',
                    'json_daily_orders'
                )
            );
        }

        if(auth()->user()->getRoleNames()->first() == 'admin') {
            $courses         = Course::all();
            $course_ids      = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
            $course_ids      = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->pluck('id');
            $live_lesson_ids = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->pluck('id');
            $schedules       = Schedule::whereIn('lesson_id', $live_lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

            $course_students = DB::table('course_student')->whereIn('course_id', $course_ids)->get();
            $students        = collect();

            foreach($course_students as $item) {
                $c_item = Course::find($item->course_id);
                $u_item = User::find($item->user_id);
                $data = [
                    'course' => $c_item,
                    'user' => $u_item
                ];
                $students->push($data);
            }

            $assignments        = Assignment::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->limit(5)->get();
            $assignment_ids     = Assignment::where('user_id', auth()->user()->id)->pluck('id');
            $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->limit(5)->get();
            $bundles            = Bundle::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->limit(3)->get();
            $test_ids           = Test::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
            $testResults        = TestResult::whereIn('test_id', $test_ids)->limit(5)->get();
            $quiz_ids           = Quiz::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
            $quizResults        = QuizResults::whereIn('quiz_id', $quiz_ids)->limit(5)->get();
            $discussions        = Discussion::limit(5)->get();

            $total              = $orderService->balance('total');
            $balance            = $orderService->balance('balance');
            $available          = $orderService->balance('available');
            // $account_fee        = $orderService->fee('account_fee');
            // $account_gst        = $orderService->fee('account_gst');
            // $total_fee          = $account_fee + $account_gst;

            $pending_orders = collect();
            $order_items = OrderItem::whereIn('item_id', $course_ids)->orderBy('created_at', 'desc')->get();
            foreach($order_items as $item) {
                if(Carbon::parse($item->course->end_date)->diffInDays(Carbon::now()) < 7 ||
                        $item->course->end_date > Carbon::now()->format('Y-m-d')) {
                            $pending_orders->push($item);
                }
            }

            return view('backend.dashboard.teacher', 
                compact(
                    'pending_orders',
                    'courses',
                    'schedules',
                    'students',
                    'assignments', 
                    'assignment_results',
                    'bundles',
                    'testResults',
                    'quizResults',
                    'discussions',
                    'total',
                    'balance',
                    'available',
                )
            );
        }

        if(auth()->user()->getRoleNames()->first() == 'Student' || auth()->user()->getRoleNames()->first() == 'Child') {

            // Get purchased Course IDs
            $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
            $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->pluck('id');
            $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
            $teachers_id = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
            $bundle_ids = DB::table('bundle_student')->where('user_id', auth()->user()->id)->pluck('bundle_id');

            $purchased_courses = Course::whereIn('id', $course_ids)->get();
            $live_lesson_ids = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->pluck('id');
            $schedules = Schedule::whereIn('lesson_id', $live_lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

            $bundles = Bundle::whereIn('id', $bundle_ids)->limit(3)->get();
            $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->limit(5)->get();
            $teachers = User::whereIn('id', $teachers_id)->limit(5)->get();
            $testResults = TestResult::where('user_id', auth()->user()->id)->limit(4)->get();
            $discussions = Discussion::limit(5)->get();

            // Parent Categories
            $parentCategories = Category::where('parent', 0)->get();

            return view('backend.dashboard.student',
                compact(
                    'purchased_courses',
                    'schedules',
                    'bundles',
                    'assignments',
                    'teachers',
                    'testResults',
                    'discussions',
                    'parentCategories'
                )
            );
        }
    }
}
