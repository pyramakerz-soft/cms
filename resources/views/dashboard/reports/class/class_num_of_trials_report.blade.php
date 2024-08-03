@extends('dashboard.layouts.layout')

@section('content')
    <div class="container">
        <h1>Class Number of Trials Report</h1>

        <form method="GET" action="{{ route('classNumOfTrialsReportWeb') }}">
            <!-- Include your filters here -->
        </form>

        <h2>Monthly Scores</h2>
        <table class="table">
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

        <h2>Star Counts</h2>
        <ul>
            <li>One Star: {{ $oneStarDisplayedPercentage }}%</li>
            <li>Two Stars: {{ $twoStarDisplayedPercentage }}%</li>
            <li>Three Stars: {{ $threeStarDisplayedPercentage }}%</li>
        </ul>

        <h2>Progress</h2>
        <table class="table">
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
    </div>
@endsection
