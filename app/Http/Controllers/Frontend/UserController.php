<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function getTeacherProfile($uuid)
    {
        $teacher = User::where('uuid', $uuid)->first();
        $professions = json_decode($teacher->profession);

        if ($professions) {
            $similar_teachers = collect();

            $teachers = User::role('Instructor')
                ->where('verified', 1)
                ->whereNotNull('avatar')
                ->where('id', '!=', $teacher->id)
                ->get();
    
            foreach($teachers as $t) {
                if ($similar_teachers->count() > 3) {
                    break;
                }

                $ps = json_decode($t->profession);
                if ($ps && count(array_intersect($professions, $ps)) > 0 && count($t->courses) > 0) {
                    $similar_teachers->push($t);
                }
            }

            return view('frontend.user.profile', compact('teacher', 'similar_teachers'));

        } else {

            $similar_teachers = User::role('Instructor')
                ->where('verified', 1)
                ->whereNotNull('avatar')
                ->where('id', '!=', $teacher->id)
                ->orderBy('created_at', 'desc')->limit(4)->get();

            return view('frontend.user.profile', compact('teacher', 'similar_teachers'));
        }
    }
}
