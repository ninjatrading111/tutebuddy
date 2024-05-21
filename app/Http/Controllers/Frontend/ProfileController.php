<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
   
    public function index(){
        return view('frontend.user.myprofile');
    }
    public function fetchdata(Request $req){
        // dd($res->first_name);
        $validator = Validator::make($req->all(), [
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email' =>'required|string|email|unique:users',
            'mobile_number'=>'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,gif,png|max:2048',
        ]);
        $error=array();
        $success='';
        if ($validator->fails()) {
            foreach($validator->errors()->all() as $message){
                $error[]="<div class='alert alert-danger'>".$message."</div>";
            }
        }else{
            $image=$req->file('image');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images'), $imageName);
            $success="<div class='alert alert-success'>ajax successed </div>";
        }
        
        // dd($error);
        $output=array(
            'error'=>$error,
            'success'=>$success
        );
        // dd(output);
        echo json_encode($output);
    }
}
