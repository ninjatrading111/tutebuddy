<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'TuteBuddy LMS') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('/storage/logos/' . config('favicon')) }}">

    <link href="https://fonts.googleapis.com/css?family=Lato:400,700%7CRoboto:400,500%7CExo+2:600&display=swap" rel="stylesheet">

    <!-- Perfect Scrollbar -->
    <link type="text/css" href="{{ asset('assets/css/perfect-scrollbar.css') }}" rel="stylesheet">

    <!-- Fix Footer CSS -->
    <link type="text/css" href="{{ asset('assets/css/fix-footer.css') }}" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link type="text/css" href="{{ asset('assets/css/fontawesome.css') }}" rel="stylesheet">

    <!-- Preloader -->
    <link type="text/css" href="{{ asset('assets/css/preloader.css') }}" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link type="text/css" href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link type="text/css" href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    @stack('after-styles')

</head>

<body class="layout-sticky-subnav layout-default ">

    <!-- Pre Loader -->
    <div class="preloader">
        <div class="sk-double-bounce">
            <div class="sk-child sk-double-bounce1"></div>
            <div class="sk-child sk-double-bounce2"></div>
        </div>
    </div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">

        <div id="header" class="mdk-header js-mdk-header mb-0" data-fixed data-effects="">
            <div class="mdk-header__content">
                <div class="navbar navbar-expand navbar-light navbar-light-dodger-blue navbar-shadow" id="default-navbar" data-primary>
                    <!-- Navbar Brand -->
                    {{-- <a href="{{ config('app.url') }}" class="navbar-brand mr-16pt">
                        <span class="avatar avatar-sm navbar-brand-icon mr-0 mr-lg-8pt">
                            <img src="{{ asset('storage/logos/' . config('nav_logo')) }}" alt="logo" class="img-fluid" />
                        </span>
                    </a> --}}

                    {{-- <ul class="nav navbar-nav ml-auto mr-0 desktop-only">
                        <li class="nav-item">
                            <a href="{{ route('register') }}?r=t" class="btn btn-outline-nav" >@lang('navs.register.teacher')</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}?r=s" class="btn btn-outline-nav">@lang('navs.register.student')</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-nav">@lang('navs.login')</a>
                        </li>
                    </ul> --}}

                    <div class="nav-item dropdown ml-auto mr-0 mobile-only">
                        <a href="#" class="btn btn-outline-nav dropdown-toggle"
                            data-toggle="dropdown" data-caret="false">Account
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('register') }}?r=t">@lang('navs.register.teacher')</a>
                            <a class="dropdown-item" href="{{ route('register') }}?r=s">@lang('navs.register.student')</a>
                            <a class="dropdown-item" href="{{ route('login') }}">@lang('navs.login')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content page-content" style="min-height: calc(100vh - 235px);">
            <div class="page-section bg-primary mb-32pt">
                <div class="container page__container">
                    <h2 class="text-center text-white"><span>Route Not Found</span></h2>
                </div>
            </div>

            <div class="container page__container page-section">

                <div class="pt-32pt pt-sm-64pt pb-32pt">
                    <div class="page-section container page__container">
                        <div class="col-lg-6 p-0 mx-auto">

                            <div class="form-group text-center">
                                <a href="{{ route('category.all') }}" class="btn btn-primary">@lang('labels.frontend.search.browse_courses')</a>
                                <a href="{{ route('teachers.search') }}" class="btn btn-accent">@lang('labels.frontend.search.browse_teachers')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--footer -->
        @include('layouts.footer')
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Perfect Scrollbar -->
    <script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>

    <!-- DOM Factory -->
    <script src="{{ asset('assets/js/dom-factory.js') }}"></script>

    <!-- MDK -->
    <script src="{{ asset('assets/js/material-design-kit.js') }}"></script>

    <!-- Fix Footer -->
    <script src="{{ asset('assets/js/fix-footer.js') }}"></script>

    <!-- App JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Global Settings -->
    <script src="{{ asset('assets/js/settings.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>

    <!-- jQuery Form -->
    <script src="{{ asset('assets/js/jquery.form.min.js') }}"></script>
</body>

</html>