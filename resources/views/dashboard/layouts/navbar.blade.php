<div class="nk-header nk-header-fixed">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1"><a href="#" class="nk-nav-toggle nk-quick-nav-icon"
                    data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a></div>
            <div class="nk-header-brand d-xl-none"><a href="../index.php" class="logo-link"><img
                        class="logo-light logo-img" src="./assets/images/logo.png" srcset="./assets/images/logo.png 2x"
                        alt="logo"><img class="logo-dark logo-img" src="../images/logo-dark.png"
                        srcset="./assets/images/logo.png 2x" alt="logo-dark"></a></div>
            <div class="nk-header-search ms-3 ms-xl-0"><em class="icon ni ni-search"></em><input type="text"
                    class="form-control border-transparent form-focus-none" placeholder="Search anything"></div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">


                    <li class="dropdown notification-dropdown"><a href="#"
                            class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
                            <div class="dropdown-head"><span class="sub-title nk-dropdown-title">Notifications</span><a
                                    href="#">Mark All as Read</a></div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon"><em
                                                class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to
                                                <span>Widthdrawl</span>
                                            </div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon"><em
                                                class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit
                                                    Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon"><em
                                                class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to
                                                <span>Widthdrawl</span>
                                            </div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon"><em
                                                class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit
                                                    Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon"><em
                                                class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to
                                                <span>Widthdrawl</span>
                                            </div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon"><em
                                                class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit
                                                    Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-foot center"><a href="#">View All</a></div>
                        </div>
                    </li>
                    <li class="dropdown user-dropdown"><a href="#" class="dropdown-toggle me-n1"
                            data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm"><em class="icon ni ni-user-alt"></em></div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-status user-status-active">
                                        {{ Auth::user()->role == 2 ? 'student' : 'teacher' }}</div>
                                    <div class="user-name dropdown-indicator">{{ Auth::user()->name }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar"><span>AB</span></div>
                                    <div class="user-info"><span class="lead-text">Abu Bin
                                            Ishtiyak</span><span class="sub-text">info@softnio.com</span></div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="admin-profile.html"><em class="icon ni ni-user-alt"></em><span>View
                                                Profile</span></a></li>
                                    <li><a href="admin-profile-setting.html"><em
                                                class="icon ni ni-setting-alt"></em><span>Account
                                                Setting</span></a></li>
                                    <li><a href="admin-profile-activity.html"><em
                                                class="icon ni ni-activity-alt"></em><span>Login
                                                Activity</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>

                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <em class="icon ni ni-signout"></em><span>Sign
                                                out</span>
                                        </a>



                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
