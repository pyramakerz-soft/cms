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
                                                    <div class="row">
                                                        <div class="col-12 mb-4">
                                                            <form action="{{ route('import.users') }}" method="POST"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-7">
                                                                        <div class="form-group">
                                                                            {{-- <label for="file">Upload Excel File</label> --}}
                                                                            <input type="file" name="file"
                                                                                class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Import
                                                                            Users</button>
                                                                    </div>
                                                                </div>


                                                            </form>
                                                        </div>
                                                    </div>
                                                    <form method="GET" action="{{ route('students.index') }}">
                                                        <ul class="nk-block-tools g-3">


                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                        data-bs-toggle="dropdown">School</a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @foreach ($schools as $school)
                                                                                <li>
                                                                                    <a href="#"
                                                                                        onclick="document.querySelector('input[name=school]').value = '{{ $school->id }}'; document.querySelector('form').submit();">
                                                                                        <span>{{ $school->name }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                        <input type="hidden" name="school"
                                                                            value="{{ request('school') }}">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                        data-bs-toggle="dropdown">Program</a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @foreach ($programs as $program)
                                                                                <li>
                                                                                    <a href="#"
                                                                                        onclick="document.querySelector('input[name=program]').value = '{{ $program->id }}'; document.querySelector('form').submit();">
                                                                                        <span>{{ $program->name }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                        <input type="hidden" name="program"
                                                                            value="{{ request('program') }}">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                        data-bs-toggle="dropdown">Grade</a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @foreach ($grades as $grade)
                                                                                <li>
                                                                                    <a href="#"
                                                                                        onclick="document.querySelector('input[name=grade]').value = '{{ $grade->id }}'; document.querySelector('form').submit();">
                                                                                        <span>{{ $grade->name }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                        <input type="hidden" name="grade"
                                                                            value="{{ request('grade') }}">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white"
                                                                        data-bs-toggle="dropdown">Class</a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @foreach ($classes as $class)
                                                                                <li>
                                                                                    <a href="#"
                                                                                        onclick="document.querySelector('input[name=group]').value = '{{ $class->id }}'; document.querySelector('form').submit();">
                                                                                        <span>{{ $class->name }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>

                                                                        <input type="hidden" name="group"
                                                                            value="{{ request('group') }}">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="nk-block-tools-opt">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Filter</button>
                                                            </li>
                                                        </ul>
                                                    </form>
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
                                                        <div class="nk-tb-col tb-col-lg"><span
                                                                class="sub-text">Roles</span></div>

                                                    </div>
                                                    @foreach ($students as $student)
                                                        <div class="nk-tb-item">

                                                            <div class="nk-tb-col"><a href="#">
                                                                    <div class="user-card">
                                                                        <div class="user-avatar"><img
                                                                                src="../images/avatar/a-sm.jpg"
                                                                                alt="">
                                                                        </div>
                                                                        <div class="user-info"><span
                                                                                class="tb-lead">{{ $student->name }} <span
                                                                                    class="dot dot-warning d-md-none ms-1"></span></span><span>{{ $student->email }}</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-mb"><span
                                                                    class="tb-lead d-lg-flex d-none">{{ $student->school->name }}
                                                                </span>
                                                                <div class="d-lg-flex d-none">

                                                                </div>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-md">
                                                                <span>{{ $student->phone }}</span>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-lg">
                                                                @if (isset($student->details[0]) && isset($student->details[0]->stage))
                                                                    <span>{{ $student->details[0]->stage->name }}</span>
                                                                @else
                                                                    <span>N/A</span>
                                                                    <!-- Or any default value you prefer -->
                                                                @endif
                                                            </div>
                                                            <div class="nk-tb-col tb-col-mb"><span
                                                                    class="tb-lead d-lg-flex d-none"></span>
                                                                <div class="d-lg-flex d-none">
                                                                    <div class="drodown"><a href="#"
                                                                            class="dropdown-toggle pt-1 text-info"
                                                                            data-bs-toggle="dropdown"> <span>View
                                                                                More</span> </a>

                                                                        <div class="dropdown-menu dropdown-menu-start">
                                                                            <ul class="link-list-opt no-bdr p-3">
                                                                                @foreach ($student->userCourses as $course)
                                                                                    <li class="tb-lead p-1">
                                                                                        {{ $course->program->course->name ?? 'N/A' }}
                                                                                        @if (!$loop->last)
                                                                                            ,
                                                                                        @endif
                                                                                    </li>
                                                                                @endforeach


                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="nk-tb-col tb-col-lg">
                                                                @if (!empty($student->getRoleNames()))
                                                                    @foreach ($student->getRoleNames() as $v)
                                                                        <label
                                                                            class="badge badge-secondary text-dark">{{ $v }}</label>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="nk-tb-col nk-tb-col-tools text-end">
                                                                <div class="dropdown">
                                                                    <a href="#"
                                                                        class="btn btn-xs btn-outline-light btn-icon dropdown-toggle"
                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <em class="icon ni ni-more-h"></em>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a
                                                                                    href="{{ route('students.edit', $student->id) }}"><em
                                                                                        class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#"
                                                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $student->id }}').submit();"><em
                                                                                        class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                            </li>
                                                                            <form id="delete-form-{{ $student->id }}"
                                                                                action="{{ route('students.destroy', $student->id) }}"
                                                                                method="POST" style="display: none;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                            </form>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endforeach





                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-inner">
                                            <div class="nk-block-between-md g-3">
                                                {!! $students->links() !!}
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
