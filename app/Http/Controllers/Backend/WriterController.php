<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WriterController extends Controller
{
    //
    public function index()
    {
        return view("backend.users.writer");
    }
    public function getdata()
    { {
            if (\request()->ajax()) {
                $data = User::where('role','=','writer')->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" data-id="' . $row->id . '"><i class="fa fa-edit"></i>Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '"><i class="fa fa-trash"></i>Delete</a>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('backend.users.writer');
        }
    }
    public function postdata(Request $request)
    {
        if (\request()->ajax()) {
            if($request->action=='insert'){
                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|email|unique:users',
                    'password' => 'required|string|max:255',
                ]);
            }elseif($request->action== 'update'){
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                // 'email' => 'required|string|email|unique:users',
                'password' => 'required|string|max:255',
            ]);}
            $error = array();
            $success = '';
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $message) {
                    $error[] = "<div class='alert alert-danger'>" . $message . "</div>";
                }
            } else {
                //    dd('ddd');
                if ($request->action == "insert") {
                    $new_writer = new User([
                        "firstname" => $request->first_name,
                        "lastname" => $request->last_name,
                        "email" => $request->email,
                        "password" => Hash::make($request->password),
                        "role" => 'writer',
                    ]);
                    $new_writer->save();
                    $success = "<div class='alert alert-success'>Your insert success! </div>";
                }elseif ($request->action == "update") {
                    $data=User::where("id","=", $request->update_id)->first();
                    $data->update([
                        "firstname"=> $request->first_name,
                        "lastname"=> $request->last_name,
                        'email'=> $request->email,
                        'password'=> Hash::make($request->password),
                    ]);
                    $success = "<div class='alert alert-success'> Updated successfully! </div>";

                }
            }
            $output = array(
                'error' => $error,
                'success' => $success
            );
            echo json_encode($output);
        } else
            return back();

    }
    public function fetchdata(Request $request)
    {
        if (\request()->ajax()) {
            $data = User::where('id', '=', $request->id)->first();
            if ($data) {
                $output = array(
                    'id'=> $data->id,
                    'first_name' => $data->firstname,
                    'last_name'=>   $data->lastname,
                    'email' => $data->email,
                    'password' => $data->password,
                );
                echo json_encode($output);
            } else
                return back();
        }
    }
    public function removedata(Request $request)
    {
        if (\request()->ajax()) {
            $data = User::where('id', '=', $request->id)->delete();
            $output = array(
                'success' => true,
            );
            echo json_encode($output);
        } else
            return back();
    }
}
