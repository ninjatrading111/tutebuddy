<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Media;

use App\Http\Controllers\Traits\FileUploadTrait;

class BundlesController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Bundles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = [
            'all' => Bundle::all()->count(),
            'published' => Bundle::where('published', 1)->count(),
            'pending' => Bundle::where('published', 0)->count(),
            'deleted' => Bundle::onlyTrashed()->count()
        ];

        $bundles = Bundle::ofAuthor()->get();

        return view('backend.bundles.index', compact('count', 'bundles'));
    }

    /**
     * Create a Bundle.
     */
    public function create() {
        $parentCategories = Category::where('parent', 0)->get();
        $courses = Course::all();

        $tags = DB::table('tags')->get();

        return view('backend.bundles.create', compact('courses', 'parentCategories', 'tags'));
    }

    /**
     * Store Bundle
     */
    public function store(Request $request)
    {
        $data = $request->all();

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

        $data['tags'] = json_encode($data['tags']);

        if(isset($data['action']) && $data['action'] == 'publish') {
            $data['published'] = 1;
        }

        if(isset($data['action']) && $data['action'] == 'draft') {
            $data['published'] = 0;
        }

        $bundle = Bundle::create($data);

        // Bundle image
        if(!empty($data['bundle_image'])) {
            $image = $request->file('bundle_image');
            $bundle_image_url = $this->saveImage($image, 'upload', true);
            $bundle->bundle_image = $bundle_image_url;
        }

        // Create Media
        if(!empty($data['bundle_video'])) {

            // Add Media
            $video_id = array_last(explode('/', $request->bundle_video));

            $media_data = [
                'model_type' => Bundle::class,
                'model_id' => $bundle->id,
                'name' => $request->title . ' - Video',
                'url' => $request->bundle_video,
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];

            $media = Media::create($media_data);
        }

        $bundle->slug = str_slug($request->title);
        $bundle->save();

        $bundle->user_id = auth()->user()->id;
        $bundle->save();

        $courses = array_filter((array)$data['courses']);
        $bundle->courses()->sync($courses);

        return response()->json([
            'success' => true,
            'bundle_id' => $bundle->id
        ]);
    }

    public function edit($id)
    {
        $bundle = Bundle::find($id);
        $parentCategories = Category::where('parent', 0)->get();
        $courses = Course::all();
        $tags = DB::table('tags')->get();

        $bundle_courses = $bundle->courses->pluck('id')->toArray();
        return view('backend.bundles.edit', compact('bundle', 'courses', 'parentCategories', 'tags', 'bundle_courses'));
    }

    public function update(Request $request, $id)
    {
        $data = array_filter($request->all());

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

        $data['tags'] = json_encode($data['tags']);
        
        $bundle = Bundle::find($id);

        // Bundle image
        if(!empty($data['bundle_image'])) {

            $image = $request->file('bundle_image');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . $bundle->bundle_image))) {
                File::delete(public_path('/storage/uploads/' . $bundle->bundle_image));
                File::delete(public_path('/storage/uploads/thumb/' . $bundle->bundle_image));
            }

            $bundle_image_url = $this->saveImage($image, 'upload', true);
            $data['bundle_image'] = $bundle_image_url;
        }

        if(isset($data['action']) && $data['action'] == 'publish') {
            $data['published'] = 1;
        }

        if(isset($data['action']) && $data['action'] == 'draft') {
            $data['published'] = 0;
        }

        try {
            $bundle->update($data);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        // Update Course Media - Course video
        if(!empty($data['bundle_video'])) {
            $video_id = array_last(explode('/', $data['bundle_video']));
            $media_data = [
                'model_type' => 'App\Models\Bundle',
                'name' => $data['title'] . ' - Video',
                'url' => $data['bundle_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];
    
            $media = Media::where('model_type', 'App\Models\Bundle')
                ->where('model_id', $id)->first();

            if(empty($media)) {
                $media_data['model_id'] = $id;
                $media = Media::create($media_data);
            } else {
    
                try {
                    Media::where('model_type', 'App\Models\Bundle')
                        ->where('model_id', $id)->update($media_data);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true
        ]);

    }

    /**
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $bundle = Bundle::find($id);
        if($bundle->published == 1) {
            $bundle->published = 0;
        } else {
            $bundle->published = 1;
        }

        $bundle->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $bundle->published
        ]);
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $bundles = Bundle::all();
            break;
            case 'published':
                $bundles = Bundle::where('published', 1)->get();
            break;
            case 'pending':
                $bundles = Bundle::where('published', 0)->get();
            break;
            case 'deleted':
                $bundles = Bundle::onlyTrashed()->get();
            break;
            default:
                $bundles = Bundle::all();
        }

        $data = $this->getArrayData($bundles);

        $count = [
            'all' => Bundle::all()->count(),
            'published' => Bundle::where('published', 1)->count(),
            'pending' => Bundle::where('published', 0)->count(),
            'deleted' => Bundle::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function destroy($id) {

        try {
            Bundle::find($id)->delete();

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

    public function restore($id) {

        try {
            Bundle::withTrashed()->find($id)->restore();

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

    public function getArrayData($bundles) {
        $data = [];
        $i = 0;

        foreach($bundles as $bundle) {
            $i++;
            $temp = [];
            $temp['index'] = '';
            $temp['no'] = $i;

            $avatar = '<div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">PA</span>
                        </div>';

            if(!empty($bundle->bundle_image)) {
                $avatar = '<div class="avatar avatar-sm mr-8pt">
                                <img src="'. asset('storage/uploads/thumb/' . $bundle->bundle_image) .'" alt="Avatar" class="avatar-img rounded">
                            </div>';
            }

            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">'. $avatar .'
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $bundle->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $bundle->slug .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . mb_substr($bundle->user->name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $bundle->user->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            if(!empty($bundle->category))
                $temp['category'] = $bundle->category->name;
            else 
                $temp['category'] = 'No Category';

            $temp['courses'] = $bundle->courses->count();

            $temp['price'] = $bundle->course;

            $show_route = route('bundles.show', $bundle->slug);
            $edit_route = route('admin.bundles.edit', $bundle->id);
            $delete_route = route('admin.bundles.destroy', $bundle->id);
            $publish_route = route('admin.bundle.publish', $bundle->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($bundle->published == 0) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            }

            if($bundle->trashed()) {
                $restore_route = route('admin.bundle.restore', $bundle->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Restore"><i class="material-icons">arrow_back</i></a>';

                $forever_delete_route = route('admin.bundle.foreverDelete', $bundle->id);
                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="delete" data-toggle="tooltip"
                    data-original-title="Delete Forever"><i class="material-icons">delete_forever</i></a>';

                $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete . '&nbsp' . $perment_delete;
            } else {
                $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }

    /** Student Dashboard */
    public function studentBundles()
    {
        $bundle_ids = DB::table('bundle_student')->where('user_id', auth()->user()->id)->pluck('bundle_id');
        $bundles = Bundle::whereIn('id', $bundle_ids)->paginate(15);

        return view('backend.bundles.student', compact('bundles'));
    }

    /**
     * Delete Forever
     */
    public function foreverDelete($id)
    {
        try {

            Bundle::withTrashed()->where('id', $id)->forceDelete();

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
}
