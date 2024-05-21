<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\Bundle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->share('active_nav', 'homepage');
    }

    // Load Homepage
    public function index() {

        // Parent Categories
        $parentCategories = '';

        // Get Featured Courses
        // $featuredCourses = Course::where('featured', 1)->limit(8)->get();

        $featuredCourses = '';

        // Top reviews
        $reviews = '';

        // Top Paths
        // $bundles = Bundle::where('published', 1)->limit(6)->get();
        $bundles = '';

        return view('frontend.index', compact('parentCategories', 'featuredCourses', 'reviews', 'bundles'));
    }

    public function getFeaturedCarouselData($current)
    {
        $from = (int)$current + 4;
        $featuredCourses = Course::where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('created_at', 'desc')->skip($from)->take(1)->get();

        $html = '';

        foreach($featuredCourses as $course) {

            $rating = view('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')]);
            $price = '';
            if (!empty($course->private_price)) {
                $price .= '<div class="price-text">
                    <span class="card-title text-primary mt-1">' .getCurrency(config('app.currency'))['symbol'] . $course->private_price .'
                        <small class="text-muted"> (Private)</small>
                    </span>
                </div>';
            }
            if (!empty($course->group_price)) {
                $price .= '<div class="price-text">
                    <span class="card-title text-accent">'. getCurrency(config('app.currency'))['symbol'] . $course->group_price .'
                        <small class="text-muted"> (Group)</small>
                    </span>
                </div>';
            }
            $lessons = '';
            foreach($course->lessons as $lesson) {
                $lessons .= '<div class="d-flex align-items-center">
                    <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                    <p class="flex text-black-50 lh-1 mb-0">
                        <small>'. $lesson->title .'</small></p>
                </div>';
            }

            $html .= '<div class="card-group-row__col">
                        <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card" data-toggle="popover" data-trigger="click" data-original-title="" title="" data-domfactory-upgraded="overlay">
                            <a href="'. route('courses.show', $course->slug) .'" class="card-img-top js-image" data-position="center" data-height="140" data-domfactory-upgraded="image" 
                            style="display: block; position: relative; overflow: hidden; background-image: url('. asset('storage/uploads/' . $course->course_image). '); background-size: cover; background-position: center center; height: 140px;">
                                <span class="overlay__content">
                                    <span class="overlay__action d-flex flex-column text-center">
                                        <i class="material-icons icon-32pt">play_circle_outline</i>
                                        <span class="card-title text-white">Preview</span>
                                    </span>
                                </span>
                            </a>

                            <div class="card-body flex">
                                <div class="d-flex">
                                    <div class="flex">
                                        <a class="card-title" href="'. route('courses.show', $course->slug) .'">'. $course->title .'</a>
                                        <small class="text-50 font-weight-bold mb-4pt">'. $course->teachers->first()->name .'</small>
                                    </div>
                                </div>
                                <div class="d-flex mt-1">
                                    <div class="rating flex">'. $rating .'</div>
                                </div>
                                <div class="d-flex mt-1">
                                    <div class="price flex">'. $price .'</div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-between">
                                    <div class="col-auto d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>'. $course->duration() .'</small></p>
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>'. $course->lessons->count() .' lessons</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="popoverContainer d-none">
                            <div class="media">
                                <div class="media-left mr-12pt">
                                    <img src="'. asset('storage/uploads/' . $course->course_image) .'" width="40" height="40" alt="'. $course->title .'" class="rounded">
                                </div>
                                <div class="media-body">
                                    <div class="card-title mb-0">'. $course->title .'</div>
                                    <p class="lh-1 mb-0">
                                        <span class="text-black-50 small">with</span>
                                        <span class="text-black-50 small font-weight-bold">'. $course->teachers->first()->name .'</span>
                                    </p>
                                </div>
                            </div>

                            <p class="my-16pt text-black-70">'. $course->short_description .'</p>

                            <div class="mb-16pt">'. $lessons .'</div>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center mb-4pt">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>'. $course->duration() .' hours</small></p>
                                    </div>
                                    <div class="d-flex align-items-center mb-4pt">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>'. $course->lessons->count() .' lessons</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>'. $course->level->name .'</small></p>
                                    </div>
                                </div>
                            <div class="col text-right">
                            <a href="'. route('courses.show', $course->slug) .'" class="btn btn-primary">View Detail</a>
                        </div>
                    </div>';
        }

        return response()->json([
            'data' => $html
        ]);
    }
}
