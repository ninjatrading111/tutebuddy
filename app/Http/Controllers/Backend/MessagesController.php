<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageSent;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use App\Models\MessageThread;

use App\User;
use App\Models\Course;
use DB;
use Mail;
use App\Mail\SendMail;
use App\Jobs\SendEmail;

class MessagesController extends Controller
{
    public function index(Request $request) {

        $userId = auth()->user()->id;
        $partners = collect();
        $user_ids = [];

        if (auth()->user()->hasRole('admin')) {
            $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
            $user_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('user_id')->unique();
        }

        if (auth()->user()->hasRole('User')) {
            $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
            $user_ids = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id')->unique();
        }

        if (auth()->user()->hasRole('Superadmin')) {
            $user_ids = User::whereNotIn('id', [$userId])->pluck('id');
        }

        foreach($user_ids as $u_id) {
            $user = User::find($u_id);
            $thread = MessageThread::forUser($user->id)->first();
            $partners->push([
                'user' => $user,
                'thread' => $thread
            ]);
        }

        return view('backend.messages.index', compact('partners'));
    }

    // public function index(Request $request) {

    //     $userId = auth()->user()->id;
    //     $threads = MessageThread::where('subject', 'like', '%' . $userId . '%')->latest('updated_at')->get();
    //     $partners = [];

    //     foreach($threads as $thread) {
    //         $grouped_participants = $thread->participants->where('user_id', '!=', $userId)->groupBy(function($item) {
    //             return $item->user_id;
    //         });
            
    //         foreach($grouped_participants as $participants) {
    //             $participant = $participants[0];

    //             $item = [
    //                 'partner_id' => $participant->user_id,
    //                 'thread' => $thread
    //             ];
    //             array_push($partners, $item);
    //         }
    //     }

    //     return view('backend.messages.index', compact('threads', 'partners'));
    // }

    /**
     * Show message by thread Id
     * @param $id - thread ID
     */
    public function show($thread_id, $partner_id) {
        try {
            $thread = MessageThread::findOrFail($thread_id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $thread_id . ' was not found.');

            return redirect()->route('messages');
        }

        $userId = auth()->user()->id;
        $partner = User::find($partner_id);
        $thread->markAsRead($userId);

        return view('backend.messages.show', compact('thread', 'partner'));
    }

    public function reply(Request $request)
    {
        $this->validate($request,[
            'message' => 'required'
        ],[
            'message.required' => 'Please input your message'
        ]);

        $userId = auth()->user()->id;

        $thread = auth()->user()->threads()
            ->where('message_threads.id', '=', $request->thread_id)
            ->first();

        // Reply to Thread
        if(!empty($thread)) {

            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'body' => $request->message,
            ]);

