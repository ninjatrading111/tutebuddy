<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditingController extends Controller
{
    //
    public function index(){
        return view('frontend.user.editing');
    }
}
