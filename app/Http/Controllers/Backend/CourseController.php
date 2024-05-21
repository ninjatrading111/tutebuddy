<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\Media;
use App\Models\Level;

use App\Services\ColorService;
use App\Services\CalendarService;
use App\Jobs\SendEmail;

use App\User;
use Spatie\Permission\Models\Role;
// use DB;
use Hash;
use Illuminate\Support\Str;

use App\Models\Bank;
use App\Models\AccessHistory;
use App\Models\Kyc;




class CourseController extends Controller
{
    use FileUploadTrait;

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
     *  Show all of Courses
     */
    public function index() {

        $count = [
            'all' => Course::all()->count(),
            'draft' => Course::where('published', 0)->count(),
            'pending' => Course::where('published', 2)->count(),
            'completed' => Course::where('published', 1)->count(),
            'deleted' => Course::onlyTrashed()->count()
        ];

        return view('backend.course.index', compact('count'));
    }

    /**
     * Show Selected Course
     */
    public function show($slug) {

        $course = Course::where('slug', $slug)->first();
       
        if(!$course) {
            return abort(404);
        }

        if(auth()->check()) {
            $is_mine = empty(DB::table('course_user')->where('course_id', $course->id)->where('user_id', auth()->user()->id)->first()) ? false : true;
        } else {
            $is_mine = false;
        }

        $course_rating = 0;
        $total_ratings = 0;
        if (isset($course->reviews) && $course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }

        $is_reviewed = false;
        if(auth()->check() && $course->reviews()->where('user_id', '=', auth()->user()->id)->first()){
            $is_reviewed = true;
        }


        // $course = Course::find($id);

        $parentCategories = Category::where('parent', 0)->get();
        $tags = DB::table('tags')->get();
        $category = Category::find($course->category_id);
        $levels = Level::where('parent', '0')->get();
        if(!empty($category)) {
            $levels = Level::where('parent', $category->level_id)->get();
        }
        
        // $schedules = $calendarService->getOnePeriodSchedule($id);
        
        // return view('backend.course.edit', compact('course', 'parentCategories', 'tags', 'levels'));

        return view('backend.course.course', compact('course', 'levels', 'parentCategories', 'course_rating', 'tags', 'total_ratings', 'is_reviewed', 'is_mine'));
    }

    /**
     * List data for Datatable
     */
    public function getList($type, Request $request) {
        $start = $request->start;
        $length = $request->length;
        $searchArray = $request->search;
        $orderArray = $request->order;
        $columns = [''];

        $orderCol = 'id';
        if ($columns[$orderArray[0]['column']] != 'index' && $columns[$orderArray[0]['column']] != 'no') {
            $orderCol = $columns[$orderArray[0]['column']];
        }
        $orderDir = $orderArray[0]['dir'];

        $count = [
            'all' => Course::all()->count(),
            'draft' => Course::where('published', 0)->count(),
            'pending' => Course::where('published', 2)->count(),
            'completed' => Course::where('published', 1)->count(),
            'deleted' => Course::onlyTrashed()->count()
        ];

        switch ($type) {
            case 'all':
                $courses = Course::where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->skip($start)
                    ->take($length)
                    ->get();
                $recordsFiltered = Course::where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->count();
            break;
            case 'draft':
                $courses = Course::where('published', 0)
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->skip($start)
                    ->take($length)
                    ->get();
                $recordsFiltered = Course::where('published', 0)
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->count();
            break;
            case 'completed':
                $courses = Course::where('published', 1)
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->skip($start)
                    ->take($length)
                    ->get();
                $recordsFiltered = Course::where('published', 1)
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->count();
            break;
            case 'pending':
                $courses = Course::where('published', 2)
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->skip($start)
                    ->take($length)
                    ->get();
                $recordsFiltered = Course::where('published', 2)
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->count();
            break;
            case 'deleted':
                $courses = Course::onlyTrashed()
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->skip($start)
                    ->take($length)
                    ->get();
                $recordsFiltered = Course::onlyTrashed()
                    ->where('title', 'like', '%' . $searchArray['value'] . '%')
                    ->count();
            break;
        }

        $data = $this->getArrayData($courses);

        if ($orderDir == 'asc') {
            $data = collect($data)->sortBy($orderCol)->toArray();
        } else {
            $data = collect($data)->sortBy($orderCol)->reverse()->toArray();
        }

        $dataArray = [];
        foreach($data as $item) {
            array_push($dataArray, $item);
        }

        $data1 = [[
            'index' => '',
            'no' => '1', 
            'title' => 'Mathematics',
            'subject' => 'Science',
            'academic' => 'University',
            'writer' => 'Dr Aleksandar',
            'name' => 'Nicole W',
            'deadline' => '2022-4-23',
            'status' => $data[0]['status'],
            'action' => $data[0]['actions']
        ]];

        return response()->json([
            'success' => true,
            'recordsTotal' => $count[$type],
            'recordsFiltered' => $recordsFiltered,
            'data' => $data1,
            'count' => $count
        ]);
    }

