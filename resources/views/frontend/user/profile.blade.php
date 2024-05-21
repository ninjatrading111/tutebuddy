@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
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

    <div class="page-section bg-primary">
        <div class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">
            @if(!empty($teacher->avatar))
            <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @else
            <img src="{{ asset('/images/no-avatar.jpg') }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @endif
            <div class="flex mb-32pt mb-md-0">
                <h2 class="text-white mb-0">{{ $teacher->name }}</h2>
                <p class="lead text-white-50 d-flex align-items-center">
                    {{ $teacher->headline }}
                    <div class="d-inline-flex align-items-center mb-8pt">
                        <div class="rating mr-8pt">
                            @include('layouts.parts.rating', ['rating' => $teacher->reviews->avg('rating')])
                        </div>
                        <small class="text-white" style="padding-top: 2px;">{{ $teacher->reviews->count() }}</small>
                    </div>
                </p>
            </div>
            <!-- <a href="" class="btn btn-outline-white">Follow</a> -->
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>@lang('labels.frontend.profile.teacher_profile')</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>@lang('labels.frontend.profile.teacher_id')</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Courses offered by {{ $teacher->name }}</label>
                                <div class="mt-8pt">
                                    @foreach($teacher->courses as $course)
                                    <span class="btn btn-light btn-sm p-2 mb-8pt mr-8pt">{{ $course->title }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.frontend.profile.professions')</label>
                                @if(!empty($teacher->profession))
                                <div class="mt-8pt">
                                    @php $pros = json_decode($teacher->profession); @endphp
                            
                                    @foreach($pros as $pro)
                                    <?php
                                        $category = App\Models\Category::find($pro);
                                        $name = !empty($category) ? $category->name : $pro;
                                    ?>
                                    <span class="btn btn-light btn-sm p-2 mb-8pt mr-8pt">{{ $name }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-5">
                            <h4>{{ $teacher->headline }}</h4>
                            <p class="font-size-16pt">{{ $teacher->about }}</p>
                        </div>
                    </div>

                    @if(!empty($teacher->qualifications))

                    <div class="card">
                        <div class="card-body p-5">
                            <h4>Professional Qualifications and Certifications</h4>
                            @foreach(json_decode($teacher->qualifications) as $qualification)
                            <p class="font-size-16pt mb-1"><strong>{{ $loop->iteration }}. </strong> {{ $qualification }}</p>
                            @endforeach
                        </div>
                    </div>

                    @endif

                    @if(!empty($teacher->achievements))

                    <div class="card">
                        <div class="card-body p-5">
                            <h4>@lang('labels.frontend.profile.achievements')</h4>
                            @foreach(json_decode($teacher->achievements) as $achievement)
                            <p class="font-size-16pt mb-1"><strong>{{ $loop->iteration }}. </strong> {{ $achievement }}</p>
                            @endforeach
                        </div>
                    </div>

                    @endif

                    @if(!empty($teacher->experience))

                    <div class="card">
                        <div class="card-body p-5">
                            <h4>@lang('labels.frontend.profile.experience')</h4>
                            <p class="font-size-16pt mb-1">{{ $teacher->experience }}</p>
                        </div>
                    </div>

                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">Courses by {{ $teacher->name }}</div>
            </div>

            <div class="row card-group-row mb-8pt">

                @foreach($teacher->courses as $course)

                <div class="col-sm-6 card-group-row__col">
                    <div class="card card-sm card-group-row__card">
                        <div class="card-body d-flex align-items-center">
                            <a href="{{ route('courses.show', $course->slug) }}" class="avatar avatar-4by3 overlay overlay--primary mr-12pt">
                                @if(!empty($course->course_image))
                                <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" alt="{{ $course->title }}" class="avatar-img rounded">
                                @else
                                <img src="{{ asset('/images/no-image.jpg') }}" alt="{{ $course->title }}" class="avatar-img rounded">
                                @endif
                            </a>
                            <div class="flex">
                                <a class="card-title mb-4pt" href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                <div class="d-flex align-items-center">
                                    <div class="rating mr-8pt">
                                        <?php
                                            $course_rating = 0;
                                            if ($course->reviews->count() > 0) {
                                                $course_rating = $course->reviews->avg('rating');
                                            }
                                        ?>
                                        @include('layouts.parts.rating', ['rating' => $course_rating])

                                    </div>
                                    <small class="text-muted">{{ $course_rating }}/5</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.profile.teacher.similar_teacher')</div>
            </div>

            <div class="row card-group-row">

                @foreach($similar_teachers as $teacher)

                <div class="col-md-6 col-xl-3 card-group-row__col">
                    <div class="card card-group-row__card">
                        <div class="card-header d-flex align-items-center">
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="card-title flex mr-12pt">{{ $teacher->name }}</a>
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-light btn-sm" data-toggle="tooltip" data-title="follow" data-placement="bottom">Follow</a>
                        </div>
                        <div class="card-body flex text-center d-flex flex-column align-items-center justify-content-center">
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="avatar avatar-xxl overlay js-overlay overlay--primary rounded-circle p-relative o-hidden mb-16pt">
                                @if(!empty($teacher->avatar))
                                <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" alt="teacher" class="avatar-img">
                                @else
                                <img src="{{ asset('/images/no-avatar.jpg') }}" alt="teacher" class="avatar-img">
                                @endif
                                {{-- <span class="overlay__content"><i class="overlay__action material-icons icon-40pt">face</i></span> --}}
                            </a>
                            <div class="flex">
                                <div class="d-inline-flex align-items-center mb-8pt">
                                    <div class="rating mr-8pt">
                                    @if($teacher->reviews->count() > 0)
                                        @include('layouts.parts.rating', ['rating' => $teacher->reviews->avg('rating')])
                                    @endif
                                    </div>
                                    @if($teacher->reviews->count() > 0)
                                    <small class="text-50">{{ number_format($teacher->reviews->avg('rating'), 2) }}/5</small>
                                    @endif
                                </div>
                                <p class="h5">{{ $teacher->headline }}</p>
                            </div>
                        </div>
                        @if(!empty($teacher->profession))
                        <div class="card-body flex-0">
                            <div class="d-flex align-items-center" style="display: block !important;">
                            @php $pros = json_decode($teacher->profession); @endphp
                            
                                @foreach($pros as $pro)
                                <?php
                                    $category = App\Models\Category::find($pro);
                                    $name = !empty($category) ? $category->name : $pro;
                                ?>
                                <a href="javascript:void()" class="chip chip-outline-secondary">{{ $name }}</a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>

</div>

@endsection