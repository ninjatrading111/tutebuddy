<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Discussion;
use App\Models\DiscussionResults;
use App\Models\Course;

use Mail;
use App\Mail\SendMail;

class DiscussionController extends Controller
{
    public function index()
    {
        return view('backend.discussions.index');
    }

    public function getTopicsByAjax()
    {
        $discussions = Discussion::where('user_id', auth()->user()->id)->get();
        $data = [];
        foreach($discussions as $item) {
            $temp = [];
            $temp['index'] = '';
            $temp['title'] = '<a href=' . route('admin.discussions.show', $item->id) . '>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
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
                                </div>
                            </a>';

            $topics = json_decode($item->topics);
            $temp['topics'] = '';
            foreach($topics as $topic){
                $temp['topics'] .= '<a href=' . route('admin.discussions.show', $item->id) . ' class="chip chip-outline-secondary">
                    ' . $item->topic($topic) . '
                </a>';
            }

            $edit_route = route('admin.discussions.edit', $item->id);
            $delete_route = route('admin.discussions.destroy', $item->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getTopics(Request $request)
    {
        // Get purchased Course IDs
        if(auth()->user()->hasRole('User')) {
            $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        } else {
            $course_ids = Course::pluck('id');
        }
        
        if(isset($request->_q)) {
            $discussions = Discussion::where('title', 'like', '%' . $request->_q . '%')->whereIn('course_id', $course_ids)->paginate(5);
        } else {
            $discussions = Discussion::whereIn('course_id', $course_ids)->paginate(5);
        }

        if(isset($request->_t)) {
            switch($request->_t) {
                case 'all':
                    $discussions = Discussion::paginate(5);
                break;

                case 'my':
                    $discussions = Discussion::where('user_id', auth()->user()->id)->paginate(5);
                break;

                case 'newst':
                    $discussions = Discussion::OrderBy('updated_at', 'desc')->paginate(5);
                break;

                case 'unanswered':
                    $discussions = Discussion::OrderBy('updated_at', 'desc')->paginate(5);
                break;
            }
        }
        
        return view('backend.discussions.topics', compact('discussions'));
    }

    /**
     * Create a new discussion
     */
    public function create()
    {
        $topics = DB::table('discussion_topics')->get();

        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $course_ids)->get();

        if(auth()->user()->hasRole('User')) {
            $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
            $courses = Course::whereIn('id', $course_ids)->get();
        }
        
        if($courses->count() < 1) {
            $message = 'You have no any courses';
            if(auth()->user()->hasRole('User')) {
                $message = 'You have no any purchased courses';
            }
            return redirect()->route('admin.discussions.index')->with('error', $message);
        }
        return view('backend.discussions.create', compact('courses', 'topics'));
    }

    /**
     * Store a new discussion
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $question_data = [
            'user_id' => auth()->user()->id,
            'course_id' => $data['course'],
            'lesson_id' => $data['lesson'],
            'title' => $data['title'],
            'question' => $data['question']
        ];

        // Set Topics
        $topics = $data['topics'];
        foreach($topics as $item) {
            $count = DB::table('discussion_topics')->where('id', $item)->count();
            if($count < 1) {
                $topic_id = DB::table('discussion_topics')->insertGetId(['topic' => $item]);
                if (($key = array_search($item, $data['topics'])) !== false) {
                    unset($data['topics'][$key]);
                }
                array_push($data['topics'], $topic_id);
            }
        }
        $data['topics'] = array_values($data['topics']);

        $question_data['topics'] = json_encode($data['topics']);

        $discusson = Discussion::create($question_data);

        return response()->json([
            'success' => true,
            'discussion_id' => $discusson->id
        ]);
    }

    public function edit($id)
    {
        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $course_ids)->get();

        $discussion = Discussion::find($id);
        $topics = DB::table('discussion_topics')->get();
        return view('backend.discussions.edit', compact('discussion', 'courses', 'topics'));
    }

    public function update(Request $request, $id)
    {
        $discussion = Discussion::find($id);
        $data = $request->all();

        $question_data = [
            'user_id' => auth()->user()->id,
            'course_id' => $data['course'],
            'lesson_id' => $data['lesson'],
            'title' => $data['title'],
            'question' => $data['question']
        ];

        // Set Topics
        $topics = $data['topics'];
        foreach($topics as $item) {
            $count = DB::table('discussion_topics')->where('id', $item)->count();
            if($count < 1) {
                $topic_id = DB::table('discussion_topics')->insertGetId(['topic' => $item]);
                if (($key = array_search($item, $data['topics'])) !== false) {
                    unset($data['topics'][$key]);
                }
                array_push($data['topics'], $topic_id);
            }
        }
        $data['topics'] = array_values($data['topics']);
        $question_data['topics'] = json_encode($data['topics']);
        $discussion->update($question_data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated'
        ]);
    }

    public function destroy($id) {

        try {
            Discussion::find($id)->delete();

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

    public function show($id)
    {
        $discussion = Discussion::find($id);
        $top_discussions = Discussion::withCount('results')
                ->orderBy('results_count', 'desc')->limit(5)->get();
        return view('backend.discussions.show', compact('discussion', 'top_discussions'));
    }

    public function postComment(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        
        $result = DiscussionResults::create($data);

        if(!empty($result->user->avatar)){
            $avatar = '<img src="' . asset('/storage/avatars/' . $result->user->avatar) . '" alt="people" class="avatar-img rounded-circle">';
        } else {
            $avatar = '<span class="avatar-title rounded-circle">' . mb_substr($result->user->name, 0, 2) . '</span>';
        }

        $html = '<div class="ml-sm-32pt mt-3 card p-3">
                    <div class="d-flex">
                        <a href="#" class="avatar avatar-sm mr-12pt">'. $avatar .'</a>
                        <div class="flex">
                            <div class="d-flex align-items-center">
                                <a href="" class="text-body"><strong>' . $result->user->name . '</strong></a>
                                <small class="ml-auto text-muted">' . Carbon::createFromTimeStamp(strtotime($result->updated_at))->diffForHumans() .'</small>
                            </div>
                            <p class="mb-1 text-70">' . $result->content . '</p>
                        </div>
                    </div>
                </div>';

        // Send Reply email
        $send_data = [
            'template_type' => 'New_Discussion_Topic_Reply_Received',
            'mail_data' => [
                'model_type' => DiscussionResults::class,
                'model_id' => $result->id
            ]
        ];

        Mail::to($result->discussion->user->email)->send(new SendMail($send_data));

        return response()->json([
            'success' => true,
            'result' => $html
        ]);
    }

    public function getSimilar(Request $request)
    {
        $discussions = Discussion::where('title', 'like', '%' . $request->key . '%')->limit(3)->get();

        if(count($discussions) > 0) {

            $html = '<div class="list-group list-group-flush">';
            
            foreach($discussions as $discussion) {

                $topics = json_decode($discussion->topics);
                $topic_html = '';

                foreach($topics as $topic){
                    $topic_html .= '<a href=' . route('admin.discussions.show', $discussion->id) . ' class="chip chip-outline-secondary">
                        ' . $discussion->topic($topic) . '
                    </a>';
                } 

                $html .= '<div class="list-group-item p-3">
                            <div class="row align-items-start">
                                <div class="col-md-3 mb-8pt mb-md-0">
                                    <div class="media align-items-center">
                                        <div class="media-left mr-12pt">
                                            <a href="javascript:void(0)" class="avatar avatar-sm">
                                                <span class="avatar-title rounded-circle">'. mb_substr($discussion->user->name, 0, 2) .'</span>
                                            </a>
                                        </div>
                                        <div class="d-flex flex-column media-body media-middle">
                                            <a href="" class="card-title">'. $discussion->user->name .'</a>
                                            <small class="text-muted">'. Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() .'</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-8pt mb-md-0">
                                    <p class="mb-8pt">
                                        <a href="'. route('admin.discussions.show', $discussion->id) .'" class="text-body">
                                            <strong>'. $discussion->title .'</strong>
                                        </a>
                                    </p>
                                    '. $topic_html .'
                                </div>
                                <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                                    <h5 class="m-0">'. $discussion->results->count() .'</h5>
                                    <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
                                </div>
                            </div>
                        </div>';
            }

            $html .= '</div>';

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }
}
