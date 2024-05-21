{{-- <div class="platform"> --}}
    <div class="platform">
        <div class="sidebar sidebar-light sidebar-light-dodger-blue sidebar-left" data-perfect-scrollbar>

            <a href="#" class="sidebar-brand ">
                <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                    <img src="@if(!empty(config('sidebar_logo'))) 
                                {{ asset('storage/logos/'.config('sidebar_logo')) }}
                            @else 
                                {{ asset('assets/img/logo/tutebuddy-menu-logo.png') }}
                            @endif" alt="logo" class="img-fluid" />
                </span>
            </a>
            <!-- Sidebar Head -->
            {{-- <div class="sidebar-heading">{{auth()->user()->getRoleNames()->first()}}</div> --}}

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
<!-- Dashboard -->
                <li class="sidebar-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.dashboard')</span>
                    </a>
                </li>
{{-- User Management --}}
                {{-- @can('setting_access') --}}
                <li class="sidebar-menu-item {{ Request::is('dashboard/users*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.users')</span>
                    </a>
                </li>
                {{-- @endcan --}}
{{-- Orders --}}
                {{-- @can('course_access') --}}
                <li class="sidebar-menu-item {{ Request::is('dashboard/course*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.courses.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">near_me</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.courses')</span>
                    </a>
                </li>
                {{-- @endcan --}}
{{-- Finance --}}
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#finance_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">monetization_on</span>
                            @lang('navs.sidebar.sales')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="finance_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.orders') }}">
                                <span class="sidebar-menu-text">Finance Report</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/transaction*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.transactions') }}">
                                <span class="sidebar-menu-text">Transaction History</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/affiliate*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.affiliate') }}">
                                <span class="sidebar-menu-text">Affiliate Earning</span>
                            </a>
                        </li>
                    </ul>
                </li>
{{-- Settings --}}
                {{-- @can('setting_access') --}}
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#setting_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                        @lang('navs.sidebar.settings')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/term*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.term') }}">
                                <span class="sidebar-menu-text">Term Of Service</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/commission*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.commission') }}">
                                <span class="sidebar-menu-text">Commission Rate</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/paygateway*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.paygateway') }}">
                                <span class="sidebar-menu-text">Payment Gateway</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/feature*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.feature') }}">
                                <span class="sidebar-menu-text">Feature</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- @endcan --}}
            </ul>
        </div>
    </div>
{{-- </div> --}}
<!-- // END drawer -->

@push('after-scripts')
<script>
    $(document).ready(function(){

        // Make parent menu active
        var active_menus = $('li.sidebar-menu-item.active');
        $.each(active_menus, function(idx, item){
            $(this).closest('ul.sidebar-submenu').parent().addClass('active open');
        });
    });
</script>
@endpush