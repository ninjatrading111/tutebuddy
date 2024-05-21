<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Level;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Traits\FileUploadTrait;
use Spatie\Permission\Models\Permission;

class CategoryController extends Controller
{
    use FileUploadTrait;
    private $cateArray = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->cateArray = [];
    }

    /**
     * List of Categories
     */
    public function index() {

        $parentCategories = Category::where('parent', 0)
            ->orderBy('updated_at', 'desc')->get();

        $levels = Level::where('parent', 0)->get();
        
        return view('backend.category.index', compact('parentCategories' , 'levels'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $category = Category::where('slug', '=', str_slug($request->name))->first();
        if($category == null){
            $category = new  Category();
        }
        $category->name = $request->name;
        $category->slug = ($request->slug !== null) ? $request->slug : str_slug($request->name);
        if($request->parent != '') {
            $category->parent = $request->parent;
        }
        $category->description = $request->description;
        $category->level_id = $request->level;
        
        $thumb = $request->has('thumb') ? $request->file('thumb') : false;
        if($thumb) {
            $thumb_url = $this->saveImage($thumb);
            $category->thumb = $thumb_url;
        }

        $error = '';

        try {
            $category->save();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        if(!empty($error)) {
            return response()->json([
                'success' => false,
                'message' => $error
            ]);
        } else {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        }
        
    }

    public function update(Request $request, $id) {

        $category = Category::find($id);

        $category->name = $request->name;
        $category->slug = ($request->slug !== null) ? $request->slug : str_slug($request->name);
        $category->description = $request->description;
        $category->parent = $request->parent;
        $category->level_id = $request->level;

        $thumb = $request->has('thumb') ? $request->file('thumb') : false;
        if($thumb) {
            $thumb_url = $this->saveImage($thumb);
            $category->thumb = $thumb_url;
        }

        try {
            $category->save();

            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id) {
        $parentCategories = Category::where('parent', 0)->get();
        $category = Category::find($id);
        $levels = Level::where('parent', 0)->get();
        return view('backend.category.edit', compact('parentCategories', 'category', 'levels'));
    }

    public function destroy($id) {

        try {
            Category::find($id)->delete();

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
     * Data for Select2
     */
    public function getSelet2Data(Request $request) {

        $data = [
            [
                'id' => '0',
                'text' => 'No Parent',
                'selected' => true
            ]
        ];

        // Get first Level Categories
        $p_categories = Category::where('parent', 0)->get();
        
        // Get Second Level Categories
        foreach($p_categories as $category) {

            $item = [
                'id' => $category->id,
                'text' => $category->name
            ];

            if(isset($request->q) && (stripos($category->name, $request->q) !== false)) {
                array_push($data, $item);
            }

            $p2_categories = Category::where('parent', $category->id)->get();

            foreach($p2_categories as $p2_category) {

                if(isset($request->q) && (stripos($p2_category->name, $request->q) !== false)) {

                    $item = [
                        'id' => $p2_category->id,
                        'text' => '- ' . $p2_category->name
                    ];
                    array_push($data, $item);
                }

                if(!isset($request->q)) {
                    $item = [
                        'id' => $p2_category->id,
                        'text' => '- ' . $p2_category->name
                    ];
                    array_push($data, $item);
                }
            }
        }

        return response()->json([
            'results' => $data
        ]);
    }

    /**
     * List data for Datatable
     */
    public function getTableData() {
        $p_categories = Category::where('parent', 0)->get();

        foreach($p_categories as $category) {

            $space = '';
            $p_array = $this->getArrayData($category, $space);
            array_push($this->cateArray, $p_array);

            if($category->children()->count() > 0) {
                $space .= '';
                $this->getChildData($category, $space);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $this->cateArray
        ]);
    }

    function getChildData($category, $space) {
        $space .= '<div class="dv">&nbsp;</div>';
        foreach($category->children as $category) {
            $c_array = $this->getArrayData($category, $space);
            array_push($this->cateArray, $c_array);
            if($category->children()->count() > 0) {
                $this->getChildData($category, $space);
            }
        }
    }

    function getArrayData($category, $space) {

        $array = [];
        $array['index'] = '';

        if(empty($category->thumb)) {
            $avatar = '<span class="avatar-title rounded-circle">' . mb_substr($category->name, 0, 2) . '</span>';
        } else {
            $avatar = '<img src="/storage/uploads/' . $category->thumb . '" alt="Avatar" class="avatar-img rounded-circle">';
        }

        $array['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                            '. $space .'
                            <div class="avatar avatar-sm mr-8pt">'
                                . $avatar .
                            '</div>
                            <div class="media-body">
                                <div class="d-flex flex-column">
                                    <p class="mb-0">
                                        <strong class="js-lists-values-category-name">' . $category->name . '</strong>
                                    </p>
                                    <small
                                        class="js-lists-values-category-slug text-50">' . $category->slug . '</small>
                                </div>
                            </div>
                        </div>';

        if (strlen($category->description) > 30)
            $description = mb_substr($category->description, 0, 30) . '...'; 
        else
            $description = $category->description;
        
        $array['description'] = $description;

        $edit_route = route('admin.categories.edit', $category->id);
        $delete_route = route('admin.categories.destroy', $category->id);

        $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
        $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

        $array['action'] = $btn_edit . '&nbsp;' . $btn_delete;

        return $array;
    }
}
