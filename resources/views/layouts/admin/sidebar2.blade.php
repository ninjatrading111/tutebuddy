{{-- <div class="mdk-drawer js-mdk-drawer" id="default-drawer"> --}}
    <div class="fluid mdk-drawer__content">
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
            <div class="sidebar-heading">ddd</div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
<!-- Dashboard -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.dashboard')</span>
                    </a>
                </li> --}}
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
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">near_me</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.courses')</span>
                    </a>
                </li>
                {{-- @endcan --}}
{{-- Finance --}}
                <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">monetization_on</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.sales')</span>
                    </a>
                </li>
{{-- Settings --}}
                {{-- @can('setting_access') --}}
                {{-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#setting_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                        @lang('navs.sidebar.settings')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/general*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.general')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/mailedits*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.email_templates')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/tax*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.taxes')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/translation*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.translation_manager')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/social*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.social')</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                {{-- @endcan --}}
<hr>
                {{-- @can('category_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/categories*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.categories')</span>
                    </a>
                </li>
                @endcan

                @can('level_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/levels*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="#">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">near_me</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.levels')</span>
                    </a>
                </li> --}}
                {{-- @endcan

                @can('workspace_access') --}}
                {{-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#workspace">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">laptop_chromebook</span>
                        @lang('navs.sidebar.workspace.title')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="workspace" style="">

                        <li class="sidebar-menu-item {{ Request::is('dashboard/live-sessions*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.scheduled_lesson')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/enrolled-students*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.enrolled_students')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/submited-assignments*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.submitted_assignments')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/submited-tests*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.submitted_tests')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/pre-enrolled*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.pre_enrolled')</span>
                            </a>
                        </li>

                    </ul>
                </li> --}}

         
        
                <!-- Demo Request -->
      

                <!-- reviews for course -->
   
                {{-- @endif

                @can('study_access') --}}
        
          
                    {{-- @can('search_access') --}}
         

                    <!-- Demo Request -->
                    {{-- @if(auth()->user()->hasRole('User') || auth()->user()->hasRole('Child'))
                    <li class="sidebar-menu-item {{ Request::is('dashboard/demo*') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="{{ route('admin.demo.index') }}">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                            <span class="sidebar-menu-text">Demo Request</span>
                        </a>
                    </li>
                    @endif --}}

                    <!-- Course Performance (Result) -->
            

                    <!-- Cert -->
            

                    <!-- Badges -->
         
                {{-- @endcan --}}

       

                {{-- @can('message_access') --}}
                <!-- Messages -->
           
                {{-- @endcan --}}

                <!-- My Account -->
             
                {{-- @can('student_payment_access') --}}
                <!-- My Payment History -->
            
                {{-- @endcan --}}

                {{-- @if(auth()->user()->child()->count() > 0)

              
                @endif --}}

                {{-- @can('help_access') --}}
   
                {{-- @endcan --}}
            </ul>

            {{-- @can('setting_access') --}}
            <!-- Sidebar Menu -->
       
            {{-- @endcan --}}
            
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