<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class TypeController extends Controller
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
     * List of types
     */
    public function index() {

        return view('backend.type.index');
    }

    /**
     * Store new Type
     */
    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required'
        ]);

        $data = $request->all();
        
        try {
            $type = DB::table('types')->updateOrInsert(
                ['name' => $data['name']],
                [
                    'name' => $data['name'],
                    'description' => $data['description']
                ]
            );

            return response()->json([
                'success' => true,
                'type' => $type
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Edit Type
     */
    public function edit($id) {

        $type = DB::table('types')->where('id', $id)->first();
        return view('backend.type.edit', compact('type'));
    }

    /**
     * Update a Type
     */
    public function update(Request $request, $id) {

        try {

            DB::table('types')->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'description' => $request->description
                ]);

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

    /**
     * Delete a Type
     */
    public function destroy($id) {

        try {
            DB::table('types')->where('id', $id)->delete();

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
     * List data for Datatable
     */
    public function getList() {
        $types = DB::table('types')->get();
        $data = [];

        foreach($types as $type) {

            $temp = [];

            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">
                                        ' . mb_substr($type->name, 0, 2) . '
                                    </span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <p class="mb-0">
                                            <strong class="">' . $type->name . '</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>';
            
            $temp['description'] = $type->description;

            $edit_route = route('admin.types.edit', $type->id);
            $delete_route = route('admin.types.destroy', $type->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);

            $temp['action'] = $btn_edit;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
