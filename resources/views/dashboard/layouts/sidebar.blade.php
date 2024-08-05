<div class="nk-sidebar nk-sidebar-fixed " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand"><a href="../index.html" class="logo-link nk-sidebar-logo"><img
                    class="logo-light logo-img" src="./assets/images/logo.png" srcset="./assets/images/logo.png"
                    alt="logo"><img class="logo-dark logo-img" src="./assets/images/logo.png"
                    srcset="/demo2/images/logo-dark2x.png 2x" alt="logo-dark"><img
                    class="logo-small logo-img logo-img-small" src="../images/logo-small.png"
                    srcset="/demo2/images/logo-small2x.png 2x" alt="logo-small"></a></div>
        <div class="nk-menu-trigger me-n2"><a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none"
                data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a><a href="#"
                class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em
                    class="icon ni ni-menu"></em></a></div>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-item"><a href="{{ route('dashboard') }}" class="nk-menu-link"><span
                                class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span><span
                                class="nk-menu-text">Dashboard</span></a></li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-book-fill"></em></span><span
                                class="nk-menu-text">Courses</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('courses.create') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Add Course</span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('courses.index') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Course List</span></a></li>
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-book-fill"></em></span><span
                                class="nk-menu-text">Roles</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('roles.create') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Add Role</span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('roles.index') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Roles List</span></a></li>
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-book-fill"></em></span><span
                                class="nk-menu-text">Stage</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('stages.create') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Add Stage</span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('stages.index') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Stage List</span></a></li>
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-book-fill"></em></span><span
                                class="nk-menu-text">Program</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('programs.create') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Add Program</span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('programs.index') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Program List</span></a></li>
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-user-fill"></em></span><span
                                class="nk-menu-text">School</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('schools.create') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Add School
                                    </span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('schools.index') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Schools
                                        List</span></a></li>


                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-user-fill"></em></span><span
                                class="nk-menu-text">Class</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('classes.create') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Add Class
                                    </span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('classes.index') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Classes
                                        List</span></a></li>


                        </ul>
                    </li>
                    {{-- @can('view_user') --}}

                        <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                    class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span><span
                                    class="nk-menu-text">Students</span></a>
                            <ul class="nk-menu-sub">
                                {{-- @can('create_user') --}}
                                    <li class="nk-menu-item"><a href="{{ route('students.create') }}"
                                            class="nk-menu-link"><span class="nk-menu-text">Add Students</span></a>
                                    </li>
                                {{-- @endcan --}}
                                <li class="nk-menu-item"><a href="{{ route('students.index') }}"
                                        class="nk-menu-link"><span class="nk-menu-text">Students
                                            List</span></a></li>
                            </ul>
                        </li>
                    {{-- @endcan --}}

                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-user-fill"></em></span><span
                                class="nk-menu-text">Instructors</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('instructors.create') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Add Instructors
                                    </span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('instructors.index') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Instructor
                                        List</span></a></li>
                        </ul>
                    </li>
                    <li class="nk-menu-item has-sub"><a href="{{ route('reports.index') }}"
                            class="nk-menu-link "></span><span class="nk-menu-text">Reports</span></a>

                    </li>
                    <li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span
                                class="nk-menu-icon"><em class="icon ni ni-property-add"></em></span><span
                                class="nk-menu-text">Select
                                Group</span></a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item"><a href="{{ route('select.group') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Completion Report</span></a></li>
                            <li class="nk-menu-item"><a href="{{ route('class.mastery.report.web') }}"
                                    class="nk-menu-link"><span class="nk-menu-text">Mastery Report</span></a></li>
                        </ul>
                    </li>
                    <li class="nk-menu-item"><a href="message.html" class="nk-menu-link"><span
                                class="nk-menu-icon"><em class="icon ni ni-chat-fill"></em></span><span
                                class="nk-menu-text">Messages</span></a></li>
                    <li class="nk-menu-item"><a href="admin-profile.html" class="nk-menu-link"><span
                                class="nk-menu-icon"><em class="icon ni ni-account-setting-fill"></em></span><span
                                class="nk-menu-text">Admin profile</span></a></li>

                    <li class="nk-menu-item"><a href="settings.html" class="nk-menu-link"><span
                                class="nk-menu-icon"><em class="icon ni ni-setting-alt-fill"></em></span><span
                                class="nk-menu-text">Settings</span></a></li>
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Return to</h6>
                    </li>
                    <li class="nk-menu-item"><a href="../index.html" class="nk-menu-link"><span
                                class="nk-menu-icon"><em class="icon ni ni-dashlite-alt"></em></span><span
                                class="nk-menu-text">Main
                                Dashboard</span></a></li>
                    <li class="nk-menu-item"><a href="../components.html" class="nk-menu-link"><span
                                class="nk-menu-icon"><em class="icon ni ni-layers-fill"></em></span><span
                                class="nk-menu-text">All Components</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
