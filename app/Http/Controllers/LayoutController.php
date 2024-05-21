<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class LayoutController extends Controller
{
    //
    public function light(Request $request)
    {
        $url=$_SERVER['HTTP_REFERER'];
        // dd($request->getPathInfo());
        Session::put('layout', 'theme-light');
        return view('frontend.user.dashboard');
    }
    public function dark(Request $request)
    {
        // dd('dd');
        Session::put('layout', 'theme-dark');
        return  view('frontend.user.dashboard');
    }
}
