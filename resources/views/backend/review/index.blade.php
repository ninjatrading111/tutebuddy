@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.reviews.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.reviews.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

    @if(count($reviews) > 0)

        @foreach($reviews as $review)
        <div class="card card-body">
            <div class="posts-card__content d-flex align-items-center flex-wrap">
                <div class="avatar avatar-lg mr-3">
                    @if(!empty($review->user->avatar))
                    <img src="{{ asset('storage/avatars/' . $review->user->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                    @else
                    <span class="avatar-title rounded-circle">{{ mb_substr($review->user->name, 0, 2) }}</span>
                    @endif
                </div>

                <div class="posts-card__title flex flex-column">
                    <label class="card-title font-size-20pt mb-2">{{ $review->user->name }}</label>
                    @if($review->published == 1)
                    <div class="d-flex flex-column">
                        <small class="text-primary text-black-70 font-size-16pt pr-12pt mr-12pt border-right-2 font-weight-bold">Review: Published</small>
                        <span class="indicator-line rounded bg-primary"></span>
                    </div>
                    @else
                    <div class="d-flex flex-column">
                        <small class="text-accent text-black-70 font-size-16pt pr-12pt mr-12pt border-right-2 font-weight-bold">Review: Unpublished</small>
                        <span class="indicator-line rounded bg-warning"></span>
                    </div>
                    @endif
                </div>
                <div class="align-items-center flex flex-column">
                    <div class="rating rating-24 float-right">
                        @include('layouts.parts.rating', ['rating' => $review->rating])
                    </div>
                </div>
            </div>

            <div class="p-12pt">
                <p class="text-70 font-size-16pt">{{ str_limit($review->content, 200) }}
                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="ml-16pt font-weight-bold font-italic" style="color: #005ea6;">
                    @lang('labels.backend.general.read_more')</a>
                </p>
                <div class="d-flex">

                    <div class="flex flex-column flex-0">
                        <span class="font-size-16pt text-black-70 pr-12pt mr-12pt border-right-2 font-weight-bold">{{ $review->course->title }}</span>
                    </div>

                    <div class="flex flex-column flex-0">
                        @if($review->course->progress() > 99)
                        <i class="font-size-16pt text-black-70 pr-12pt mr-12pt border-right-2 font-weight-bold">@lang('labels.backend.general.completed')</i>
                        @else
                        <i class="font-size-16pt text-black-70 pr-12pt mr-12pt border-right-2 font-weight-bold">@lang('labels.backend.general.in_progressing')</i>
                        @endif
                    </div>

                    <div class="flex flex-column flex-0">
                        <i class="text-muted text-black-70 font-size-16pt pr-12pt mr-12pt border-right-2 font-weight-bold">
                            {{ \Carbon\Carbon::parse($review->updated_at)->toFormattedDateString() }}
                        </i>
                    </div>
                    
                    <div class="flex flex-column text-right">
                        <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-primary">@lang('labels.backend.general.detail')</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        <div class="p-8pt">
            @if($reviews->hasPages())
            {{ $reviews->links('layouts.parts.page') }}
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
            <span class="card-title">@lang('labels.backend.reviews.no_result')</span>
        </div>

    @endif
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    var table;

    $(document).ready(function() {

        table = $('#tbl_reviews').DataTable({
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: {
                url: '{{ route("admin.getReviewsByAjax") }}',
                complete: function(res) {
                    $.each(res.responseJSON.count, function(key, count){
                        $('#tbl_selector').find('span.count-' + key).text(count);
                    });
                }
            },
            columns: [
                { data: 'index'},
                { data: 'no'},
                { data: 'name' },
                { data: 'course'},
                { data: 'rate'},
                { data: 'content'},
                { data: 'time' },
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "@lang('labels.backend.reviews.table.no_result')"
            }
        });
    });

    $('#tbl_reviews').on('click', 'a[data-action="publish"]', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", 'Published successfully', "success");
                    } else {
                        swal("Success!", 'Unpublished successfully', "success");
                    }
                    
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                }
            }
        });
    });

    $(document).on('submit', 'form[name="delete_item"]', function(e) {

        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                } else {
                    swal("Warning!", res.message, "warning");
                }
            }
        });
    });
</script>

@endpush

@endsection
