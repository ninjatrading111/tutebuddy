<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Course;
use App\Models\Review;

class ReviewController extends Controller
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
     * List all reviews
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
            $reviews = Review::where('reviewable_type', Course::class)->whereIn('reviewable_id', $course_ids)->paginate(25);
            return view('backend.review.index', compact('reviews'));
        }
        
        $reviews = Review::paginate(25);
        return view('backend.review.index', compact('reviews'));
    }

    /**
     * Show a review
     */
    public function show($id)
    {
        $review = Review::find($id);
        return view('backend.review.show', compact('review'));
    }

    public function destroy($id) {

        try {
            Review::find($id)->delete();

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
     * Publish Review
     */
    public function publish($id)
    {
        $review = Review::find($id);
        if($review->published == 1) {
            $review->published = 0;
        } else {
            $review->published = 1;
        }

        $review->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $review->published
        ]);
    }

    /**
     * Get Reviews for DataTable
     */
    public function getTableData()
    {
        $reviews = Review::all();
        $data = $this->getArrayData($reviews);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    function getArrayData($reviews)
    {
        $data = [];
        $i = 0;

        foreach($reviews as $review) {

            if(empty($review->course)) {
                continue;
            }

            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;

            if(!empty($review->user->avatar)) {
                $avatar = '<img src="'. asset('storage/avatars/' . $review->user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">' . mb_substr($review->user->name, 0, 2) . '</span>';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    '. $avatar .'
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $review->user->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            $course_title = (strlen($review->course->title) > 20) ? mb_substr($review->course->title, 0, 18) . '...' : $review->course->title;
            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . mb_substr($course_title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $course_title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $review->course->category->name .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['rate'] = $review->rating;
            $temp['content'] = (strlen($review->content) > 20) ? mb_substr($review->content, 0, 18) . '...' : $review->content;
            $temp['time'] = $review->created_at->diffforhumans();

            $show_route = route('admin.reviews.show', $review->id);
            $delete_route = route('admin.reviews.destroy', $review->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            $publish_route = route('admin.publishByAjax', $review->id);
            
            if($review->published == 0) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            }

            if($review->trashed()) {
                $restore_route = route('admin.review.restore', $course->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-title="Recover"><i class="material-icons">restore_from_trash</i></a>';
            }

            if(auth()->user()->hasRole('Superadmin')) {
                $temp['action'] = $btn_show . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
            } else {
                $temp['action'] = $btn_show . '&nbsp;';
            }
            
            array_push($data, $temp);
        }

        return $data;
    }
}