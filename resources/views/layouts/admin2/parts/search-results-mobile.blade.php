@if(count($courses) > 0)

<div class="row card-group-row">
    @foreach($courses as $course)
    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 card-group-row__col">
        <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
            data-toggle="popover"
            data-trigger="click">

            <a href="{{ route('courses.show', $course->slug) }}"
                class="card-img-top js-image"
                data-position=""
                data-height="140">
                @if(!empty($course->course_image))
                    <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" alt="{{ $course->title }}">
                @else
                    <img src="{{ asset('/assets/img/no-image.jpg') }}" alt="{{ $course->title }}">
                @endif
            </a>

            <div class="card-body flex">
                <div class="d-flex">
                    <div class="flex">
                        <a class="card-title"
                            href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                        <small class="text-50 font-weight-bold mb-4pt">{{ $course->teachers->first()->name }}</small>
                    </div>
                </div>

                <div class="d-flex py-2">
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
                </div>

                <div class="pb-2">
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

                <div class="d-flex pb-2">
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

                @if(auth()->user() && !auth()->user()->hasRole('Child'))
                <div class="d-flex">
                    @if(!$course->isEnrolled())
                    <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary btn-md">Enroll</a>
                    @else
                    <a href="javascript:void(0)" class="btn btn-success btn-md">Enrolled</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@else
<div class="card card-body">
    <p class="card-title">No result</p>
</div>
@endif