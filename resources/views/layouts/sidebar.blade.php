<div class="mdk-drawer js-mdk-drawer" id="default-drawer">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-light-dodger-blue sidebar-left" data-perfect-scrollbar>

            <a href="#" class="sidebar-brand ">
                <span class="avatar avatar-xl sidebar-brand-icon h-auto">
                    <img src="@if (!empty(config('sidebar_logo'))) {{ asset('storage/logos/' . config('sidebar_logo')) }}
                            @else 
                                {{ asset('assets/img/logo/tutebuddy-menu-logo.png') }} @endif"
                        alt="logo" class="img-fluid" />
                </span>
            </a>
            <!-- Sidebar Head -->
            <div class="sidebar-heading">{{ auth()->user()->role }}</div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
              

                {{-- @can('category_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/categories*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.categories.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">category</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.categories')</span>
                    </a>
                </li>
                @endcan

                @can('level_access')
                <li class="sidebar-menu-item {{ Request::is('dashboard/levels*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.levels.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">near_me</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.levels')</span>
                    </a>
                </li>
                @endcan --}}

                {{-- @can('workspace_access') --}}
                {{-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#workspace">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">laptop_chromebook</span>
                        @lang('navs.sidebar.workspace.title')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="workspace" style=""> --}}

                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/live-sessions*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.liveSessions') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.scheduled_lesson')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/enrolled-students*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.students.enrolled') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.enrolled_students')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/submited-assignments*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.submitedAssignments') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.submitted_assignments')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/submited-tests*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.submitedTests') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.submitted_tests')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/pre-enrolled*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.messages.preEnrolledStudents') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.workspace.pre_enrolled')</span>
                            </a>
                        </li> --}}
                {{-- 
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#courses_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                        @lang('navs.sidebar.teach')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="courses_menu" style=""> --}}

                {{-- @can('course_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/course*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.courses.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.courses')</span>
                            </a>
                        </li>
                        @endcan

                        @can('bundle_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/bundle*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.bundles.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.paths')</span>
                            </a>
                        </li>
                        @endcan

                        @can('schedule_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/schedule*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.schedule') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.schedule')</span>
                            </a>
                        </li>
                        @endcan --}}
                {{-- </ul>
                </li> --}}
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#task_menu1">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">dashboard</span>
                        Dashboard
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="task_menu1" style="">
                        {{-- @can('assignment_access') --}}
                        <li class="sidebar-menu-item {{ Request::is('dashboard/active*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="/dashboard/active">
                                <span class="sidebar-menu-text">Active Order</span>
                            </a>
                        </li>
                        {{-- @endcan

                        @can('test_access') --}}
                        <li class="sidebar-menu-item {{ Request::is('dashboard/draft*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="/dashboard/draft">
                                <span class="sidebar-menu-text">Draft</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#task_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment</span>
                        @lang('navs.sidebar.ordering')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="task_menu" style="">
                        {{-- @can('assignment_access') --}}
                        <li class="sidebar-menu-item {{ Request::is('writing*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="/writing">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.writing')</span>
                            </a>
                        </li>
                        {{-- @endcan

                        @can('test_access') --}}
                        <li class="sidebar-menu-item {{ Request::is('editing*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="/editing">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.editing')</span>
                            </a>
                        </li>
                        {{-- @endcan

                        @can('quiz_access') --}}
                        <li class="sidebar-menu-item {{ Request::is('other*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="/other">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.other')</span>
                            </a>
                        </li>
                        {{-- @endcan --}}
                    </ul>
                </li>

                <!-- Demo Request -->
                <li class="sidebar-menu-item {{ Request::is('profile*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/profile">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                        <span class="sidebar-menu-text">profile</span>
                    </a>
                </li>

                <!-- reviews for course -->
                <li class="sidebar-menu-item {{ Request::is('logut*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">logout</span>
                        <span class="sidebar-menu-text">logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
 {{--
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#report_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">event_note</span>
                        @lang('navs.sidebar.reports')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="report_menu" style="">
                    
                        <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.orders') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.sales')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/transaction*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.transactions') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.transactions')</span>
                            </a>
                        </li>

                        @if (auth()->user()->hasRole('Administrator'))
                        <li class="sidebar-menu-item {{ Request::is('dashboard/withdraw*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.instructor.withdraws') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.withdraws')</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-menu-item {{ Request::is('dashboard/contact*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.contacts.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.contacts')</span>
                            </a>
                        </li>
                        @endif

                        <li class="sidebar-menu-item {{ Request::is('dashboard/refund*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.refunds') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.refunds')</span>
                            </a>
                        </li> --}}

                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/invoices*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.invoices') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.invoices')</span>
                            </a>
                        </li> --}}
                {{-- </ul>
                </li>
                @endif

                @can('study_access')
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#study_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">laptop_chromebook</span>
                            @lang('navs.sidebar.my_study')
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>

                        <ul class="sidebar-submenu collapse sm-indent" id="study_menu" style="">

                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/course*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.courses') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.courses')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/live*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.liveSessions') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.live_lessons')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/path*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.bundles') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.paths')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/instructor*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.instructors') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.instructors')</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#my_task_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">assignment</span>
                            My Tasks
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>

                        <ul class="sidebar-submenu collapse sm-indent" id="my_task_menu" style="">
                        
                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/assignment*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.assignments') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.assignments')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/quiz*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.quizs') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.quizzes')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Request::is('dashboard/my/test*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.student.tests') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.tests')</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @can('search_access')
                    <li class="sidebar-menu-item">
                        <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#browse_menu">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">import_contacts</span>
                            @lang('navs.sidebar.browse')
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>

                        <ul class="sidebar-submenu collapse sm-indent" id="browse_menu" style="">

                            <li class="sidebar-menu-item {{ Request::is('search/courses*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('courses.search') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.courses')</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-menu-item {{ Request::is('search/instructor*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('teachers.search') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.instructors')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ Request::is('dashboard/courses/get/favorite*') ? 'active' : '' }}">
                                <a class="sidebar-menu-button" href="{{ route('admin.courses.favorites') }}">
                                    <span class="sidebar-menu-text">@lang('navs.sidebar.favorites')</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan --}}

                <!-- Demo Request -->
                {{-- @if (auth()->user()->hasRole('Student') || auth()->user()->hasRole('Child'))
                    <li class="sidebar-menu-item {{ Request::is('dashboard/demo*') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="{{ route('admin.demo.index') }}">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">portrait</span>
                            <span class="sidebar-menu-text">Demo Request</span>
                        </a>
                    </li>
                    @endif --}}

                <!-- Course Performance (Result) -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/result*') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="{{ route('admin.results.student') }}">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">poll</span>
                            <span class="sidebar-menu-text">@lang('navs.sidebar.course_performance')</span>
                        </a>
                    </li> --}}

                <!-- Cert -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/certificate*') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="{{ route('admin.certificates.index') }}">
                            <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">new_releases</span>
                            <span class="sidebar-menu-text">@lang('navs.sidebar.my_certifications')</span>
                        </a>
                    </li> --}}

                <!-- Badges -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/badges*') ? 'active' : '' }}">
                        <a class="sidebar-menu-button" href="{{ route('admin.results.student.badges') }}">
                            <i class="material-icons sidebar-menu-icon sidebar-menu-icon--left fa fa-medal"></i>
                            <span class="sidebar-menu-text">@lang('navs.sidebar.my_badges')</span>
                        </a>
                    </li>
                @endcan

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#community_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">people_outline</span>
                        @lang('navs.sidebar.discussion')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="community_menu">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/discussion*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.discussions.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.my_topics')</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ Request::is('dashboard/topic*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.discussions.topics') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.discussion_topics')</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @can('message_access') --}}
                <!-- Messages -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/message*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.messages.index') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">send</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.messages')</span>
                    </a>
                </li>
                @endcan --}}

                <!-- My Account -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/account*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.myaccount') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">account_circle</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.my_account')</span>
                    </a>
                </li>

                @can('student_payment_access') --}}
                <!-- My Payment History -->
                {{-- <li class="sidebar-menu-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.orders') }}">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">monetization_on</span>
                        <span class="sidebar-menu-text">@lang('navs.sidebar.my_payments')</span>
                    </a>
                </li>
                @endcan --}}

                {{-- @if (auth()->user()->child()->count() > 0)

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#childs_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">face</span>
                        My Childs
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="childs_menu" style="">
                        @foreach (auth()->user()->child() as $child)
                        <li class="sidebar-menu-item {{ Request::is('dashboard/childs/', $child->id) ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.child', $child->id) }}">
                                <span class="sidebar-menu-text">{{ $child->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    
                <li>
                @endif --}}

                {{-- @can('help_access')
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#help">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">help</span>
                        Help
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="help" style="">

                        <li class="sidebar-menu-item {{ Request::is('dashboard/faqs*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.faqs.index') }}">
                                <span class="sidebar-menu-text">Faqs</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/helps*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="#">
                                <span class="sidebar-menu-text">Tickets</span>
                            </a>
                        </li>
                    </ul>
                    
                <li>
                @endcan --}}
            </ul>

            {{-- @can('setting_access') --}}
            <!-- Sidebar Menu -->
            {{-- <ul class="sidebar-menu"> --}}
            <!-- Sidebar Head -->
            {{-- <div class="sidebar-heading">@lang('navs.sidebar.system')</div> --}}

            <!-- Pages -->
            {{-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#pages_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">book</span>
                        @lang('navs.sidebar.pages')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse sm-indent" id="pages_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/page*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.pages.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.all_pages')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/faq*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.faqs.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.faqs')</span>
                            </a>
                        </li>
                    </ul>
                </li> --}}

            <!-- Access -->
            {{-- <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#access_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">person</span>
                        @lang('navs.sidebar.access')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="access_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/users*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.users.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.users')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/kyc*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.kyc.index') }}">
                                <span class="sidebar-menu-text">KYC Verification</span>
                            </a>
                        </li>

                        @can('role_access')
                        <li class="sidebar-menu-item {{ Request::is('dashboard/roles*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.roles.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.role')</span>
                            </a>
                        </li>
                        @endcan

                        <li class="sidebar-menu-item {{ Request::is('dashboard/access-history*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.users.access_history') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.history')</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#setting_menu">
                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">settings</span>
                        @lang('navs.sidebar.settings')
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>

                    <ul class="sidebar-submenu collapse sm-indent" id="setting_menu" style="">
                        <li class="sidebar-menu-item {{ Request::is('dashboard/settings/general*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.settings.general') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.general')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/mailedits*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.mailedits.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.email_templates')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/tax*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.tax.index') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.taxes')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/translation*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ asset('dashboard/translations') }}">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.translation_manager')</span>
                            </a>
                        </li>

                        <li class="sidebar-menu-item {{ Request::is('dashboard/social*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="">
                                <span class="sidebar-menu-text">@lang('navs.sidebar.social')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            @endcan
            
        </div> --}}
        </div>
    </div>
    <!-- // END drawer -->

    @push('after-scripts')
        <script>
            $(document).ready(function() {

                // Make parent menu active
                var active_menus = $('li.sidebar-menu-item.active');
                $.each(active_menus, function(idx, item) {
                    $(this).closest('ul.sidebar-submenu').parent().addClass('active open');
                });
            });
        </script>
    @endpush
