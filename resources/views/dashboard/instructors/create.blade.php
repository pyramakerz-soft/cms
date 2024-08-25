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
                                                                            for="phone-no">Phone Number</label>
                                                                        <span>(Optional)</span>

                                                                        <input type="text" class="form-control"
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
                                                                                name="school_id" id="school_id"
                                                                                data-placeholder="Select School">
                                                                                <option value="">Select School
                                                                                </option>
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
                                                                    <div class="form-group">
                                                                        <label class="form-label">Grade</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select js-select2"
                                                                                name="stage_id" id="stage_id"
                                                                                data-placeholder="Select Stage" disabled>
                                                                                <option value="">Select Stage</option>
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
                                                                    <div class="form-group">
                                                                        <label class="form-label">Program</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select js-select2"
                                                                                name="program_id[]" id="program_id" multiple
                                                                                data-placeholder="Select Program" disabled>

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
                                                                        <div class="form-control-wrap">

                                                                            <select class="form-select js-select2"
                                                                                id="class_id" name="group_id[]" multiple
                                                                                data-placeholder="Select Classes" disabled>

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
                                                                        <span>(Optional)</span>
                                                                        <br>
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
            $('#school_id').change(function() {
                $('#stage_id').prop('disabled', false);
                $('#program_id').empty().prop('disabled', true);
                $('#class_id').empty().prop('disabled', true);
            });

            $('#stage_id').change(function() {
                $('#program_id').prop('disabled', false);
                var stageId = $(this).val();
                var schoolId = $('#school_id').val();
                if (schoolId && stageId) {
                    // Fetch programs based on selected school and stage
                    $.ajax({
                        
                        url: ' /cms/public/get-programs/' + schoolId + '/' + stageId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#program_id').empty();
                            $.each(data, function(key, value) {
                                var programName = value.name;
                                var courseName = value.course ? value.course.name :
                                    'No Course'; // Check if course exists
                                $('#program_id').append('<option value="' + value.id +
                                    '">' + programName + ' / ' + courseName +
                                    '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                        }
                    });
                } else {
                    $('#program_id').empty();
                }
            });

            $('select[name="program_id[]"]').change(function() {
                $('#class_id').prop('disabled', false);

                var programId = $(this).val();
                var stageId = $('#stage_id').val();
                var schoolId = $('#school_id').val();

                if (programId && stageId && schoolId) {
                    // Fetch groups based on selected program, stage, and school
                    $.ajax({
                        url: ' /cms/public/get-groups/' + schoolId + '/' + stageId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="group_id[]"]').empty();
                            $('select[name="group_id[]"]').append(
                                '<option value="">Select a class</option>');
                            $.each(data, function(key, value) {
                                $('select[name="group_id[]"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                        }
                    });
                } else {
                    $('select[name="group_id[]"]').empty();
                }
            });
        });
    </script>
@endsection