            // Sender
            $participant = Participant::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'last_read' => new Carbon,
            ]);

            $view = view('backend.messages.parts.ele-right', ['message' => $message])->render();

            // broadcast(new MessageSent(auth()->user(), $message))->toOthers();

            // Send replay message by email
            // $participant = $thread->getParticipantFromUser($userId);
            // $send_data = [
            //     'template_type' => 'New_Message_Received',
            //     'mail_data' => [
            //         'model_type' => Message::class,
            //         'model_id' => $message->id
            //     ]
            // ];
            // $sender_email = User::find($participant->user_id)->email;
            // Mail::to($sender_email)->send(new SendMail($send_data));

            return response()->json([
                'success' => true,
                'action' => 'reply',
                'html' => $view
            ]);

        } else { // Create New Thread

            $subject = $userId . '_' . $request->recipients;

            $thread = MessageThread::create([
                'subject' => $subject,
            ]);
    
            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'body' => $request->message,
            ]);
    
            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'last_read' => new Carbon,
            ]);
    
            // Recipients
            if ($request->has('recipients')) {
                $thread->addParticipant($request->recipients);
            }

            $view = view('backend.messages.parts.ele-right', ['message' => $message])->render();
    
            return response()->json([
                'success' => true,
                'action' => 'send',
                'thread_id' => $thread->id,
                'html' => $view
            ]);
        }
    }

    public function getMessages(Request $request)
    {
        $partner = User::find($request->partner);
        $thread = MessageThread::find($request->thread);

        if(!empty($thread)) {
            $thread->markAsRead(auth()->user()->id);
        }
        
        $view = view('backend.messages.parts.msg', ['partner' => $partner, 'thread' => $thread])->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function getUsers($key)
    {
        if($key == '') {
            return response()->json([
                'success' => false
            ]);
        }
        $user_id = auth()->user()->id;

        if(auth()->user()->hasRole('admin')) {
            $course_ids = DB::table('course_user')->where('user_id', $user_id)->pluck('course_id');
            $student_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('user_id');
            $users = User::whereIn('id', $student_ids)->where('name', 'like', '%' . $key . '%')->get();
        }

        if(auth()->user()->hasRole('User')) {
            $course_ids = DB::table('course_student')->where('user_id', $user_id)->pluck('course_id');
            $teacher_ids = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
            $users = User::whereIn('id', $teacher_ids)->where('name', 'like', '%' . $key . '%')->get();
        }

        if(auth()->user()->hasRole('Superadmin')) {
            $users = User::where('name', 'like', '%' . $key . '%')->get();
        }

        // Thread
        $threads = MessageThread::forUser($user_id)->latest('updated_at')->get();
        $partners = [];
        foreach($threads as $thread) {
            $partner = $thread->participants->where('user_id', '!=', $user_id)->first();
            if(isset($partner)){
                $partners += [$partner->user_id => $thread->id];
            }
        }

        $li = '';

        foreach($users as $user) {
            if($user->id == auth()->user()->id) continue;
            if(!empty($user->avatar)) {
                $avatar = '<img src="' . asset('/storage/avatars/' . $user->avatar ) . '" alt="people" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">' . mb_substr(auth()->user()->avatar, 0, 2) .'</span>';
            }

            // Get Thread ID for User
            $thread_id = (isset($partners[$user->id])) ? $partners[$user->id] : '';
            
            $li .= '<li class="list-group-item px-3 py-12pt bg-light" data-id="' . $user->id . '" data-thread="'. $thread_id .'">
                        <a href="javascript:void(0)" class="d-flex align-items-center position-relative">
                            <span class="avatar avatar-xs avatar-online mr-3 flex-shrink-0">'. $avatar .'</span>
                            <span class="flex d-flex flex-column" style="max-width: 175px;">
                                <strong class="text-body">'. $user->name .'</strong>
                                <span class="text-muted text-ellipsis">'. $user->headline .'</span>
                            </span>
                        </a>
                    </li>';
        }

        if($li == '') {
            $li = '<li class="list-group-item px-3 py-12pt bg-light">Not found</li>';
        }

        return response()->json([
            'success' => true,
            'html' => $li
        ]);
    }

    public function lastMessages(Request $request) {

        $userId = auth()->user()->id;
        $partner = User::find($request->partner);
        $thread = MessageThread::find($request->thread);

        try {
            $participant = $thread->getParticipantFromUser($userId);
        } catch (ModelNotFoundException $e) {
            return collect();
        }

        $messages = $thread->messages()->where('user_id', '!=', $userId)->get();
        // $view = view('backend.messages.parts.ele-left', ['partner' => $partner, 'message' => $messages->last()])->render();

        $view = '';

        foreach($messages as $message) {
            if($message->updated_at->gt($participant->last_read->toDateTimeString())) {
                $view .= view('backend.messages.parts.ele-left', ['partner' => $partner, 'message' => $message]);
            }
        }

        $thread->markAsRead($userId);

        return response()->json([
            'success' => true,
            'action' => 'read',
            'html' => $view
        ]);
    }

    function getUnreadMessagesCount($thread) {
        $userId = auth()->user()->id;
        $messages = $thread->messages()->where('user_id', '!=', $userId)->get();
        $participant = $thread->getParticipantFromUser($userId);
        $count = 0;
        foreach($messages as $message) {
            if($message->updated_at->gt($participant->last_read->toDateTimeString())) {
                $count++;
            }
        }
        return $count;
    }

    // Enroll
    public function getEnrollThread(Request $request)
    {
        $userId = auth()->user()->id;
        $partner = User::find($request->user_id);

        if($request->type == 'student') {
            $thread = MessageThread::where('type', 'enroll')
                ->where('subject', 'enroll_' . $request->course_id)
                ->where('from', $userId)
                ->where('to', $partner->id)
                ->first();
        }

        if($request->type == 'teacher') {
            $thread = MessageThread::where('type', 'enroll')
                ->where('from', $partner->id)
                ->where('to', $userId)
                ->where('subject', 'enroll_' . $request->course_id)
                ->first();
        }

        if(!empty($thread)) {

            $view = view('frontend.course.enroll-chat.msg', ['partner' => $partner, 'thread' => $thread])->render();
            return response()->json([
                'success' => true,
                'thread_id' => $thread->id,
                'html' => $view
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function sendEnrollChat(Request $request)
    {
        $userId = auth()->user()->id;
        $participantUser = User::find($request->user_id);

        if(empty($request->thread_id)) {

            $subject = 'enroll' . '_' . $request->course_id;

            $thread = MessageThread::create([
                'subject' => $subject,
                'type' => 'enroll',
                'from' => $userId,
                'to' => $request->user_id
            ]);
    
            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'body' => $request->message,
            ]);
    
            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'last_read' => new Carbon,
            ]);
    
            // Participant
            $thread->addParticipant($request->user_id);

            $view = view('frontend.course.enroll-chat.ele-right', ['message' => $message])->render();

            // Send by Email to Participant
            // $send_data = [
            //     'template_type' => 'Pre_Enroll_Message_From_admin',
            //     'mail_data' => [
            //         'email' => $participantUser->email,
            //         'message' => $request->message,
            //         'participant' => $participant->name,
            //         'sender' => auth()->user()->name,
            //         'course' => Course::find($request->course_id)->title,
            //     ]
            // ];

            // dd($send_data);
            // SendEmail::dispatch($send_data);
            // Mail::to($participantUser->email)->send(new SendMail($send_data));
    
            return response()->json([
                'success' => true,
                'action' => 'send',
                'thread_id' => $thread->id,
                'html' => $view
            ]);

        } else {

            $thread = auth()->user()->threads()
                ->where('message_threads.id', '=', $request->thread_id)
                ->first();

            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'body' => $request->message,
            ]);

            $view = view('frontend.course.enroll-chat.ele-right', ['message' => $message])->render();

            // Send Pre enroll message by email

            // $send_data = [
            //     'template_type' => 'Pre_Enroll_Message_From_admin',
            //     'mail_data' => [
            //         'model_type' => Message::class,
            //         'model_id' => $message->id
            //     ]
            // ];
            // Mail::to($participantUser->email)->send(new SendMail($send_data));

            $send_data = [
                'template_type' => 'Pre_Enroll_Message_From_admin',
                'mail_data' => [
                    'email' => $participantUser->email,
                    'message' => $request->message,
                    'participant' => $participantUser->name,
                    'sender' => auth()->user()->name,
                    'course' => Course::find($request->course_id)->title,
                ]
            ];
            SendEmail::dispatch($send_data);

            return response()->json([
                'success' => true,
                'action' => 'reply',
                'html' => $view
            ]);
        }
    }

    // Get Pre Enrolled Students in admin Side
    public function getPreEnrolledStudents()
    {
        return view('backend.messages.pre-enroll');
    }

    public function getPreEnrolledStudentsData()
    {
        $threads = MessageThread::where('to', auth()->user()->id)->get();
        $data = [];
        foreach($threads as $thread)
        {
            $temp = [];
            $temp['index'] = '';
            $student = User::find($thread->from);

            if(!empty($student->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $student->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. mb_substr($student->name, 0, 2) .'</span>';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    '. $avatar .'
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-name">'. $student->name .'</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $subject = $thread->subject;
            $course_id = mb_substr($subject, strpos($subject, "_") + 1);
            $course = Course::find($course_id);

            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded bg-primary text-white">
                                            '. mb_substr($course->title, 0, 2) .'
                                        </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-project">
                                                <strong>'. $course->title .'</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>';

            $participant = $thread->getParticipantFromUser(auth()->user()->id);

            $messages = $thread->messages()->get();
            $thread->markAsRead(auth()->user()->id);

            $last_message = '';
            $message_time = '';

            foreach($messages as $message) {
                if(!$message->updated_at->gt($participant->last_read->toDateTimeString())) {
                    $last_message = $message->body;
                    $message_time = $message->created_at;
                }
            }

            $temp['last'] = str_limit($last_message, 30);
            $temp['time'] = Carbon::parse(timezone()->convertFromTimezone($message_time, auth()->user()->timezone))->diffForHumans();
            $temp['action'] = '<button class="btn btn-md btn-accent start-chat" data-course="'. $course->id .'" data-user="'. $student->id .'">
                                    Chat
                            </button>';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
