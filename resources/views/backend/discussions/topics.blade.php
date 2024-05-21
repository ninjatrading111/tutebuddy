@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="container page__container">
        <div class="page-section">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.discussions.topics.title')</div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center" style="white-space: nowrap;">
                        <div class="col-lg-auto">
                            <form class="search-form  d-lg-inline-flex mb-8pt mb-lg-0" action="{{ route('admin.discussions.topics') }}">
                                <input type="text" class="form-control w-lg-auto" placeholder="Search discussions" name="_q">
                                <button class="btn" type="submit" role="button"><i
                                        class="material-icons">search</i></button>
                            </form>
                        </div>
                        <div class="col-lg d-flex flex-wrap align-items-center">
                            <div class="ml-lg-auto dropdown">
                                <a href="#" class="btn btn-link dropdown-toggle text-70" data-toggle="dropdown">
                                    @lang('labels.backend.discussions.topics.title')
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('admin.discussions.topics', ['_t' => 'all']) }}" class="dropdown-item active">
                                        @lang('labels.backend.discussions.topics.title')
                                    </a>
                                    <a href="{{ route('admin.discussions.topics', ['_t' => 'my']) }}" class="dropdown-item">
                                        @lang('labels.backend.discussions.topics.my_topics')
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown mr-8pt">
                                <a href="javascript:void(0)" class="btn btn-link dropdown-toggle text-70" data-toggle="dropdown">
                                    @lang('labels.backend.discussions.topics.newest')
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('admin.discussions.topics', ['_t' => 'newst']) }}" class="dropdown-item active">
                                    @lang('labels.backend.discussions.topics.newest')
                                    </a>
                                    <a href="{{ route('admin.discussions.topics', ['_t' => 'unanswered']) }}" class="dropdown-item">
                                    @lang('labels.backend.discussions.topics.unread')
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('admin.discussions.create') }}" class="btn btn-accent">
                                @lang('labels.backend.discussions.topics.new')
                            </a>
                        </div>
                    </div>
                </div>

                <div class="list-group list-group-flush">

                @if(count($discussions) > 0)

                    @foreach($discussions as $discussion)

                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-3 mb-8pt mb-md-0">
                                <div class="media align-items-center">
                                    <div class="media-left mr-12pt">
                                        <a href="{{ route('admin.discussions.show', $discussion->id) }}" class="avatar avatar-sm">
                                            @if(!empty($discussion->user->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $discussion->user->avatar) }}" alt="{{ $discussion->user->avatar }}"
                                            class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ mb_substr($discussion->user->name, 0, 2) }}</span>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column media-body media-middle">
                                        <a href="" class="card-title">{{ $discussion->user->name }}</a>
                                        <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->updated_at))->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <a href="{{ route('admin.discussions.show', $discussion->id) }}">
                                            <span class="avatar-title rounded bg-primary text-white">{{ mb_substr($discussion->title, 0, 2) }}</span>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('admin.discussions.show', $discussion->id) }}">
                                            <small class="js-lists-values-project">
                                                <strong>{{ $discussion->title }}</strong></small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0 pt-1">
                                <?php $topics = json_decode($discussion->topics); ?>
                                @foreach($topics as $topic)
                                <a href="{{ route('admin.discussions.show', $discussion->id) }}" class="chip chip-outline-secondary">
                                    {{ $discussion->topic($topic) }}
                                </a>
                                @endforeach
                            </div>
                            <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                                <h5 class="m-0">{{ $discussion->results->count() }}</h5>
                                <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
                            </div>
                        </div>
                    </div>

                    @endforeach

                @else
                    <div class="list-group-item p-3">
                        <span class="card-title">@lang('labels.backend.discussions.topics.no_result')</span>
                    </div>
                @endif

                </div>

                <div class="card-footer">
                    @if($discussions->hasPages())
                    {{ $discussions->links('layouts.parts.page') }}
                    @else
                    <ul class="pagination justify-content-start pagination-xsm m-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true" class="material-icons">chevron_left</span>
                                <span>Prev</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Page 1">
                                <span>1</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Next">
                                <span>Next</span>
                                <span aria-hidden="true" class="material-icons">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection