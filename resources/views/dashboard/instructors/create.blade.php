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
                                                <h5 class="title">Add Teachers</h5>

                                                <form method="POST" action="{{ route('instructors.store') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="student-info">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label class="form-label"
                                                                            for="full-name">Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="full-name" placeholder="Teacher name"
                                                                            name="name">

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
                                                                            placeholder="Email Address">
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
                                                                            name="phone">
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
                                                                                    <option value="{{ $school->id }}">
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
                                                                    <div class="form-group"><label
                                                                            class="form-label">Program</label>
                                                                        <div class="form-control-wrap"><select
                                                                                class="form-select js-select2"
                                                                                name="program_id"
                                                                                data-placeholder="Select multiple options">
                                                                                <option value="" disabled selected>
                                                                                    Select Program</option>
                                                                                @foreach ($programs as $program)
                                                                                    <option value="{{ $program->id }}">
                                                                                        {{ $program->name  }}
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
                                                                            class="form-label">Class</label>
                                                                        <div class="form-control-wrap"><select
                                                                                class="form-select js-select2"
                                                                                name="group_id"
                                                                                data-placeholder="Select multiple options">
                                                                                {{-- @foreach ($groups as $group)
                                                                                    <option value="{{ $group->id }}">
                                                                                        {{ $group->name }}</option>
                                                                                @endforeach --}}
                                                                            </select>
                                                                            @error('group_id')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Role</label>
                                                                        <div class="form-control-wrap">
                                                                            <select name="roles[]" id="role"
                                                                                class="form-select">
                                                                                <option value="" disabled selected>
                                                                                    Role</option>
                                                                                @foreach ($roles as $role)
                                                                                    <option value="{{ $role->name }}">
                                                                                        {{ $role->name }}</option>
                                                                                @endforeach

                                                                            </select>
                                                                            @error('roles')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="col-md-6">
                                                                    <div class="form-group"><label
                                                                            class="form-label">Grade</label>
                                                                        <div class="form-control-wrap"><select
                                                                                class="form-select js-select2"
                                                                                name="stage_id"
                                                                                data-placeholder="Select multiple options">
                                                                                @foreach ($stages as $stage)
                                                                                    <option value="{{ $stage->id }}">
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
@section('page_js')
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();

            $('select[name="program_id"]').change(function() {
                var programId = $(this).val();
                if (programId) {
                    $.ajax({
                        url: '/get-groups/' + programId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="group_id"]').empty();
                            $('select[name="group_id"]').append(
                                '<option value="">Select a class</option>');
                            $.each(data, function(key, value) {
                                $('select[name="group_id"]').append('<option value="' +
                                    value.id + '">' + value.sec_name + '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="group_id"]').empty();
                }
            });
        });
    </script>
@endsection
