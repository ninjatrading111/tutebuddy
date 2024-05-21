<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'TuteBuddy LMS') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('/storage/logos/' . config('favicon')) }}">

    @if (Request::is('profile/*'))
        <!-- Prevent the demo from appearing in search engines -->
        <meta name="robots" content="noindex">
    @endif

    <!-- Metatags -->
    <meta name="title" content="Tutebuddy - Learn anything Online">
    <meta name="description"
        content="Find an instructor, guru, teacher or course to learn anything online through self paced learning or Live Sessions. Expert teachers and intsructors available that cover almost any subject. Search, discuss and tailor your course to suit your needs.">
    <meta name="keywords"
        content="Teaching, Training, Live Sessions, Lessons, Remote Learning, whiteboard, courses,  Online Assessment, E-Learning, Certification, online courses platform, LMS, classroom, tuition, coaching, study, Schools, Institutions">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="revisit-after" content="3 days">

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Tutebuddy - Learn anything Online">
    <meta itemprop="description"
        content="Find an instructor, guru, teacher or course to learn anything online through self paced learning or Live Sessions. Expert teachers and intsructors available that cover almost any subject. Search, discuss and tailor your course to suit your needs.">
    {{-- <meta itemprop="image" content="https://www.tutebuddy.com/storage/logos/1597777614-tutebuddy-logo.png"> --}}

    <!-- Facebook Meta Tags -->
    <meta property="og:title" content="Tutebuddy - Learn anything Online">
    <meta property="og:type" content="article" />
    {{-- <meta property="og:url" content="https://www.tutebuddy.com/"> --}}
    <meta property="og:site_name" content="TuteBuddy" />
    <meta property="og:description"
        content="Find an instructor, guru, teacher or course to learn anything online through self paced learning or Live Sessions. Expert teachers and instructors available that cover almost any subject. Search, discuss and tailor your course to suit your needs.">
    {{-- <meta property="og:image" content="https://www.tutebuddy.com/storage/logos/1597777614-tutebuddy-logo.png"> --}}

    <!-- Twitter Meta Tags Card -->
    <meta name="twitter:card" content="summary_large_image">
    {{-- <meta property="twitter:domain" content="https://tutebuddy.com"> --}}
    {{-- <meta property="twitter:url" content="https://www.tutebuddy.com/"> --}}
    <meta name="twitter:title" content="Tutebuddy - Learn anything Online">
    <meta name="twitter:description"
        content="Find a teacher or guru or course to learn anything online through self paced learning or Live Sessions. Expert teachers and instructors for any subject. ">
    {{-- <meta name="twitter:image" content="https://www.tutebuddy.com/storage/logos/1597777614-tutebuddy-logo.png"> --}}
    <!-- Twitter summary card with large image must be at least 280x150px -->

    {{-- <link href="https://fonts.googleapis.com/css?family=Lato:400,700%7CRoboto:400,500%7CExo+2:600&display=swap" rel="stylesheet"> --}}
    @yield('link')
    <!-- Perfect Scrollbar -->
    <link type="text/css" href="{{ asset('assets/css/perfect-scrollbar.css') }}" rel="stylesheet">

    <!-- Fix Footer CSS -->
    <link type="text/css" href="{{ asset('assets/css/fix-footer.css') }}" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">

    <!-- Font Awesome Icons -->
    {{-- <link rel="stylesheet" href="accordion/bootstrap.min.css"> --}}

    <link type="text/css" href="{{ asset('assets/css/fontawesome.css') }}" rel="stylesheet">
    {{-- <link type="text/css" href="{{ asset('js/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('js/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
    {{-- <script src="accordion/jquery.slim.min.js"></script>
    <script src="accordion/popper.min.js"></script>
    <script src="accordion/bootstrap.bundle.min.js"></script> --}}
    {{-- <link type="text/css" href="{{ asset('font_awesome4_cheatsheet-master\css\font-awesome.min.css') }}"
        rel="stylesheet"> --}}


    <!-- Preloader -->
    <link type="text/css" href="{{ asset('assets/css/preloader.css') }}" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link type="text/css" href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link type="text/css" href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('summernote-0.8.18/summernote.min.css') }}" rel="stylesheet">
    @yield('style')
    {{-- @stack('after-styles') --}}
    @if (Session::get('layout') === 'theme-dark')
        <style>
            [dir=ltr] .control-fileupload {
                background: rgb(11, 23, 39) !important;
                color: white !important;
            }

            [dir=ltr] .form-label {
                color: white !important;
            }

            [dir=ltr] input.form-control {
                background: rgb(11, 23, 39) !important;
                color: white !important;
            }

            [dir=ltr] .custom-select {
                background: rgb(11, 23, 39) !important;
                color: white !important;
            }

            [dir=ltr] .dark-bg-black {
                background-color: rgb(11, 23, 39) !important;

            }

            [dir=ltr] .dark-bg-black-80 {
                background-color: rgb(18, 30, 45) !important;

            }
            [dir=ltr] .dark-text{
                color:white;
            }
        </style>
    @endif
    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-38KTQ6T76N"></script> --}}
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-38KTQ6T76N');
    </script>

