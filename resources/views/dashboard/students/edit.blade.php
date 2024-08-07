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
                                <div class="" role="dialog" id="student-add">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content"><a href="#" class="close"
                                                data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                            <div class="modal-body modal-body-md">
                                                <h5 class="title">Add Students</h5>

                                                <form method="POST" action="{{ route('students.update', $student->id) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('put')
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="student-info">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="full-name">Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="full-name" placeholder="First name"
                                                                            name="name" value="{{ $student->name }}">

                                                                        @error('name')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="email">Email Address</label><input
                                                                            type="email" class="form-control"
                                                                            id="email" name="email"
                                                                            placeholder="Email Address"
                                                                            value="{{ $student->email }}">
                                                                        @error('email')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="phone-no">Phone Number</label><input
                                                                            type="text" class="form-control"
                                                                            id="phone-no" placeholder="Phone Number"
                                                                            name="phone" value="{{ $student->phone }}">
                                                                        @error('phone')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label
                                                                            class="form-label">School</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select js-select2"
                                                                                name="school_id"
                                                                                data-placeholder="Select multiple options">
                                                                                @foreach ($schools as $school)
                                                                                    <option value="{{ $school->id }}"
                                                                                        {{ $student->school_id == $school->id ? 'selected' : '' }}>
                                                                                        {{ $school->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('school_id')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Program</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select js-select2"
                                                                                name="program_id[]" multiple
                                                                                data-placeholder="Select multiple options">
                                                                                @foreach ($programs as $program)
                                                                                    <option value="{{ $program->id }}"
                                                                                        @if (isset($student->userCourses[0]) && $student->userCourses[0]->program->id == $program->id) selected @endif>
                                                                                        {{ $program->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('program_id')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label
                                                                            class="form-label">Grade</label>
                                                                        <div class="form-control-wrap"><select
                                                                                class="form-select js-select2"
                                                                                name="stage_id"
                                                                                data-placeholder="Select multiple options">
                                                                                @foreach ($stages as $stage)
                                                                                    <option value="{{ $stage->id }}"
                                                                                        {{ $student->details[0]->stage->id == $stage->id ? 'selected' : '' }}>
                                                                                        {{ $stage->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('stage_id')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label
                                                                            class="form-label">Class</label>
                                                                        <div class="form-control-wrap"><select
                                                                                class="form-select js-select2"
                                                                                name="group_id"
                                                                                data-placeholder="Select multiple options">
                                                                                @foreach ($groups as $group)
                                                                                    <option value="{{ $group->id }}">
                                                                                        {{ $group->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('group_id')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="password">Password</label>
                                                                        <input type="password" class="form-control"
                                                                            id="password" placeholder="Password"
                                                                            name="password">
                                                                        @error('password')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="confirm-password">Confirm Password</label>
                                                                        <input type="password" class="form-control"
                                                                            id="confirm-password"
                                                                            placeholder="Confirm Password"
                                                                            name="password_confirmation">
                                                                        @error('password_confirmation')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Role</label>
                                                                        <div class="form-control-wrap">
                                                                            <select name="roles[]" id="role"
                                                                                class="form-select">

                                                                                @foreach ($roles as $role)
                                                                                    <option value="{{ $role }}">
                                                                                        {{ $role }}</option>
                                                                                @endforeach

                                                                            </select>
                                                                            @error('roles')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="profile-picture">Profile Picture</label>
                                                                        <input type="file" id="profile-picture"
                                                                            name="parent_image">
                                                                        @error('parent_image')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <ul
                                                                        class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                        <li><button type="submit"
                                                                                class="btn btn-primary">Create</button>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

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
