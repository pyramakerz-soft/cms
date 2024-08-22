{{-- @extends('dashboard.layouts.layout')

@section('content')
    <div class="container">
        <h1>Class Completion Report</h1>

        <form method="GET" action="{{ route('class.completion.report.web') }}">
            <!-- Include your filters here -->
        </form>

        <h2>Counts</h2>
        <ul>
            <li>Completed: {{ $counts['completed'] }}</li>
            <li>Overdue: {{ $counts['overdue'] }}</li>
            <li>Pending: {{ $counts['pending'] }}</li>
        </ul>

        <h2>Assignments Percentages</h2>
        <ul>
            <li>Completed: {{ $assignments_percentages['completed'] }}%</li>
            <li>Overdue: {{ $assignments_percentages['overdue'] }}%</li>
            <li>Pending: {{ $assignments_percentages['pending'] }}%</li>
        </ul>

        <h2>Tests</h2>
           <table class="table">
                                    <thead class="thead-dark">
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tests as $test)
                    <tr>
                        <td>{{ $test->tests->name }}</td>
                        <td>{{ $test->start_date }}</td>
                        <td>{{ $test->due_date }}</td>
                        <td>{{ $test->status == 1 ? 'Completed' : (new Date($test->due_date) < now() ? 'Overdue' : 'Pending') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection --}}

@extends('dashboard.layouts.layout')

@section('content')
    <div class="nk-main">
        @include('dashboard.layouts.sidebar')
        <div class="container">
            <div class="row">
                <div class="col-10 mx-auto">
                    <h1 style="font-size: 35px; margin-top: 15px">Class Completion Report</h1>

                    <form method="GET" action="{{ route('reports.classCompletionReportWeb') }}">
                        <div class="row">
                            <!-- Group Filter -->
                            <div class="col-md-4">
                                <label for="group_id">Select class</label>
                                <select class="form-select js-select2" name="group_id" id="group_id" required>
                                    <option value="" disabled selected>Choose a class</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Program Filter -->
                            <div class="col-md-4">
                                <label for="program_id">Select Program</label>
                                <select class="form-select js-select2" name="program_id" id="program_id">
                                    <option value="" disabled selected>Choose a program</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Status Filter -->
                            <div class="col-md-4">
                                <label for="status">Select Status</label>
                                <select class="form-select js-select2" name="status" id="status">
                                    <option value="" disabled selected>Choose a status</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Overdue">Overdue</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>



                        </div>

                        <div class="row mt-3">
                            <!-- From Date Filter -->
                            <div class="col-md-4">
                                <label for="from_date">From Date</label>
                                <input type="date" class="form-control" name="from_date" id="from_date">
                            </div>

                            <!-- To Date Filter -->
                            <div class="col-md-4">
                                <label for="to_date">To Date</label>
                                <input type="date" class="form-control" name="to_date" id="to_date">
                            </div>

                            <!-- Assignment Type Filter -->
                            <div class="col-md-4">
                                <label for="types">Select Assignment Types</label>
                                <select class="form-select js-select2" name="types[]" id="types" multiple>
                                    @foreach ($assignmentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mt-3">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <h2 style="font-size: 25px; margin-top: 15px">Counts</h2>
                    <ul class="d-flex justify-content-between">
                        <li>Completed: <b style="font-size: 16px">{{ $counts['completed'] }}</b></li>
                        <li>Overdue: <b style="font-size: 16px">{{ $counts['overdue'] }}</b></li>
                        <li>Pending: <b style="font-size: 16px">{{ $counts['pending'] }}</b></li>
                    </ul>

                    <h2 style="font-size: 25px; margin-top: 15px">Assignments Percentages</h2>
                    <ul>
                        <li>Completed: {{ $assignments_percentages['completed'] }}%</li>
                        <li>Overdue: {{ $assignments_percentages['overdue'] }}%</li>
                        <li>Pending: {{ $assignments_percentages['pending'] }}%</li>
                    </ul>

                    <h2 style="font-size: 25px; margin-top: 15px">Tests</h2>
                    <table class="table">
                        <thead class="thead-dark">
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Start Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        <tbody>
                            @foreach ($tests as $test)
                                <tr>
                                    <td>{{ $test->tests->name }}</td>
                                    <td>{{ $test->start_date }}</td>
                                    <td>{{ $test->due_date }}</td>
                                    <td>{{ $test->status == 1 ? 'Completed' : (new Date($test->due_date) < now() ? 'Overdue' : 'Pending') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page_js')
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();
        });
    </script>
@endsection
