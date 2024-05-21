<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Course;
use App\Models\Transaction;

class OrderService
{
    /**
     * Get Order Balances
     */
    public function balance($type)
    {
        $course_ids     = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $purchased_ids  = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');

        switch($type) {
            case 'total':
                $order_item_ids = OrderItem::whereIn('item_id', $purchased_ids)->pluck('id');
                $amount = Transaction::whereIn('order_item_id', $order_item_ids)->where('type', 'in')->sum('amount');
                $tax    = Transaction::whereIn('order_item_id', $order_item_ids)->where('type', 'in')->sum('tax');
                return $amount + $tax;
            break;

            case 'month':
                $start = new Carbon('first day of this month');
                $now   = Carbon::now();

                // Get courses end_date is in this month
                $course_ids_this_month = Course::whereBetween('end_date', [
                        $start->format('Y-m-d')." 00:00:00",
                        $now->format('Y-m-d')." 23:59:59"])
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');
                $order_item_ids = OrderItem::whereIn('item_id', $course_ids_this_month)->pluck('id');
                $amount = Transaction::whereIn('order_item_id', $order_item_ids)
                    ->where('type', 'in')
                    ->sum(\DB::raw('amount + tax'));
                return $amount;
            break;

            case 'balance':
                $order_item_ids = OrderItem::whereIn('item_id', $purchased_ids)->pluck('id');
                $amount = Transaction::whereIn('order_item_id', $order_item_ids)
                    ->where('type', 'in')
                    ->sum(\DB::raw('teacher_fee + teacher_gst'));
                return $amount - $this->withdrawAmount();
            break;

            case 'available':
                $subdays = !empty(auth()->user()->withhold) ? auth()->user()->withhold : (int)config('withdraw.days');
                $start   = new Carbon('first day of this month');
                $now     = Carbon::now();
                $end     = new Carbon('last day of this month');
        
                // Get courses end_date is in this month with withholds
                $course_ids_this_month = Course::whereBetween('end_date', [
                        $start->subDays($subdays)->format('Y-m-d')." 00:00:00",
                        $now->subDays($subdays)->format('Y-m-d')." 23:59:59"])
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');
                $order_item_ids = OrderItem::whereIn('item_id', $course_ids_this_month)
                    ->pluck('id');
                $amount = Transaction::whereIn('order_item_id', $order_item_ids)
                    ->where('type', 'in')
                    ->sum(\DB::raw('teacher_fee + teacher_gst'));
                return $amount - $this->withdrawAmount();
            break;
        }
    }

    public function fee($type)
    {
        $course_ids     = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $purchased_ids  = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
        $now     = Carbon::now();
        $subdays = !empty(auth()->user()->withhold) ? auth()->user()->withhold : (int)config('withdraw.days');
        // Get courses end_date to today
        $course_ids_since_now = Course::where('end_date', '<', $now->subDays($subdays)->format('Y-m-d')." 23:59:59")
            ->whereIn('id', $purchased_ids)
            ->pluck('id');
        $order_item_ids = OrderItem::whereIn('item_id', $course_ids_since_now)->pluck('id');
        $fee = Transaction::whereIn('order_item_id', $order_item_ids)->where('type', 'in')->sum($type);

        return $fee;
    }

    /**
     * Get earned by Admin
     */
    public function adminBalance($type)
    {
        $course_ids = DB::table('course_student')->pluck('course_id');
        $now = Carbon::now();

        switch($type)
        {
            case 'month':
                $start = new Carbon('first day of this month');
                

                // Get courses end_date is in this month
                $course_ids_this_month = Course::whereBetween('end_date', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                    ->whereIn('id', $course_ids)
                    ->pluck('id');

                $earned = OrderItem::whereIn('item_id', $course_ids_this_month)
                        ->whereBetween('created_at', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                        ->sum('price');

                return $earned;
            break;

            case 'total':
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $course_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                return $total;
            break;

            case 'balance':
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                ->whereIn('id', $course_ids)
                ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                $earned = $total * config('account.fee') / 100;
                return $earned;
            break;
        }
    }

    private function withdrawAmount()
    {
        return Transaction::where('user_id', auth()->user()->id)->where('type', 'withdraw')->sum('amount');
    }   

    private function refundAmount()
    {
        return Transaction::where('user_id', auth()->user()->id)->where('type', 'refund')->sum('amount');
    }

    public function withdrawFee()
    {
        $account_fees = Transaction::where('user_id', auth()->user()->id)->where('type', 'withdraw')->sum('account_fee');
        $gst_fees     = Transaction::where('user_id', auth()->user()->id)->where('type', 'withdraw')->sum('gst_fee');

        return round($account_fees + $gst_fees, 2);
    }
}