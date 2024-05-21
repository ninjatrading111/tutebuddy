<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Course;
use App\User;
use App\Models\Tax;
use App\Models\Transaction;
use App\Models\Refund;
use App\Models\Schedule;
use Mail;
use App\Mail\SendMail;
use App\Services\OrderService;

class PaymentController extends Controller
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
     * Get Transactions
     */
    public function getTransactions()
    {
        if(auth()->user()->hasRole('admin')) {
            $transactions = [[
                'transaction_id'=>'trans-zpiiXxiX',
                'type' => 'In',
                'title' => 'Study on Phy...',
                'name' => 'Ali',
                'amount' => '$50',
                'fee' => '2.5',
                'date' => 'Jul 01 2022',
                'status' => 'captured',
            ],
            [
                'transaction_id'=>'trans-7y7hr39i',
                'type' => 'Withdraw',
                'title' => 'Research on ...',
                'name' => 'Laura',
                'amount' => '$100',
                'fee' => '5',
                'date' => 'Jul 08 2022',
                'status' => 'captured',
            ]];
            return view('backend.payment.teacher.transactions', compact('transactions'));
        }

        if(auth()->user()->hasRole('Superadmin')) {
            $transactions = Transaction::orderBy('created_at', 'desc')->paginate(50);
            return view('backend.payment.admin.transactions', compact('transactions'));
        }
    }

    /**
     * Transaction Detail
     * @param string $id
     */
    public function transactionsDetail($id)
    {
        $order = Order::find($id);
        return view('backend.payment.transaction-detail', compact('order'));
    }

    /**
     * Get Orders
     * 
     * @return view
     */
    public function getOrders(OrderService $orderService)
    {
        // $count = [
        //     'all' => Course::all()->count(),
        //     'draft' => Course::where('published', 0)->count(),
        //     'pending' => Course::where('published', 2)->count(),
        //     'completed' => Course::where('published', 1)->count(),
        //     'deleted' => Course::onlyTrashed()->count()
        // ];

        // if(auth()->user()->hasRole('superadmin')) {
        //     $orders             = Order::orderBy('created_at', 'desc')->paginate(15);
        //     $earned_this_month  = $orderService->adminBalance('month');
        //     $balance            = $orderService->adminBalance('balance');
        //     $total              = $orderService->adminBalance('total');

        //     return view('backend.payment.admin.orders', 
        //         compact('orders', 'earned_this_month', 'balance', 'total')
        //     );
        // }

        if(auth()->user()->hasRole('admin')) {
            return view('backend.payment.teacher.orders');
        }

        // if(auth()->user()->hasRole('Student')) {
        //     $orders = Order::where('user_id', auth()->user()->id)->paginate(15);
        //     return view('backend.payment.student.orders', compact('orders'));
        // }
    }

    /**
     * Order Detail
     */
    public function orderDetail($id)
    {
        $orderItem = OrderItem::find($id);
        return view('backend.payment.teacher.order-detail', compact('orderItem'));
    }

    public function getAffiliate()
    {
        return view('backend.payment.teacher.affiliate');
    }

    /**
     * Refund request
     */
    public function refundRequest(Request $request, $id)
    {
        try {
            Refund::updateOrCreate([
                'order_id' => $id,
                'user_id' => auth()->user()->id,
                'reason' => $request->reason
            ]);
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Refund Confirm By Teacher
     */
    public function ajaxRefundReply(Request $request)
    {
        if (empty($request->id)) {

            return response()->json([
                'success' => false,
                'content' => 'Refund Id is required'
            ]);
        }

        $refund = Refund::find($request->id);

        if (!empty($refund)) {

            $refund->confirm = $request->confirm;
            $refund->confirm_status = 1;
            $refund->save();

            return response()->json([
                'success' => true
            ]);

        } else {

            return response()->json([
                'success' => false,
                'content' => 'Refund database did not found'
            ]);
        }
        
    }

    /**
     * Get Refunds
     */
    public function getRefunds()
    {
        if(auth()->user()->hasRole('Instructor')) {
            $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
            $purchased_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
            $order_ids = OrderItem::whereIn('item_id', $purchased_ids)->pluck('order_id');
            $refunds = Refund::whereIn('order_id', $order_ids)->orderBy('created_at', 'desc')->paginate(15);
            return view('backend.payment.teacher.refunds', compact('refunds'));
        } else {
            $refunds = Refund::orderBy('created_at', 'desc')->paginate(15);
            return view('backend.payment.admin.refunds', compact('refunds'));
        }
    }

    /**
     * Refund Detail
     */
    public function refundDetail($id)
    {
        $refund = Refund::find($id);
        return view('backend.payment.admin.refund-detail', compact('refund'));
    }

    /**
     * Refund Reply
     */

    public function refundReply($id)
    {
        $refund = Refund::find($id);
        return view('backend.payment.teacher.refund-reply', compact('refund'));
    }

    /**
     * Process Refund
     */
    public function processRefund($id)
    {
        $refund = Refund::find($id);
        $pay_id = $refund->order->payment_id;

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        // Check Already refunded or not.
        $refunded_payments = $api->payment->fetch($pay_id)->refunds();

        if(count($refunded_payments->items) > 0) {
            foreach($refunded_payments->items as $item) {
                $refund->status = 1;
                $refund->save();

                $transaction = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'transaction_id' => 'trans-' . str_random(8),
                    'amount' => $item->amount,
                    'type' => 'refund',
                    'order_id' => $refund->order->id,
                    'status' => $item->status,
                    'payout_id' => $item->id
                ]);

                // Deactive Course
                $order_id = $refund->order_id;
                $order = Order::find($order_id);
                $orderItems = $order->items;

                foreach($orderItems as $item) {
                    $item_type = $item->item_type;
                    if($item_type == 'App\Models\Course') {
                        DB::table('course_student')
                            ->where('course_id', $item->item_id)
                            ->where('user_id', $order->user_for)
                            ->delete();
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Already refunded!'
            ]);
        }

        $payment = $api->payment->fetch($pay_id);
        $refund_payment = $payment->refund();

        if($refund_payment) {
            $refund->status = 1;
            $refund->save();

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_id' => 'trans-' . str_random(8),
                'amount' => $refund_payment->amount,
                'type' => 'refund',
                'order_id' => $refund->order->id,
                'status' => $refund_payment->status,
                'payout_id' => $refund_payment->id
            ]);

            // Deactive Course
            $order_id = $refund->order_id;
            $order = Order::find($order_id);
            $orderItems = $order->items;

            foreach($orderItems as $item) {
                $item_type = $item->item_type;
                if($item_type == 'App\Models\Course') {
                    DB::table('course_student')
                        ->where('course_id', $item->item_id)
                        ->where('user_id', $order->user_for)
                        ->delete();
                }
            }

            return response()->json([
                'success' => true
            ]);
        }
    }

    /**
     * download Invoice for Student
     */
    public function downloadInvoice($id)
    {
        $order = Order::find($id);

        foreach($order->items as $item) {
            $pdf = \PDF::loadView('downloads.invoice', compact('item'));
            $invoice_name = 'Invoice_' . $order->order_id . $item->id . '.pdf';
            if (!file_exists(public_path('storage/invoices'))) {
                mkdir(public_path('storage/invoices'), 0777);
            }
            $pdf->save(public_path('storage/invoices/' . $invoice_name))->setPaper('', 'portrait');

            $file = public_path('storage/invoices/' . $invoice_name);
            return Response::download($file);
        }
    }

    /**
     * Download Invoice for Teacher
     * @param $transaction_id
     */
    public function downloadInvoiceByTransactionId($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);

        $pdf = \PDF::loadView('downloads.invoice-' . $transaction->type, compact('transaction'));
        $invoice_name = 'Invoice_' . $transaction->transaction_id . '.pdf';
        if (!file_exists(public_path('storage/invoices'))) {
            mkdir(public_path('storage/invoices'), 0777);
        }
        $pdf->save(public_path('storage/invoices/' . $invoice_name))->setPaper('', 'portrait');

        $file = public_path('storage/invoices/' . $invoice_name);
        return Response::download($file);
    }

    /**
     * withdraw
     */
    public function withdraw(Request $request, OrderService $orderService)
    {
        // KYC check
        if(!isset(auth()->user()->kyc) || isset(auth()->user()->kyc) && auth()->user()->kyc->status !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'KYC verification is required to withdraw balances.'
            ]);
        }

        // Bank check
        if(!auth()->user()->bank || empty(auth()->user()->bank->fund_account_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Bank Account is not added! Please confirm in your account setting -> bank.'
            ]);
        }

        $amount       = floatval($request->amount);
        $balance      = floatval($orderService->balance('balance'));

        if($balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Amount is added more than available balance. Please try again with correct amount'
            ]);
        }

        $params = [
            'account_number'  => config('services.razorpayX.number'),
            'fund_account_id' => auth()->user()->bank->fund_account_id,
            'amount'          => $amount * 100,
            'currency'        => $request->currency,
            'mode'            => 'IMPS',
            'purpose'         => 'payout'
        ];

        $curl_headers = [
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode(config('services.razorpayX.key') . ':' . config('services.razorpayX.secret'))
        ];

        $options = [
            CURLOPT_URL            => 'https://api.razorpay.com/v1/payouts',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($params),
            CURLOPT_HTTPHEADER     => $curl_headers,
            CURLOPT_RETURNTRANSFER => 1
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        
        if(isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error']['description']
            ]);
        }

        $payout_id = $result['id'];
        $status = $result['status'];
        curl_close($ch);

        $transaction_id = 'trans-' . str_random(8);
        
        // Widthdraw transaction
        $transaction = Transaction::create([
            'user_id'        => auth()->user()->id,
            'transaction_id' => $transaction_id,
            'amount'         => $amount,
            'type'           => 'withdraw',
            'payout_id'      => $payout_id,
            'status'         => $status
        ]);

        // Send Email
        $email_data = [
            'template_type' => 'Withdraw_Request',
            'mail_data' => [
                'withdraw_amount' => $amount . $request->currency
            ]
        ];

        Mail::to(auth()->user()->email)->send(new SendMail($email_data));
        
        return response()->json([
            'success' => true,
            'transaction' => $transaction->id
        ]);
    }

    /**
     * Display all withdraws for students
     * Super admin
     */
    public function instructorWithdraws()
    {
        $transactions = Transaction::where('type', 'withdraw')->get();
        return view('backend.payment.teacher.withdraws', compact('transactions'));
    }
}
