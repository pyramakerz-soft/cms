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
                                            <h3 class="nk-block-title page-title">Students</h3>
                                        </div>
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle"><a href="#"
                                                    class="btn btn-icon btn-trigger toggle-expand me-n1"
                                                    data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="more-options">
                                                    <ul class="nk-block-tools g-3">
                                                        <li>
                                                            <div class="form-control-wrap">
                                                                <div class="form-icon form-icon-right"><em
                                                                        class="icon ni ni-search"></em></div><input
                                                                    type="text" class="form-control" id="default-04"
                                                                    placeholder="Search by name">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown"><a href="#"
                                                                    class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                    data-bs-toggle="dropdown">School</a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span></span>1</a></li>
                                                                        <li><a href="#"><span>2</span></a></li>
                                                                        <li><a href="#"><span>3</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown"><a href="#"
                                                                    class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                    data-bs-toggle="dropdown">Program</a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span></span>1</a></li>
                                                                        <li><a href="#"><span>2</span></a></li>
                                                                        <li><a href="#"><span>3</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown"><a href="#"
                                                                    class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                    data-bs-toggle="dropdown">Grade</a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span></span>1</a></li>
                                                                        <li><a href="#"><span>2</span></a></li>
                                                                        <li><a href="#"><span>3</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="nk-block-tools-opt"><a
                                                                class="btn btn-icon btn-primary d-md-none"
                                                                data-bs-toggle="modal" href="#student-add"><em
                                                                    class="icon ni ni-plus"></em></a><a
                                                                class="btn btn-primary d-none d-md-inline-flex"
                                                                data-bs-toggle="modal" href="#student-add"><em
                                                                    class="icon ni ni-plus"></em><span>Add</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-block">
                                    <div class="card">
                                        <div class="card-inner-group">
                                            <div class="card-inner p-0">
                                                <div class="nk-tb-list nk-tb-ulist">
                                                    <div class="nk-tb-item nk-tb-head">
                                                        <div class="nk-tb-col nk-tb-col-check">
                                                            <div
                                                                class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="uid"><label class="custom-control-label"
                                                                    for="uid"></label>
                                                            </div>
                                                        </div>
                                                        <div class="nk-tb-col"><span class="sub-text">Student</span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-mb"><span
                                                                class="sub-text d-lg-flex d-none">School</span></div>
                                                        <div class="nk-tb-col tb-col-md"><span class="sub-text">Phone</span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text">Grade</span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-lg"><span
                                                                class="sub-text">Program</span></div>

                                                    </div>

                                                    <div class="nk-tb-item">
                                                        <div class="nk-tb-col nk-tb-col-check">
                                                            <div
                                                                class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="uid2"><label class="custom-control-label"
                                                                    for="uid2"></label>
                                                            </div>
                                                        </div>
                                                        <div class="nk-tb-col"><a href="students-details.html">
                                                                <div class="user-card">
                                                                    <div class="user-avatar"><img
                                                                            src="../images/avatar/a-sm.jpg" alt="">
                                                                    </div>
                                                                    <div class="user-info"><span class="tb-lead">Ashley
                                                                            Lawson <span
                                                                                class="dot dot-warning d-md-none ms-1"></span></span><span>ashley@softnio.com</span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-mb"><span
                                                                class="tb-lead d-lg-flex d-none">mindbuzz
                                                            </span>
                                                            <div class="d-lg-flex d-none">

                                                            </div>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-md"><span>+124 394-1787</span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-lg"><span>prek1</span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-mb"><span
                                                                class="tb-lead d-lg-flex d-none">Phonics</span>
                                                            <div class="d-lg-flex d-none">
                                                                <div class="drodown"><a href="#"
                                                                        class="dropdown-toggle pt-1 text-info"
                                                                        data-bs-toggle="dropdown"> <span>View
                                                                            More</span> </a>
                                                                    <div class="dropdown-menu dropdown-menu-start">
                                                                        <ul class="link-list-opt no-bdr p-3">
                                                                            <li class="tb-lead p-1">Phonics</li>
                                                                            <li class="tb-lead p-1">Phonics
                                                                            </li>
                                                                            <li class="tb-lead p-1">Phonics</li>
                                                                            <li class="tb-lead p-1">Phonics</li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-mb"><span
                                                                class="badge badge-dim bg-warning d-lg-flex d-none center">Edit
                                                            </span>
                                                            <div class="d-lg-flex d-none">

                                                            </div>
                                                        </div>

                                                        <div class="nk-tb-col tb-col-mb"><span
                                                                class=" d-lg-flex  d-none badge badge-dim bg-danger center">Delete
                                                            </span>
                                                            <div class="d-lg-flex d-none">

                                                            </div>
                                                        </div>

                                                    </div>







                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-inner">
                                            <div class="nk-block-between-md g-3">
                                                <div class="g">
                                                    <ul class="pagination justify-content-center justify-content-md-start">
                                                        <li class="page-item"><a class="page-link" href="#"><em
                                                                    class="icon ni ni-chevrons-left"></em></a></li>
                                                        <li class="page-item"><a class="page-link" href="#">1</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">2</a>
                                                        </li>
                                                        <li class="page-item"><span class="page-link"><em
                                                                    class="icon ni ni-more-h"></em></span></li>
                                                        <li class="page-item"><a class="page-link" href="#">6</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">7</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#"><em
                                                                    class="icon ni ni-chevrons-right"></em></a></li>
                                                    </ul>
                                                </div>
                                                <div class="g">
                                                    <div
                                                        class="pagination-goto d-flex justify-content-center justify-content-md-start gx-3">
                                                        <div>Page</div>
                                                        <div><select class="form-select js-select2" data-search="on"
                                                                data-dropdown="xs center">
                                                                <option value="page-1">1</option>
                                                                <option value="page-2">2</option>
                                                                <option value="page-4">4</option>
                                                                <option value="page-5">5</option>
                                                                <option value="page-6">6</option>
                                                                <option value="page-7">7</option>
                                                                <option value="page-8">8</option>
                                                                <option value="page-9">9</option>
                                                                <option value="page-10">10</option>
                                                                <option value="page-11">11</option>
                                                                <option value="page-12">12</option>
                                                                <option value="page-13">13</option>
                                                                <option value="page-14">14</option>
                                                                <option value="page-15">15</option>
                                                                <option value="page-16">16</option>
                                                                <option value="page-17">17</option>
                                                                <option value="page-18">18</option>
                                                                <option value="page-19">19</option>
                                                                <option value="page-20">20</option>
                                                            </select></div>
                                                        <div>OF 102</div>
                                                    </div>
                                                </div>
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
