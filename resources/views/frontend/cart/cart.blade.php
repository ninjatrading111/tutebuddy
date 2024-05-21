@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content">

    <div class="page-section bg-primary mb-32pt">
        <div class="container page__container">
            <h2 class="text-center text-white"><span>@lang('labels.frontend.cart.cart')</span></h2>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="mb-lg-32pt">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.cart.cart_items'):</div>
            </div>

            <div class="card table-responsive" data-toggle="lists"
                data-lists-values='["js-lists-values-name", "js-lists-values-email"]'>
                <table class="table mb-0 thead-border-top-0 table-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">@lang('labels.backend.table.no')</th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">
                                @lang('labels.backend.table.course_name')</a></th>
                            <th>@lang('labels.backend.table.price') ({{ getCurrency(config('app.currency'))['symbol'] }})</th>
                            <th>@lang('labels.backend.table.course_type')</th>
                            <th>Purchase by</th>
                            <th>@lang('labels.backend.table.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="items">
                        @if(Cart::session(auth()->user()->id)->getContent()->count() > 0)
                            @foreach(Cart::session(auth()->user()->id)->getContent() as $cart)
                            <?php

                                if ($cart->attributes->product_type == 'course') {
                                    $item = App\Models\Course::find($cart->id);
                                }

                                if ($cart->attributes->product_type == 'bundle') {
                                    $item = App\Models\Bundle::find($cart->id);
                                }
                            ?>
                            <tr>
                                <td class="pr-0"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-lg mr-16pt">
                                            @if($cart->attributes->image)
                                            <img src="{{ asset('/storage/uploads/' . $cart->attributes->image) }}" alt="Avatar"
                                                class="avatar-img rounded">
                                            @else
                                            <span
                                                class="avatar-title rounded bg-primary text-white">{{ mb_substr($item->title, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <p class="card-title mb-1">
                                                    <strong class="">{{ $item->title }}</strong>
                                                </p>
                                                @if($cart->attributes->type == 'bundle')
                                                <small class="text-50">@lang('labels.frontend.cart.created_by')
                                                    {{ $item->user->name }}</small>
                                                @else
                                                <small class="text-50">@lang('labels.frontend.cart.created_by')
                                                    {{ $item->teachers[0]->name }}</small>
                                                @endif
                                                @if($item->reviews->count() > 0)
                                                <div class="rating">
                                                    @include('layouts.parts.rating', ['rating' =>
                                                    $item->reviews->avg('rating')])
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="mb-0">{{ getCurrency(config('app.currency'))['symbol'] . ' ' . number_format($cart->price, 2) }}</h5>
                                    @if($cart->attributes->gst_status == 1)
                                    <small class="">
                                    {{ getCurrency(config('app.currency'))['symbol'] . ' ' . number_format($cart->price * 0.18, 2) }} (GST)
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-accent p-2"> {{ $cart->attributes->product_type }} </span>
                                    <span class="badge badge-pill badge-primary p-2"> {{ $cart->attributes->price_type }} </span>
                                </td>
                                <td>
                                    <?php
                                        $user = auth()->user();
                                        if($cart->attributes->child_id != '') {
                                            $user = Auth::user()->find($cart->attributes->child_id);
                                        }
                                    ?>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded-circle">{{ mb_substr($user->name, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $user->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">@lang('labels.frontend.cart.student')</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a class="text-danger" href="{{route('cart.remove', ['course'=>$item])}}">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="card-title text-center">@lang('labels.frontend.cart.empty')</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

        @if(Cart::session(auth()->user()->id)->getContent()->count() > 0)
        <div class="form-group text-right">
            <a href="{{ route('cart.checkout') }}" class="btn btn-primary">@lang('labels.frontend.cart.process_checkout')</a>
        </div>
        @else
        <div class="form-group text-center">
            <a href="{{ route('courses.search') }}" class="btn btn-primary">@lang('labels.frontend.search.browse_courses')</a>
            <a href="{{ route('teachers.search') }}" class="btn btn-accent">@lang('labels.frontend.search.browse_teachers')</a>
        </div>
        @endif

    </div>

</div>

@endsection