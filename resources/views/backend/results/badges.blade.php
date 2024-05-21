@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
    [dir=ltr] .badge-border {
        border-color: #bbb!important;
    }
    .badge-d-table {
        border-collapse: separate;
        border-spacing: 15px;
    }
    @media (min-width: 768px){
        [dir=ltr] .avatar-xxl {
            font-size: 2.66667rem;
            width: 12rem;
            height: 12rem;
        }
    }
</style>
@endpush

<?php
    function get_badge($percent) {
        if($percent >= 70 && $percent < 80 ) {
            return [
                'title' => 'Bronze Badge',
                'image' => 'bronze-badge.png'
            ];
        }

        if($percent >= 80 && $percent < 90 ) {
            return [
                'title' => 'Silver Badge',
                'image' => 'silver-badge.png'
            ];
        }

        if($percent >= 90 && $percent <= 100 ) {
            return [
                'title' => 'Gold Badge',
                'image' => 'gold-badge.png'
            ];
        }

        return false;
    }
?>

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.results.badges.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.results.badges.title')
                        </li>

                    </ol>

                </div>
            </div>

        </div>
    </div>

    <div class="container page__container page-section">

        @foreach($badges as $item)
        
        <?php
            $badge = get_badge($item->percent);
        ?>
        @if($badge)
        <div class="media d-table badge-d-table card">
            <div class="media-left d-table-cell mr-24pt">
                <div class="avatar avatar-xxl border badge-border">
                    <img src="{{ asset('/images/' . $badge['image']) }}" alt="Badge" class="avatar-img rounded">
                </div>
            </div>
            <div class="media-body d-table-cell w-100 border badge-border p-3 align-top">
                <div class="wrap">
                    <p class="font-size-16pt mb-0">
                        <strong>Course: {{ $item->data->course->title }}</strong>
                    </p>
                    <p class="font-size-16pt">
                        <strong>Lesson: {{ $item->data->lesson->title }}</strong>
                    </p>
                    <p class="font-size-16pt">
                        <strong>{{ $badge['title'] }}</strong> awarded to {{ auth()->user()->name }} for excellent Performance
                    </p>
                    <p class="font-size-20pt mb-0">
                        @if($item->type == 'assignment')
                        <strong>Score: {{ (int)$item->data->result->mark }} / {{ $item->data->total_mark }}</strong>
                        @endif

                        @if($item->type == 'test')
                        <strong>Score: {{ (int)$item->data->result->mark }} / {{ $item->data->score }}</strong>
                        @endif

                        @if($item->type == 'quiz')
                        <strong>Score: {{ (int)$item->data->result->quiz_result }} / {{ $item->data->score }}</strong>
                        @endif
                    </p>
                    <small class="text-muted">
                        @if($item->type == 'quiz')
                            Test Date: {{ \Carbon\Carbon::parse($item->data->result->updated_at)->toFormattedDateString() }}
                        @else
                            Test Date: {{ \Carbon\Carbon::parse($item->data->result->submit_date)->toFormattedDateString() }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @endif

        @endforeach

        @if(count($badges) < 1)
        <div class="card card-body">
            <h4 class="mb-0">@lang('labels.backend.results.badges.no_results')</h4>
        </div>
        @endif

    </div>
</div>

@endsection