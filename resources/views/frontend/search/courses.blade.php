@extends('layouts.app')

@section('content')

@push('after-styles')

<link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">

<style>
    [dir=ltr] .list-group-flush>.list-group-item {
        border-width: 0 0 5px;
    }
    [dir=ltr] #right_side .sidebar-submenu .sidebar-menu-text {
        border-left: none;
        padding-left: .25rem;
    }
    [dir=ltr] #right_side .sidebar-submenu.sm-last {
        padding-left: 1.25rem;
    }
    [dir=ltr] #right_side .sidebar-submenu .sidebar-menu-text.active {
        color: #0085eb;
    }
    [dir=ltr] #right_side .open>.sidebar-menu-button .sidebar-menu-text {
        color: #0085eb;
    }
    [dir=ltr] .sidebar-right {
        padding-bottom: 240px !important;
    }

    [dir=ltr] .top-navbar {
        top: 74px;
    }

    @media (max-width: 768px) {
        [dir=ltr] .top-navbar {
            top: 0px;
        }
    }

    @media (max-width: 450px) {

    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    
    <div data-push data-responsive-width="1200px" class="mdk-drawer-layout js-mdk-drawer-layout">
        <div class="mdk-drawer-layout__content">
        
            <div class="pt-32pt">
                <div
                    class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
                    <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                        <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                            <h2 class="mb-0">@lang('labels.frontend.search.browse_course')</h2>

                            @if(auth()->check())
                            <ol class="breadcrumb p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                                <li class="breadcrumb-item active">
                                    @lang('labels.frontend.search.browse_course')
                                </li>
                            </ol>
                            @endif

                        </div>
                    </div>

                    <a href="#"
                        data-target="#library-drawer"
                        data-toggle="sidebar"
                        class="btn btn-sm btn-white ml-sm-16pt">
                        <i class="material-icons icon--left">tune</i> Filters
                    </a>
                </div>
            </div>

            <div class="page-section">
                <div class="container page__container" style="min-height: 50vh;">

                    <div class="form-group pb-16pt position-relative">
                        <!-- <div class="search-form input-group-lg">
                            <input type="text" class="form-control" placeholder="What do you want to learn today?" 
                            value="@if(isset($_GET['_q'])){{ $_GET['_q'] }}@endif" search-type="course">
                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                        </div> -->
                        <div class="ui fluid category search course font-size-20pt">
                            <div class="ui icon input w-100">
                                <input class="prompt pb-16pt" type="text" placeholder="@lang('labels.frontend.home.search_course_placeholder')"
                                value="@if(isset($_GET['_q'])){{ $_GET['_q'] }}@endif">
                                <i class="search icon"></i>
                            </div>
                            <div class="results"></div>
                        </div>
                    </div>

                    <div id="search_result">
                        @if(!is_mobile())
                            @include('layouts.parts.search-results', ['courses' => $courses])
                        @else
                            @include('layouts.parts.search-results-mobile', ['courses' => $courses])
                        @endif
                    </div>

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

                    <div class="sidebar-heading">Category</div>
                    <ul id="right_side" class="sidebar-menu">
                        @foreach($parentCategories as $category)
                        <?php
                            $class = '';
                            if((isset($_GET['_t']) && $_GET['_k'] == $category->id) || (isset($_GET['_q']) && $_GET['_q'] == $category->name)) {
                                $class = 'open';
                            }

                            if(isset($_GET['_k']) && $class == '') {
                                $category_id = $_GET['_k'];
                                $sub_ids = $category->children->pluck('id')->toArray();
                                if(in_array((int)$category_id, $sub_ids)) {
                                    $class = 'open';
                                }
                                if($class == '') {
                                    $subs = $category->children;
                                    foreach($subs as $sub) {
                                        $sub1_ids = $sub->children->pluck('id')->toArray();
                                        if(in_array((int)$category_id, $sub1_ids)) {
                                            $class = 'open';
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
                                data-toggle="collapse" href="#child_{{ $category->id }}"
                                data-url="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}"
                                @if($class == 'open') aria-expanded="true" @endif>

                                @if(!empty($category->thumb))
                                <div class="avatar avatar-xs mr-3">
                                    <img src="{{ asset('/storage/uploads/' . $category->thumb) }}" alt="" class="avatar-img rounded" style="vertical-align: baseline;">
                                </div>
                                @else
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                @endif
                                <span class="sidebar-menu-text">
                                    {{ $category->name }}
                                </span>
                                @if($category->children->count() > 0)
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                @endif
                            </a>

                            <ul class="sidebar-submenu collapse sm-indent" id="child_{{ $category->id }}" style="">
                                @foreach($category->children as $sub1)
                                <?php
                                    $sub_class = '';
                                    if(isset($_GET['_k']) && $sub_class == '') {
                                        $category_id = $_GET['_k'];
                                        $sub1_ids = $sub1->children->pluck('id')->toArray();
                                        if(in_array((int)$category_id, $sub1_ids)) {
                                            $sub_class = 'open';
                                        }

                                        if($category_id == $sub1->id) {
                                            $sub_class = 'open';
                                        }
                                    }
                                ?>
                                <li class="sidebar-menu-item {{ $sub_class }}">
                                    <a class="sidebar-menu-button js-sidebar-collapse"
                                        data-toggle="collapse" href="#sub_child_{{ $sub1->id }}"
                                        data-url="/search/courses?_q={{ $sub1->name }}&_t=category&_k={{ $sub1->id }}"
                                        @if($sub_class == 'open') aria-expanded="true" @endif>

                                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                        <span class="sidebar-menu-text">
                                            {{ $sub1->name }}
                                        </span>
                                        @if($sub1->children->count() > 0)
                                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                        @endif
                                    </a>

                                    <ul class="sidebar-submenu collapse sm-last" id="sub_child_{{ $sub1->id }}" style="">
                                        @foreach($sub1->children as $sub2)
                                        <li class="sidebar-menu-item">
                                            <a class="sidebar-menu-button"
                                                href="/search/courses?_q={{ $sub2->name }}&_t=category&_k={{ $sub2->id }}">
                                                <span class="sidebar-menu-text @if(isset($_GET['_k']) && $category_id == $sub2->id) active @endif">{{ $sub2->name }}</span>
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
@include('layouts.parts.search-script')

@push('after-scripts')

<script>

$(function(e) {

    $('#search_result').on('click', 'a[name="add_favorite"]', function(e) {
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

    $('#search_result').on('click', 'a[name="remove_favorite"]', function(e) {
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

    $('#right_side a.sidebar-menu-button').on('click', function(e){
        e.preventDefault();
        $('span.sidebar-menu-text').removeClass('active');
        var route = $(this).attr('data-url');

        if(route == undefined) {
            route = $(this).attr('href');
            $(this).find('.sidebar-menu-text').addClass('active');
        }

        var li = $(this).closest('.sidebar-menu-item');

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                if(res.success) {
                    $('#search_result').html(res.html);
                    if(li.siblings('.open').length > 0) {
                        li.siblings('.open').removeClass('open');
                    }
                }
            }
        });
    });
});

</script>

@endpush

@endsection