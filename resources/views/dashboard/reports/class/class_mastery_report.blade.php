@extends('dashboard.layouts.layout')

@section('content')
    <div class="page-wrapper">
        <!-- Sidebar -->
        @include('dashboard.layouts.sidebar')

        <div class="page-container">
            <!-- Navbar -->
            @include('dashboard.layouts.navbar')

            <!-- Main Content -->
            <div class="row">
                <div class="col-6 mx-auto mt-5">
                    <div class="main-content">
                        <div class="container-fluid">
                            <h1 style="font-size: 25px; margin-top: 30px">Class Mastery Report</h1>

                            <form method="GET" action="{{ route('reports.classMasteryReportWeb') }}">
                                <div class="row">
                                    <!-- Group Filter -->
                                    <div class="col-md-4">
                                        <label for="group_id">Select Group</label>
                                        <select class="form-select js-select2" name="group_id" id="group_id" required>
                                            <option value="" disabled selected>Choose a group</option>
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

                                    <!-- Filter By -->
                                    <div class="col-md-4">
                                        <label for="filter">Filter By</label>
                                        <select class="form-select js-select2" name="filter" id="filter">
                                            <option value="" disabled selected>Choose a filter</option>
                                            <option value="Unit">Unit</option>
                                            <option value="Lesson">Lesson</option>
                                            <option value="Game">Game</option>
                                            <option value="Skill">Skill</option>
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

                                    <!-- Submit Button -->
                                    <div class="col-md-4 mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>

                            @if (!empty($skills) || !empty($units) || !empty($lessons) || !empty($games))
                                <h2>Mastery Report</h2>
                                @if (!empty($units))
                                    <h3>Units Mastery</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Unit</th>
                                                <th>Failed</th>
                                                <th>Introduced</th>
                                                <th>Practiced</th>
                                                <th>Mastered</th>
                                                <th>Mastery Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($units as $unit)
                                                <tr>
                                                    <td>{{ $unit['name'] }}</td>
                                                    <td>{{ $unit['failed'] }}</td>
                                                    <td>{{ $unit['introduced'] }}</td>
                                                    <td>{{ $unit['practiced'] }}</td>
                                                    <td>{{ $unit['mastered'] }}</td>
                                                    <td>{{ $unit['mastery_percentage'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                                @if (!empty($lessons))
                                    <h3>Lessons Mastery</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Lesson</th>
                                                <th>Failed</th>
                                                <th>Introduced</th>
                                                <th>Practiced</th>
                                                <th>Mastered</th>
                                                <th>Mastery Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lessons as $lesson)
                                                <tr>
                                                    <td>{{ $lesson['name'] }}</td>
                                                    <td>{{ $lesson['failed'] }}</td>
                                                    <td>{{ $lesson['introduced'] }}</td>
                                                    <td>{{ $lesson['practiced'] }}</td>
                                                    <td>{{ $lesson['mastered'] }}</td>
                                                    <td>{{ $lesson['mastery_percentage'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                                @if (!empty($games))
                                    <h3>Games Mastery</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Game</th>
                                                <th>Failed</th>
                                                <th>Introduced</th>
                                                <th>Practiced</th>
                                                <th>Mastered</th>
                                                <th>Mastery Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($games as $game)
                                                <tr>
                                                    <td>{{ $game['name'] }}</td>
                                                    <td>{{ $game['failed'] }}</td>
                                                    <td>{{ $game['introduced'] }}</td>
                                                    <td>{{ $game['practiced'] }}</td>
                                                    <td>{{ $game['mastered'] }}</td>
                                                    <td>{{ $game['mastery_percentage'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                                @if (!empty($skills))
                                    <h3>Skills Mastery</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Skill</th>
                                                <th>Failed</th>
                                                <th>Introduced</th>
                                                <th>Practiced</th>
                                                <th>Mastered</th>
                                                <th>Mastery Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($skills as $skill)
                                                <tr>
                                                    <td>{{ $skill['name'] }}</td>
                                                    <td>{{ $skill['failed'] }}</td>
                                                    <td>{{ $skill['introduced'] }}</td>
                                                    <td>{{ $skill['practiced'] }}</td>
                                                    <td>{{ $skill['mastered'] }}</td>
                                                    <td>{{ $skill['mastery_percentage'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @else
                                <p>No data available for the selected filters.</p>
                            @endif
                        </div>
                    </div>
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
