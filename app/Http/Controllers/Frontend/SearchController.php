<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Course;
use App\User;

use Carbon\Carbon;

class SearchController extends Controller
{
    // Search Course
    public function courses(Request $request)
    {
        $params = $request->all();
        $parentCategories = Category::where('parent', 0)->get();

        if(isset($params['_t']) && $params['_t'] == 'category') {

            $category = Category::find($params['_k']);

            if ($category->parent == 0) {
                $categoryIds = [];
                array_push($categoryIds, $category->id);
                foreach($category->children as $subCategory) {
                    array_push($categoryIds, $subCategory->id);
                    $items = $subCategory->children;
                    
                    foreach($items as $item) {
                        array_push($categoryIds, $item->id);
                    }
                }
            } else {
                $categoryIds = Category::where('parent', $parentId = $params['_k'])
                ->pluck('id')
                ->push($parentId)
                ->all();
            }
            
            $courses = Course::whereIn('category_id', $categoryIds)
                ->where('published', 1)
                ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $courses->setPath('/search/courses?_q='. $params['_q'] .'&_t='. $params['_t'] .'&_k='. $params['_k']);

        } else {
            
            if(isset($params['_q'])) {
                $courses_me = Course::where('title', 'like', '%' . $params['_q'] . '%')
                    ->where('published', 1)
                    ->where('end_date', '>', Carbon::now()->format('Y-m-d'));
                    
                $categories = Category::where('name', 'like', '%' . $params['_q'] . '%')->get();
                foreach($categories as $category) {
                    $subCategories = Category::where('parent', $category->id)->get();
                    foreach($subCategories as $subcategory) {
                        $courses_c = Course::where('category_id', $subcategory->id)->where('end_date', '>=', Carbon::now()->format('Y-m-d'));
                        $courses_me = $courses_me->union($courses_c);
                    }
                }
                $courses = $courses_me->paginate(10);
                $courses->setPath('/search/courses?_q='. $params['_q']);
            } else {
                $courses = Course::where('published', 1)->where('end_date', '>=', Carbon::now()->format('Y-m-d'))->paginate('10');
            }
        }

        if($request->ajax()) {
            $html = view('layouts.parts.search-results', ['courses' => $courses])->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }
        
        return view('frontend.search.courses', compact('parentCategories', 'courses'));
    }

    // Search instructor
    public function teachers(Request $request)
    {
        $params = $request->all();

        if(isset($params['_q'])) {
            $users = User::role('Instructor')
                ->where('verified', 1)
                ->where('name', 'like', '%' . $params['_q'] . '%')
                ->orWhere('headline', 'like', '%' . $params['_q'] . '%')->get();
        } else {
            $users = User::role('Instructor')->where('verified', 1)->orderBy('created_at', 'desc')->get();
        }

        $userIds = [];

        foreach($users as $user) {
            if (count($user->publishedCourses) > 0) {
                array_push($userIds, $user->id);
            }
        }

        $teachers = User::whereIn('id', $userIds)->paginate(10);
        
        return view('frontend.search.teachers', compact('teachers'));
    }

    public function getSearchFormCourseData($key)
    {

        $data = [];
        $categories = Category::where('name', 'like', '%' . $key . '%')->get();

        foreach($categories as $category) {
            array_push($data, [
                'id' => $category->id,
                'name' => $category->name,
                'type' => 'category'
                ]
            );
        }

        $courses = Course::where('title', 'like', '%' . $key . '%')->get();

        foreach($courses as $course) {
            array_push($data, [
                'id' => $course->id,
                'name' => $course->title,
                'type' => 'course'
                ]
            );
        }

        $ele = '<ul id="search___result" class="list-unstyled search_result collapse show">';

        $i = 0;

        foreach($data as $item) {
            $i++;
            $ele .= '<li data-id="'. $item['id'] .'" data-type="'. $item['type'] .'">'. $item['name'] .'</li>';
            if($i > 5) {
                break;
            }
        }

        $ele .= '</ul>';

        return response()->json([
            'success' => true,
            'result' => $data,
            'html' => $ele
        ]);
    }

