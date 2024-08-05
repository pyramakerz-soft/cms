@extends('dashboard.layouts.layout')
@section('content')
    <div class="nk-app-root">
        <div class="nk-main ">
            @include('dashboard.layouts.sidebar')

            <div class="nk-wrap ">
                @include('dashboard.layouts.navbar')

                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Dashboard</h3>
                                            <div class="nk-block-des text-soft">
                                                <p>Welcome to Your Dashboard.</p>
                                            </div>
                                        </div>
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle"><a href="#"
                                                    class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                    data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        {{-- <li>
                                                            <div class="drodown"><a href="#"
                                                                    class="dropdown-toggle btn btn-white btn-dim btn-outline-light"
                                                                    data-bs-toggle="dropdown"><em
                                                                        class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span
                                                                            class="d-none d-md-inline">Last</span> 30
                                                                        Days</span><em
                                                                        class="dd-indc icon ni ni-chevron-right"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span>Last 30 Days</span></a>
                                                                        </li>
                                                                        <li><a href="#"><span>Last 6 Months</span></a>
                                                                        </li>
                                                                        <li><a href="#"><span>Last 1 Years</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li> --}}
                                                        <li class="nk-block-tools-opt"><a
                                                                href="{{ route('reports.index') }}"
                                                                class="btn btn-primary"><em
                                                                    class="icon ni ni-reports"></em><span>Reports</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="row g-gs">
                                        <div class="col-xxl-12">
                                            <div class="row g-gs">

                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="nk-ecwg nk-ecwg3">
                                                            <div class="card-inner pb-0">
                                                                <div class="card-title-group">
                                                                    <div class="card-title">
                                                                        <h4 class="title">Total Students</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="data">
                                                                    <div class="data-group">
                                                                        {{-- <div class="amount fw-normal">$9,495.20</div> --}}
                                                                        <div class="info text-end"><br><b
                                                                                class="fs-2 text-dark text-end">{{ $studentsInSchool }}</b>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="nk-ecwg3-ck"><canvas class="courseSells"
                                                                    id="totalSells"></canvas></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="nk-ecwg nk-ecwg3">
                                                            <div class="card-inner pb-0">
                                                                <div class="card-title-group">
                                                                    <div class="card-title">
                                                                        <h4 class="title">Total Teachers</h4>
                                                                    </div>
                                                                </div>
                                                                <div class="data">
                                                                    <div class="data-group">
                                                                        {{-- <div class="amount fw-normal">$9,495.20</div> --}}
                                                                        <div class="info text-end"><br><b
                                                                                class="fs-2 text-dark text-end">{{ $teachersInSchool }}</b>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="nk-ecwg3-ck"><canvas class="courseSells"
                                                                    id="totalSells"></canvas></div>
                                                        </div>
                                                    </div>
                                                </div>
{{-- 
                                                <div class="col-md-6">
                                                    <div class="card">
                                                        <div class="nk-ecwg nk-ecwg3">
                                                            <div class="card-inner pb-0">
                                                                <div class="card-title-group">
                                                                    <div class="card-title">
                                                                        <h6 class="title">Total Sales</h6>
                                                                    </div>
                                                                </div>
                                                                <div class="data">
                                                                    <div class="data-group">
                                                                        <div class="amount fw-normal">$9,495.20</div>
                                                                        <div class="info text-end"><span
                                                                                class="change up text-danger"><em
                                                                                    class="icon ni ni-arrow-long-up"></em>4.63%</span><br><span>vs.
                                                                                last month</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="nk-ecwg3-ck"><canvas class="courseSells"
                                                                    id="totalSells"></canvas></div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('dashboard.layouts.footer')

            </div>
        </div>
    </div>
@endsection
