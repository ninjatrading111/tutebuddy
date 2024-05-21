@if(!auth()->check())

<div class="js-fix-footer2 bg-white border-top-2 position-relative">
    <div class="container page__container page-section d-flex flex-column">
        <div class="row pb-16pt mb-16pt border-bottom-2">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="{{ config('app.url') }}" class="">
                    <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                        <img src="@if(!empty(config('sidebar_logo'))) 
                                {{ asset('storage/logos/'.config('sidebar_logo')) }}
                            @else 
                                {{ asset('assets/img/logo/tutebuddy-menu-logo.png') }}
                            @endif" alt="logo" class="img-fluid" style="width: 80%;" />
                    </span>
                </a>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <ul class="footer-menu">
                    <li class="footer-menu-item">
                        <a href="/page/about-us" >About Us</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/teach-on-tutebuddy" >Teach on TuteBuddy</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/solutions-for-business" >Solutions for Business</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/solutions-for-institutions" >Solutions for Institutions</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <ul class="footer-menu">
                    <li class="footer-menu-item">
                        <a href="/page/how-it-works" >How It Works</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/terms-and-conditions" >Terms of Service</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/privacy-policy" >Privacy Policy</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/cookie-policy-for-tutebuddycom" >Cookie Policy</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <ul class="footer-menu">
                    <li class="footer-menu-item">
                        <a href="/page/student-safety" >Student Safety</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/support" >Support</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/faqs" >FAQs</a>
                    </li>
                    <li class="footer-menu-item">
                        <a href="/page/contact-us" >Contact Us</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                    <img src="{{ asset('images/secure-by-thawte.png') }}" alt="logo" class="img-fluid" style="width: 80%;" />
                </span>

                <div class="social-icons mt-3">
                    <a href="https://www.facebook.com/tutebuddyofficial" target="_blank" class="mr-2"><i class="fab fa-facebook-square icon-24pt text-primary"></i></a>
                    <a href="https://www.instagram.com/tutebuddy/" target="_blank" class="mr-2"><i class="fab fa-instagram-square icon-24pt text-primary"></i></a>
                    <a href="https://twitter.com/tutebuddy" target="_blank" class="mr-2"><i class="fab fa-twitter-square icon-24pt text-primary"></i></a>
                    <a href="https://www.linkedin.com/company/tutebuddy/" target="_blank" class=""><i class="fab fa-linkedin icon-24pt text-primary"></i></a>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12 pt-2">
                <p class="text-50 small mt-n1 mb-0">Copyright 2020 &copy; All rights reserved.</p>
            </div>
        </div>            
    </div>
</div>

@else
<div class="js-fix-footer2 bg-white border-top-2 position-relative">
    <div class="container page__container page-section d-flex flex-column p-3">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="avatar avatar-xl navbar-brand-icon mr-8pt">
                    <a href="{{ config('app.url') }}" class="">
                        <img src="{{ asset('/images/tutebuddy-logo-full.png') }}" alt="logo" class="img-fluid" />
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="text-center pt-16pt">
                    <strong class="js-lists-values-project">Copyright 2020 &copy; All rights reserved.</strong>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div class="text-center pt-16pt">
                    <a href="/page/terms-and-conditions" class="text-100">Terms of Service</a> | <a href="/page/support"  class="text-100">Support</a>
                </div>
            </div>
        </div> 
    </div>
</div>
@endif