    // Search for Course by Semantic
    public function searchCourse(Request $request)
    {
        $q = $request->q;
        $data = [];
        $results = [];

        $courses = Course::where('title', 'like', '%' . $q . '%')
            ->where('published', 1)
            ->where('end_date', '>=', Carbon::now()
            ->format('Y-m-d'))
            ->get();
        $search = [];

        foreach($courses as $course) {
            $image = ($course->course_image) ? asset('/storage/uploads/thumb/' . $course->course_image) : asset('/assets/img/no-image.jpg');
            $cat_id = isset($course->category) ? $course->category->id : '';
            array_push($search, [
                    'title' => $course->title,
                    'description' => $course->short_description,
                    'image' => $image,
                    'url' => config("app.url") . 'search/courses?_q=' . $course->title . '&_t=course&_k=' . $cat_id
                ]
            );
        }

        $results['course'] = [
            'name' => 'Course',
            'results' => $search
        ];

        $categories = Category::where('name', 'like', '%' . $q . '%')->get();
        $search = [];

        foreach($categories as $category) {
            $image = ($category->thumb) ? asset('/storage/uploads/' . $category->thumb) : asset('/assets/img/no-image.jpg');
            array_push($search, [
                    'title' => $category->name,
                    'description' => $category->description,
                    'image' => $image,
                    'url' => config("app.url") . 'search/courses?_q=' . $q . '&_t=category&_k=' . $category->id
                ]
            );
        }

        $results['category'] = [
            'name' => 'Category',
            'results' => $search
        ];

        $data['results'] = $results;
        return response()->json($data);
    }

    // Search for Instructor by Semantic
    public function searchInstructor(Request $request)
    {
        $q = $request->q;
        $data = [];
        $results = [];

        $users = User::role('Instructor')->where('verified', 1)->where('name', 'like', '%' . $q . '%')->get();
        $instructors = [];

        foreach($users as $user) {

            if (count($user->publishedCourses) > 0) {
                $avatar = ($user->avatar) ? asset('/storage/avatars/'. $user->avatar) : asset('/images/no-avatar.jpg');
                array_push($instructors, [
                        'title' => $user->name,
                        'description' => !empty($user->headline) ? $user->headline : '',
                        'image' => $avatar,
                        'url' => config("app.url") . 'search/instructors?_q=' . $user->name . '&_t=user&_k=' . $user->id
                    ]
                );
            }
        }

        $results['name'] = [
            'name' => 'Match with Name',
            'results' => $instructors
        ];

        $subjects = User::role('Instructor')->where('headline', 'like', '%' . $q . '%')->get();
        $instructors = [];

        foreach($subjects as $user) {
            if (count($user->publishedCourses) > 0) {
                $avatar = ($user->avatar) ? asset('/storage/avatars/'. $user->avatar) : asset('/images/no-avatar.jpg');
                array_push($instructors, [
                        'title' => $user->name,
                        'description' => !empty($user->headline) ? $user->headline : '',
                        'image' => $avatar,
                        'url' => config("app.url") . 'search/instructors?_q=' . $q
                    ]
                );
            }
        }

        $results['headline'] = [
            'name' => 'Match with Headline',
            'results' => $instructors
        ];

        // $courses = Course::where('title', 'like', '%' . $q . '%')->get();
        // $instructors = [];

        // foreach($courses as $course) {
        //     $teachers = $course->teachers;
        //     foreach($teachers as $user) {
        //         $avatar = ($user->avatar) ? asset('/storage/avatars/'. $user->avatar) : asset('/images/no-avatar.jpg');
        //         array_push($instructors, [
        //                 'title' => $user->name,
        //                 'description' => !empty($user->headline) ? $user->headline : '',
        //                 'image' => $avatar,
        //                 'url' => config("app.url") . 'search/instructors?_q=' . $q
        //             ]
        //         );
        //     }
        // }

        // $results['course'] = [
        //     'name' => 'Match with Course',
        //     'results' => $instructors
        // ];

        $data['results'] = $results;

        return response()->json($data);
    }
}
