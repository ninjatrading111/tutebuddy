<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;

use App\Models\Page;

class PagesController extends Controller
{
    use FileUploadTrait;
    
    /**
     * Display a listing of Pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = [
            'all' => Page::all()->count(),
            'published' => Page::where('published', 1)->count(),
            'pending' => Page::where('published', 0)->count(),
            'deleted' => Page::onlyTrashed()->count()
        ];

        return view('backend.pages.index', compact('count'));
    }

    /**
     * Create a new Page
     */
    public function create()
    {
        return view('backend.pages.create');
    }

    /**
     * Edit a Page
     */
    public function edit($id)
    {
        $page = Page::find($id);
        return view('backend.pages.edit', compact('page'));
    }

    /**
     * Store new Page
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $page_data = [
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'user_id' => auth()->user()->id
        ];

        $page = Page::create($page_data);

        // Attachment
        if(isset($request->image)) {
            $image_file = $request->file('image');
            $img_url = $this->saveImage($image_file, 'upload', true);
            $page->image = $img_url;
        }

        $page->save();

        return response()->json([
            'success' => true,
            'page_id' => $page->id
        ]);
    }

    /**
     * Update Page
     */
    public function update(Request $request, $id)
    {
        $page = Page::find($id);
        $page_data = [
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords
        ];

        try {
            $page->update($page_data);

            // Featured image
            if(isset($request->image)) {
                $image_file = $request->file('image');

                // Delete existing file
                if (File::exists(public_path('/storage/uploads/' . $page->image))) {
                    File::delete(public_path('/storage/uploads/' . $page->image));
                    File::delete(public_path('/storage/uploads/thumb/' . $page->image));
                }

                $image_url = $this->saveImage($image_file, 'upload', true);
                $page->image = $image_url;
                $page->save();
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
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
        $page = Page::find($id);
        if($page->published == 1) {
            $page->published = 0;
        } else {
            $page->published = 1;
        }

        $page->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $page->published
        ]);
    }

    public function destroy($id) {

        try {
            Page::find($id)->delete();

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
            Page::withTrashed()->find($id)->restore();

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
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $pages = Page::all();
            break;
            case 'published':
                $pages = Page::where('published', 1)->get();
            break;
            case 'pending':
                $pages = Page::where('published', 0)->get();
            break;
            case 'deleted':
                $pages = Page::onlyTrashed()->get();
            break;
            default:
                $pages = Page::all();
        }

        $data = $this->getArrayData($pages);

        $count = [
            'all' => Page::all()->count(),
            'published' => Page::where('published', 1)->count(),
            'pending' => Page::where('published', 0)->count(),
            'deleted' => Page::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getArrayData($pages) {
        $data = [];

        foreach($pages as $item) {

            $temp = [];
            $temp['index'] = '';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
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
                            </div>';
            $temp['slug'] = '<strong>' . $item->slug . '</strong>';

            if($item->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            $edit_route = route('admin.pages.edit', $item->id);
            $delete_route = route('admin.pages.destroy', $item->id);
            $publish_route = route('admin.pages.publish', $item->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($item->published == 0) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            }

            if($item->trashed()) {
                $restore_route = route('admin.pages.restore', $item->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Recover"><i class="material-icons">restore_from_trash</i></a>';
            }

            if(auth()->user()->hasRole('Superadmin')) {
                $temp['action'] = $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
            } else {
                $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }
}
