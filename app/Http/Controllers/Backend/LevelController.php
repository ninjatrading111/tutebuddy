<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Level;
use App\Models\Category;

use DB;

class LevelController extends Controller
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
     * List of Level types
     */
    public function index() {

        $types = Level::where('parent', 0)->get();
        return view('backend.level.index', compact('types'));
    }

    /**
     * Edit level
     */
    public function edit($id) {

        $level = Level::find($id);
        return view('backend.level.edit', compact('level'));
    }

    /**
     * Store a Level
     */
    public function store(Request $request) {
        
        $request_data = $request->all();

        $level_data = [
            'name' => $request_data['name'],
            'slug' => str_slug($request_data['name']),
            'order' => $request->order,
            'description' => $request_data['description']
        ];

        // Parent Level check
        $level = Level::find($request_data['type']);

        // If no parent level then create new one
        if(empty($level)) {

            $p_level = Level::create([
                'name' => $request_data['type'],
                'slug' => str_slug($request_data['type']),
                'description' => '',
                'parent' => 0,
                'order' => 0
            ]);
            $level_data['parent'] = $p_level->id;
        } else {
            $level_data['parent'] = $request_data['type'];
        }

        $level = Level::create($level_data);

        // Adjust order
        $levels = DB::table('levels')->where('parent', '=', $level_data['parent'])
            ->orderBy('order')->get();
        
        $order = 0;
        foreach($levels as $item) {
            $order++;
            Level::find($item->id)->update(['order' => $order]);
        }        

        return response()->json([
            'success' => true,
            'level' => $level
        ]);
    }

    /**
     * Update a Level
     */
    public function update(Request $request, $id) {

        $level = Level::find($id);
        $level->name = $request->name;
        $level->slug = str_slug($request->name);
        $level->description = $request->description;

        try {
            $level->save();

            return response()->json([
                'success' => true,
                'action' => 'update'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function destroy($id) {

        try {
            Level::find($id)->delete();

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

    public function getLevelsByCategory($id) {

        $category = Category::find($id);

        if(empty($category)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ]);
        }

        $level_id = $category->level_id;

        if(!empty($level_id)) {
            $levels = Level::where('parent', $level_id)->get();
            $html = $this->html_options($levels);
            return response()->json([
                'success' => true,
                'levels' => $html
            ]);
        } else {
            $p_category = Category::find($category->parent);
            if(!empty($p_category->level_id)) {
                $levels = Level::where('parent', $p_category->level_id)->get();
                $html = $this->html_options($levels);
                return response()->json([
                    'success' => true,
                    'levels' => $html
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'not found'
                ]);
            }
        }
    }

    function html_options($data) {
        $html = '';
        foreach($data as $item) {
            $html .= "<option value='$item->id'>$item->name</option>";
        }

        return $html;
    }

    /**
     * List data for Datatable
     */
    public function getList() {

        $p_levels = Level::where('parent', 0)->get();
        $data = [];

        foreach($p_levels as $level) {

            $space = '';
            $p_array = $this->getArrayData($level, $space);
            array_push($data, $p_array);

            if($level->children()->count() > 0) {
                $space .= '<div class="dv">&nbsp;</div>';
                foreach($level->children as $level) {
                    $c_array = $this->getArrayData($level, $space);
                    array_push($data, $c_array);
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    function getArrayData($level, $space) {

        $array = [];
        $array['index'] = '';
        
        $array['order'] = ($level->parent == '0') ? '---' : $level->order;

        $array['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                            '. $space .'
                            <div class="avatar avatar-sm mr-8pt">
                                <span class="avatar-title rounded-circle">' . mb_substr($level->name, 0, 2) . '</span>
                            </div>
                            <div class="media-body">
                                <div class="d-flex flex-column">
                                    <p class="mb-0">
                                        <strong class="js-lists-values-level-name">' . $level->name . '</strong>
                                    </p>
                                    <small
                                        class="js-lists-values-level-slug text-50">' . $level->slug . '</small>
                                </div>
                            </div>
                        </div>';

        if (strlen($level->description) > 30)
            $description = mb_substr($level->description, 0, 30) . '...'; 
        else
            $description = $level->description;
        
        $array['description'] = $description;

        $edit_route = route('admin.levels.edit', $level->id);
        $delete_route = route('admin.levels.destroy', $level->id);

        $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
        $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

        $array['action'] = $btn_edit . '&nbsp;' . $btn_delete;

        return $array;
    }
}
