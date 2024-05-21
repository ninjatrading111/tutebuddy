@extends('layouts.app')

@section('content')


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.paths.learning_paths.title')</h2>
                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.paths.learning_paths.title')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.student.courses') }}" class="btn btn-outline-secondary">@lang('labels.backend.courses.my_courses')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.paths.my_paths')</div>
        </div>

        @if(count($bundles) > 0)

        <div class="row card-group-row mb-lg-8pt">

            @foreach($bundles as $bundle)

            <div class="col-sm-4 card-group-row__col">

                <div class="card overlay--show card-lg overlay--primary-dodger-blue stack stack--1 card-group-row__card">

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="flex">
                                <div class="d-flex align-items-center">
                                    <div class="rounded mr-12pt z-0 o-hidden">
                                        <div class="overlay">
                                            @if(!empty($bundle->bundle_image))
                                            <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                            @else
                                            <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                            @endif
                                            <span class="overlay__content overlay__content-transparent">
                                                <span class="overlay__action d-flex flex-column text-center lh-1">
                                                    <small class="h6 small text-white mb-0" style="font-weight: 500;">
                                                        {{ mb_substr($bundle->title, 0, 2) }}
                                                    </small>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class="card-title">{{ $bundle->title }}</div>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>{{ $bundle->courses->count() }} courses</small></p>
                                    </div>
                                </div>
                            </div>

                            <a href="" data-toggle="tooltip" data-title="Remove Favorite" data-placement="top" data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite</a>

                        </div>
                        @if($bundle->getRatingAttribute() > 0)
                        <div class="d-flex align-items-center mt-8pt">
                            <small class="text-black-50 mr-8pt">@lang('labels.backend.general.your_rating')</small>
                            <div class="rating mr-8pt">
                                @include('layouts.parts.rating', ['rating' => $bundle->getRatingAttribute()])
                            </div>
                            <small class="text-black-50">{{ $bundle->getRatingAttribute() }}/5</small>
                        </div>
                        @endif

                        <p class="mt-16pt text-black-70 flex">{{ $bundle->description }}</p>

                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $bundle->category->name }}</small></p>
                                </div>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('bundles.show', $bundle->slug) }}" class="btn btn-outline-secondary">
                                    @lang('labels.backend.paths.begin')
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mb-32pt">
            @if($bundles->hasPages())
            {{ $bundles->links('layouts.parts.page') }}
            @else
            <ul class="pagination justify-content-start pagination-xsm m-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true" class="material-icons">chevron_left</span>
                        <span>@lang('labels.backend.general.prev')</span>
                    </a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Page 1">
                        <span>1</span>
                    </a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="Next">
                        <span>@lang('labels.backend.general.next')</span>
                        <span aria-hidden="true" class="material-icons">chevron_right</span>
                    </a>
                </li>
            </ul>
            @endif
        </div>
        @else

        <div class="card card-body">
            <div class="card-title">@lang('labels.backend.paths.no_paths')</div>
        </div>

        @endif
    </div>
</div>

@endsection