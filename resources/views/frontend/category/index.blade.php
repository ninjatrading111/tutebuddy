@extends('layouts.app')

@section('content')

@push('after-styles')
<style>
[dir=ltr] .sidebar-light .sidebar-submenu .sidebar-menu-text {
    border-left: none;
    padding-left: .25rem;
}
[dir=ltr] .sidebar-light .sidebar-submenu.sm-last {
    padding-left: 1.25rem;
}
[dir=ltr] .sidebar-right {
    padding-bottom: 240px !important;
}
</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div data-push data-responsive-width="992px" class="mdk-drawer-layout js-mdk-drawer-layout">
        <div class="mdk-drawer-layout__content">

            <div class="page-section">
                <div class="container page__container">

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-24pt" style="white-space: nowrap;">
                        <small class="flex text-muted text-headings text-uppercase mr-3 mb-2 mb-sm-0">
                            @lang('string.frontend.category.displaying_description')
                        </small>
                    </div>

                    @foreach($parentCategories as $category)

                    @if($category->coursesWithSubs()->count() > 0)

                    <div class="page-separator">
                        <div class="page-separator__text">{{ $category->name }}</div>
                        <div class="d-flex flex">
                            <div class="flex">&nbsp;</div>
                            <div style="padding-left: 8px; background-color: #f5f7fa;">
                                <a href="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}" 
                                class="btn btn-md btn-white float-right border">@lang('labels.frontend.general.browse_all')</a>
                            </div>
                        </div>
                    </div>

                    <div class="row card-group-row">

                        @foreach($category->coursesWithSubs() as $course)
                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card" data-toggle="popover" data-trigger="click">

                                <a href="{{ route('courses.show', $course->slug) }}" class="card-img-top js-image" data-position="" data-height="140">
                                    @if(!empty($course->course_image))
                                    <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" 
                                        alt="{{ $course->title }}" class="avatar-img rounded" >
                                    @else
                                    <img src="{{ asset('/assets/img/no-image.jpg') }}" 
                                        alt="{{ $course->title }}" class="avatar-img rounded" >
                                    @endif
                                    <span class="overlay__content">
                                        <span class="overlay__action d-flex flex-column text-center">
                                            <i class="material-icons icon-32pt">play_circle_outline</i>
                                            <span class="card-title text-white">@lang('labels.frontend.general.preview')</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="card-body flex">
                                    <div class="d-flex">
                                        <div class="flex">
                                            <a class="card-title" href="fixed-student-course.html">{{ $course->title }}</a>
                                            <small class="text-50 font-weight-bold mb-4pt">{{ !empty($course->teachers->first()) ? $course->teachers->first()->name : '' }}</small>
                                        </div>
                                        @if(auth()->check())
                                        <a href="{{ route('admin.course.removeFavorite', $course->id) }}" name="remove_favorite" data-toggle="tooltip" data-title="Remove Favorite" data-placement="top" 
                                            data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite font-color-red @if(!$course->favorited()) d-none @endif"
                                            data-original-title="" title="">favorite</a>
                                        <a href="{{ route('admin.course.addFavorite', $course->id) }}" name="add_favorite" data-toggle="tooltip" data-title="Add Favorite" data-placement="top" 
                                            data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite @if($course->favorited()) d-none @endif"
                                            data-original-title="" title="">favorite_border</a>
                                        @endif
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            @if($course->reviews->count() > 0)
                                            @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                            @else
                                                <small class="text-50">@lang('labels.frontend.general.no_rating')</small>
                                            @endif
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>

                                    <div class="d-flex mt-1">
                                        <div class="price flex">
                                            @if (!empty($course->private_price))
                                            <div class="price-text">
                                                <span class="card-title text-primary mt-1">
                                                    {{ getCurrency(config('app.currency'))['symbol'] . $course->private_price }}
                                                    <small class="text-muted"> (@lang('labels.frontend.course.private'))</small>
                                                </span>
                                            </div>
                                            @endif
                                            @if (!empty($course->group_price))
                                            <div class="price-text">
                                                <span class="card-title text-accent">
                                                    {{ getCurrency(config('app.currency'))['symbol'] . $course->group_price }} 
                                                    <small class="text-muted"> (@lang('labels.frontend.course.group'))</small>
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }}</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('storage/uploads/thumb/' . $course->course_image) }}" width="40" height="40" alt="{{ $course->title }}" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">{{ $course->title }}</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span class="text-black-50 small font-weight-bold">{{ !empty($course->teachers->first()) ? $course->teachers->first()->name : '' }}</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">{{ $course->short_description }}</p>

                                <div class="mb-16pt">
                                    @foreach($course->lessons as $lesson)
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0">
                                            <small>{{ $lesson->title }}</small></p>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }} hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->category->name }}</small></p>
                                        </div>
                                    </div>

                                    <div class="col text-right">
                                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary">
                                        @lang('labels.frontend.general.view_detail')</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>

                    @endif

                    @endforeach

                </div>
            </div>

        </div>


        <div class="mdk-drawer js-mdk-drawer " id="library-drawer" data-align="end">
            <div class="mdk-drawer__content top-navbar">
                <div class="sidebar sidebar-light sidebar-right py-16pt" data-perfect-scrollbar data-perfect-scrollbar-wheel-propagation="true">

                    <div class="d-flex align-items-center mb-24pt  d-lg-none">
                        <form action="" class="search-form search-form--light mx-16pt pr-0 pl-16pt">
                            <input type="text" class="form-control pl-0" placeholder="Search">
                            <button class="btn" type="submit"><i class="material-icons">search</i></button>
                        </form>
                    </div>

                    <div class="sidebar-heading">@lang('labels.frontend.treeview.category')</div>
                    <ul class="sidebar-menu">
                        @foreach($parentCategories as $category)
                        <?php
                            $class = '';
                            if((isset($_GET['_t']) && $_GET['_k'] == $category->id) || (isset($_GET['_q']) && $_GET['_q'] == $category->name)) {
                                $class = 'active';
                            }

                            if(isset($_GET['_k']) && $class == '') {
                                $category_id = $_GET['_k'];
                                $sub_ids = $category->children->pluck('id')->toArray();
                                if(in_array((int)$category_id, $sub_ids)) {
                                    $class = 'active';
                                }
                                if($class == '') {
                                    $subs = $category->children;
                                    foreach($subs as $sub) {
                                        $sub1_ids = $sub->children->pluck('id')->toArray();
                                        if(in_array((int)$category_id, $sub1_ids)) {
                                            $class = 'active';
                                        }
                                        if($class != '') {
                                            break;
                                        }
                                    }
                                }
                            }
                        ?>
                        <li class="sidebar-menu-item {{ $class }}">
                            <a class="sidebar-menu-button js-sidebar-collapse" 
                                data-toggle="collapse" href="#child_{{ $category->id }}">
                                @if(!empty($category->thumb))
                                <div class="avatar avatar-xs mr-3">
                                    <img src="{{ asset('/storage/uploads/' . $category->thumb) }}" alt="" class="avatar-img rounded" style="vertical-align: baseline;">
                                </div>
                                @else
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                @endif
                                <span class="sidebar-menu-text" data-url="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}">
                                    {{ $category->name }}
                                </span>
                                @if($category->children->count() > 0)
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                @endif
                            </a>

                            <ul class="sidebar-submenu collapse sm-indent" id="child_{{ $category->id }}" style="">
                                @foreach($category->children as $sub1)
                                <li class="sidebar-menu-item">
                                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#sub_child_{{ $sub1->id }}">
                                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                        <span class="sidebar-menu-text" data-url="/search/courses?_q={{ $sub1->name }}&_t=category&_k={{ $sub1->id }}">
                                            {{ $sub1->name }}
                                        </span>
                                        @if($sub1->children->count() > 0)
                                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        @endif
                                    </a>

                                    <ul class="sidebar-submenu collapse sm-last" id="sub_child_{{ $sub1->id }}" style="">
                                        @foreach($sub1->children as $sub2)
                                        <li class="sidebar-menu-item">
                                            <a class="sidebar-menu-button" href="/search/courses?_q={{ $sub2->name }}&_t=category&_k={{ $sub2->id }}">
                                                <span class="sidebar-menu-text">{{ $sub2->name }}</span>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script>
    $(document).ready(function(){

        // Make parent menu active
        var active_menus = $('li.sidebar-menu-item.active');
        $.each(active_menus, function(idx, item){
            $(this).closest('ul.sidebar-submenu').parent().addClass('active open');
        });

        $('span.sidebar-menu-text').on('click', function(e){
            e.preventDefault();
            if($(this).attr('data-url') != undefined) {
                window.location.href = $(this).attr('data-url');
                return false;
            } else {
                return true;
            }
        });

        $('a[name="add_favorite"]').on('click', function(e) {
            e.preventDefault();
            var route = $(this).attr('href');
            var btn_add_favorite = $(this);

            $.ajax({
                method: 'GET',
                url: route,
                success: function(res) {
                    if(res) {
                        btn_add_favorite.addClass('d-none');
                        btn_add_favorite.siblings('a[name="remove_favorite"]').removeClass('d-none');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $('a[name="remove_favorite"]').on('click', function(e) {
            e.preventDefault();
            var route = $(this).attr('href');
            var btn_remove_favorite = $(this);

            $.ajax({
                method: 'GET',
                url: route,
                success: function(res) {
                    if(res) {
                        btn_remove_favorite.addClass('d-none');
                        btn_remove_favorite.siblings('a[name="add_favorite"]').removeClass('d-none');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    });
</script>

@endpush

@endsection