</head>

<body class="layout-sticky-subnav layout-default mx-auto" style="width: 100%;">
    <!-- Pre Loader -->
    <div class="preloader">
        <div class="sk-double-bounce">
            <div class="sk-child sk-double-bounce1"></div>
            <div class="sk-child sk-double-bounce2"></div>
        </div>
    </div>

    <!-- Header Layout -->
    {{-- <div class="mdk-header-layout js-mdk-header-layout" > --}}

        {{-- @if (Session::get('layout') === 'theme-dark')
            @include('layouts.dark.nav')
        @else
            @include('layouts.lights.nav')
        @endif --}}
        @include('layouts.nav')
        <!-- Main Content -->
        @yield('content')

        <!--footer -->
        {{-- @if (Session::get('layout') === 'theme-dark')
            @include('layouts.dark.footer')
        @else
            @include('layouts.lights.footer')
        @endif --}}
        @include('layouts.admin.footer')
    {{-- </div> --}}


    @if (auth()->check())
        <!-- Side bar -->
        @include('layouts.admin.sidebar')
        {{-- @if (Session::get('layout') === 'theme-dark')
            @include('layouts.dark.sidebar')
        @else
            @include('layouts.lights.sidebar')
        @endif --}}
    @endif

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
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- List.js -->
    {{-- <script src="{{ asset('assets/js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/list.js') }}"></script> --}}

    <!-- Global Settings -->
    <script src="{{ asset('assets/js/settings.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    {{-- <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script> --}}

    <!-- jQuery Form -->
    {{-- <script src="{{ asset('assets/js/jquery.form.min.js') }}"></script>
 --}}
    <script src="{{ asset('summernote-0.8.18/summernote.min.js') }}"></script>

    <script>
        // Ajax header for Ajax POST
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    </script>

    @stack('after-scripts')

    <!-- Global Helper Script -->
    <script src="{{ asset('assets/js/helper.js') }}"></script>

    @include('layouts.parts.sweet-alert')

    {{-- @if (\Request::route()->getName() == 'homepage')
<script>
    (function() {
        'use strict';
        var headerNode = document.querySelector('.mdk-header')
        var layoutNode = document.querySelector('.mdk-header-layout')
        var componentNode = layoutNode ? layoutNode : headerNode

        componentNode.addEventListener('domfactory-component-upgraded', function() {
            headerNode.mdkHeader.eventTarget.addEventListener('scroll', function() {
                var progress = headerNode.mdkHeader.getScrollState().progress
                var navbarNode = headerNode.querySelector('#default-navbar')
                navbarNode.classList.toggle('bg-transparent', progress <= 0.2)
            })
        })
    })()
</script>
@endif --}}

    {{-- @if (\Auth::check())
<script>
    if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                // swal('Success!', 'Camera Enabled', 'success');
            })
            .catch(function (err0r) {
                // swal('Error!', 'No camera supported in your browser.', 'error');
            });
    }
</script>
@endif --}}

    <!-- Load Facebook SDK for JavaScript -->
    {{-- <div id="fb-root"></div> --}}
    {{-- <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script> --}}
    {{-- 
<!-- Chat widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = 1; w.lang = ''; w.cName = ''; w.cEmail = ''; w.cMessage = ''; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = 'https://support.tutebuddy.com/js/jaklcpchat.js';
		h.parentNode.insertBefore(j, h);
	})(window, document, 'script', 'https://support.tutebuddy.com/');
</script> --}}
    {{-- <div id="jaklcp-chat-container"></div> --}}
    <!-- end chat widget -->

</body>

</html>
