<!-- resources/views/dashboard/reports/class/class_num_of_trials_report.blade.php -->
@extends('dashboard.layouts.layout')

@section('content')
    @include('dashboard.layouts.sidebar')

    <div class="container">
        <div class="row">
            <div class="col-8 mx-auto">
                <h1 style="font-size: 25px; margin-top: 25px">Class Number of Trials Report</h1>

                <form method="GET" action="{{ route('class.num.of.trials.report.web') }}">
                    <div class="row">
                        <!-- Group Filter -->
                        <div class="col-md-4">
                            <label for="group_id">Select class</label>
                            <select class="form-select js-select2" name="group_id" id="group_id" required>
                                <option value="" disabled selected>Choose a class</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name . ' / ' . $group->stage->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Program Filter -->
                        <div class="col-md-4">
                            <label for="program_id">Select Program</label>
                            <select class="form-select js-select2" name="program_id" id="program_id">
                                <option value="" disabled selected>Choose a program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}">
                                        {{ $program->name . ' / ' . $program->course->name . ' / ' . $program->stage->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

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

                        <!-- Submit Button -->
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                @if (!empty($progress))
                    <h2 style="font-size: 25px; margin-top: 25px">Monthly Scores</h2>
                       <table class="table">
                                    <thead class="thead-dark">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total Score</th>
                                <th>Tests</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tprogress as $month => $monthlyScore)
                                <tr>
                                    <td>{{ $monthlyScore['month'] }}</td>
                                    <td>{{ $monthlyScore['total_score'] }}</td>
                                    <td>
                                        @foreach ($monthlyScore['tests'] as $test)
                                            <div>{{ $test['name'] }}: {{ $test['score'] }}</div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h2 style="font-size: 25px; margin-top: 25px">Trial Counts</h2>
                    <ul>
                        <li>First Trial: {{ $oneStarDisplayedPercentage }}%</li>
                        <li>Second Trial: {{ $twoStarDisplayedPercentage }}%</li>
                        <li>Third Trial: {{ $threeStarDisplayedPercentage }}%</li>
                    </ul>

                    <h2 style="font-size: 25px; margin-top: 25px">Progress</h2>
                       <table class="table">
                                    <thead class="thead-dark">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Test ID</th>
                                <th>Score</th>
                                <th>Mistake Count</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($progress as $prog)
                                <tr>
                                    <td>{{ $prog->student_id }}</td>
                                    <td>{{ $prog->test_id }}</td>
                                    <td>{{ $prog->score }}</td>
                                    <td>{{ $prog->mistake_count }}</td>
                                    <td>{{ $prog->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No data available for the selected filters.</p>
                @endif
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
