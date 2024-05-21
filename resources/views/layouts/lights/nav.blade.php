<!-- Header -->
@if (\Request::route()->getName() == 'homepage')
    <div id="header" class="mdk-header mdk-header--bg-dark bg-dark js-mdk-header mb-0"
        data-effects="parallax-background waterfall" data-fixed data-condenses>
    @else
        <div id="header" class="mdk-header js-mdk-header mb-0" data-fixed data-effects="">
@endif

@if (\Request::route()->getName() == 'homepage')
    <div class="mdk-header__bg">
        <div class="mdk-header__bg-front"
            style="background-image: url({{ asset('assets/img/hero-background-elearning.jpg') }});">
        </div>
    </div>
    <div class="mdk-header__content justify-content-center">
    @else
        <div class="mdk-header__content">
@endif

<?php
$nav_class = \Request::route()->getName() == 'homepage' ? 'navbar-dark navbar-dark-dodger-blue bg-transparent will-fade-background' : 'navbar-light navbar-light-dodger-blue navbar-shadow';
?>

<div class="navbar navbar-expand {{ $nav_class }}" id="default-navbar" data-primary>

    @if (auth()->check())
        <!-- Navbar toggler -->
        <button class="navbar-toggler w-auto mr-16pt d-block rounded-0" type="button" data-toggle="sidebar">
            <span class="material-icons">short_text</span>
        </button>
    @endif

    <!-- Navbar Brand -->
    <a href="{{ config('app.url') }}" class="navbar-brand mr-16pt">
        <!-- <img class="navbar-brand-icon" src="assets/images/logo/white-100@2x.png" width="30" alt="Luma"> -->

        <?php
        $nav_logo = asset('assets/img/logo/tutebuddy-logo-full.png');
        if (\Request::route()->getName() == 'homepage' && !empty(config('nav_logo_dark'))) {
            $nav_logo = asset('storage/logos/' . config('nav_logo_dark'));
        }
        
        if (\Request::route()->getName() != 'homepage' && !empty(config('nav_logo'))) {
            $nav_logo = asset('storage/logos/' . config('nav_logo'));
        }
        ?>

        <span class="avatar avatar-sm navbar-brand-icon mr-0 mr-lg-8pt">
            <img src="{{ $nav_logo }}" alt="logo" class="img-fluid" />
        </span>
    </a>

    @if (!auth()->check())
        <ul class="nav navbar-nav ml-auto mr-0 desktop-only">
            <!-- <li class="nav-item">
                    <a href="{{ route('register') }}?r=t" class="btn btn-outline-nav" >@lang('navs.register.teacher')</a>
                </li> -->
            <li class="nav-item">
                <a href="{{ route('register') }}" class="btn btn-outline-nav">@lang('navs.register.user')</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('login') }}" class="btn btn-outline-nav">@lang('navs.login')</a>
            </li>
            {{-- <li class="nav-item">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-outline-nav">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li> --}}
            {{-- @if (count($locales) > 1)
                <li class="nav-item">
                    <!-- Multi Language Option -->
                    <div class="nav-item dropdown dropdown-notifications dropdown-xs-down-full">
                        <button class="nav-link btn-flush dropdown-toggle" type="button" data-toggle="dropdown" data-caret="false">
                            <i class="material-icons icon-24pt">language</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" style="width: 200px; min-width:200px;">
                        @foreach ($locales as $lang)
                            @if ($lang != app()->getLocale())
                            <a href="{{ '/lang/'.$lang }}" class="list-group-item list-group-item-action">
                                <span class="d-flex">
                                    <span class="avatar avatar-xs mr-2">
                                        <img src="{{ asset('images/icon-' . $lang . '.png') }}" alt="people" class="avatar-img rounded-circle">
                                    </span>
                                    <span class="flex d-flex flex-column">
                                        <span class="text-black-70" style="line-height: 26px;"> @lang('menus.language-picker.langs.'.$lang)</span>
                                    </span>
                                </span>
                            </a>
                            @else
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action" style="background-color: #efefef;">
                                <span class="d-flex">
                                    <span class="avatar avatar-xs mr-2">
                                        <img src="{{ asset('images/icon-' . $lang . '.png') }}" alt="people" class="avatar-img rounded-circle">
                                    </span>
                                    <span class="flex d-flex flex-column">
                                        <span class="text-black-70" style="line-height: 26px;"> @lang('menus.language-picker.langs.'.$lang)</span>
                                    </span>
                                </span>
                            </a>
                            @endif
                        @endforeach
                        </div>
                    </div>
                </li>
                @endif --}}
        </ul>

        <div class="nav-item dropdown ml-auto mr-0 mobile-only">
            <a href="#" class="btn btn-outline-nav dropdown-toggle" data-toggle="dropdown"
                data-caret="false">Account
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- <a class="dropdown-item" href="{{ route('register') }}?r=t">@lang('navs.register.teacher')</a> -->
                <a class="dropdown-item" href="{{ route('register') }}">@lang('navs.register.user')</a>
                <a class="dropdown-item" href="{{ route('login') }}">@lang('navs.login')</a>
            </div>
        </div>
    @else
        <div class="flex"></div>

        <div class="nav navbar-nav flex-nowrap d-flex mr-16pt">

            <!-- Dashboard link -->
            {{-- <div class="nav-item" data-toggle="tooltip" data-title="Dashboard">
                <a href="# " class="nav-link btn-flush " type="button">
                    <i class="material-icons">dashboard</i>
                </a>
            </div> --}}
            <div class="nav-item dropdown" data-toggle="tooltip" data-title="Setting Mode">
                <a href="#" class="nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown"
                    data-caret="false">
                    {{-- <i class="material-icons">Settings</i> --}}
                    <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                    {{-- <span class="ml-auto sidebar-menu-toggle-icon"></span> --}}

                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="/layouts/light">Light</a>
                    <a class="dropdown-item" href="/layouts/dark">Dark</a>
                </div>
            </div>
            <!-- Notifications dropdown -->
            <div class="nav-item dropdown dropdown-notifications dropdown-xs-down-full" data-toggle="tooltip"
                data-title="Message">
                <button class="nav-link btn-flush dropdown-toggle" type="button" data-toggle="dropdown"
                    data-caret="false">
                    <i class="material-icons icon-24pt">mail_outline</i>
                    {{-- @if (count(auth()->user()->notify_message()) > 0) --}}
                    <span class="badge badge-notifications badge-accent"></span>
                    {{-- @endif --}}
                </button>
                {{-- @if (count(auth()->user()->notify_message()) > 0)
                    <div class="dropdown-menu dropdown-menu-right">
                        <div data-perfect-scrollbar class="position-relative">
                            <div class="dropdown-header"><strong>@lang('navs.sidebar.messages')</strong></div>
                            <div class="list-group list-group-flush mb-0">

                                @foreach (auth()->user()->notify_message() as $notify)
                                    @php
                                        $partner_user = Auth::user()
                                            ->where('id', $notify['partner_id'])
                                            ->first();
                                    @endphp
                                    <a href="{{ route('admin.messages.show', [$notify['msg']->thread_id, $partner_user->id]) }}"
                                        class="list-group-item list-group-item-action unread">
                                        <span class="d-flex align-items-center mb-1">
                                            <small
                                                class="text-black-50">{{ \Carbon\Carbon::parse($notify['msg']->created_at)->format('h:i A | M d Y') }}</small>
                                            <span class="ml-auto unread-indicator bg-accent"></span>
                                        </span>
                                        <span class="d-flex">
                                            <span class="avatar avatar-xs mr-2">
                                                @if (!empty($partner_user->avatar))
                                                    <img src="{{ asset('/storage/avatars/' . $partner_user->avatar) }}"
                                                        alt="" class="avatar-img rounded-circle">
                                                @else
                                                    <span
                                                        class="avatar-title rounded-circle">{{ mb_substr($partner_user->avatar, 0, 2) }}</span>
                                                @endif
                                            </span>
                                            <span class="flex d-flex flex-column">
                                                <strong class="text-black-100">{{ $partner_user->name }}</strong>
                                                <span class="text-black-70">{{ $notify['msg']->body }}</span>
                                            </span>
                                        </span>
                                    </a>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif --}}
            </div>
            <!-- // END Notifications dropdown -->

            {{-- @if (auth()->user()->hasRole('Student'))
                <!-- Mini card -->
                <div class="nav-item ml-16pt nav-cart" data-toggle="tooltip" data-title="My Cart">
                    <a href="{{ route('cart.index') }}" class="nav-link btn-flush" type="button">
                        <i class="material-icons">add_shopping_cart</i>
                        @if (auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                            <span class="badge badge-notifications badge-accent">
                                {{ Cart::session(auth()->user()->id)->getTotalQuantity() }}
                            </span>
                        @endif
                    </a>
                </div>
            @endif --}}

            ?

            <!-- Account Menu -->
            <div class="nav-item dropdown" data-toggle="tooltip" data-title="My Account">
                <a href="#" class="nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown"
                    data-caret="false">

                    <span class="avatar avatar-sm mr-8pt2">
                        <img src="{{ asset('/images/no-avatar.jpg') }}" alt="people"
                            class="avatar-img rounded-circle">
                    </span>

                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-item">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">face</span>
                        <span class="sidebar-menu-text">{{ auth()->user()->firstname }}</span> ({{ auth()->user()->lastname }})
                    </div>
                    {{-- <a class="dropdown-item" href="/profile"></a> --}}
                    <a class="dropdown-item" href="/profile">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                        <span class="sidebar-menu-text">profile</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">logout</span>
                        <span class="sidebar-menu-text">logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@if (\Request::route()->getName() == 'homepage')
    <div class="hero container page__container text-center text-md-left py-112pt" style="min-height: 540px;">
        <div class="col-lg-10 mx-auto">
            <h1 class="text-white text-shadow py-16pt text-center">@lang('labels.frontend.home.search_course_title')</h1>
            <div class="form-group" style="position: relative;">
                <div class="ui fluid category search course font-size-20pt">
                    <div class="ui icon input w-100">
                        <input class="prompt pb-16pt" type="text" placeholder="@lang('labels.frontend.home.search_course_placeholder')">
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>
        </div>
        </p>
    </div>
@endif
</div>
</div>
