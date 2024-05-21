@extends('layouts.app')

@section('content')

@push('after-styles')

<link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">

<style>
    [dir=ltr] .list-group-flush>.list-group-item {
        border-width: 0 0 2px;
    }
    [dir=ltr] .chip {
        margin-bottom: .5rem;
    }
    [dir=ltr] .chip+.chip {
        margin-right: .5rem;
        margin-left: 0;
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.frontend.search.find_instructors')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.frontend.search.find_instructors')
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">

            <div class="form-group pb-16pt" style="position: relative;">
                <div class="ui fluid category search instructor font-size-20pt">
                    <div class="ui icon input w-100">
                        <input class="prompt pb-16pt" type="text" placeholder="@lang('labels.frontend.home.search_teachers_placeholder')"
                        value="@if(isset($_GET['_q'])){{ $_GET['_q'] }}@endif">
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>

            @if(count($teachers) > 0)
            <div class="search-result wrapper">

                @foreach($teachers as $teacher)

                <div class="card card-body">
                    <div class="media">

                        <div class="media-left mr-12pt">
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="avatar avatar-xxl mr-3 border">
                                @if(!empty($teacher->avatar))
                                <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" alt="teacher" class="avatar-img">
                                @else
                                <img src="{{ asset('/images/no-avatar.jpg') }}" alt="teacher" class="avatar-img">
                                @endif
                            </a>
                        </div>

                        <div class="media-body media-middle">
                            <p class="card-title mb-8pt">{{ $teacher->name }} | {{ $teacher->headline }} </p>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="d-inline-flex align-items-center mb-8pt">
                                        <div class="rating mr-8pt">
                                            @include('layouts.parts.rating', ['rating' => $teacher->reviews->avg('rating')])
                                        </div>
                                        <small class="text-50">{{ $teacher->reviews->count() }}</small>
                                    </div>
                                    <p class="text-70 font-size-16pt">{{ str_limit($teacher->about, 150) }}
                                        <a href="{{ route('profile.show', $teacher->uuid) }}" style="color: #005ea6;">
                                            @lang('labels.frontend.general.read_more')
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-primary btn-block">
                                        @lang('labels.frontend.search.teacher.get_start')
                                    </a>
                                </div>
                            </div>
                            <div class="mr-3 text-70">
                                @if(!empty($teacher->qualifications))
                                <i class="material-icons mr-8pt">school</i>
                                <small class="text-100">
                                    @lang('labels.frontend.search.teacher.qualifications'):
                                </small>
                                @foreach(json_decode($teacher->qualifications) as $qualification)
                                <small>{{ $qualification }}</small>
                                @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                @endforeach

                @if($teachers->hasPages())
                <div class="card-footer p-8pt">
                    {{ $teachers->links('layouts.parts.page') }}
                </div>
                @endif

            </div>
            @else

            <div class="card card-body">
                <p class="card-title">
                    @lang('labels.frontend.search.teacher.no_results'):
                </p>
            </div>

            @endif
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@include('layouts.parts.search-script');

@endsection