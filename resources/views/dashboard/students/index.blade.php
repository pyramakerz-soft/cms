@extends('dashboard.layouts.layout')
@section('content')
<div class="nk-app-root">
    <div class="nk-main">
        @include('dashboard.layouts.sidebar')
        <div class="nk-wrap">
            @include('dashboard.layouts.navbar')
            <div class="nk-content">
                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="nk-block-head nk-block-head-sm">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h3 class="nk-block-title page-title">Students</h3>
                                    </div>
                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="more-options">
                                                <em class="icon ni ni-more-v"></em>
                                            </a>
                                            <div class="toggle-expand-content " data-content="more-options">
                                                <form method="GET" action="{{ route('students.index') }}">
                                                    @csrf
                                                    <ul class="nk-block-tools d-flex justify-content-between">
                                                        <li>
                                                            <div class="drodown">
                                                                <select name="school" class="form-select" onchange="this.form.submit()">
                                                                    <option value="">Select School</option>
                                                                    @foreach ($schools as $school)
                                                                        <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                                                            {{ $school->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown">
                                                                <select name="program" class="form-select" onchange="this.form.submit()">
                                                                    <option value="">Select Program</option>
                                                                    @foreach ($programs as $program)
                                                                        <option value="{{ $program->id }}" {{ request('program') == $program->id ? 'selected' : '' }}>
                                                                            {{ $program->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown">
                                                                <select name="grade" class="form-select" onchange="this.form.submit()">
                                                                    <option value="">Select Grade</option>
                                                                    @foreach ($grades as $grade)
                                                                        <option value="{{ $grade->id }}" {{ request('grade') == $grade->id ? 'selected' : '' }}>
                                                                            {{ $grade->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown">
                                                                <select name="group" class="form-select" onchange="this.form.submit()">
                                                                    <option value="">Select Class</option>
                                                                    @foreach ($classes as $class)
                                                                        <option value="{{ $class->id }}" {{ request('group') == $class->id ? 'selected' : '' }}>
                                                                            {{ $class->sec_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li class="nk-block-tools-opt">
                                                            <button type="submit" class="btn btn-primary">Filter</button>
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
                                                    <div class="nk-tb-col"><span class="sub-text">Student</span></div>
                                                    <div class="nk-tb-col tb-col-mb"><span class="sub-text d-lg-flex d-none">School</span></div>
                                                    <div class="nk-tb-col tb-col-md"><span class="sub-text">Phone</span></div>
                                                    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Grade</span></div>
                                                    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Program</span></div>
                                                    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Roles</span></div>
                                                </div>
                                                @foreach ($students as $student)
                                                    <div class="nk-tb-item">
                                                        <div class="nk-tb-col"><a href="#">
                                                            <div class="user-card">
                                                                <div class="user-avatar"><img src="../images/avatar/a-sm.jpg" alt=""></div>
                                                                <div class="user-info"><span class="tb-lead">{{ $student->name }} <span class="dot dot-warning d-md-none ms-1"></span></span><span>{{ $student->email }}</span></div>
                                                            </div></a>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-mb"><span class="tb-lead d-lg-flex d-none">{{ $student->school->name }}</span></div>
                                                        <div class="nk-tb-col tb-col-md"><span>{{ $student->phone }}</span></div>
                                                        <div class="nk-tb-col tb-col-lg">
                                                            @if (isset($student->details[0]) && isset($student->details[0]->stage))
                                                                <span>{{ $student->details[0]->stage->name }}</span>
                                                            @else
                                                                <span>N/A</span>
                                                            @endif
                                                        </div>
                                                        <div class="nk-tb-col tb-col-lg">
                                                            @if (!empty($student->getRoleNames()))
                                                                @foreach ($student->getRoleNames() as $v)
                                                                    <label class="badge badge-secondary text-dark">{{ $v }}</label>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="nk-tb-col nk-tb-col-tools text-end">
                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-xs btn-outline-light btn-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <em class="icon ni ni-more-h"></em>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="{{ route('students.edit', $student->id) }}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                                                        <li>
                                                                            <a href="#" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $student->id }}').submit();">
                                                                                <em class="icon ni ni-trash"></em><span>Delete</span>
                                                                            </a>
                                                                        </li>
                                                                        <form id="delete-form-{{ $student->id }}" action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: none;">
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
</div>
@endsection