    /**
     * Create a Course.
     */ 
    public function create() {
        $parentCategories = Category::where('parent', 0)->get();

        $tags = DB::table('tags')->get();
        $levels = Level::where('parent', $parentCategories[0]->level_id)->get();

        return view('backend.course.create', compact('parentCategories', 'levels', 'tags'));
    }

    /**
     * Get Slug by Ajax
     */
    public function getSlugByTitle(Request $request)
    {
        $slug = $this->get_slug($request->title);
        return response()->json([
            'success' => true,
            'slug' => $slug
        ]);
    }

    private function get_slug($title) {
        $slug = str_slug($title);
    
        if ($this->slugExist($slug)) {
        	$title = $title . '_1';
            return $this->get_slug($title);
        }
    
        // otherwise, it's valid and can be used
        return $slug;
    }
    
    private function slugExist($slug) {
        return (DB::table('course')->where('slug', $slug)->count()) ? true : false;
    }

    /**
     * Store new course data
     */
    public function store(Request $request) {

        $data = $request->all();

        // Validate course title: rule max_len <= 40
        if (empty($data['title']) || strlen($data['title']) > 40) {
            return response()->json([
                'success' => false,
                'message' => 'Course title is invalid. title should not empty and less than 40 characters',
                'action'  => 'title'
            ]);
        }

        // Check slug is duplicated
        $is_slug_exist = $this->slugExist($data['slug']);
        if(empty($data['slug']) || $is_slug_exist) {
            return response()->json([
                'success' => false,
                'message' => 'Course slug empty or same slug is exist. Please take another slug.',
                'action'  => 'slug'
            ]);
        }

        // Check short description length
        if(empty($data['short_description']) || strlen($data['short_description']) > 500) {
            return response()->json([
                'success' => false,
                'message' => 'Short description empty or greater than 500. Please review short description again',
                'action'  => 'short_description'
            ]);
        }

        // Check Course Price
        if ($data['group_price'] != null && $data['group_price'] == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Group Price added 0. Please check again!',
                'action'  => 'group_price'
            ]);
        }

        if ($data['private_price'] != null && $data['private_price'] == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Private Price added 0. Please check again!',
                'action'  => 'private_price'
            ]);
        }

        if(!isset($data['tags'])) {
            $data['tags'] = ['Default'];
        }

        // Set tags
        foreach($data['tags'] as $item) {
            $count = DB::table('tags')->where('name', $item)->count();
            if($count < 1) {
                DB::table('tags')->insert(['name' => $item]);
            }
        }

        // Course Data
        $course_data = [
            'category_id' => $data['category'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'],
            'description' => $data['course_description'],
            'level_id' => $data['level'],
            'tags' => json_encode($data['tags']),
            'private_price' => $data['private_price'],
            'group_price' => $data['group_price'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'repeat' => $data['repeat'],
            'repeat_value' => $data['repeat_value'],
            'repeat_type' => $data['repeat_type'],
            'min' => $data['min'],
            'max' => $data['max'],
            'style' => rand(0, 10)
        ];

        if(isset($data['action']) && $data['action'] == 'pending') {
            $course_data['published'] = 2;  // Pending status - Sent to publish request
        }

        // Course image
        if(!empty($data['course_image'])) {
            $image = $request->file('course_image');
            $course_image_url = $this->saveImage($image, 'upload', true);
            $course_data['course_image'] = $course_image_url;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Course thumbnail is required',
                'action'  => 'course_image'
            ]);
        }

        // Create Media
        if(!empty($data['course_video'])) {

            // Add Media
            $video_id = array_last(explode('/', $data['course_video']));

            $media_data = [
                'model_type' => 'App\Models\Course',
                'name' => $data['title'] . ' - Video',
                'url' => $data['course_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];
        }

        $message = '';
        $course_id = (!empty($data['course_id'])) ? $data['course_id'] : '';

        if(empty($course_id)) {
            try {
                $course = Course::create($course_data);
                $course_id = $course->id;

                // Add teacher to this course (me)
                DB::table('course_user')->insert([
                    'course_id' => $course_id,
                    'user_id' => auth()->user()->id
                ]);

                if(!empty($media_data)) {
                    $media_data['model_id'] = $course_id;
                    $media = Media::create($media_data);
                }

            } catch(Exception $e) {
                $message .= $e->getMessage();
            }

        } else {

            try {
                $rlt = Course::find($course_id)->update($course_data);

                // Update Media
                $media = Media::where('model_type', 'App\Models\Course')
                    ->where('model_id', $course_id)->first();

                if(!empty($media_data)) {
                    if(empty($media)) {
                        $media_data['model_id'] = $course_id;
                        $media = Media::create($media_data);
                    } else {
                        $media->update($media_data);
                    }
                }
                
            } catch(Exception $e) {
                $message .= $e->getMessage();
            }
        }

        if(empty($message)) {
            return response()->json([
                'success' => true,
                'course_id' => $course_id
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }

    /**
     * Edit course
     */
    public function edit($id, CalendarService $calendarService)
    {
        $course = Course::find($id);
        $parentCategories = Category::where('parent', 0)->get();
        $tags = DB::table('tags')->get();
        $category = Category::find($course->category_id);
        $levels = Level::where('parent', '0')->get();
        if(!empty($category)) {
            $levels = Level::where('parent', $category->level_id)->get();
        }
        
        // $schedules = $calendarService->getOnePeriodSchedule($id);
        
        return view('backend.course.edit', compact('course', 'parentCategories', 'tags', 'levels'));
    }

    /**
     * Update Course
     */
    public function update(Request $request, $id, ColorService $colorService) {

        $data   = $request->all();
        $course = Course::find($id);

        // Validate course title: rule max_len <= 40
        if (empty($data['title']) || strlen($data['title']) > 40) {
            return response()->json([
                'success' => false,
                'message' => 'Course title is invalid. title should not empty and less than 40 characters',
                'action'  => 'title'
            ]);
        }

        // Check slug is duplicated
        $is_slug_exist = (DB::table('course')->where('id', '!=', $id)->where('slug', $data['slug'])->count() > 0) ? true : false;

        if(empty($data['slug']) || $is_slug_exist || strlen($data['slug']) > 42) {
            return response()->json([
                'success' => false,
                'message' => 'Course slug empty or same slug is exist or less than 40 characters. Please take another slug.',
                'action'  => 'slug'
            ]);
        }

        // Check Course Price
        if ($data['group_price'] != null && $data['group_price'] == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Group Price added 0. Please check again!',
                'action'  => 'group_price'
            ]);
        }

        if ($data['private_price'] != null && $data['private_price'] == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Private Price added 0. Please check again!',
                'action'  => 'private_price'
            ]);
        }

        if(!isset($data['tags'])) {
            $data['tags'] = ['Default'];
        }

        // Set tags
        foreach($data['tags'] as $item) {
            $count = DB::table('tags')->where('name', $item)->count();
            if($count < 1) {
                DB::table('tags')->insert(['name' => $item]);
            }
        }

        // Course Data
        $course_data = [
            'category_id' => $data['category'],
            'title' => $data['title'],
            'slug'  => $data['slug'],
            'short_description' => $data['short_description'],
            'description' => $data['course_description'],
            'level_id' => $data['level'],
            'tags' => json_encode($data['tags']),
            'private_price' => $data['private_price'],
            'group_price' => $data['group_price'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'repeat' => $data['repeat'],
            'repeat_value' => $data['repeat_value'],
            'repeat_type' => $data['repeat_type'],
            'min' => $data['min'],
            'max' => $data['max']
        ];

        if(isset($data['action']) && $data['action'] == 'pending') {
            $course_data['published'] = 2;  // Pending Stataus
        }

        if(isset($data['action']) && $data['action'] == 'draft') {
            $course_data['published'] = 0;  // Draft Status
        }

        if(isset($data['action']) && $data['action'] == 'publish') {
            $course_data['published'] = 1;  // Published Status
        }

        // Course image
        if(!empty($data['course_image'])) {
            $image = $request->file('course_image');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                File::delete(public_path('/storage/uploads/' . $course->course_image));
                File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
            }

            $course_image_url = $this->saveImage($image, 'upload', true);
            $course_data['course_image'] = $course_image_url;
        }

        try {
            $course->update($course_data);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
 
        // Update Course Media - Course video
        if(!empty($data['course_video'])) {
            $video_id = array_last(explode('/', $data['course_video']));
            $media_data = [
                'model_type' => 'App\Models\Course',
                'name' => $data['title'] . ' - Video',
                'url' => $data['course_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];
    
            $media = Media::where('model_type', 'App\Models\Course')
                ->where('model_id', $id)->first();

            if(empty($media)) {
                $media_data['model_id'] = $id;
                $media = Media::create($media_data);
            } else {
    
                try {
                    Media::where('model_type', 'App\Models\Course')
                        ->where('model_id', $id)->update($media_data);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
            }
        } else {
            Media::where('model_type', 'App\Models\Course')->where('model_id', $id)->delete();
        }
        
        // Update Tags
        $tags = DB::table('tags')->get();
        $tags_array = [];
        foreach($tags as $tag) {
            array_push($tags_array, $tag->name);
        }

        $differenceTags = array_diff($data['tags'], $tags_array);

        if(!empty($differenceTags)) {
            foreach($data['tags'] as $tag) {
                DB::table('tags')->updateOrInsert(
                    ['name' => $tag],
                    ['name' => $tag]
                );
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Delete a Course
     */
    public function destroy($id) {

        try {
            Course::find($id)->delete();

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
     * Restore a Course
     */
    public function restore($id) {

        try {
            Course::withTrashed()->find($id)->restore();

            return response()->json([
                'success' => true,
                'action' => 'restore'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $course = Course::find($id);
        if($course->published == 1) {
            $course->published = 2;
        } else {
            $course->published = 1;

            // Send Course Approval Email
            $email_data = [
                'template_type' => 'Course_Approval_By_Admin',
                'mail_data' => [
                    'model_type' => Course::class,
                    'model_id' => $id,
                    'email' => $course->teachers()->first()->email,
                    'approval_requester_name' => $course->teachers()->first()->name
                ]
            ];

            SendEmail::dispatch($email_data);
        }

        $course->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $course->published
        ]);
    }

    /**
     * Delete Forever
     */
    public function foreverDelete($id)
    {
        try {

            // Delete from course_user table;
            DB::table('course_user')->where('course_id', $id)->delete();
            Course::withTrashed()->where('id', $id)->forceDelete();

            // Delete lessons
            $lesson_ids = Lesson::where('course_id', $id)->pluck('id');
            Step::whereIn('lesson_id', $lesson_ids)->delete();
            Lesson::where('course_id', $id)->forceDelete();

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

    public function getArrayData($courses) {
        $data = [];
        $i = 0;

        foreach($courses as $course) {
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $temp['academic'] = 'University';
            $temp['writer'] = 'Dr Aleksandar';
            $temp['deadline'] = "2024-11-12";
            $temp['amount'] = "$150";
            $temp['fee'] = "$15";

            
            $avatar = '<div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">CO</span>
                        </div>';

            if(!empty($course->course_image)) {
                $avatar = '<div class="avatar avatar-sm mr-8pt">
                                <img src="'. asset('storage/uploads/thumb/' . $course->course_image) .'" alt="Avatar" class="avatar-img rounded">
                            </div>';
            }

            if (strlen($course->title) < 30) {
                $course_title = $course->title;
            } else {
                $course_title = mb_substr($course->title, 0, 25) . '...';
            }

            if (strlen($course->slug) < 30) {
                $course_slug = $course->slug;
            } else {
                $course_slug = mb_substr($course->slug, 0, 25) . '...';
            }

            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">'. $avatar .'
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="">
                                            <strong>' . $course_title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $course_slug .'</small>
                                    </div>
                                </div>
                            </div>';

            $temp['course_title'] = $course_title;

            $teacher_name = $course->teachers[0]->name;
            if (strlen($teacher_name) > 25) {
                $teacher_name = mb_substr($teacher_name, 0, 25) . '...';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . mb_substr($teacher_name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $teacher_name . '</strong></p>
                                            <small class="js-lists-values-email text-50">'. $course->teachers[0]->roles->pluck('name')[0] . '</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['teacher'] = $teacher_name;
            
            if(!empty($course->category))
                $temp['subject'] = $course->category->name;
            else 
                $temp['subject'] = 'No Subject';

            $status=58;

            if($status > 99) {
                $status = '<span class="indicator-line rounded bg-success"></span>';
            } else {
                $status = '<span class="indicator-line rounded bg-primary"></span>';
            }

            
            if($course->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. 58 .'%</small>
                                    '. $status .'
                                </div>';
            } else if($course->published == 0) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. 58 .'%</small>
                                    '. $status .'
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. 58 .'%</small>
                                    '. $status .'
                                </div>';
            }

            if($course->end_date < Carbon::now()->format('Y-m-d')) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. 58 .'%</small>
                                    '. $status .'
                                </div>';
            }

            $temp['published'] = $course->published;

            $show_route = route('admin.courses.show', $course->slug);
            $edit_route = route('admin.courses.edit', $course->id);
            $delete_route = route('admin.courses.destroy', $course->id);
            $publish_route = route('admin.courses.publish', $course->id);
            $detail_route = route('admin.orders.detail', $course->id);


            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);
            $btn_detail = view('backend.buttons.detail', ['detail_route' => $detail_route]);
            
            if($course->published == 2) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else if($course->published == 1) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            } else {
                $btn_publish = '';
            }

            // if(auth()->user()->hasRole('Superadmin')) {
                $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
                $temp['detail'] =  $btn_detail . '&nbsp;';
                
                // } else {
                // $temp['action'] = $btn_show . '&nbsp;' . $btn_edit;
            // }

            // if($course->enrolledStudents->count() < 1) {
            //     $temp['action'] .= '&nbsp;' . $btn_delete;
            // }

            if($course->trashed()) {
                $restore_route = route('admin.courses.restore', $course->id);
                $forever_delete_route = route('admin.courses.foreverDelete', $course->id);

                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                data-original-title="Restore to Review"><i class="material-icons">arrow_back</i></a>';

                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="delete" data-toggle="tooltip"
                data-original-title="Delete Forever"><i class="material-icons">delete_forever</i></a>';

                $temp['action'] = $btn_restore . '&nbsp;' . $perment_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }

    public function studentCourses()
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $course_ids)->get();
        $count_all = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->count();
        $count_achieved = Course::whereIn('id', $course_ids)->where('end_date', '<', Carbon::now()->format('Y-m-d'))->count();
        $count = [
            'actived' => $count_all,
            'deleted' => $count_achieved
        ];

        return view('backend.course.student', compact('count'));
    }

    public function getStudentCoursesByAjax($type)
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');

        switch($type) {
            case 'actived':
                $courses = Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->get();
            break;

            case 'deleted':
                $courses = Course::whereIn('id', $course_ids)->where('end_date', '<', Carbon::now()->format('Y-m-d'))->get();
            break;
        }

        $data = $this->getStudentData($courses);

        $count = [
            'actived' => Course::whereIn('id', $course_ids)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->count(),
            'deleted' => Course::whereIn('id', $course_ids)->where('end_date', '<', Carbon::now()->format('Y-m-d'))->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getStudentData($courses) {
        $data = [];
        $i = 0;

        foreach($courses as $course) {
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;
            $avatar = '<div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">CO</span>
                        </div>';

            if(!empty($course->course_image)) {
                $avatar = '<div class="avatar avatar-sm mr-8pt">
                                <img src="'. asset('storage/uploads/thumb/' . $course->course_image) .'" alt="Avatar" class="avatar-img rounded">
                            </div>';
            }
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">'. $avatar .'
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $course->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $course->slug .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . mb_substr($course->teachers[0]->name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->teachers[0]->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            if(!empty($course->category))
                $temp['category'] = $course->category->name;
            else 
                $temp['category'] = 'No Category';

            $temp['progress'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. $course->progress() . '% </small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';

            $show_route = route('admin.courses.show', $course->slug);
            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            if($course->published == 2) {
                $temp['action'] = '<button class="btn btn-info btn-sm btn-pending" data-action="show" data-toggle="tooltip" data-original-title="Show Item">
                                        <i class="material-icons">block</i>
                                </button>';
            } else {
                $temp['action'] = $btn_show . '&nbsp;';
            }

            array_push($data, $temp);
        }
        return $data;
    }

    /**
     * Add Favorite
     */
    public function addFavorite($course_id)
    {
        $rlt = DB::table('course_favorite')->insert([
            'course_id' => $course_id,
            'user_id' => auth()->user()->id
        ]);

        if($rlt) {
            return response()->json([
                'success' => true,
                'action' => 'add_favorite'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'action' => 'add_favorite'
            ]);
        }
        
    }

    /**
     * Remove course from Favoirtes
     */
    public function removeFavorite($course_id)
    {
        $favorite = DB::table('course_favorite')->where('course_id', $course_id)->where('user_id', auth()->user()->id);
        if($favorite->count() > 0) {
            $rlt = $favorite->delete();
            if($rlt) {
                return response()->json([
                    'success' => true,
                    'action' => 'remove_favorite'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'action' => 'remove_favorite'
                ]);
            }
        }
    }

    /**
     * Get favorite Courses
     */
    public function favorites()
    {
        $favorites = DB::table('course_favorite')->where('user_id', auth()->user()->id)->pluck('course_id');
        $courses = Course::whereIn('id', $favorites)->paginate(10);
        return view('backend.course.favorites', compact('courses'));
    }
}
