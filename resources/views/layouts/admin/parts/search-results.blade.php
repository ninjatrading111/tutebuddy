@if(count($courses) > 0)

<div class="card">

    <div class="list-group list-group-flush">

        @foreach($courses as $course)

        <div class="list-group-item p-3">
            <div class="align-items-start">
                <div class="media">
                    <div class="media-left mr-12pt">
                        <a href="{{ route('courses.show', $course->slug) }}" class="avatar avatar-xxl mr-3">
                            @if(!empty($course->course_image))
                            <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" 
                                alt="{{ $course->title }}" class="avatar-img rounded" >
                            @else
                            <img src="{{ asset('/assets/img/no-image.jpg') }}" 
                                alt="{{ $course->title }}" class="avatar-img rounded" >
                            @endif
                        </a>
                        <div class="d-flex p-1" style="white-space: nowrap;">
                            <div class="rating mr-4pt">
                                @if($course->reviews->count() > 0)
                                @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                @else
                                    <small class="text-50">No rating</small>
                                @endif
                            </div>
                            @if($course->reviews->count() > 0)
                            <small class="text-50">{{ number_format($course->reviews->avg('rating'), 2) }}/5</small>
                            @endif
                        </div>
                    </div>
                    <div class="media-body media-middle">
                        <div class="d-flex">
                            <div class="flex">
                                <a href="{{ route('courses.show', $course->slug) }}" class="card-title">{{ $course->title }}</a>
                            </div>
                            @if(!empty($course->group_price))
                            <span class="card-title text-accent mr-16pt">
                                {{ getCurrency(config('app.currency'))['symbol'] . $course->group_price }} <small class="text-50">(Group)</small>
                            </span>
                            @endif

                            @if(!empty($course->private_price))
                            <span class="card-title text-primary mr-16pt">
                                {{ getCurrency(config('app.currency'))['symbol'] . $course->private_price }} <small class="text-50">(Private)</small>
                            </span>
                            @endif
                            
                            @if(auth()->check())
                            <a href="{{ route('admin.course.removeFavorite', $course->id) }}" name="remove_favorite" 
                                data-toggle="tooltip" data-title="Remove Favorite" data-placement="top" data-boundary="window" 
                                class="ml-4pt material-icons text-20 card-course__icon-favorite font-color-red @if(!$course->favorited()) d-none @endif"
                                data-original-title="" title="">favorite</a>
                            <a href="{{ route('admin.course.addFavorite', $course->id) }}" name="add_favorite" 
                                data-toggle="tooltip" data-title="Add Favorite" data-placement="top" data-boundary="window" 
                                class="ml-4pt material-icons text-20 card-course__icon-favorite @if($course->favorited()) d-none @endif"
                                data-original-title="" title="">favorite_border</a>
                            @endif
                        </div>
                        <div class="d-flex">
                            <span class="text-70 text-muted mr-8pt"><strong>Session Time: {{ $course->duration() }},</strong></span>
                            <span class="text-70 text-muted mr-8pt"><strong>Sessions: {{ $course->lessons->count() }},</strong></span>
                            <span class="text-70 text-muted mr-8pt"><strong>Category: 
                                @if(!empty($course->category))
                                {{ $course->category->name }},
                                @else
                                No Category
                                @endif
                                </strong>
                            </span>
                            <span class="text-70 text-muted mr-8pt"><strong>Level: {{ $course->level->name }}</strong></span>
                        </div>
                        <div class="page-separator mb-0">
                            <div class="page-separator__text bg-transparent">&nbsp;</div>
                        </div>
                        <div class="">
                            <p class="text-muted">{{ $course->short_description }}</p>
                        </div>
                        <div class="d-flex align-items-end">
                            <a href="" class="flex">
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        @if(!empty($course->teachers[0]->avatar))
                                        <img src="{{ asset('/storage/avatars/' . $course->teachers[0]->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ mb_substr($course->teachers[0]->name, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-lead">{{ $course->teachers[0]->name }}</strong></p>
                                                <small class="js-lists-values-email text-50">
                                                    @if (strlen($course->teachers[0]->headline) > 50)
                                                        {{ mb_substr($course->teachers[0]->headline, 0, 50) }} ...
                                                    @else 
                                                        {{ $course->teachers[0]->headline }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            @if(auth()->user() && !auth()->user()->hasRole('Child'))
                                @if(!$course->isEnrolled())
                                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary btn-md">
                                    @lang('labels.frontend.general.view_detail')
                                </a>
                                @else
                                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary btn-md mr-2">
                                    @lang('labels.frontend.general.view_detail')
                                </a>
                                <a href="javascript:void(0)" class="btn btn-success btn-md">Enrolled</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endforeach

    </div>

    @if($courses->hasPages())
    <div class="card-footer p-8pt">
        {{ $courses->links('layouts.parts.page') }}
    </div>
    @endif
</div>

@else
<div class="card card-body">
    <p class="card-title">No result</p>
</div>
@endif